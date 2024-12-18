<?php
require_once 'config.php';
include('navbar.php');
include('template_header.php');

// Verifica se è stato passato un ID tramite la query string
if (!isset($_GET['id'])) {
    die("ID studente non valido.");
}

$studentId = $_GET['id'];

// Recupera i dettagli dello studente
$stmtStudent = $pdo->prepare("SELECT * FROM studente WHERE IdStudente = :studentId");
$stmtStudent->bindParam(':studentId', $studentId);
$stmtStudent->execute();
$student = $stmtStudent->fetch(PDO::FETCH_ASSOC);

if (!$student) {
    die("Studente non trovato.");
}

// Recupera i corsi a cui lo studente è iscritto
$stmtCourses = $pdo->prepare("
    SELECT c.Nome AS corso_nome, c.Durata, c.DataInizio, c.DataFine, ist.Nome AS istruttore_nome, ist.Cognome AS istruttore_cognome, cat.NomeCategoria
    FROM corso c
    JOIN iscrizione isc ON c.IdCorso = isc.IdCorso
    JOIN istruttore ist ON c.IdIstruttore = ist.IdIstruttore
    JOIN categoria cat ON c.IdCategoria = cat.IdCategoria
    WHERE isc.IdStudente = :studentId
");
$stmtCourses->bindParam(':studentId', $studentId);
$stmtCourses->execute();
$courses = $stmtCourses->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Header Section -->
<header class="header-bg">
    <div class="overlay">
        <div class="container text-center text-white d-flex align-items-center justify-content-center flex-column">
            <h1 class="hero-title">Dashboard Studente</h1>
            <p class="hero-subtext">Gestisci i tuoi corsi e il tuo apprendimento.</p>
        </div> 
    </div>
</header>

<div class="container my-5">
    <h1 class="text-center mb-4">Dettagli Studente</h1>
    
    <!-- Informazioni Studente -->
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <div class="student-info">
                <div class="student-details">
                    <h4 class="mb-2"><?php echo htmlspecialchars($student['Nome']) . ' ' . htmlspecialchars($student['Cognome']); ?></h4>
                    <p class="mb-1"><strong>Email:</strong> <?php echo htmlspecialchars($student['Email']); ?></p>
                    <p class="mb-1"><strong>Telefono:</strong> <?php echo htmlspecialchars($student['Telefono']); ?></p>
                    <div class="text-right mt-3">
                        <a href="edit_studente.php?id=<?php echo urlencode($student['IdStudente']); ?>" class="btn btn-primary">Modifica</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Corsi iscritti -->
    <div class="card">
        <div class="card-body">
            <h4 class="mb-3">Corsi Iscritti</h4>
            <?php if (count($courses) > 0): ?>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nome Corso</th>
                            <th>Durata</th>
                            <th>Data Inizio</th>
                            <th>Data Fine</th>
                            <th>Istruttore</th>
                            <th>Categoria</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($courses as $course): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($course['corso_nome']); ?></td>
                                <td><?php echo htmlspecialchars($course['Durata']); ?> ore</td>
                                <td><?php echo htmlspecialchars($course['DataInizio']); ?></td>
                                <td><?php echo htmlspecialchars($course['DataFine']); ?></td>
                                <td><?php echo htmlspecialchars($course['istruttore_nome']) . ' ' . htmlspecialchars($course['istruttore_cognome']); ?></td>
                                <td><?php echo htmlspecialchars($course['NomeCategoria']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Lo studente non è iscritto a nessun corso.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include('template_footer.php'); ?>
