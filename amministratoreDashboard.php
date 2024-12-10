<?php
include('config.php'); // Includi la connessione al database
include('template_header.php');

// Recupera i corsi
$coursesQuery = "SELECT
    corso.Nome As nomeCorso,
    categoria.NomeCategoria,
    CONCAT(istruttore.Nome, ' ', istruttore.Cognome) AS NomeIstruttore
FROM
    corso
JOIN
    categoria ON corso.IdCategoria = categoria.IdCategoria
JOIN
    istruttore ON corso.IdIstruttore = istruttore.IdIstruttore;
"; 
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

// Aggiungi ulteriori query per altre tabelle, se necessario
// Ad esempio: Recupera altre tabelle come 'iscrizioni' o 'pagamenti'

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
    </div>
</header>

<!-- Admin Dashboard Section -->
<section class="section py-5 bg-light">
    <div class="container">
        <!-- Creazione Corsi -->
        <div class="row mb-5">
            <div class="col-md-12">
                <h3>Crea un Nuovo Corso</h3>
                <form action="add_corso_handler.php" method="POST">
                    <label for="nome_corso">Nome del Corso:</label>
                    <input type="text" id="nome_corso" name="nome_corso" required>

                    <label for="durata">Durata:</label>
                    <input type="number" id="durata" name="durata" required>

                    <label for="data_inizio">Data di Inizio:</label>
                    <input type="date" id="data_inizio" name="data_inizio" required>

                    <label for="data_fine">Data di Fine:</label>
                    <input type="date" id="data_fine" name="data_fine" required>

                    <label for="id_categoria">Categoria:</label>
                    <select id="id_categoria" name="id_categoria" required>
                        <?php
                        $query = $pdo->query("SELECT IdCategoria, NomeCategoria FROM categoria");
                        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                            echo "<option value='{$row['IdCategoria']}'>{$row['NomeCategoria']}</option>";
                        }
                        ?>
                    </select>

                    <label for="id_istruttore">Istruttore:</label>
                    <select id="id_istruttore" name="id_istruttore" required>
                        <?php
                        $query = $pdo->query("SELECT IdIstruttore, Nome FROM istruttore");
                        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                            echo "<option value='{$row['IdIstruttore']}'>{$row['Nome']}</option>";
                        }
                        ?>
                    </select>

                    <label for="id_amministratore">Amministratore:</label>
                    <select id="id_amministratore" name="id_amministratore" required>
                        <?php
                        $query = $pdo->query("SELECT IdAmministratore, Nome FROM amministratore");
                        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                            echo "<option value='{$row['IdAmministratore']}'>{$row['Nome']}</option>";
                        }
                        ?>
                    </select>
                    <button type="submit">Aggiungi Corso</button>
                </form>

            </div>
        </div>

        <!-- Visualizzazione Corsi -->
        <div class="row mb-5">
            <div class="col-md-12">
                <h3>I Corsi Creati</h3>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nome Corso</th>
                            <th>Categoria</th>
                            <th>Istruttore</th>
                            <th>Azioni</th>
                            </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($courses as $course): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($course['nomeCorso']); ?></td>
                                <td><?php echo htmlspecialchars($course['NomeCategoria']); ?></td>
                                <td><?php echo htmlspecialchars($course['NomeIstruttore']); ?></td>
                                <td><button class="btn btn-danger btn-sm">Elimina</button></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
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
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Sei sicuro di voler eliminare questo studente?');">Elimina</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
<!-- Creazione Nuovi Utenti (Amministratore e Istruttore) -->
<div class="row mb-5">
    <!-- Form Aggiungi un Amministratore -->
    <div class="col-md-6">
        <h3>Aggiungi un Amministratore</h3>
        <form action="add_ammin_handler.php" method="post">
            <div class="form-group">
                <label for="adminName">Nome</label>
                <input type="text" class="form-control" id="adminName" name="adminName" required>
            </div>
            <div class="form-group">
                <label for="adminSurname">Cognome</label>
                <input type="text" class="form-control" id="adminSurname" name="adminSurname" required>
            </div>
            <div class="form-group">
                <label for="adminEmail">Email</label>
                <input type="email" class="form-control" id="adminEmail" name="adminEmail" required>
            </div>
            <div class="form-group">
                <label for="adminPhone">Telefono</label>
                <input type="tel" class="form-control" id="adminPhone" name="adminPhone" required>
            </div>
            <div class="form-group">
                <label for="adminPassword">Password</label>
                <input type="password" class="form-control" id="adminPassword" name="adminPassword" required>
            </div>
            <button type="submit" class="btn btn-success">Aggiungi Amministratore</button>
        </form>
    </div>
    
    <!-- Form Aggiungi un Istruttore -->
    <div class="col-md-6">
        <h3>Aggiungi un Istruttore</h3>
        <form action="add_istru_handler.php" method="post">
            <div class="form-group">
                <label for="instructorName">Nome</label>
                <input type="text" class="form-control" id="instructorName" name="instructorName" required>
            </div>
            <div class="form-group">
                <label for="instructorSurname">Cognome</label>
                <input type="text" class="form-control" id="instructorSurname" name="instructorSurname" required>
            </div>
            <div class="form-group">
                <label for="instructorEmail">Email</label>
                <input type="email" class="form-control" id="instructorEmail" name="instructorEmail" required>
            </div>
            <div class="form-group">
                <label for="instructorPhone">Telefono</label>
                <input type="tel" class="form-control" id="instructorPhone" name="instructorPhone" required>
            </div>
            <div class="form-group">
                <label for="instructorSpecializzazione">Specializzazione</label>
                <input type="text" class="form-control" id="instructorSpecializzazione" name="instructorSpecializzazione" required>
            </div>
            <div class="form-group">
                <label for="instructorPassword">Password</label>
                <input type="password" class="form-control" id="instructorPassword" name="instructorPassword" required>
            </div>
            <button type="submit" class="btn btn-info">Aggiungi Istruttore</button>
        </form>
    </div>
</div>

</section>

<?php include('template_footer.php'); ?>
