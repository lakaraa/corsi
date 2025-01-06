<?php
// Inclusione del template header
include('../templates/template_header.php');

// Inclusione della navbar in base allo stato dell'utente
if (isset($_SESSION['user_id'])) {
    include('navbar.php');
} else {
    include('navbar_guest.php');
}
?>

<!-- Header Section -->
<header class="header-bg">
    <div class="overlay"></div>
    <div class="container text-center text-white d-flex align-items-center justify-content-center flex-column">
        <h1 class="hero-title">Impara, Cresci, Realizzati</h1>
        <p class="hero-subtext">I migliori corsi per il tuo successo, disponibili ovunque.</p>
    </div>
</header>


<!-- Main Content Section -->
<div class="container text-center py-5">
    <h1 class="mt-4">Grazie per averci contattato!</h1>
    <p class="mb-4">Abbiamo ricevuto il tuo messaggio e ti risponderemo il prima possibile.</p>
    <a href="../index.php" class="btn btn-primary mt-3">Torna alla Home</a>
</div>

<?php
// Inclusione del template footer
include('../templates/template_footer.php');
?>
