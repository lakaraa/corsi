<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include('config.php'); // Connessione al DB con PDO

$user_type = 'guest'; // Valore predefinito

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    try {
        // Query unificata per verificare il ruolo
        $query = "
            SELECT 'studente' AS user_type FROM studente WHERE IdStudente = :user_id
            UNION
            SELECT 'istruttore' AS user_type FROM istruttore WHERE IdIstruttore = :user_id
            UNION
            SELECT 'amministratore' AS user_type FROM amministratore WHERE IdAmministratore = :user_id
        ";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['user_id' => $user_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $user_type = $result['user_type'];
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
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
                <?php if ($user_type !== 'guest'): ?>
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
                    </li>
                    <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
