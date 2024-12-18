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
        
            <div class="container text-center">
                <div class="search-container">
                    <input 
                        type="text" 
                        name="search_query" 
                        class="search-input"
                        placeholder="Cerca corsi, categorie, istruttori o studenti...">
                    <button type="submit" class="search-button">🔍</button>
                </div>
                <div class="d-flex justify-content-center gap-2 mt-3">
                    <a href="corsi_20_iscritti.php" class="btn btn-info btn-lg">Corsi con più di 20 iscritti</a>
                    <a href="studenti_nessun_corso.php" class="btn btn-primary btn-lg">Studenti senza corso</a>
                </div>
            </div> <br>
   


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
/* Ridurre l'effetto zoom */
.btn:hover {
    transform: scale(1.05);
    transition: transform 0.3s ease-in-out;
}


.btn-primary {
    background-color: #007bff !important;
    border-color: #007bff !important;
}


.btn-info {
    background-color: #5bc0de !important;
    border-color: #5bc0de !important;
}


.search-container {
    position: relative;
    max-width: 1000px;
    margin: 20px auto 10px auto; 
}

.search-input {
    width: 100%;
    padding: 10px 40px 10px 15px;
    border: 1px solid #ced4da;
    border-radius: 25px;
    font-size: 16px;
}

.search-button {
    position: absolute;
    right: 5px;
    top: 50%;
    transform: translateY(-50%);
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 50%;
    height: 35px;
    width: 35px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background-color 0.3s ease, transform 0.2s ease;
}

.search-button:hover {
    background-color: #0056b3;
    transform: translateY(-50%) scale(1.1);
}

.d-flex .btn {
    margin: 0 10px; /* Spaziatura orizzontale tra i pulsanti */
}


</style>