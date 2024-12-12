<?php
session_start(); 
require_once 'config.php';  // Includi il file di configurazione con la connessione PDO

// Configurazioni dinamiche
$pageTitle = "Homepage";
include('template_header.php'); 

$menu_items = [
    ["name" => "Home", "link" => "index.php"],
    ["name" => "Corsi", "link" => "corsi.php"],
    ["name" => "About Us", "link" => "aboutUs.php"],
    ["name" => "Contact", "link" => "contact.php"],
    ["name" => "Login", "link" => "login.php"],
    ["name" => "Amministratore", "link" => "amministratoreDashboard.php"],
    ["name" => "Istruttori", "link" => "istruttoreDashboard.php"],
    ["name" => "Studenti", "link" => "studentDashboard.php"],
];
?>

<!-- Header Section -->
<header class="header-bg">
    <div class="overlay"></div>
    <div class="container text-center text-white d-flex align-items-center justify-content-center flex-column">
        <h1 class="hero-title">Trasforma le tue competenze con i nostri corsi online</h1>
        <p class="hero-subtext">Accedi a conoscenze di qualità ovunque ti trovi.</p>
        <div class="mt-4">
            <a href="#courses" class="btn btn-primary btn-lg mr-2">Scopri tutti i Corsi</a>
            <a href="#about" class="btn btn-outline-light btn-lg">Leggi di più</a>
        </div>
    </div>
</header>

<!-- Courses Section -->
<section class="section py-5 bg-light" id="courses">
    <div class="container">
        <h3 class="text-center mb-4">I nostri Corsi</h3>
        <div class="row">
            <?php
            try {
                // Query per ottenere al massimo 6 corsi dal database
                $sql = "SELECT Nome, Durata, DataInizio, DataFine, idCorso FROM Corso LIMIT 6";
                $stmt = $pdo->query($sql);

                if ($stmt->rowCount() > 0) {
                    while ($course = $stmt->fetch()) {
                        $courseName = htmlspecialchars($course['Nome']);
                        $courseImage = "image/" . str_replace(' ', '', $courseName) . ".png";
                        $courseDescription = "Durata: {$course['Durata']} ore | Inizio: {$course['DataInizio']} | Fine: {$course['DataFine']}";
                        $courseId = $course['idCorso'];
            ?>
            <div class="col-sm-6 col-md-4 mb-4">
                <div class="card text-center border-0 shadow">
                    <div class="services-terri-figure position-relative">
                        <img src="<?= htmlspecialchars($courseImage) ?>" alt="<?= $courseName ?>" class="img-fluid rounded">
                        <!-- Verifica se l'utente è loggato e reindirizza di conseguenza -->
                        <a href="javascript:void(0);" class="lens-icon position-absolute top-50 start-50 translate-middle"
                           onclick="redirectToCourse(<?= $courseId ?>)">
                            <i class="fas fa-search"></i>
                        </a>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title"><?= $courseName ?></h5>
                        <p class="card-text"><?= htmlspecialchars($courseDescription) ?></p>
                    </div>
                </div>
            </div>
            <?php
                    }
                } else {
                    echo "<p class='text-center'>Nessun corso disponibile al momento.</p>";
                }
            } catch (PDOException $e) {
                echo "<p class='text-center'>Errore nel recupero dei corsi: " . $e->getMessage() . "</p>";
            }
            ?>
        </div>

        <!-- Messaggio aggiuntivo -->
        <div class="text-center mt-4">
            <p>Ci sono altri corsi disponibili! Visita la nostra <a href="corsi.php">pagina Corsi</a> per scoprirli tutti.</p>
        </div>
    </div>
</section>

<script>
    function redirectToCourse(courseId) {
        // Controlla se l'utente è loggato
        var studentId = '<?= isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '' ?>';

        if (!studentId) {
            // Se l'utente non è loggato, invia l'utente alla pagina di login
            window.location.href = "login.php";
        } else {
            // Se l'utente è loggato, reindirizza alla pagina di iscrizione corso
            window.location.href = "iscrizione_corso.php?corso_id=" + courseId;
        }
    }
</script>

<?php include('template_footer.php'); ?>
