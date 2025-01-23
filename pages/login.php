<?php
ob_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('../config.php');

// Controlla se la connessione al database esiste
if (!isset($pdo) || !$pdo) {
    die("Connessione al database fallita. Verifica le credenziali.");
}

// Includi il template header
include('../templates/template_header.php');

// Includi la navbar dinamica
if (!isset($_SESSION['user_id'])) {
    if (!file_exists('navbar_guest.php')) {
        die("File navbar_guest.php non trovato.");
    }
    include('navbar_guest.php');
} else {
    if (!file_exists('navbar.php')) {
        die("File navbar.php non trovato.");
    }
    include('navbar.php');
}
?>
<!-- Inizia il contenuto HTML -->
<header class="header-bg">
    <div class="overlay"></div>
    <div class="container text-center text-white d-flex align-items-center justify-content-center flex-column">
        <h1 class="hero-title">Scopri i nostri corsi</h1>
        <p class="hero-subtext">Accedi a conoscenze di qualità ovunque ti trovi.</p>
    </div>
</header>

<section class="section py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-4">Accedi al Tuo Account</h2>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-body">
                        <form action="../auth/login_handler.php" method="post">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <button type="submit" class="btn btn-primary btn-lg btn-block">Accedi</button>
                        </form>
                        <div class="text-center mt-3">
                            <p>Non hai un account? <a href="register.php">Registrati qui</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include('../templates/template_footer.php'); ?>
