<?php
include('../config/config.php');
include('../templates/template_header.php');
include('../pages/navbar.php');

// Recupera gli studenti
$studentsQuery = "SELECT * FROM studente";
$studentsStmt = $pdo->query($studentsQuery);
$students = $studentsStmt->fetchAll(PDO::FETCH_ASSOC);

// Assicurati di controllare la connessione al database per evitare errori
if (!$pdo) {
    die("Connessione al database fallita.");
}
?>

<!-- Header Section -->
<header class="header-bg">
    <div class="overlay"></div>
    <div class="container text-center text-white d-flex align-items-center justify-content-center flex-column">
        <h1 class="hero-title">Gestione Studenti</h1>
        <p class="hero-subtext">Visualizza, modifica o elimina gli studenti.</p>
    </div>
</header>

<!-- Gestione Studenti Section -->
<section class="section py-5 bg-light">
    <div class="container">
        <div class="row mb-5">
            <div class="col-md-12">
                <h3 class="mb-4">Gestisci Studenti</h3>
                <?php if (count($students) > 0): ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Email</th>
                                <th>Ruolo</th>
                                <th>Azioni</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($students as $student): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($student['Nome']); ?></td>
                                    <td><?php echo htmlspecialchars($student['Email']); ?></td>
                                    <td><?php echo htmlspecialchars('Studente'); ?></td>
                                    <td>
                                        <!-- Modifica Studente -->
                                        <a href="../admin/edit_studente.php?id=<?php echo $student['IdStudente']; ?>" class="btn btn-warning btn-sm">Modifica</a>

                                        <!-- Elimina Studente -->
                                        <form action="../admin/delete_student.php" method="post" style="display:inline;">
                                            <input type="hidden" name="studentId" value="<?php echo $student['IdStudente']; ?>">
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Sei sicuro di voler eliminare questo studente?');">Elimina</button>
                                        </form>
                                        <!-- Dettagli Studente -->
                                        <a href="../studenti/informazioni_studente.php?id=<?php echo $student['IdStudente']; ?>" class="btn btn-info btn-sm">Dettagli</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>Non ci sono studenti registrati.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php include('../templates/template_footer.php'); ?>

