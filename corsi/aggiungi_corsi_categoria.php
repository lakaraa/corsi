<?php
include('../config.php');
include('../templates/template_header.php');
include('../pages/navbar.php');

// Recupera le categorie
$query = $pdo->query("SELECT IdCategoria, NomeCategoria FROM categoria");
$categories = $query->fetchAll(PDO::FETCH_ASSOC);

// Recupera gli istruttori
$queryIstruttori = $pdo->query("SELECT IdIstruttore, Nome FROM istruttore");
$instructors = $queryIstruttori->fetchAll(PDO::FETCH_ASSOC);

// Assicurati di controllare la connessione al database per evitare errori
if (!$pdo) {
    die("Connessione al database fallita.");
}
?>

<header class="header-bg">
    <div class="overlay"></div>
    <div class="container text-center text-white d-flex align-items-center justify-content-center flex-column">
        <h1 class="hero-title">Crea un Nuovo Corso</h1>
        <p class="hero-subtext">Inserisci tutte le informazioni per il nuovo corso.</p>
    </div>
</header>

<!-- Creazione Corso -->
<section class="section py-5 bg-light">
    <div class="container">
    <h3 class="mb-4">Crea un Nuovo Corso</h3>
                <form action="../admin/add_corso_handler.php" method="POST" class="p-4 border rounded shadow-sm bg-light">
                    <div class="mb-3">
                        <label for="nome_corso" class="form-label">Nome del Corso:</label>
                        <input type="text" id="nome_corso" name="nome_corso" class="form-control" placeholder="Inserisci il nome del corso" required>
                    </div>

                    <div class="mb-3">
                        <label for="durata" class="form-label">Durata (in ore):</label>
                        <input type="number" id="durata" name="durata" class="form-control" placeholder="Inserisci la durata" required>
                    </div>

                    <div class="mb-3">
                        <label for="data_inizio" class="form-label">Data di Inizio:</label>
                        <input type="date" id="data_inizio" name="data_inizio" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="data_fine" class="form-label">Data di Fine:</label>
                        <input type="date" id="data_fine" name="data_fine" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="id_categoria" class="form-label">Categoria:</label>
                        <select id="id_categoria" name="id_categoria" class="form-select custom-select" required>
                            <?php
                            $query = $pdo->query("SELECT IdCategoria, NomeCategoria FROM categoria");
                            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                                echo "<option value='{$row['IdCategoria']}'>{$row['NomeCategoria']}</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="id_istruttore" class="form-label">Istruttore:</label>
                        <select id="id_istruttore" name="id_istruttore" class="form-select custom-select" required>
                            <?php
                            $query = $pdo->query("SELECT IdIstruttore, Nome FROM istruttore");
                            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                                echo "<option value='{$row['IdIstruttore']}'>{$row['Nome']}</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="id_amministratore" class="form-label">Amministratore:</label>
                        <select id="id_amministratore" name="id_amministratore" class="form-select custom-select" required>
                            <?php
                            $query = $pdo->query("SELECT IdAmministratore, Nome FROM amministratore");
                            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                                echo "<option value='{$row['IdAmministratore']}'>{$row['Nome']}</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Aggiungi Corso</button>
                    </div>
                </form>
    </div>
</section>

<!-- Creazione Categoria -->
<section class="section py-5 bg-light">
    <div class="container">
    <h3 class="mb-4">Crea una Nuova Categoria</h3>
        <form action="../admin/add_categoria_handler.php" method="POST" class="p-4 border rounded shadow-sm bg-light">
            <div class="mb-3">
                <label for="nome_categoria" class="form-label">Nome Categoria:</label>
                <input type="text" id="nome_categoria" name="nome_categoria" class="form-control" placeholder="Inserisci il nome della categoria" required>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Aggiungi Categoria</button>
            </div>
        </form>
    </div>
</section>

<?php include('../templates/template_footer.php'); ?>
