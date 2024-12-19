<?php
include('../config/config.php');
include('../templates/template_header.php');
include('../pages/navbar.php');

// Recupera gli istruttori dal database
$instructorsQuery = "SELECT * FROM istruttore";
$instructorsStmt = $pdo->query($instructorsQuery);
$instructors = $instructorsStmt->fetchAll(PDO::FETCH_ASSOC);

// Assicurati di controllare la connessione al database per evitare errori
if (!$pdo) {
    die("Connessione al database fallita.");
}
?>

<!-- Header Section -->
<header class="header-bg">
    <div class="overlay"></div>
    <div class="container text-center text-white d-flex align-items-center justify-content-center flex-column">
        <h1 class="hero-title">Gestione Istruttori</h1>
        <p class="hero-subtext">Visualizza, modifica o elimina gli istruttori.</p>
    </div>
</header>

<!-- Gestione Istruttori Section -->
<section class="section py-5 bg-light">
    <div class="container">
        <div class="row mb-5">
            <div class="col-md-12">
                <h3 class="mb-4">Gestisci Istruttori</h3>
                <?php if (count($instructors) > 0): ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Cognome</th>
                                <th>Email</th>
                                <th>Specializzazione</th>
                                <th>Azioni</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($instructors as $instructor): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($instructor['Nome'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($instructor['Cognome'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($instructor['Email'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($instructor['Specializzazione'] ?? ''); ?></td>
                                    <td>
                                        <!-- Modifica Istruttore -->
                                        <a href="../admin/edit_istruttore.php?id=<?php echo $instructor['IdIstruttore']; ?>" class="btn btn-warning btn-sm">Modifica</a>

                                        <!-- Elimina Istruttore -->
                                        <form action="../admin/delete_instructor.php" method="POST" style="display:inline;">
                                            <input type="hidden" name="instructorId" value="<?php echo $instructor['IdIstruttore']; ?>">
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Sei sicuro di voler eliminare questo istruttore?');">Elimina</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>Non ci sono istruttori registrati.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php include('../templates/template_footer.php'); ?>
