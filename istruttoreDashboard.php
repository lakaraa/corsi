<?php
include('template_header.php');
include('navbar.php');

require_once 'config.php'; // Include il file di configurazione

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verifica se l'utente è loggato e ha il ruolo "istruttore"
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'istruttore') {
    header("Location: login.php");
    exit;
}

// Supponiamo che l'ID dell'istruttore sia memorizzato in una sessione
$userId = $_SESSION['user_id'];

// Recupera i corsi in corso
$ongoingCourses = [];
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
$stmtOngoingCourses = $pdo->prepare($sqlOngoingCourses);
$stmtOngoingCourses->bindParam(':userId', $userId, PDO::PARAM_INT);
$stmtOngoingCourses->execute();
$ongoingCourses = $stmtOngoingCourses->fetchAll();

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

// Funzione per ottenere gli studenti iscritti a un corso
function getStudentsForCourse($pdo, $courseId) {
    $sqlStudents = "
        SELECT 
            s.Nome AS NomeStudente, 
            s.Livello
        FROM iscrizione i
        JOIN studente s ON i.IdStudente = s.IdStudente
        WHERE i.IdCorso = :courseId
    ";
    $stmtStudents = $pdo->prepare($sqlStudents);
    $stmtStudents->bindParam(':courseId', $courseId, PDO::PARAM_INT);
    $stmtStudents->execute();
    return $stmtStudents->fetchAll();
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
    <link rel="stylesheet" href="style.css">
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

    <!-- Instructor Dashboard Section -->
    <section class="section py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-4">Dashboard Istruttore</h2>
            <p class="text-center mb-5">Visualizza i corsi che stai gestendo, divisi in corsi in corso, corsi completati e corsi futuri, vedi gli studenti e assegna i voti.</p>

            <!-- Corsi in corso -->
            <div class="row mb-5">
                <div class="col-md-12">
                    <h3>Corsi in Corso</h3>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Corso</th>
                                <th>Categoria</th>
                                <th>Data Inizio</th>
                                <th>Data Fine</th>
                                <th>Azioni</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($ongoingCourses)): ?>
                                <?php foreach ($ongoingCourses as $course): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($course['NomeCorso']) ?></td>
                                        <td><?= htmlspecialchars($course['NomeCategoria']) ?></td>
                                        <td><?= htmlspecialchars($course['DataInizio']) ?></td>
                                        <td><?= $course['DataFine'] ? htmlspecialchars($course['DataFine']) : 'In corso' ?></td>
                                        <td><button class="btn btn-info btn-sm" onclick="toggleCourseDetails('courseDetails_<?= $course['IdCorso'] ?>')">Gestisci Studenti</button></td>
                                    </tr>
                                    <tr id="courseDetails_<?= $course['IdCorso'] ?>" style="display:none;">
                                        <td colspan="5">
                                            <div class="card mt-3">
                                                <div class="card-body">
                                                    <h5>Elenco Studenti e Voti</h5>
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th>Nome Studente</th>
                                                                <th>Livello</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                                $students = getStudentsForCourse($pdo, $course['IdCorso']);
                                                                if (!empty($students)) {
                                                                    foreach ($students as $student) {
                                                                        echo "<tr>";
                                                                        echo "<td>" . htmlspecialchars($student['NomeStudente']) . "</td>";
                                                                        echo "<td><input type='text' class='form-control' value='" . htmlspecialchars($student['Livello']) . "' disabled></td>";
                                                                        echo "</tr>";
                                                                    }
                                                                } else {
                                                                    echo "<tr><td colspan='2'>Nessuno studente iscritto a questo corso.</td></tr>";
                                                                }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5">Non ci sono corsi in corso.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Corsi completati -->
            <div class="row mb-5">
                <div class="col-md-12">
                    <h3>Corsi Completati</h3>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Corso</th>
                                <th>Categoria</th>
                                <th>Data Inizio</th>
                                <th>Data Fine</th>
                                <th>Azioni</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($completedCourses)): ?>
                                <?php foreach ($completedCourses as $course): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($course['NomeCorso']) ?></td>
                                        <td><?= htmlspecialchars($course['NomeCategoria']) ?></td>
                                        <td><?= htmlspecialchars($course['DataInizio']) ?></td>
                                        <td><?= htmlspecialchars($course['DataFine']) ?></td>
                                        <td><button class="btn btn-info btn-sm" onclick="toggleCourseDetails('courseDetails_<?= $course['IdCorso'] ?>')">Visualizza Studenti</button></td>
                                    </tr>
                                    <tr id="courseDetails_<?= $course['IdCorso'] ?>" style="display:none;">
                                        <td colspan="5">
                                            <div class="card mt-3">
                                                <div class="card-body">
                                                    <h5>Elenco Studenti e Voti</h5>
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th>Nome Studente</th>
                                                                <th>Livello</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                                $students = getStudentsForCourse($pdo, $course['IdCorso']);
                                                                if (!empty($students)) {
                                                                    foreach ($students as $student) {
                                                                        echo "<tr>";
                                                                        echo "<td>" . htmlspecialchars($student['NomeStudente']) . "</td>";
                                                                        echo "<td><input type='text' class='form-control' value='" . htmlspecialchars($student['Livello']) . "' disabled></td>";
                                                                        echo "</tr>";
                                                                    }
                                                                } else {
                                                                    echo "<tr><td colspan='2'>Nessuno studente iscritto a questo corso.</td></tr>";
                                                                }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5">Non ci sono corsi completati.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Corsi Futuri -->
            <div class="row mb-5">
                <div class="col-md-12">
                    <h3>Corsi Futuri</h3>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Corso</th>
                                <th>Categoria</th>
                                <th>Data Inizio</th>
                                <th>Data Fine</th>
                                <th>Azioni</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($futureCourses)): ?>
                                <?php foreach ($futureCourses as $course): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($course['NomeCorso']) ?></td>
                                        <td><?= htmlspecialchars($course['NomeCategoria']) ?></td>
                                        <td><?= htmlspecialchars($course['DataInizio']) ?></td>
                                        <td><?= $course['DataFine'] ? htmlspecialchars($course['DataFine']) : 'In corso' ?></td>
                                        <td><button class="btn btn-info btn-sm" onclick="toggleCourseDetails('courseDetails_<?= $course['IdCorso'] ?>')">Gestisci Studenti</button></td>
                                    </tr>
                                    <tr id="courseDetails_<?= $course['IdCorso'] ?>" style="display:none;">
                                        <td colspan="5">
                                            <div class="card mt-3">
                                                <div class="card-body">
                                                    <h5>Elenco Studenti e Voti</h5>
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th>Nome Studente</th>
                                                                <th>Livello</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                                $students = getStudentsForCourse($pdo, $course['IdCorso']);
                                                                if (!empty($students)) {
                                                                    foreach ($students as $student) {
                                                                        echo "<tr>";
                                                                        echo "<td>" . htmlspecialchars($student['NomeStudente']) . "</td>";
                                                                        echo "<td><input type='text' class='form-control' value='" . htmlspecialchars($student['Livello']) . "' disabled></td>";
                                                                        echo "</tr>";
                                                                    }
                                                                } else {
                                                                    echo "<tr><td colspan='2'>Nessuno studente iscritto a questo corso.</td></tr>";
                                                                }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5">Non ci sono corsi futuri.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <script>
        // Funzione per mostrare/nascondere i dettagli dei corsi
        function toggleCourseDetails(courseId) {
            const courseDetails = document.getElementById(courseId);
            if (courseDetails.style.display === "none") {
                courseDetails.style.display = "block";
            } else {
                courseDetails.style.display = "none";
            }
        }
    </script>
</body>
</html>

<?php include('template_footer.php'); ?>
