<?php
include('../config.php');
include('../pages/navbar.php');
include('../templates/template_header.php');

// Inizia la sessione
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); 

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    echo "Sessione non valida.";
    exit;
}

//
//// Verifica se l'utente è loggato
//if (!isset($_SESSION['user_id'])) {
//    header("Location: ../pages/login.php");
//    exit;
//}
// Supponiamo che l'ID dello studente sia memorizzato in una sessione
$userId = $_SESSION['user_id']; // Assicurati di avere un sistema di autenticazione

// Recupera i corsi a cui lo studente è iscritto
$coursesEnrolled = [];
$coursesEnrolledIds = []; // Array per memorizzare gli ID dei corsi a cui lo studente è iscritto

$sqlEnrolledIds = "
    SELECT c.IdCorso
    FROM corso c
    JOIN iscrizione isc ON c.IdCorso = isc.IdCorso
    WHERE isc.IdStudente = :userId
";
$stmtEnrolledIds = $pdo->prepare($sqlEnrolledIds);
$stmtEnrolledIds->bindParam(':userId', $userId);
$stmtEnrolledIds->execute();
$coursesEnrolledIds = $stmtEnrolledIds->fetchAll(PDO::FETCH_COLUMN); // Ottieni solo gli ID dei corsi iscritti

// Recupera i corsi in cui lo studente è iscritto (con i dettagli)
$sql = "
    SELECT c.Nome AS corso_nome, c.Durata, c.DataInizio, c.DataFine, c.IdIstruttore, 
           ist.Nome AS istruttore_nome, ist.Cognome AS istruttore_cognome, cat.NomeCategoria
    FROM corso c
    JOIN iscrizione isc ON c.IdCorso = isc.IdCorso
    JOIN istruttore ist ON c.IdIstruttore = ist.IdIstruttore
    JOIN categoria cat ON c.IdCategoria = cat.IdCategoria
    WHERE isc.IdStudente = :userId
