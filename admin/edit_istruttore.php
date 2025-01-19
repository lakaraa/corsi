<?php
include('../config.php');
include('../templates/template_header.php');
include('../pages/navbar.php');

// Controlla se è passato un ID valido
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die('ID istruttore mancante.');
}

$idIstruttore = $_GET['id'];

// Recupera i dettagli dell'istruttore
$query = $pdo->prepare("SELECT * FROM istruttore WHERE IdIstruttore = :id");
$query->execute(['id' => $idIstruttore]);
$instructor = $query->fetch(PDO::FETCH_ASSOC);

if (!$instructor) {
    die('Istruttore non trovato.');
}

// Se il form è stato inviato
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $cognome = $_POST['cognome'];
    $telefono = $_POST['telefono'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $specializzazione = $_POST['specializzazione'];

    // Aggiorna l'istruttore
    $updateQuery = $pdo->prepare("
        UPDATE istruttore
        SET Nome = :nome, Cognome = :cognome, Telefono = :telefono, Email = :email, 
            Password = :password, Specializzazione = :specializzazione
        WHERE IdIstruttore = :id
    ");
    $updateQuery->execute([
        'nome' => $nome,
        'cognome' => $cognome,
        'telefono' => $telefono,
        'email' => $email,
        'password' => $password,
        'specializzazione' => $specializzazione,
        'id' => $idIstruttore,
    ]);

    // Creazione della query SQL da inserire nel file .sql
    $sqlOperation = sprintf(
        "UPDATE istruttore SET Nome = '%s', Cognome = '%s', Telefono = '%s', Email = '%s', Password = '%s', Specializzazione = '%s' WHERE IdIstruttore = %d;\n",
        $nome,
        $cognome,
        $telefono,
        $email,
        $password,
        $specializzazione,
        $idIstruttore
    );

    // Scrive la query nel file 'sql_insert.sql'
    file_put_contents('../sql_insert.sql', $sqlOperation, FILE_APPEND);


    // Redirect dopo l'aggiornamento
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
    <h1 class="mb-4 text-center">Modifica Istruttore</h1>
    <form method="POST" class="p-4 border rounded shadow-sm bg-light">
        <div class="mb-3">
            <label for="nome" class="form-label">Nome:</label>
            <input type="text" id="nome" name="nome" class="form-control" value="<?php echo htmlspecialchars($instructor['Nome']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="cognome" class="form-label">Cognome:</label>
            <input type="text" id="cognome" name="cognome" class="form-control" value="<?php echo htmlspecialchars($instructor['Cognome']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="telefono" class="form-label">Telefono:</label>
            <input type="tel" id="telefono" name="telefono" class="form-control" value="<?php echo htmlspecialchars($instructor['Telefono']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email:</label>
            <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($instructor['Email']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password:</label>
            <input type="password" id="password" name="password" class="form-control" value="<?php echo htmlspecialchars($instructor['Password']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="specializzazione" class="form-label">Specializzazione:</label>
            <input type="text" id="specializzazione" name="specializzazione" class="form-control" value="<?php echo htmlspecialchars($instructor['Specializzazione']); ?>" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Salva Modifiche</button>
    </form>
</div>

<?php include('../templates/template_footer.php'); ?>
