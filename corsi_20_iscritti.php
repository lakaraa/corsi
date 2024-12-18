<?php
include('config.php');
include('template_header.php');
include('navbar.php');

// Assicurati che la connessione al database funzioni
if (!$pdo) {
    die("Connessione al database fallita.");
}

// Query per ottenere i corsi con più di 20 studenti iscritti
try {
    $stmt = $pdo->prepare("
        SELECT 
            corso.IdCorso, 
            corso.Nome, 
            corso.Durata, 
            corso.DataInizio, 
            corso.DataFine, 
            COUNT(iscrizione.IdStudente) as numero_studenti
        FROM corso
        LEFT JOIN iscrizione ON corso.IdCorso = iscrizione.IdCorso
        GROUP BY corso.IdCorso, corso.Nome, corso.Durata, corso.DataInizio, corso.DataFine
        HAVING COUNT(iscrizione.IdStudente) > 20
    ");
    $stmt->execute();
    $corsi = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Errore nella query: " . $e->getMessage());
}
?>

<!-- Header della Pagina -->
<header class="header-bg">
    <div class="overlay"></div>
    <div class="container text-center text-white d-flex align-items-center justify-content-center flex-column">
        <h1 class="hero-title">Corsi con Più di 20 Studenti Iscritti</h1>
        <p class="hero-subtext">Scopri i corsi con un alto numero di studenti iscritti.</p>
    </div>
</header>

<!-- Contenuto principale -->
<section class="section py-5 bg-light">
    <div class="container">
        <h3 class="mb-4 text-dark">Corsi con Più di 20 Iscritti</h3>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Nome Corso</th>
                        <th>Durata</th>
                        <th>Data di Inizio</th>
                        <th>Data di Fine</th>
                        <th>Numero di Studenti Iscritti</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($corsi as $corso): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($corso['Nome']); ?></td>
                            <td><?php echo htmlspecialchars($corso['Durata']); ?> ore</td>
                            <td><?php echo htmlspecialchars($corso['DataInizio']); ?></td>
                            <td><?php echo htmlspecialchars($corso['DataFine']); ?></td>
                            <td><?php echo htmlspecialchars($corso['numero_studenti']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php if (empty($corsi)): ?>
            <div class="alert alert-info text-center mt-4">
                Nessun corso trovato con più di 20 studenti iscritti.
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include('template_footer.php'); ?>
