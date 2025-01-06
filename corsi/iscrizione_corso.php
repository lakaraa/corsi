<?php
include('../config.php');
include('../templates/template_header.php');
session_start();

// Verifica se l'utente è loggato
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'studente') {
    header("Location: ../pages/login.php");
    exit;
}

// Recupera l'ID dello studente dalla sessione
$userId = $_SESSION['user_id'];

// Controlla se il corso specifico è stato selezionato
if (isset($_GET['corso_id'])) {
    $courseId = $_GET['corso_id'];

    try {
        // Recupera il corso specifico selezionato
        $sqlCourse = "
            SELECT c.IdCorso, c.Nome AS corso_nome, c.Durata, c.DataInizio, c.DataFine, 
                   ist.Nome AS istruttore_nome, ist.Cognome AS istruttore_cognome, cat.NomeCategoria
            FROM corso c
            JOIN istruttore ist ON c.IdIstruttore = ist.IdIstruttore
            JOIN categoria cat ON c.IdCategoria = cat.IdCategoria
            WHERE c.IdCorso = :courseId
        ";

        $stmtCourse = $pdo->prepare($sqlCourse);
        $stmtCourse->bindParam(':courseId', $courseId, PDO::PARAM_INT);
        $stmtCourse->execute();
        $course = $stmtCourse->fetch(PDO::FETCH_ASSOC);

        if (!$course) {
            header("Location: ../index.php");
            exit;
        }

    } catch (PDOException $e) {
        echo "<p>Errore nella query: " . $e->getMessage() . "</p>";
        exit;
    }
} else {
    header("Location: ../index.php");
    exit;
}

// Gestione iscrizione corso
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['course_id'])) {
    $courseId = $_POST['course_id'];

    // Verifica se l'utente non è già iscritto
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


    <!-- Header Section -->
    <header class="header-bg">
        <div class="overlay"></div>
        <div class="container text-center text-white d-flex align-items-center justify-content-center flex-column">
            <h1 class="hero-title">Corsi Online - Scopri il corso giusto per te</h1>
            <p class="hero-subtext">Esplora corsi con filtri personalizzati in modo rapido e semplice.</p>
        </div>
    </header>


    <!-- Sezione del corso selezionato -->
    <section class="section py-5 bg-light">
        <div class="container">
            <?php if (isset($message)): ?>
                <div class="alert alert-success text-center">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card shadow-lg border-0">
                        <div class="card-body">
                            <h5 class="card-title text-center"><?= htmlspecialchars($course['corso_nome']) ?></h5>
                            <p><strong>Durata:</strong> <?= htmlspecialchars($course['Durata']) ?> ore</p>
                            <p><strong>Data Inizio:</strong> <?= htmlspecialchars($course['DataInizio']) ?></p>
                            <p><strong>Data Fine:</strong> <?= htmlspecialchars($course['DataFine']) ?></p>
                            <p><strong>Istruttore:</strong> <?= htmlspecialchars($course['istruttore_nome']) . ' ' . htmlspecialchars($course['istruttore_cognome']) ?></p>
                            <p><strong>Categoria:</strong> <?= htmlspecialchars($course['NomeCategoria']) ?></p>
                            <div class="text-center mt-3">
                                <form action="../corsi/iscrizione_corso.php?corso_id=<?= htmlspecialchars($course['IdCorso']) ?>" method="post">
                                    <input type="hidden" name="course_id" value="<?= htmlspecialchars($course['IdCorso']) ?>">
                                    <button type="submit" class="btn btn-success btn-lg">Iscriviti al Corso</button>
                                </form>
                                <a href="../index.php" class="btn btn-primary btn-lg mt-3">Torna alla Home</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<? include('../templates/template_footer.php');?>
