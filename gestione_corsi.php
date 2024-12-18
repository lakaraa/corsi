<?php
include('config.php'); // Connessione al database
include('template_header.php');
include('navbar.php');

// Recupera i corsi
$coursesQuery = "SELECT
    corso.IdCorso,
    corso.Nome As nomeCorso,
    categoria.NomeCategoria,
    CONCAT(istruttore.Nome, ' ', istruttore.Cognome) AS NomeIstruttore
FROM
    corso
JOIN
    categoria ON corso.IdCategoria = categoria.IdCategoria
JOIN
    istruttore ON corso.IdIstruttore = istruttore.IdIstruttore";

$coursesStmt = $pdo->query($coursesQuery);
$courses = $coursesStmt->fetchAll(PDO::FETCH_ASSOC);

// Assicurati di controllare la connessione al database per evitare errori
if (!$pdo) {
    die("Connessione al database fallita.");
}
?>

<!-- Header Section -->
<header class="header-bg">
    <div class="overlay"></div>
    <div class="container text-center text-white d-flex align-items-center justify-content-center flex-column">
        <h1 class="hero-title">Gestione Corsi</h1>
        <p class="hero-subtext">Visualizza, modifica o elimina i corsi disponibili.</p>
    </div>
</header>

<!-- Corsi disponibili Section -->
<section class="section py-5 bg-light">
    <div class="container">
        <div class="row mb-5">
            <div class="col-md-12">
                <h3 class="mb-4">Corsi disponibili</h3>
                <?php if (count($courses) > 0): ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nome Corso</th>
                                <th>Categoria</th>
                                <th>Nome Istruttore</th>
                                <th>Azioni</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($courses as $course): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($course['nomeCorso']); ?></td>
                                    <td><?php echo htmlspecialchars($course['NomeCategoria']); ?></td>
                                    <td><?php echo htmlspecialchars($course['NomeIstruttore']); ?></td>
                                    <td>
                                        <!-- Modifica Corso -->
                                        <a href="edit_corso.php?id=<?php echo $course['IdCorso']; ?>" class="btn btn-warning btn-sm">Modifica</a>

                                        <!-- Elimina Corso -->
                                        <form action="delete_corso.php" method="POST" style="display:inline;">
                                            <input type="hidden" name="courseId" value="<?php echo $course['IdCorso']; ?>">
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Sei sicuro di voler eliminare questo corso?');">Elimina</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>Non ci sono corsi disponibili.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php include('template_footer.php'); ?>
