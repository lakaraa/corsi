<?php
include('../config.php');
include('../templates/template_header.php');
include('../pages/navbar.php');

// Controlla se è passato un ID valido
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die('ID corso mancante.');
}

$idCorso = $_GET['id'];

// Recupera i dettagli del corso
$query = $pdo->prepare("SELECT * FROM corso WHERE IdCorso = :id");
$query->execute(['id' => $idCorso]);
$course = $query->fetch(PDO::FETCH_ASSOC);

if (!$course) {
    die('Corso non trovato.');
}

// Recupera le categorie e gli istruttori per i dropdown
$categories = $pdo->query("SELECT IdCategoria, NomeCategoria FROM categoria")->fetchAll(PDO::FETCH_ASSOC);
$instructors = $pdo->query("SELECT IdIstruttore, CONCAT(Nome, ' ', Cognome) AS NomeIstruttore FROM istruttore")->fetchAll(PDO::FETCH_ASSOC);

// Se il form è stato inviato
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $durata = $_POST['durata'];
    $dataInizio = $_POST['data_inizio'];
    $dataFine = $_POST['data_fine'];
    $idIstruttore = $_POST['id_istruttore'];
    $idCategoria = $_POST['id_categoria'];
    $idAmministratore = $_POST['id_amministratore'];

    // Aggiorna il corso
    $updateQuery = $pdo->prepare("
        UPDATE corso
        SET Nome = :nome, Durata = :durata, DataInizio = :data_inizio, DataFine = :data_fine,
            IdIstruttore = :id_istruttore, IdCategoria = :id_categoria, IdAmministratore = :id_amministratore
        WHERE IdCorso = :id
    ");
    $updateQuery->execute([
        'nome' => $nome,
        'durata' => $durata,
        'data_inizio' => $dataInizio,
        'data_fine' => $dataFine,
        'id_istruttore' => $idIstruttore,
        'id_categoria' => $idCategoria,
        'id_amministratore' => $idAmministratore,
        'id' => $idCorso,
    ]);

    header('Location: dashboard.php');
    exit;
}
?>

<!-- Header Section -->
<header class="header-bg">
    <div class="overlay"></div>
    <div class="container text-center text-white d-flex align-items-center justify-content-center flex-column">
        <h1 class="hero-title">Dashboard Amministratore</h1>
        <p class="hero-subtext">Gestisci corsi, utenti, e iscrizioni agli studenti.</p>
    </div>
</header>

<!-- Main Content -->
<div class="container my-5">
    <h1 class="mb-4 text-center">Modifica Corso</h1>
    <form method="POST" class="p-4 border rounded shadow-sm bg-light">
        <div class="mb-3">
            <label for="nome" class="form-label">Nome Corso:</label>
            <input type="text" id="nome" name="nome" class="form-control" value="<?php echo htmlspecialchars($course['Nome']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="durata" class="form-label">Durata (ore):</label>
            <input type="number" id="durata" name="durata" class="form-control" value="<?php echo htmlspecialchars($course['Durata']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="data_inizio" class="form-label">Data Inizio:</label>
            <input type="date" id="data_inizio" name="data_inizio" class="form-control" value="<?php echo htmlspecialchars($course['DataInizio']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="data_fine" class="form-label">Data Fine:</label>
            <input type="date" id="data_fine" name="data_fine" class="form-control" value="<?php echo htmlspecialchars($course['DataFine']); ?>" required>
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
        <button type="submit" class="btn btn-primary w-100">Salva Modifiche</button>
    </form>
</div>

<?php include('../templates/template_footer.php'); ?>
