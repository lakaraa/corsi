<?php
require_once 'config.php'; // Include il file di configurazione

// Inizia la sessione
session_start();

// Verifica se l'utente è loggato
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'studente') {
    header("Location: login.php");
    exit;
}

// Supponiamo che l'ID dello studente sia memorizzato in una sessione
$userId = $_SESSION['user_id']; // Assicurati di avere un sistema di autenticazione

// Recupera i corsi in cui lo studente è iscritto
$coursesEnrolled = [];
$sql = "
    SELECT c.Nome AS corso_nome, c.Durata, c.DataInizio, c.DataFine, c.IdIstruttore, ist.Nome AS istruttore_nome, ist.Cognome AS istruttore_cognome, cat.NomeCategoria
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

// Recupera i corsi disponibili
$coursesAvailable = [];
$sqlAvailable = "
    SELECT c.Nome AS corso_nome, c.Durata, c.DataInizio, c.DataFine, c.IdIstruttore, ist.Nome AS istruttore_nome, ist.Cognome AS istruttore_cognome, cat.NomeCategoria
    FROM corso c
    JOIN istruttore ist ON c.IdIstruttore = ist.IdIstruttore
    JOIN categoria cat ON c.IdCategoria = cat.IdCategoria
    WHERE c.DataInizio > CURDATE() -- Solo corsi futuri
";
$stmtAvailable = $pdo->prepare($sqlAvailable);
$stmtAvailable->execute();
$coursesAvailable = $stmtAvailable->fetchAll(PDO::FETCH_ASSOC);
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
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/x-icon" href="image/logo.png">
    <style>
        .course-details {
            display: none;
        }
        .btn-subscribe {
            background-color: #28a745;
            color: white;
            font-weight: bold;
        }
        .btn-subscribe:hover {
            background-color: #218838;
        }
        .btn-info {
            background-color: #007bff;
            color: white;
        }
        .btn-info:hover {
            background-color: #0056b3;
        }
        .table th, .table td {
            vertical-align: middle;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg fixed-top custom-navbar">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="image/logo.png" alt="Logo" width="40" height="40" class="d-inline-block align-middle">
                <span>Online Learning Hub</span>
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="corsi.php">Corsi</a></li>
                    <li class="nav-item"><a class="nav-link" href="aboutUs.php">About Us</a></li>
                    <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
                    <?php if (!isset($_SESSION['user_id'])): ?>
                        <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Header Section -->
    <header class="header-bg">
        <div class="overlay"></div>
        <div class="container text-center text-white d-flex align-items-center justify-content-center flex-column">
            <h1 class="hero-title">Dashboard Studente</h1>
            <p class="hero-subtext">Gestisci i tuoi corsi e il tuo apprendimento.</p>
            <a href="logout.php" class="btn btn-danger mt-4">Logout</a>
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
                                <?php echo htmlspecialchars($course['corso_nome']); ?>
                                <span class="badge badge-primary">Iscritto</span>
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
                                        <a href="iscrizione_corso.php" class="btn btn-subscribe btn-sm">Iscriviti</a>
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

    <script>
        function toggleDetails(courseId) {
            const courseDetails = document.getElementById(courseId);
            if (courseDetails.style.display === "none" || courseDetails.style.display === "") {
                courseDetails.style.display = "table-row"; // Mostra la riga della tabella
            } else {
                courseDetails.style.display = "none"; // Nasconde la riga della tabella
            }
        }
    </script>

    <?php include('template_footer.php'); ?>
</body>
</html>
