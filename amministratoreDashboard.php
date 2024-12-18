<?php
include('config.php');
include('template_header.php');
include('navbar.php');

// Assicurati che la connessione al database funzioni
if (!$pdo) {
    die("Connessione al database fallita.");
}
?>

<header class="header-bg" style="background-color: #f8f9fa;">
    <div class="overlay"></div>
    <div class="container text-center text-dark d-flex align-items-center justify-content-center flex-column">
        <h1 class="hero-title">Dashboard Amministratore</h1>
        <p class="hero-subtext">Gestisci corsi, utenti, e iscrizioni.</p>
    </div>
</header>

<!-- Admin Dashboard Section -->
<section class="section py-5 bg-light">
    <div class="container">
        <h3 class="mb-4" style="color: #333;">Benvenuto nella Dashboard Amministratore</h3>
        <p class="mb-5" style="color: #555;">Gestisci corsi, studenti, istruttori e altri utenti.</p>

        <div class="row">
            <!-- Gestione Corsi e Categorie -->
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center">
                        <h5 class="card-title mb-3" style="color: #333;">Gestione Corsi e Categorie</h5>
                        <a href="gestione_corsi_categoria.php" class="btn btn-custom-blue btn-lg w-100">
                            <i class="fas fa-bookmark"></i> Gestisci Corsi
                        </a>
                    </div>
                </div>
            </div>

            <!-- Lista Corsi -->
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center">
                        <h5 class="card-title mb-3" style="color: #333;">Lista Corsi</h5>
                        <a href="gestione_corsi.php" class="btn btn-custom-blue btn-lg w-100">
                            <i class="fas fa-list-alt"></i> Visualizza Corsi
                        </a>
                    </div>
                </div>
            </div>

            <!-- Gestione Studenti -->
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center">
                        <h5 class="card-title mb-3" style="color: #333;">Gestione Studenti</h5>
                        <a href="gestione_studenti.php" class="btn btn-custom-blue btn-lg w-100">
                            <i class="fas fa-users"></i> Gestisci Studenti
                        </a>
                    </div>
                </div>
            </div>

            <!-- Gestione Istruttori -->
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center">
                        <h5 class="card-title mb-3" style="color: #333;">Gestione Istruttori</h5>
                        <a href="gestione_istruttori.php" class="btn btn-custom-blue btn-lg w-100">
                            <i class="fas fa-chalkboard-teacher"></i> Gestisci Istruttori
                        </a>
                    </div>
                </div>
            </div>

            <!-- Aggiungi Istruttore/Amministratore -->
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center">
                        <h5 class="card-title mb-3" style="color: #333;">Aggiungi Istruttore/Amministratore</h5>
                        <a href="registrazione_utenti.php" class="btn btn-custom-blue btn-lg w-100">
                            <i class="fas fa-user-plus"></i> Aggiungi Utente
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include('template_footer.php'); ?>
