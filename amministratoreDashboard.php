<?php
include('template_header.php');
include('template_header.php');
// Dati dinamici del sito
$title = "Dashboard Amministratore | Online Courses";
$navbarLinks = [
    "Home" => "index.php",
    "Corsi" => "corsi.php",
    "About Us" => "aboutUs.php",
    "Contact" => "contact.php",
    "Login" => "login.php"
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
                    <tbody id="courseTableBody"></tbody>
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
        <!-- Creazione Nuovi Utenti (Amministratore e Istruttore) -->
        <div class="row mb-5">
                <div class="col-md-6">
                    <h3>Aggiungi un Amministratore</h3>
                    <form action="create_user_handler.php" method="post">
                        <div class="form-group">
                            <label for="adminName">Nome</label>
                            <input type="text" class="form-control" id="adminName" name="adminName" required>
                        </div>
                        <div class="form-group">
                            <label for="adminSurname">Cognome</label>
                            <input type="text" class="form-control" id="adminSurname" name="adminSurname" required>
                        </div>
                        <div class="form-group">
                            <label for="telefono">Telefono</label>
                            <input type="tel" class="form-control" id="adminTelefono" name="adminTelefono" required>
                        </div>
                        <div class="form-group">
                            <label for="adminEmail">Email</label>
                            <input type="email" class="form-control" id="adminEmail" name="adminEmail" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="adminPassword" name="adminPassword" required>
                        </div>
                        <button type="submit" class="btn btn-success">Aggiungi Amministratore</button>
                    </form>
                </div>

                <div class="col-md-6">
                    <h3>Aggiungi un Istruttore</h3>
                    <form action="create_user_handler.php" method="post">
                        <div class="form-group">
                            <label for="instructorName">Nome</label>
                            <input type="text" class="form-control" id="instructorName" name="instructorName" required>
                        </div>
                        <div class="form-group">
                            <label for="adminSurname">Cognome</label>
                            <input type="text" class="form-control" id="instructorSurname" name="instructorSurname" required>
                        </div>
                        <div class="form-group">
                            <label for="telefono">Telefono</label>
                            <input type="tel" class="form-control" id="instructorTelefono" name="instructorTelefono" required>
                        </div>
                        <div class="form-group">
                            <label for="instructorEmail">Email</label>
                            <input type="email" class="form-control" id="instructorEmail" name="instructorEmail" required>
                        </div>
                        <div class="form-group">
                            <label for="instructorSpecializzazione">Specializzazione</label>
                            <input type="text" class="form-control" id="instructorSpecializzazione" name="instructorSpecializzazione" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="instructorPassword" name="instructorPassword" required>
                        </div>
                        <button type="submit" class="btn btn-info">Aggiungi Istruttore</button>
                    </form>
                </div>
            </div>

        </div>
    </section>
<?php include('template_footer.php');?>