<?php
session_start(); 
include('config.php');

// Configurazioni dinamiche
$pageTitle = "Homepage";
include('templates/template_header.php'); 

// Includi la navbar dinamica
if (!isset($_SESSION['user_id'])) {
    include('navbar_guest_index.php'); // Navbar per gli utenti non loggati
} else {
    include('navbar_index.php'); // Navbar per gli utenti loggati
}
?>
<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Header Section -->
<header class="header-bg">
    <div class="overlay"></div>
    <div class="container text-center text-white d-flex align-items-center justify-content-center flex-column">
        <h1 class="hero-title">Trasforma le tue competenze con i nostri corsi online</h1>
        <p class="hero-subtext">Accedi a conoscenze di qualità ovunque ti trovi.</p>
        <div class="mt-4">
            <a href="corsi/corsi.php" class="btn btn-primary btn-lg mr-2">Scopri tutti i Corsi</a>
            <a href="pages/aboutUs.php" class="btn btn-outline-light btn-lg">Leggi di più</a>
<!------------------------------------------------------------------------------------------------>
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
                $sql = "SELECT Nome, Durata, DataInizio, DataFine, idCorso FROM Corso LIMIT 6";
                $stmt = $pdo->query($sql);

                if ($stmt->rowCount() > 0) {
                    while ($course = $stmt->fetch()) {
                        $courseName = htmlspecialchars($course['Nome']);
                        $courseImage = file_exists("resources/image/" . str_replace(' ', '', $courseName) . ".png") ? 
                        "resources/image/" . str_replace(' ', '', $courseName) . ".png" : "resources/image/Default.png";
                        $courseDescription = "Durata: {$course['Durata']} ore | Inizio: {$course['DataInizio']} | Fine: {$course['DataFine']}";
                        $courseId = $course['idCorso'];
            ?>
            <div class="col-sm-6 col-md-4 mb-4">
                <div class="card text-center border-0 shadow">
                    <div class="services-terri-figure position-relative">
                        <img src="<?= htmlspecialchars($courseImage) ?>" alt="<?= $courseName ?>" class="img-fluid rounded" loading="lazy">
                        <a href="javascript:void(0);" class="lens-icon position-absolute top-50 start-50 translate-middle" onclick="redirectToCourse(<?= $courseId ?>)">
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

        <div class="text-center mt-4">
            <p>Ci sono altri corsi disponibili! Visita la nostra <a href="corsi/corsi.php">pagina Corsi</a> per scoprirli tutti.</p>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section class="section py-5 bg-white" id="testimonials">
    <div class="container">
        <h3 class="text-center mb-4">Cosa dicono di noi</h3>
        <div id="testimonialCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <blockquote class="blockquote text-center">
                        <p>"I corsi mi hanno permesso di raggiungere i miei obiettivi professionali!"</p>
                        <footer class="blockquote-footer">Anna Rossi</footer>
                    </blockquote>
                </div>
                <div class="carousel-item">
                    <blockquote class="blockquote text-center">
                        <p>"La piattaforma è fantastica, semplice e intuitiva."</p>
                        <footer class="blockquote-footer">Luca Bianchi</footer>
                    </blockquote>
                </div>
                <div class="carousel-item">
                    <blockquote class="blockquote text-center">
                        <p>"Ottimo supporto da parte dei tutor, sempre disponibili a rispondere."</p>
                        <footer class="blockquote-footer">Maria Verdi</footer>
                    </blockquote>
                </div>
                <div class="carousel-item">
                    <blockquote class="blockquote text-center">
                        <p>"Il miglior investimento che abbia mai fatto per la mia carriera."</p>
                        <footer class="blockquote-footer">Marco Neri</footer>
                    </blockquote>
                </div>
                <div class="carousel-item">
                    <blockquote class="blockquote text-center">
                        <p>"Finalmente ho trovato corsi che rispettano i miei tempi di studio."</p>
                        <footer class="blockquote-footer">Giulia Gialli</footer>
                    </blockquote>
                </div>
                <div class="carousel-item">
                    <blockquote class="blockquote text-center">
                        <p>"Un’esperienza che consiglierei a tutti i miei colleghi!"</p>
                        <footer class="blockquote-footer">Simone Blu</footer>
                    </blockquote>
                </div>
                <div class="carousel-item">
                    <blockquote class="blockquote text-center">
                        <p>"Un'ampia gamma di corsi e materiale di alta qualità."</p>
                        <footer class="blockquote-footer">Federica Viola</footer>
                    </blockquote>
                </div>
                <div class="carousel-item">
                    <blockquote class="blockquote text-center">
                        <p>"La flessibilità dei corsi mi ha permesso di studiare mentre lavoravo."</p>
                        <footer class="blockquote-footer">Roberto Marrone</footer>
                    </blockquote>
                </div>
                <div class="carousel-item">
                    <blockquote class="blockquote text-center">
                        <p>"Perfetto per chi vuole migliorare le proprie competenze in modo pratico."</p>
                        <footer class="blockquote-footer">Laura Rosa</footer>
                    </blockquote>
                </div>
                <div class="carousel-item">
                    <blockquote class="blockquote text-center">
                        <p>"Un servizio eccellente con corsi aggiornati alle ultime tendenze."</p>
                        <footer class="blockquote-footer">Paolo Nero</footer>
                    </blockquote>
                </div>
            </div>
            <!-- Frecce di navigazione -->
            <button class="carousel-control-prev custom-carousel-control" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="prev">
                <span class="custom-control-icon">&#8249;</span>
            </button>
            <button class="carousel-control-next custom-carousel-control" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="next">
                <span class="custom-control-icon">&#8250;</span>
            </button>
        </div>
    </div>
</section>

<!-- Stile personalizzato -->
<style>
    #testimonials .custom-carousel-control {
        background: none;
        border: none;
        color: black;
        font-size: 2rem;
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        z-index: 2;
    }

    #testimonials .custom-carousel-control:hover {
        color: #555; /* Colore hover */
    }

    #testimonials .custom-carousel-control .custom-control-icon {
        font-size: 2.5rem; /* Dimensioni delle frecce */
    }

    .carousel-control-prev {
        left: -2rem; /* Posizione a sinistra */
    }

    .carousel-control-next {
        right: -2rem; /* Posizione a destra */
    }
</style>


<!-- Statistics Section -->
<section class="section py-5 bg-light" id="stats">
    <div class="container text-center">
        <h3 class="mb-4">Perché scegliere noi?</h3>
        <div class="row">
            <div class="col-md-4">
                <h2>50+</h2>
                <p>Corsi disponibili</p>
            </div>
            <div class="col-md-4">
                <h2>1000+</h2>
                <p>Studenti registrati</p>
            </div>
            <div class="col-md-4">
                <h2>5000+</h2>
                <p>Ore di apprendimento</p>
            </div>
        </div>
    </div>
</section>

<script>
    function redirectToCourse(courseId) {
        var studentId = '<?= isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '' ?>';

        if (!studentId) {
            window.location.href = "pages/login.php";
        } else {
            window.location.href = "corsi/iscrizione_corso.php?corso_id=" + courseId;
        }
    }
</script>

<?php include('templates/template_footer.php'); ?>
