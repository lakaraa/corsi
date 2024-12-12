<?php
require_once 'config.php'; // Include il file di configurazione

// Inizia la sessione
session_start();

// Verifica se l'utente è loggato
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'studente') {
    header("Location: login.php");
    exit;
}

// Recupera l'ID dello studente dalla sessione
$userId = $_SESSION['user_id'];

// Recupera i corsi disponibili
$coursesAvailable = [];
$sqlAvailable = "
    SELECT c.IdCorso, c.Nome AS corso_nome, c.Durata, c.DataInizio, c.DataFine, ist.Nome AS istruttore_nome, ist.Cognome AS istruttore_cognome, cat.NomeCategoria
    FROM corso c
    JOIN istruttore ist ON c.IdIstruttore = ist.IdIstruttore
    JOIN categoria cat ON c.IdCategoria = cat.IdCategoria
    WHERE c.DataInizio > CURDATE() -- Solo corsi futuri
";
$stmtAvailable = $pdo->prepare($sqlAvailable);
$stmtAvailable->execute();
$coursesAvailable = $stmtAvailable->fetchAll(PDO::FETCH_ASSOC);

// Gestisci l'iscrizione al corso
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['course_id'])) {
    $courseId = $_POST['course_id'];

    // Verifica che l'utente non sia già iscritto al corso
    $sqlCheck = "SELECT * FROM iscrizione WHERE IdStudente = :userId AND IdCorso = :courseId";
    $stmtCheck = $pdo->prepare($sqlCheck);
    $stmtCheck->bindParam(':userId', $userId);
    $stmtCheck->bindParam(':courseId', $courseId);
    $stmtCheck->execute();
    
    if ($stmtCheck->rowCount() == 0) {
        // Iscrivi lo studente al corso
        $sqlInsert = "INSERT INTO iscrizione (IdStudente, IdCorso, Livello) VALUES (:userId, :courseId, 'In corso')";
        $stmtInsert = $pdo->prepare($sqlInsert);
        $stmtInsert->bindParam(':userId', $userId);
        $stmtInsert->bindParam(':courseId', $courseId);
        $stmtInsert->execute();

        $message = "Iscrizione al corso avvenuta con successo!";
    } else {
        $message = "Sei già iscritto a questo corso!";
    }
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iscrizione Corso | Online Courses</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/x-icon" href="image/logo.png">
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
            <h1 class="hero-title">Iscriviti al Corso</h1>
            <p class="hero-subtext">Scegli un corso e inizia il tuo apprendimento oggi!</p>
        </div>
    </header>

    <!-- Iscrizione Corso Section -->
    <section class="section py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-4">Corsi Disponibili per l'Iscrizione</h2>
            <?php if (isset($message)): ?>
                <div class="alert alert-info text-center">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>
            <div class="row">
                <?php foreach ($coursesAvailable as $course): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($course['corso_nome']); ?></h5>
                                <p><strong>Durata:</strong> <?php echo htmlspecialchars($course['Durata']); ?> ore</p>
                                <p><strong>Inizio:</strong> <?php echo htmlspecialchars($course['DataInizio']); ?></p>
                                <p><strong>Fine:</strong> <?php echo htmlspecialchars($course['DataFine']); ?></p>
                                <p><strong>Istruttore:</strong> <?php echo htmlspecialchars($course['istruttore_nome']) . ' ' . htmlspecialchars($course['istruttore_cognome']); ?></p>
                                <p><strong>Categoria:</strong> <?php echo htmlspecialchars($course['NomeCategoria']); ?></p>
                                <form action="iscrizione_corso.php" method="post">
                                    <input type="hidden" name="course_id" value="<?php echo $course['IdCorso']; ?>">
                                    <button type="submit" class="btn btn-success btn-block">Iscriviti al Corso</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

    <?php include('template_footer.php'); ?>
</body>
</html>
