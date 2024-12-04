<?php
// Dati dinamici del sito
$title = "Dashboard Studente | Online Courses";
$navbarLinks = [
    "Home" => "index.php",
    "Corsi" => "corsi.php",
    "About Us" => "aboutUs.php",
    "Contact" => "contact.php",
    "Login" => "login.php"
];
$contactInfo = [
    "phone" => "+1 718-999-3939",
    "email" => "info@onlinelearning.com",
    "address" => "1234 Learning St. New York, NY 10001"
];
$socialLinks = [
    "Facebook" => "#",
    "Twitter" => "#",
    "Instagram" => "#",
    "LinkedIn" => "#"
];
$coursesEnrolled = [
    ["name" => "Web Development", "status" => "In corso", "details" => [
        "start_date" => "01/02/2022",
        "end_date" => "30/06/2022",
        "instructor" => "Maria Rossi"
    ]]
];
$coursesCompleted = [
    ["name" => "Graphic Design", "status" => "Completato", "details" => [
        "start_date" => "01/01/2022",
        "end_date" => "30/03/2022",
        "level" => "Avanzato",
        "instructor" => "Mario Verdi"
    ]]
];
$coursesAvailable = [
    ["name" => "Data Science", "details" => [
        "start_date" => "15/03/2022",
        "end_date" => "15/06/2022",
        "instructor" => "Luca Neri"
    ]]
];
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <!-- Font Awesome for the lens icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/x-icon" href="image/logo.png">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg fixed-top custom-navbar">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="image/logo.png" alt="Logo" width="40" height="40" class="d-inline-block align-middle"> 
                <span>Online Learning Hub</span>
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"><i class="fas fa-bars"></i></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <?php foreach ($navbarLinks as $name => $link): ?>
                        <li class="nav-item"><a class="nav-link" href="<?php echo $link; ?>"><?php echo $name; ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Header Section -->
    <header class="header-bg">
        <div class="overlay"></div>
        <div class="container text-center text-white d-flex align-items-center justify-content-center flex-column">
            <h1 class="hero-title">Dashboard Studente</h1>
            <p class="hero-subtext">Gestisci i tuoi corsi e il tuo apprendimento.</p>
        </div>
    </header>

    <!-- Student Dashboard Section -->
    <section class="section py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-4">Benvenuto caro</h2>
            <p class="text-center mb-5">Qui puoi visualizzare i corsi a cui sei iscritto, i corsi che hai completato e quelli disponibili per te.</p>

            <!-- Iscrizioni -->
            <div class="row mb-5">
                <div class="col-md-12">
                    <h3>I tuoi Corsi Iscritti</h3>
                    <div class="list-group">
                        <?php foreach ($coursesEnrolled as $course): ?>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <?php echo $course['name']; ?>
                                <span class="badge badge-primary"><?php echo $course['status']; ?></span>
                                <button class="btn btn-info btn-sm" onclick="toggleDetails('<?php echo str_replace(' ', '', $course['name']); ?>Details')">Dettagli</button>
                            </div>
                            <div id="<?php echo str_replace(' ', '', $course['name']); ?>Details" class="course-details" style="display:none;">
                                <div class="card mt-3">
                                    <div class="card-body">
                                        <h5>Dettagli Corso</h5>
                                        <p><strong>Data Inizio:</strong> <?php echo $course['details']['start_date']; ?></p>
                                        <p><strong>Data Fine:</strong> <?php echo $course['details']['end_date']; ?></p>
                                        <p><strong>Istruttore:</strong> <?php echo $course['details']['instructor']; ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Corsi Disponibili -->
            <div class="row">
                <div class="col-md-12">
                    <h3>Corsi Disponibili</h3>
                    <div class="list-group">
                        <?php foreach ($coursesAvailable as $course): ?>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <?php echo $course['name']; ?>
                                <a href="#" class="btn btn-primary btn-sm">Iscriviti</a>
                                <button class="btn btn-info btn-sm" onclick="toggleDetails('<?php echo str_replace(' ', '', $course['name']); ?>Details')">Dettagli</button>
                            </div>
                            <div id="<?php echo str_replace(' ', '', $course['name']); ?>Details" class="course-details" style="display:none;">
                                <div class="card mt-3">
                                    <div class="card-body">
                                        <h5>Dettagli Corso</h5>
                                        <p><strong>Data Inizio:</strong> <?php echo $course['details']['start_date']; ?></p>
                                        <p><strong>Data Fine:</strong> <?php echo $course['details']['end_date']; ?></p>
                                        <p><strong>Istruttore:</strong> <?php echo $course['details']['instructor']; ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        function toggleDetails(courseId) {
            const courseDetails = document.getElementById(courseId);
            if (courseDetails.style.display === "none" || courseDetails.style.display === "") {
                courseDetails.style.display = "block";
            } else {
                courseDetails.style.display = "none";
            }
        }
    </script>

    <?php include('template_footer.php'); ?>
</body>
</html>