";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':userId', $userId);
$stmt->execute();
$coursesEnrolled = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Recupera i corsi disponibili (escludendo quelli a cui lo studente è già iscritto)
$coursesAvailable = [];
if (count($coursesEnrolledIds) > 0) {
    $sqlAvailable = "
    SELECT c.IdCorso AS corso_id, c.Nome AS corso_nome, c.Durata, c.DataInizio, c.DataFine, c.IdIstruttore, 
           ist.Nome AS istruttore_nome, ist.Cognome AS istruttore_cognome, cat.NomeCategoria
    FROM corso c
    JOIN istruttore ist ON c.IdIstruttore = ist.IdIstruttore
    JOIN categoria cat ON c.IdCategoria = cat.IdCategoria
    WHERE c.DataInizio > CURDATE()
    AND c.IdCorso NOT IN (" . implode(',', array_fill(0, count($coursesEnrolledIds), '?')) . ")
    ";

    $stmtAvailable = $pdo->prepare($sqlAvailable);
    $stmtAvailable->execute($coursesEnrolledIds); // Passa gli ID dei corsi già iscritti come parametri
    $coursesAvailable = $stmtAvailable->fetchAll(PDO::FETCH_ASSOC);
} else {
    // Se l'utente non è iscritto a nessun corso, mostra tutti i corsi futuri
    $sqlAvailable = "
        SELECT c.Nome AS corso_nome, c.Durata, c.DataInizio, c.DataFine, c.IdIstruttore, 
               ist.Nome AS istruttore_nome, ist.Cognome AS istruttore_cognome, cat.NomeCategoria
        FROM corso c
        JOIN istruttore ist ON c.IdIstruttore = ist.IdIstruttore
        JOIN categoria cat ON c.IdCategoria = cat.IdCategoria
        WHERE c.DataInizio > CURDATE() -- Solo corsi futuri
    ";
    $stmtAvailable = $pdo->prepare($sqlAvailable);
    $stmtAvailable->execute();
    $coursesAvailable = $stmtAvailable->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Studente | Online Courses</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../style.css">
    <link rel="icon" type="image/x-icon" href="../resources/image/logo.png">
</head>
<body>

    <!-- Header Section -->
    <header class="header-bg">
        <div class="overlay"></div>
        <div class="container text-center text-white d-flex align-items-center justify-content-center flex-column">
            <h1 class="hero-title">Dashboard Studente</h1>
            <p class="hero-subtext">Gestisci i tuoi corsi e il tuo apprendimento.</p>
            
        </div>
    </header>

    <!-- Student Dashboard Section -->
    <section class="section py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-4">Benvenuto caro</h2>
            <p class="text-center mb-5">Qui puoi visualizzare i corsi a cui sei iscritto, i corsi che hai completato e quelli disponibili per te.</p>

            <!-- Corsi Iscritti -->
            <div class="row mb-5">
                <div class="col-md-12">
                    <h3>I tuoi Corsi Iscritti</h3>
                    <div class="list-group">
                        <?php foreach ($coursesEnrolled as $course): ?>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div class="d-flex justify-content-between w-100">
                                    <span><?php echo htmlspecialchars($course['corso_nome']); ?></span>
                                </div>
                                <button class="btn btn-info btn-sm" onclick="toggleDetails('<?php echo str_replace(' ', '', $course['corso_nome']); ?>Details')">Dettagli</button>
                            </div>
                            <div id="<?php echo str_replace(' ', '', $course['corso_nome']); ?>Details" class="course-details" style="display:none;">
                                <div class="card mt-3">
                                    <div class="card-body">
                                        <h5>Dettagli Corso</h5>
                                        <p><strong>Data Inizio:</strong> <?php echo htmlspecialchars($course['DataInizio']); ?></p>
                                        <p><strong>Data Fine:</strong> <?php echo htmlspecialchars($course['DataFine']); ?></p>
                                        <p><strong>Durata:</strong> <?php echo htmlspecialchars($course['Durata']); ?> ore</p>
                                        <p><strong>Istruttore:</strong> <?php echo htmlspecialchars($course['istruttore_nome']) . ' ' . htmlspecialchars($course['istruttore_cognome']); ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Corsi Disponibili -->
            <div class="row">
                <div class="col-md-12">
                    <h3>Corsi Disponibili</h3>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Nome Corso</th>
                                <th>Durata</th>
                                <th>Data Inizio</th>
                                <th>Data Fine</th>
                                <th>Istruttore</th>
                                <th>Categoria</th>
                                <th>Azioni</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($coursesAvailable as $course): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($course['corso_nome']); ?></td>
                                    <td><?php echo htmlspecialchars($course['Durata']); ?> ore</td>
                                    <td><?php echo htmlspecialchars($course['DataInizio']); ?></td>
                                    <td><?php echo htmlspecialchars($course['DataFine']); ?></td>
                                    <td><?php echo htmlspecialchars($course['istruttore_nome']) . ' ' . htmlspecialchars($course['istruttore_cognome']); ?></td>
                                    <td><?php echo htmlspecialchars($course['NomeCategoria']); ?></td>
                                    <td>
                                    <a href="javascript:void(0);" class="btn btn-subscribe btn-sm" onclick="redirectToCourse(<?php echo htmlspecialchars($course['corso_id']); ?>)">Iscriviti</a>
                                    <!--<a href="../corsi/iscrizione_corso.php" class="btn btn-subscribe btn-sm">Iscriviti</a>-->
                                        <button class="btn btn-info btn-sm" onclick="toggleDetails('<?php echo str_replace(' ', '', $course['corso_nome']); ?>Details')">Dettagli</button>
                                    </td>
                                </tr>
                                <tr id="<?php echo str_replace(' ', '', $course['corso_nome']); ?>Details" class="course-details" style="display:none;">
                                    <td colspan="7">
                                        <div class="card mt-3">
                                            <div class="card-body">
                                                <h5>Dettagli Corso</h5>
                                                <p><strong>Durata:</strong> <?php echo htmlspecialchars($course['Durata']); ?> ore</p>
                                                <p><strong>Data Inizio:</strong> <?php echo htmlspecialchars($course['DataInizio']); ?></p>
                                                <p><strong>Data Fine:</strong> <?php echo htmlspecialchars($course['DataFine']); ?></p>
                                                <p><strong>Istruttore:</strong> <?php echo htmlspecialchars($course['istruttore_nome']) . ' ' . htmlspecialchars($course['istruttore_cognome']); ?></p>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <script defer>
        function toggleDetails(courseId) {
            const courseDetails = document.getElementById(courseId);
            if (courseDetails.style.display === "none" || courseDetails.style.display === "") {
                courseDetails.style.display = "table-row"; // Mostra la riga della tabella
            } else {
                courseDetails.style.display = "none"; // Nasconde la riga della tabella
            }
        }
        function redirectToCourse(courseId) {
            // Recupera l'ID dello studente dalla sessione
            var studentId = '<?= isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '' ?>';
                
            // Se lo studente non è loggato, reindirizza alla pagina di login
            if (!studentId) {
                alert("Devi effettuare il login prima di iscriverti!");
                window.location.href = "../pages/login.php";
            } else {
                // Altrimenti, reindirizza alla pagina di iscrizione con l'ID del corso
                window.location.href = "../corsi/iscrizione_corso.php?corso_id=" + courseId;

            }
        }

    </script>

    <?php include('../templates/template_footer.php'); ?>
</body>
</html>
