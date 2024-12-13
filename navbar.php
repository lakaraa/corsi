<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start();  // Avvia la sessione solo se non è già attiva
}
include('config.php'); // Connessione al DB con PDO

$user_type = 'guest'; // Valore predefinito

// Verifica se l'utente è loggato
if (isset($_SESSION['user_email']) && isset($_SESSION['user_role'])) {
    $user_email = $_SESSION['user_email'];
    $user_role = $_SESSION['user_role']; // Recupera il ruolo dall sessione

    try {
        // Puoi fare altre verifiche se necessario, ma il ruolo è già impostato
    } catch (PDOException $e) {
        echo "Errore: " . $e->getMessage();
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
                
                <!-- Link per il profilo, con redirezione dinamica -->
                <li class="nav-item">
                    <a class="nav-link" href="<?php 
                        // Determina la destinazione in base al tipo di utente
                        if ($user_role === 'studente') {
                            echo 'studentDashboard.php';  // Dashboard per lo studente
                        } elseif ($user_role === 'istruttore') {
                            echo 'istruttoreDashboard.php'; // Dashboard per l'istruttore
                        } elseif ($user_role === 'amministratore') {
                            echo 'amministratoreDashboard.php'; // Dashboard per l'amministratore
                        } else {
                            echo 'login.php'; // Se l'utente non è loggato, reindirizza alla pagina di login
                        }
                    ?>">
                        Profilo
                    </a>
                </li>
                
                <!-- Logout -->
                <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>
