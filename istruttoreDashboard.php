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

// Recupera gli studenti nei corsi attivi
$ongoingStudents = [];
$sqlOngoing = "
    SELECT 
        c.Nome AS NomeCorso, 
        s.Nome AS NomeStudente, 
        s.Cognome, 
        s.Email
    FROM corso c
    JOIN iscrizione i ON c.IdCorso = i.IdCorso
    JOIN studente s ON i.IdStudente = s.IdStudente
    WHERE c.IdIstruttore = :userId
      AND c.DataInizio <= CURDATE()
      AND (c.DataFine IS NULL OR c.DataFine > CURDATE())
";
$stmtOngoing = $pdo->prepare($sqlOngoing);
$stmtOngoing->bindParam(':userId', $userId, PDO::PARAM_INT);
$stmtOngoing->execute();
$ongoingStudents = $stmtOngoing->fetchAll();

// Recupera gli studenti nei corsi completati
$completedStudents = [];
$sqlCompleted = "
    SELECT 
        c.Nome AS NomeCorso, 
        s.Nome AS NomeStudente, 
        s.Cognome, 
        s.Email
    FROM corso c
    JOIN iscrizione i ON c.IdCorso = i.IdCorso
    JOIN studente s ON i.IdStudente = s.IdStudente
    WHERE c.IdIstruttore = :userId
      AND c.DataFine <= CURDATE()
";
$stmtCompleted = $pdo->prepare($sqlCompleted);
$stmtCompleted->bindParam(':userId', $userId, PDO::PARAM_INT);
$stmtCompleted->execute();
$completedStudents = $stmtCompleted->fetchAll();
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
            <p class="hero-subtext">Visualizza gli studenti nei corsi in corso e completati.</p>
        </div>
    </header>

    <!-- Istruttore Dashboard Section -->
    <section class="section py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-4">Dashboard Istruttore</h2>
            <p class="text-center mb-5">Visualizza gli studenti nei corsi in corso e completati.</p>

            <!-- Studenti nei corsi in corso -->
            <div class="row mb-5">
                <div class="col-md-12">
                    <h3>Studenti nei Corsi in Corso</h3>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Corso</th>
                                <th>Studente</th>
                                <th>Email</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($ongoingStudents)): ?>
                                <?php foreach ($ongoingStudents as $student): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($student['NomeCorso']) ?></td>
                                        <td><?= htmlspecialchars($student['NomeStudente']) . ' ' . htmlspecialchars($student['Cognome']) ?></td>
                                        <td><?= htmlspecialchars($student['Email']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="3">Nessuno studente trovato.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Studenti nei corsi completati -->
            <div class="row mb-5">
                <div class="col-md-12">
                    <h3>Studenti nei Corsi Completati</h3>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Corso</th>
                                <th>Studente</th>
                                <th>Email</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($completedStudents)): ?>
                                <?php foreach ($completedStudents as $student): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($student['NomeCorso']) ?></td>
                                        <td><?= htmlspecialchars($student['NomeStudente']) . ' ' . htmlspecialchars($student['Cognome']) ?></td>
                                        <td><?= htmlspecialchars($student['Email']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="3">Nessuno studente trovato.</td>
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
