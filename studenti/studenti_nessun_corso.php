<?php
include('../config/config.php');
include('../templates/template_header.php');
include('../pages/navbar.php');

// Assicurati che la connessione al database funzioni
if (!$pdo) {
    die("Connessione al database fallita.");
}

// Query per ottenere studenti non iscritti a nessun corso
try {
    $stmt = $pdo->prepare("
        SELECT * 
        FROM studente 
        WHERE IdStudente NOT IN (
            SELECT DISTINCT IdStudente 
            FROM Iscrizione
        )
    ");
    $stmt->execute();
    $studenti = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Errore nella query: " . $e->getMessage());
}
?>

<!-- Header della Pagina -->
<header class="header-bg">
    <div class="overlay"></div>
    <div class="container text-center text-white d-flex align-items-center justify-content-center flex-column">
        <h1 class="hero-title">Studenti Non Iscritti a Nessun Corso</h1>
        <p class="hero-subtext">Visualizza la lista degli studenti che non sono iscritti a nessun corso.</p>
    </div>
</header>

<!-- Contenuto principale -->
<section class="section py-5 bg-light">
    <div class="container">
        <h3 class="mb-4 text-dark">Lista Studenti Non Iscritti</h3>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Cognome</th>
                        <th>Email</th>
                        <th>Telefono</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($studenti as $studente): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($studente['Nome']); ?></td>
                            <td><?php echo htmlspecialchars($studente['Cognome']); ?></td>
                            <td><?php echo htmlspecialchars($studente['Email']); ?></td>
                            <td><?php echo htmlspecialchars($studente['Telefono']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php if (empty($studenti)): ?>
            <div class="alert alert-info text-center mt-4">
                Nessuno studente trovato non iscritto a un corso.
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include('../templates/template_footer.php'); ?>
