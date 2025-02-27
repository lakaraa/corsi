<?php
// Avvio della sessione e inclusione della configurazione
session_start();
include('../config.php');

// Controllo della sessione per decidere quale navbar includere
if (isset($_SESSION['user_id'])) {
    include('navbar.php');
} else {
    include('navbar_guest.php');
}

// Inclusione dell'header
include('../templates/template_header.php');
?>
<!-- Header Section -->
<header class="header-bg">
    <div class="overlay"></div>
    <div class="container text-center text-white d-flex align-items-center justify-content-center flex-column">
        <h1 class="hero-title">Chi siamo</h1>
    </div>
</header>

<section class="section py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h4 class="mb-3">La Nostra Missione</h4>
                <p>Online Learning Hub è dedicato a fornire corsi di formazione online di alta qualità per professionisti e studenti che vogliono migliorare le proprie competenze. Offriamo corsi in una vasta gamma di settori, come sviluppo web, data science, marketing digitale, e molto altro.</p>
                <p>La nostra missione è rendere l'apprendimento accessibile a tutti, ovunque ti trovi, con contenuti aggiornati e di alta qualità che possono fare una vera differenza nella tua carriera.</p>
            </div>
            <div class="col-md-6">
                <img src="../resources/image/mission.png" alt="Mission Image" class="img-fluid rounded shadow">
            </div>
        </div>
    </div>
</section>

<!-- Team Section -->
<section class="section py-5">
    <div class="container">
        <h3 class="text-center mb-4">Il Nostro Team</h3>
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card text-center">
                    <img src="../resources/image/team1.png" class="card-img-top" alt="Team Member 1" loading="lazy">
                    <div class="card-body">
                        <h5 class="card-title">Mario Rossi</h5>
                        <p class="card-text">CEO & Founder</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card text-center">
                    <img src="../resources/image/team2.png" class="card-img-top" alt="Team Member 2" loading="lazy">
                    <div class="card-body">
                        <h5 class="card-title">Giulia Bianchi</h5>
                        <p class="card-text">CFO</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card text-center">
                    <img src="../resources/image/team3.png" class="card-img-top" alt="Team Member 3" loading="lazy">
                    <div class="card-body">
                        <h5 class="card-title">Marco Verdi</h5>
                        <p class="card-text">Head of Education</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include('../templates/template_footer.php'); ?>
