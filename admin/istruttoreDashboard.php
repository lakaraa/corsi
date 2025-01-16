<?php
// Inclusione dei file di configurazione e template
include('../config.php');
include('../templates/template_header.php'); // Header HTML con inclusione stili
include('../pages/navbar.php');          // Navbar

// Assicurati che la connessione al database funzioni
if (!$pdo) {
    die("Connessione al database fallita.");
}

// Avvia la sessione, se non già avviata
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$userId = $_SESSION['user_id'] ?? null;
if (!$userId) {
    die("Utente non autenticato.");
}

// Recupera i corsi in corso
$sqlOngoingCourses = "
    SELECT 
        c.Nome AS NomeCorso, 
        c.DataInizio, 
        c.DataFine,
        cat.NomeCategoria,
        c.IdCorso
    FROM corso c
    JOIN categoria cat ON c.IdCategoria = cat.IdCategoria
    WHERE c.IdIstruttore = :userId
      AND c.DataInizio <= CURDATE()
      AND (c.DataFine IS NULL OR c.DataFine > CURDATE())
";
$stmt = $pdo->prepare($sqlOngoingCourses);
$stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
$stmt->execute();
$ongoingCourses = $stmt->fetchAll();

// Recupera i corsi completati
$completedCourses = [];
$sqlCompletedCourses = "
    SELECT 
        c.Nome AS NomeCorso, 
        c.DataInizio, 
        c.DataFine,
        cat.NomeCategoria,
        c.IdCorso
    FROM corso c
    JOIN categoria cat ON c.IdCategoria = cat.IdCategoria
    WHERE c.IdIstruttore = :userId
      AND c.DataFine <= CURDATE()
";
$stmtCompletedCourses = $pdo->prepare($sqlCompletedCourses);
$stmtCompletedCourses->bindParam(':userId', $userId, PDO::PARAM_INT);
$stmtCompletedCourses->execute();
$completedCourses = $stmtCompletedCourses->fetchAll();

// Recupera i corsi futuri
$futureCourses = [];
$sqlFutureCourses = "
    SELECT 
        c.Nome AS NomeCorso, 
        c.DataInizio, 
        c.DataFine,
        cat.NomeCategoria,
        c.IdCorso
    FROM corso c
    JOIN categoria cat ON c.IdCategoria = cat.IdCategoria
    WHERE c.IdIstruttore = :userId
      AND c.DataInizio > CURDATE()
";
$stmtFutureCourses = $pdo->prepare($sqlFutureCourses);
$stmtFutureCourses->bindParam(':userId', $userId, PDO::PARAM_INT);
$stmtFutureCourses->execute();
$futureCourses = $stmtFutureCourses->fetchAll();

// Funzione per recuperare gli studenti di un corso
function getStudentsByCourse($pdo, $courseId) {
    $sql = "
        SELECT 
            s.Nome,
            s.Cognome,
            iscr.Livello,
            s.IdStudente
        FROM studente s
        JOIN iscrizione iscr ON s.IdStudente = iscr.IdStudente
        WHERE iscr.IdCorso = :courseId
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':courseId', $courseId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}

