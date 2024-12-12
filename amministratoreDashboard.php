<?php
include('config.php'); // Includi la connessione al database
include('template_header.php');

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

// Recupera gli utenti
$studentsQuery = "SELECT *, CONCAT(s.Nome, ' ', s.Cognome) AS Nome FROM studente s";  
$studentsStmt = $pdo->query($studentsQuery);
$students = $studentsStmt->fetchAll(PDO::FETCH_ASSOC);

// Recupera gli istruttori
$instructorsQuery = "SELECT *, CONCAT(istruttore.Nome, ' ', istruttore.Cognome) AS NomeIstruttore FROM istruttore"; 
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
        <h1 class="hero-title">Dashboard Amministratore</h1>
        <p class="hero-subtext">Gestisci corsi, utenti, e iscrizioni agli studenti.</p>
        <a href="logout.php" class="btn btn-danger mt-4">Logout</a>
    </div>
</header>

<!-- Admin Dashboard Section -->
<section class="section py-5 bg-light">
    <div class="container">
        <!-- Creazione Corsi -->
        <div class="row mb-5">
            <div class="col-md-12">
                <h3 class="mb-4">Crea un Nuovo Corso</h3>
                <form action="add_corso_handler.php" method="POST" class="p-4 border rounded shadow-sm bg-light">
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
        </div>

       <!-- Visualizzazione Corsi -->
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
                       

        <!-- Visualizzazione Utenti -->
        <div class="row mb-5">
            <div class="col-md-12">
                <h3>Gestisci Utenti</h3>
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
                                    <form action="delete_student.php" method="post" style="display:inline;">
                                        <input type="hidden" name="studentId" value="<?php echo $student['IdStudente']; ?>">
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Sei sicuro di voler eliminare questo studente?');">Elimina</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Visualizzazione Istruttori -->
        <div class="row mb-5">
            <div class="col-md-12">
                <h3>Gestisci Istruttori</h3>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Email</th>
                            <th>Specializzazione</th>
                            <th>Azioni</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($instructors as $instructor): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($instructor['NomeIstruttore']); ?></td>
                                <td><?php echo htmlspecialchars($instructor['Email']); ?></td>
                                <td><?php echo htmlspecialchars($instructor['Specializzazione']); ?></td>
                                <td>
                                    <form action="delete_instructor.php" method="POST" style="display:inline;">
                                        <input type="hidden" name="instructorId" value="<?php echo $instructor['IdIstruttore']; ?>">
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Sei sicuro di voler eliminare questo istruttore?');">Elimina</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</section>
<script>
// Popola la modale con l'ID del corso e il nome quando l'utente clicca su "Elimina"
var deleteButtons = document.querySelectorAll('.btn-danger');
deleteButtons.forEach(function(button) {
    button.addEventListener('click', function() {
        var courseId = button.getAttribute('data-id');
        var courseName = button.getAttribute('data-nome');
        document.getElementById('courseId').value = courseId;
        document.getElementById('courseName').textContent = courseName;
    });
});
</script>
<?php include('template_footer.php'); ?>
