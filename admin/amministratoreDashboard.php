<?php
include('../session.php');
include_once('../config.php');
include('../templates/template_header.php');
include('../pages/navbar.php');

// Assicurati che la connessione al database funzioni
if (!$pdo) {
    die("Connessione al database fallita.");
}
//print_r($_SESSION);
ob_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$userId = $_SESSION['user_id'] ?? null;
if (!$userId) {
    die("Utente non autenticato.");
}
?>

<!-- Header Section -->
<header class="header-bg">
    <div class="overlay"></div>
    <div class="container text-center text-white d-flex align-items-center justify-content-center flex-column">
        <h1 class="hero-title">Dashboard Amministratore</h1>
        <p class="hero-subtext">Gestisci con facilità i tuoi corsi e i tuoi dipendenti.</p>
    </div>
</header>

<!-- Admin Dashboard Section -->
<section class="section py-5 bg-light">
    <div class="container">
        <h3 class="text-center mb-4">Benvenuto nella Dashboard Amministratore</h3>
        <p class="text-center mb-5">Gestisci corsi, studenti, istruttori e altri utenti in modo semplice e veloce.</p>

        <!-- Barra di Ricerca --> 
        <div class="container text-center">
            <!-- Form di ricerca principale -->
            <form action="../search/search_results_ammin.php" method="GET" class="search-bar p-3 border rounded shadow-sm bg-light">
                <div class="d-flex align-items-center justify-content-center" style="gap: 10px;">
                    <input 
                        type="text" 
                        name="search_query" 
                        class="form-control search-input" 
                        placeholder="Cerca corsi, categorie, istruttori o studenti..."
                        value="<?php echo isset($_GET['search_query']) ? htmlspecialchars($_GET['search_query']) : ''; ?>" 
                        style="flex: 1; padding: 12px; font-size: 16px; border-radius: 8px; border: 1px solid #ced4da;">
                    <button type="submit" class="btn btn-primary search-button">Cerca</button>
                </div>
            </form>

            <!-- Pulsanti aggiuntivi sotto la barra di ricerca -->
            <div class="d-flex justify-content-center gap-2 mt-3">
                <a href="../corsi/corsi_20_iscritti.php" class="btn btn-info btn-lg">Corsi con più di 20 iscritti</a>
                <a href="../studenti/studenti_nessun_corso.php" class="btn btn-primary btn-lg">Studenti senza corso</a>
            </div>
        </div>
        <br>

        <div class="row">
            <!-- Gestione Corsi e Categorie -->
            <div class="col-md-4 mb-4">
                <div class="card shadow-lg border-0">
                    <div class="card-body text-center">
                        <h5 class="card-title mb-3 text-dark">Aggiungi Corsi e Categorie</h5>
                        <a href="../corsi/aggiungi_corsi_categoria.php" class="btn btn-primary btn-lg w-100">
                            <i class="fas fa-bookmark"></i> Gestisci Corsi
                        </a>
                    </div>
                </div>
            </div>

            <!-- Lista Corsi -->
            <div class="col-md-4 mb-4">
                <div class="card shadow-lg border-0">
                    <div class="card-body text-center">
                        <h5 class="card-title mb-3 text-dark">Lista Corsi</h5>
                        <a href="gestione_corsi.php" class="btn btn-primary btn-lg w-100">
                            <i class="fas fa-list-alt"></i> Visualizza Corsi
                        </a>
                    </div>
                </div>
            </div>

            <!-- Gestione Studenti -->
            <div class="col-md-4 mb-4">
                <div class="card shadow-lg border-0">
                    <div class="card-body text-center">
                        <h5 class="card-title mb-3 text-dark">Gestione Studenti</h5>
                        <a href="gestione_studenti.php" class="btn btn-primary btn-lg w-100">
                            <i class="fas fa-users"></i> Gestisci Studenti
                        </a>
                    </div>
                </div>
            </div>

            <!-- Gestione Istruttori -->
            <div class="col-md-4 mb-4">
                <div class="card shadow-lg border-0">
                    <div class="card-body text-center">
                        <h5 class="card-title mb-3 text-dark">Gestione Istruttori</h5>
                        <a href="gestione_istruttori.php" class="btn btn-primary btn-lg w-100">
                            <i class="fas fa-chalkboard-teacher"></i> Gestisci Istruttori
                        </a>
                    </div>
                </div>
            </div>

            <!-- Aggiungi Istruttore/Amministratore -->
            <div class="col-md-4 mb-4">
                <div class="card shadow-lg border-0">
                    <div class="card-body text-center">
                        <h5 class="card-title mb-3 text-dark">Aggiungi Utente</h5>
                        <a href="registrazione_utenti.php" class="btn btn-primary btn-lg w-100">
                            <i class="fas fa-user-plus"></i> Aggiungi Utente
                        </a>
                    </div>
                </div>
            </div>

            <!-- Visualizza Messaggi -->
            <div class="col-md-4 mb-4">
                <div class="card shadow-lg border-0">
                    <div class="card-body text-center">
                        <h5 class="card-title mb-3 text-dark">Visualizza Messaggi</h5>
                        <a href="../pages/visualizza_messaggi.php" class="btn btn-primary btn-lg w-100">
                            <i class="fas fa-envelope"></i> Messaggi
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include('../templates/template_footer.php'); ?>

<style>
/* Barra di Ricerca */
.search-bar {
    max-width: 800px;
    margin: 0 auto;
    padding: 10px;
    background-color: #f8f9fa;
    border-radius: 8px;
    box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
}

.search-input {
    flex: 1;
    border-radius: 8px;
    border: 1px solid #ced4da;
    font-size: 16px;
    padding: 8px 12px;
}

.search-button {
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 8px;
    padding: 8px 16px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.search-button:hover {
    background-color: #0056b3;
}
.d-flex.justify-content-center.gap-2 {
    gap: 20px; /* Distanza tra i pulsanti */
}
</style>