// Gestione dei livelli
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['student_id'], $_POST['course_id'], $_POST['new_level'])) {
    $studentId = $_POST['student_id'];
    $courseId = $_POST['course_id'];
    $newLevel = $_POST['new_level'];

    // Recupera la data di inizio del corso
    $sql = "SELECT DataInizio FROM corso WHERE IdCorso = :courseId";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':courseId', $courseId, PDO::PARAM_INT);
    $stmt->execute();
    $course = $stmt->fetch();

    // Controlla se il corso è nel futuro
    if ($course && strtotime($course['DataInizio']) > time()) {
        // Se la data di inizio è nel futuro, restituisci un errore
        header('Location: istruttoreDashboard.php?status=error&message=Non puoi assegnare il livello in un corso futuro.');
        exit;
    }

    try {
        // Prepara la query per aggiornare il livello dello studente
        $sql = "
            UPDATE iscrizione
            SET Livello = :newLevel
            WHERE IdStudente = :studentId AND IdCorso = :courseId
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':newLevel', $newLevel, PDO::PARAM_STR);
        $stmt->bindParam(':studentId', $studentId, PDO::PARAM_INT);
        $stmt->bindParam(':courseId', $courseId, PDO::PARAM_INT);

        // Esegui la query
        if ($stmt->execute()) {
            // Se l'aggiornamento è riuscito, reindirizza con successo
            header('Location: istruttoreDashboard.php?status=success&message=Livello aggiornato con successo.');
            exit;
        } else {
            // Se l'esecuzione fallisce, logga l'errore e visualizza il messaggio
            $errorInfo = $stmt->errorInfo();
            header('Location: istruttoreDashboard.php?status=error&message=Errore nell\'aggiornamento del livello.');
            exit;
        }
    } catch (PDOException $e) {
        // Log dell'errore nel file di log e visualizzazione dell'errore
        error_log("Errore di database: " . $e->getMessage());
        echo "Errore di database: " . $e->getMessage();  // Stampa l'errore per il debug
        header('Location: istruttoreDashboard.php?status=error&message=Errore di database: ' . $e->getMessage());
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Istruttore | Online Courses</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    
    <!-- Header Section -->
    <header class="header-bg">
        <div class="overlay"></div>
        <div class="container text-center text-white d-flex align-items-center justify-content-center flex-column">
            <h1 class="hero-title">Dashboard Istruttore</h1>
            <p class="hero-subtext">Visualizza i tuoi corsi in corso, completati e futuri, vedi gli studenti e assegna i voti.</p>
        </div>
    </header>

    <!-- Dashboard Content -->
    <section class="section py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-4">Dashboard Istruttore</h2>
            <p class="text-center mb-5">Visualizza i corsi che stai gestendo, divisi in corsi in corso, corsi completati e corsi futuri.</p>
            <?php
            if (isset($_GET['status']) && isset($_GET['message'])) {
                $status = $_GET['status'];
                $message = $_GET['message'];
                echo "<div class='alert alert-$status text-center mb-4'>$message</div>";  // Visualizza il messaggio in una alert
            } ?>
            <!-- Corsi in Corso -->
            <h3>Corsi in Corso</h3>
            <div class="list-group">
                <?php if (empty($ongoingCourses)): ?>
                    <div class="alert alert-info">Nessun corso in corso al momento.</div>
                <?php else: ?>
                    <?php foreach ($ongoingCourses as $course): ?>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <?php echo htmlspecialchars($course['NomeCorso']); ?>
                            <button class="btn btn-info btn-sm" onclick="toggleCourseDetails(<?php echo $course['IdCorso']; ?>)">Gestisci Studenti</button>
                        </div>
                        <div id="course<?php echo $course['IdCorso']; ?>" class="course-details" style="display:none;">
                            <div class="card mt-3">
                                <div class="card-body">
                                    <h5>Elenco Studenti</h5>
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Nome Studente</th>
                                                <th></th>
                                                <th></th>
                                                <th>Livello</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php 
                                        $students = getStudentsByCourse($pdo, $course['IdCorso']);
                                        if (empty($students)): ?>
                                            <tr>
                                                <td colspan="4">Nessuno studente iscritto a questo corso.</td>
                                            </tr>
                                        <?php else: ?>
                                            <?php foreach ($students as $studente): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($studente['Nome']) . ' ' . htmlspecialchars($studente['Cognome']); ?></td> <!-- Nome e Cognome -->
                                                    <td></td>
                                                    <td></td>
                                                    <td>
                                                        <!-- Form per l'aggiornamento del livello -->
                                                        <form method="POST" action="istruttoreDashboard.php" class="d-flex align-items-center">
                                                            <!-- ID dello studente -->
                                                            <input type="hidden" name="student_id" value="<?php echo isset($studente['IdStudente']) ? $studente['IdStudente'] : ''; ?>">

                                                            <!-- ID del corso -->
                                                            <input type="hidden" name="course_id" value="<?php echo isset($course['IdCorso']) ? $course['IdCorso'] : ''; ?>">

                                                            <!-- Dropdown per il livello -->
                                                            <select name="new_level" class="form-control mr-2" style="width: auto; min-width: 120px;">
                                                                <option value="Base" <?php echo isset($studente['Livello']) && $studente['Livello'] === 'Base' ? 'selected' : ''; ?>>Base</option>
                                                                <option value="Intermedio" <?php echo isset($studente['Livello']) && $studente['Livello'] === 'Intermedio' ? 'selected' : ''; ?>>Intermedio</option>
                                                                <option value="Avanzato" <?php echo isset($studente['Livello']) && $studente['Livello'] === 'Avanzato' ? 'selected' : ''; ?>>Avanzato</option>
                                                            </select>

                                                            <!-- Pulsante Assegna -->
                                                            <button type="submit" class="btn btn-primary btn-sm" style="height: 38px;">Assegna</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>

                                        <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>


            <!-- Corsi Futuri -->
            
            <h3>Corsi Futuri</h3>
            <div class="list-group">
                <?php if (empty($futureCourses)): ?>
                    <div class="alert alert-info">Nessun corso futuro al momento.</div>
                <?php else: ?>
                    <?php foreach ($futureCourses as $course): ?>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <?php echo htmlspecialchars($course['NomeCorso']); ?>
                            <button class="btn btn-info btn-sm" onclick="toggleCourseDetails(<?php echo $course['IdCorso']; ?>)">Gestisci Studenti</button>
                        </div>
                        <div id="course<?php echo $course['IdCorso']; ?>" class="course-details" style="display:none;">
                            <div class="card mt-3">
                                <div class="card-body">
                                    <h5>Elenco Studenti</h5>
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Nome Studente</th>
                                                <th>Livello</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            $students = getStudentsByCourse($pdo, $course['IdCorso']);
                                            if (empty($students)): ?>
                                                <tr>
                                                    <td colspan="4">Nessuno studente iscritto a questo corso.</td>
                                                </tr>
                                            <?php else: ?>
                                                <?php foreach ($students as $studente): ?>
                                                    <tr>
                                                        <td><?php echo htmlspecialchars($studente['Nome']) . ' ' . htmlspecialchars($studente['Cognome']); ?></td>
                                                        <td>
                                                            <!-- Dropdown per livello, disabilitato -->
                                                            <select name="new_level" class="form-control mr-2" style="width: auto; min-width: 120px;" disabled>
                                                                <option value="Base" <?php echo $studente['Livello'] === 'Base' ? 'selected' : ''; ?>>Base</option>
                                                                <option value="Intermedio" <?php echo $studente['Livello'] === 'Intermedio' ? 'selected' : ''; ?>>Intermedio</option>
                                                                <option value="Avanzato" <?php echo $studente['Livello'] === 'Avanzato' ? 'selected' : ''; ?>>Avanzato</option>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <!-- Form per l'assegnamento, solo per i corsi futuri -->
                                                            <form method="POST" action="istruttoreDashboard.php" class="d-flex align-items-center" onsubmit="return confirmAssignLevel('<?php echo $course['DataInizio']; ?>')">
                                                                <input type="hidden" name="student_id" value="<?php echo isset($studente['IdStudente']) ? $studente['IdStudente'] : ''; ?>">
                                                                <input type="hidden" name="course_id" value="<?php echo isset($course['IdCorso']) ? $course['IdCorso'] : ''; ?>">
                                                
                                                                <button type="submit" class="btn btn-primary btn-sm" style="height: 38px;">Assegna</button>
                                                            </form>
                                                
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
                                                

            <!-- Corsi Completati -->
                                                
            <h3>Corsi Completati</h3>
            <div class="list-group">
                <?php if (empty($completedCourses)): ?>
                    <div class="alert alert-info">Nessun corso completato al momento.</div>
                <?php else: ?>
                    <?php foreach ($completedCourses as $course): ?>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <?php echo htmlspecialchars($course['NomeCorso']); ?>
                            <button class="btn btn-info btn-sm" onclick="toggleCourseDetails(<?php echo $course['IdCorso']; ?>)">Gestisci Studenti</button>
                        </div>
                        <div id="course<?php echo $course['IdCorso']; ?>" class="course-details" style="display:none;">
                            <div class="card mt-3">
                                <div class="card-body">
                                    <h5>Elenco Studenti</h5>
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Nome Studente</th>
                                                <th>Livello</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            $students = getStudentsByCourse($pdo, $course['IdCorso']);
                                            if (empty($students)): ?>
                                                <tr>
                                                    <td colspan="4">Nessuno studente iscritto a questo corso.</td>
                                                </tr>
                                            <?php else: ?>
                                                <?php foreach ($students as $studente): ?>
                                                    <tr>
                                                        <td><?php echo htmlspecialchars(isset($studente['Nome']) ? $studente['Nome'] : '') . ' ' . htmlspecialchars(isset($studente['Cognome']) ? $studente['Cognome'] : ''); ?></td>
                                                        <td><?php echo htmlspecialchars(isset($studente['Livello']) ? $studente['Livello'] : ''); ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>
    <script defer>
        function toggleCourseDetails(courseId) {
            const courseDetails = document.getElementById('course' + courseId);
            courseDetails.style.display = (courseDetails.style.display === 'none') ? 'block' : 'none';
        }
            
        function confirmAssignLevel(courseStartDate) {
            // Converte la data di inizio del corso in un formato timestamp
            const courseStartTimestamp = new Date(courseStartDate).getTime();
            const currentTimestamp = Date.now();
                
            // Se la data di inizio del corso è nel futuro, blocca l'assegnazione
            if (courseStartTimestamp > currentTimestamp) {
                alert("Non puoi assegnare il livello in un corso futuro.");
                return false; // Impedisce l'invio del form
            }
        
            return true; // Consente l'invio del form
        }
        </script>
    </body>
</html>

<?php include('../templates/template_footer.php'); ?>
