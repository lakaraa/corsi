<?php
include('../config.php');
include('../templates/template_header.php');
include('../pages/navbar.php');
// Controlla se è passato un ID valido
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die('ID studente mancante.');
}

$idStudente = $_GET['id'];

// Recupera i dettagli dello studente
$query = $pdo->prepare("SELECT * FROM studente WHERE IdStudente = :id");
$query->execute(['id' => $idStudente]);
$student = $query->fetch(PDO::FETCH_ASSOC);

if (!$student) {
    die('Studente non trovato.');
}

// Se il form è stato inviato
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $cognome = $_POST['cognome'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];
    $password = $_POST['password']; // Puoi gestire la password in modo sicuro con un hash

    // Aggiorna lo studente
    $updateQuery = $pdo->prepare("
        UPDATE studente
        SET Nome = :nome, Cognome = :cognome, Email = :email, Telefono = :telefono, Password = :password
        WHERE IdStudente = :id
    ");
    $updateQuery->execute([
        'nome' => $nome,
        'cognome' => $cognome,
        'email' => $email,
        'telefono' => $telefono,
        'password' => password_hash($password, PASSWORD_DEFAULT), // Aggiungi una sicurezza per la password
        'id' => $idStudente,
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
    <h1 class="mb-4 text-center">Modifica Studente</h1>
    <form method="POST" class="p-4 border rounded shadow-sm bg-light">
        <div class="mb-3">
            <label for="nome" class="form-label">Nome:</label>
            <input type="text" id="nome" name="nome" class="form-control" value="<?php echo htmlspecialchars($student['Nome']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="cognome" class="form-label">Cognome:</label>
            <input type="text" id="cognome" name="cognome" class="form-control" value="<?php echo htmlspecialchars($student['Cognome']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email:</label>
            <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($student['Email']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="telefono" class="form-label">Telefono:</label>
            <input type="tel" id="telefono" name="telefono" class="form-control" value="<?php echo htmlspecialchars($student['Telefono']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password:</label>
            <input type="password" id="password" name="password" class="form-control" placeholder="Lascia vuoto se non vuoi cambiare la password">
        </div>
        <button type="submit" class="btn btn-primary w-100">Salva Modifiche</button>
    </form>
</div>

<?php include('../templates/template_footer.php'); ?>
