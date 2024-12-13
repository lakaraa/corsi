<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include('config.php'); // Connessione al DB con PDO

if (isset($_SESSION['user_id'])) {
    // Ottieni l'ID dell'utente loggato
    $user_id = $_SESSION['user_id'];

    // Verifica se l'utente è uno studente, un istruttore o un amministratore
    try {
        // Verifica se è uno studente
        $query = "SELECT * FROM studente WHERE IdStudente = :user_id";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['user_id' => $user_id]);
        if ($stmt->rowCount() > 0) {
            $user_type = 'studente';
        } else {
            // Verifica se è un istruttore
            $query = "SELECT * FROM istruttore WHERE IdIstruttore = :user_id";
            $stmt = $pdo->prepare($query);
            $stmt->execute(['user_id' => $user_id]);
            if ($stmt->rowCount() > 0) {
                $user_type = 'istruttore';
            } else {
                // Verifica se è un amministratore
                $query = "SELECT * FROM amministratore WHERE IdAmministratore = :user_id";
                $stmt = $pdo->prepare($query);
                $stmt->execute(['user_id' => $user_id]);
                if ($stmt->rowCount() > 0) {
                    $user_type = 'amministratore';
                } else {
                    $user_type = 'guest';
                }
            }
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        $user_type = 'guest';
    }
}
?>

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
                <li class="nav-item">
                        <a class="nav-link" href="<?php 
                            if ($user_type === 'studente') {
                                echo 'studentDashboard.php';
                            } elseif ($user_type === 'istruttore') {
                                echo 'istruttoreDashboard.php';
                            } elseif ($user_type === 'amministratore') {
                                echo 'amministratoreDashboard.php';
                            }
                        ?>">
                            Profilo
                        </a>
                <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>
