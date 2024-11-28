<?php include('template_header.php');
$host = 'localhost'; // Cambia con i dettagli del tuo server
$user = 'corsi';
$password = 'password.123';
$dbname = 'corsi'; // Cambia con il nome del tuo database

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("Connessione al database fallita: " . $conn->connect_error);
}
?>
<!-- Header Section -->
<header class="header-bg">
    <div class="overlay"></div>
    <div class="container text-center text-white d-flex align-items-center justify-content-center flex-column">
        <h1 class="hero-title">Trasforma le tue competenze con i nostri corsi online</h1>
        <p class="hero-subtext">Accedi a conoscenze di qualità ovunque ti trovi.</p>
        <div class="mt-4">
            <a href="#courses" class="btn btn-primary btn-lg mr-2">Scopri i Corsi</a>
            <a href="#about" class="btn btn-outline-light btn-lg">Leggi di più</a>
        </div>
    </div>
</header>

<!-- Courses Section -->
<section class="section py-5 bg-light" id="courses">
    <div class="container">
        <h3 class="text-center mb-4">Our Courses</h3>
        <div class="row">
            <?php
            // Query per ottenere i corsi dal database
            $sql = "SELECT Nome, Durata, DataInizio, DataFine FROM Corso";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($course = $result->fetch_assoc()) {
                    $courseName = htmlspecialchars($course['Nome']);
                    $courseImage = "image/" . str_replace(' ', '', $courseName) . ".png";
                    $courseDescription = "Durata: {$course['Durata']} ore | Inizio: {$course['DataInizio']} | Fine: {$course['DataFine']}";
            ?>
                    <div class="col-sm-6 col-md-4 mb-4">
                        <div class="card text-center border-0 shadow">
                            <div class="services-terri-figure">
                                <img src="<?= htmlspecialchars($courseImage) ?>" alt="<?= $courseName ?>" class="img-fluid">
                                <a href="#" class="lens-icon"><i class="fas fa-search"></i></a>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title"><?= $courseName ?></h5>
                                <p><?= htmlspecialchars($courseDescription) ?></p>
                            </div>
                        </div>
                    </div>
            <?php
                }
            } else {
                echo "<p class='text-center'>Nessun corso disponibile al momento.</p>";
            }
            $conn->close();
            ?>
        </div>
    </div>
</section>

<?php include('template_footer.php'); ?>