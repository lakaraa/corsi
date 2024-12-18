<?php
include('config.php');
include('template_header.php');
include('navbar.php');

// Assicurati che la connessione al database funzioni
if (!$pdo) {
    die("Connessione al database fallita.");
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
        <h3 class="mb-4 text-dark">Benvenuto nella Dashboard Amministratore</h3>
        <p class="lead mb-5 text-muted">Gestisci corsi, studenti, istruttori e altri utenti in modo semplice e veloce.</p>
        <!-- Barra di Ricerca -->
<section class="section py-4 bg-light">
    <div class="container">
        <form action="search_results_ammin.php" method="GET" class="search-bar p-3 border rounded shadow-sm bg-light">
            <div class="d-flex align-items-center" style="gap: 10px;">
                <input type="text" name="search_query" class="form-control search-input" 
                       placeholder="Cerca corsi, categorie, istruttori o studenti..."
                       value="<?php echo isset($_GET['search_query']) ? htmlspecialchars($_GET['search_query']) : ''; ?>" 
                       style="flex: 1; padding: 12px; font-size: 16px; border-radius: 8px; border: 1px solid #ced4da;">
                <button type="submit" class="btn btn-primary search-button">Cerca</button>
            </div>
        </form>
    </div>
</section>
        <div class="row">
            <!-- Gestione Corsi e Categorie -->
            <div class="col-md-4 mb-4">
                <div class="card shadow-lg border-0">
                    <div class="card-body text-center">
                        <h5 class="card-title mb-3 text-dark">Aggiungi Corsi e Categorie</h5>
                        <a href="aggiungi_corsi_categoria.php" class="btn btn-primary btn-lg w-100">
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
                        <a href="visualizza_messaggi.php" class="btn btn-primary btn-lg w-100">
                            <i class="fas fa-envelope"></i> Messaggi
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include('template_footer.php'); ?>

<style>
    .search-bar {
    max-width: 800px;
    margin: 0 auto;
    border: 1px solid #ced4da;
}

.search-input {
    border: 1px solid #ced4da;
    border-radius: 8px;
}

.search-button {
    border-radius: 8px;
}
</style>