<?php
include('../config.php');
include('../templates/template_header.php');
include('navbar.php');

// Recupera i messaggi dal database
try {
    $stmt = $pdo->prepare("SELECT * FROM messaggi ORDER BY created_at DESC");
    $stmt->execute();
    $messaggi = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Errore nella connessione al database: " . $e->getMessage());
}
?>

<!-- Header Section -->
<header class="header-bg">
    <div class="overlay"></div>
    <div class="container text-center text-white d-flex align-items-center justify-content-center flex-column">
        <h1 class="hero-title">Visualizza Messaggi</h1>
        <p class="hero-subtext">Consulta i messaggi inviati dagli utenti.</p>
    </div>
</header>

<!-- Contenuto principale -->
<div class="container my-5">
    <h3 class="mb-4 text-dark text-center">Messaggi Ricevuti</h3>
    <p class="lead mb-4 text-muted text-center">Consulta i dettagli di ciascun messaggio ricevuto.</p>

    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Nome</th>
                    <th scope="col">Email</th>
                    <th scope="col">Oggetto</th>
                    <th scope="col">Messaggio</th>
                    <th scope="col">Data</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($messaggi && count($messaggi) > 0): ?>
                    <?php foreach ($messaggi as $messaggio): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($messaggio['id']); ?></td>
                            <td><?php echo htmlspecialchars($messaggio['name']); ?></td>
                            <td><?php echo htmlspecialchars($messaggio['email']); ?></td>
                            <td><?php echo htmlspecialchars($messaggio['oggetto']); ?></td>
                            <td><?php echo htmlspecialchars(substr($messaggio['message'], 0, 50)) . (strlen($messaggio['message']) > 50 ? '...' : ''); ?></td>
                            <td><?php echo htmlspecialchars($messaggio['created_at']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">Nessun messaggio trovato</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include('../templates/template_footer.php'); ?>

