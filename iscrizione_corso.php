<?php
require_once 'config.php'; // Include il file di configurazione
include('navbar.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

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
        // Imposta la data di iscrizione (data corrente)
        $dateIscrizione = date('Y-m-d H:i:s'); // Ottieni la data e ora correnti

        // Iscrivi lo studente al corso con la data di iscrizione
        $sqlInsert = "INSERT INTO iscrizione (IdStudente, IdCorso, Livello, DataIscrizione) 
                      VALUES (:userId, :courseId, 'In corso', :dateIscrizione)";
        $stmtInsert = $pdo->prepare($sqlInsert);
        $stmtInsert->bindParam(':userId', $userId);
        $stmtInsert->bindParam(':courseId', $courseId);
        $stmtInsert->bindParam(':dateIscrizione', $dateIscrizione);
        $stmtInsert->execute();

        // Messaggio di successo
        $message = "Iscrizione al corso avvenuta con successo! Sarai reindirizzato alla home page.";

        // Impostare il reindirizzamento dopo 3 secondi
        echo "<script>
                setTimeout(function(){
                    window.location.href = 'index.php';
                }, 3000); // 3 secondi di attesa
              </script>";
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
    <!-- Header Section -->
    <header class="header-bg">
        <div class="overlay"></div>
        <div class="container text-center text-white d-flex align-items-center justify-content-center flex-column">
            <h1 class="hero-title">Iscriviti al Corso</h1>
            <p class="hero-subtext">Scegli un corso e inizia il tuo apprendimento !</p>
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
