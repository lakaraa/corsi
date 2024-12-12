<?php
include('template_header.php');

require_once 'config.php'; // Include il file di configurazione

// Inizia la sessione
session_start();

// Verifica se l'utente è loggato e ha il ruolo "istruttore"
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'istruttore') {
    header("Location: login.php");
    exit;
}

// Supponiamo che l'ID dell'istruttore sia memorizzato in una sessione
$userId = $_SESSION['user_id'];

// Recupera i corsi in corso (DataInizio <= CURDATE() AND (DataFine > CURDATE() OR DataFine IS NULL))
$ongoingCourses = [];
$sqlOngoingCourses = "
    SELECT 
        c.Nome AS NomeCorso, 
        c.DataInizio, 
        c.DataFine,
        cat.NomeCategoria
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

// Recupera i corsi completati (DataFine <= CURDATE())
$completedCourses = [];
$sqlCompletedCourses = "
    SELECT 
        c.Nome AS NomeCorso, 
        c.DataInizio, 
        c.DataFine,
        cat.NomeCategoria
    FROM corso c
    JOIN categoria cat ON c.IdCategoria = cat.IdCategoria
    WHERE c.IdIstruttore = :userId
      AND c.DataFine <= CURDATE()
";
$stmtCompletedCourses = $pdo->prepare($sqlCompletedCourses);
$stmtCompletedCourses->bindParam(':userId', $userId, PDO::PARAM_INT);
$stmtCompletedCourses->execute();
$completedCourses = $stmtCompletedCourses->fetchAll();

// Recupera i corsi che inizieranno nel futuro (DataInizio > CURDATE())
$futureCourses = [];
$sqlFutureCourses = "
    SELECT 
        c.Nome AS NomeCorso, 
        c.DataInizio, 
        c.DataFine,
        cat.NomeCategoria
    FROM corso c
    JOIN categoria cat ON c.IdCategoria = cat.IdCategoria
    WHERE c.IdIstruttore = :userId
      AND c.DataInizio > CURDATE()
";
$stmtFutureCourses = $pdo->prepare($sqlFutureCourses);
$stmtFutureCourses->bindParam(':userId', $userId, PDO::PARAM_INT);
$stmtFutureCourses->execute();
$futureCourses = $stmtFutureCourses->fetchAll();
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
            <p class="hero-subtext">Visualizza i tuoi corsi in corso, completati e futuri.</p>
            <a href="logout.php" class="btn btn-danger mt-4">Logout</a>
        </div>
    </header>

    <!-- Instructor Dashboard Section -->
    <section class="section py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-4">Dashboard Istruttore</h2>
            <p class="text-center mb-5">Visualizza i corsi che stai gestendo, divisi in corsi in corso, corsi completati e corsi futuri.</p>

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
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4">Non ci sono corsi in corso.</td>
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
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4">Non ci sono corsi completati.</td>
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
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4">Non ci sono corsi futuri.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </section>
</body>
</html>

<?php include('template_footer.php'); ?>
