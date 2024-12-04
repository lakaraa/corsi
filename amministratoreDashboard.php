<?php
// Dati dinamici del sito
$title = "Dashboard Amministratore | Online Courses";
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
$courses = [
    ["name" => "Web Development", "category" => "Web Development", "instructor" => "Maria Rossi"],
    ["name" => "Data Science", "category" => "Data Science", "instructor" => "Luca Bianchi"]
];
$users = [
    ["name" => "Giovanni Rossi", "email" => "giovanni@example.com", "role" => "Studente"],
    ["name" => "Maria Verdi", "email" => "maria@example.com", "role" => "Istruttore"]
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
            <h1 class="hero-title">Dashboard Amministratore</h1>
            <p class="hero-subtext">Gestisci corsi, utenti, e iscrizioni agli studenti.</p>
        </div>
    </header>

    <!-- Admin Dashboard Section -->
    <section class="section py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-4">Dashboard Amministratore</h2>
            <p class="text-center mb-5">Gestisci corsi, utenti, e iscrizioni agli studenti.</p>

            <!-- Creazione Corsi -->
            <div class="row mb-5">
                <div class="col-md-12">
                    <h3>Crea un Nuovo Corso</h3>
                    <form action="create_course_handler.php" method="post">
                        <div class="form-group">
                            <label for="courseName">Nome Corso</label>
                            <input type="text" class="form-control" id="courseName" name="courseName" required>
                        </div>
                        <div class="form-group">
                            <label for="courseCategory">Categoria Corso</label>
                            <select class="form-control" id="courseCategory" name="courseCategory" required>
                                <option value="Web Development">Web Development</option>
                                <option value="Data Science">Data Science</option>
                                <option value="Graphic Design">Graphic Design</option>
                                <option value="Marketing Digitale">Marketing Digitale</option>
                                <option value="Cybersecurity">Cybersecurity</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="courseDescription">Descrizione Corso</label>
                            <textarea class="form-control" id="courseDescription" name="courseDescription" rows="3" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="courseInstructor">Istruttore</label>
                            <input type="text" class="form-control" id="courseInstructor" name="courseInstructor" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Crea Corso</button>
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
                                    <td><?php echo $course['name']; ?></td>
                                    <td><?php echo $course['category']; ?></td>
                                    <td><?php echo $course['instructor']; ?></td>
                                    <td>
                                        <button class="btn btn-warning btn-sm">Modifica</button>
                                        <button class="btn btn-danger btn-sm">Elimina</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Gestione Utenti -->
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
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td><?php echo $user['name']; ?></td>
                                    <td><?php echo $user['email']; ?></td>
                                    <td><?php echo $user['role']; ?></td>
                                    <td><button class="btn btn-danger btn-sm">Elimina</button></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </section>
<?php include('template_footer.php');?>