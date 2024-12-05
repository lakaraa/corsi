<?php
include('template_header.php');

// Dati dinamici del sito
$title = "Dashboard Istruttore | Online Courses";
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

// Esempio di corsi a cui l'istruttore sta insegnando
$ongoingCourses = [
    ["name" => "Web Development", "category" => "Web Development", "students" => [
        ["name" => "Giovanni Rossi", "email" => "giovanni@example.com", "level" => "Base"],
        ["name" => "Maria Verdi", "email" => "maria@example.com", "level" => "Intermedio"]
    ]],
    ["name" => "Data Science", "category" => "Data Science", "students" => [
        ["name" => "Luca Bianchi", "email" => "luca@example.com", "level" => "Avanzato"]
    ]]
];

$completedCourses = [
    ["name" => "Graphic Design", "category" => "Graphic Design", "students" => [
        ["name" => "Carla Neri", "email" => "carla@example.com"],
        ["name" => "Marco Lupi", "email" => "marco@example.com"]
    ]],
    ["name" => "Marketing Digitale", "category" => "Marketing Digitale", "students" => [
        ["name" => "Elena Di Mauro", "email" => "elena@example.com"],
        ["name" => "Fabio Masi", "email" => "fabio@example.com"]
    ]]
];

// Handle form submission to update student levels (for demo purposes, will print results)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_levels'])) {
    $updatedLevels = $_POST['studentLevels'];
    echo "<div class='alert alert-success'>Livelli aggiornati con successo!</div>";
}
?>
    <!-- Header Section -->
    <header class="header-bg">
        <div class="overlay"></div>
        <div class="container text-center text-white d-flex align-items-center justify-content-center flex-column">
            <h1 class="hero-title">Dashboard Istruttore</h1>
            <p class="hero-subtext">Gestisci i tuoi corsi e visualizza gli studenti iscritti.</p>
        </div>
    </header>

    <!-- Instructor Dashboard Section -->
    <section class="section py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-4">Dashboard Istruttore</h2>
            <p class="text-center mb-5">Visualizza i corsi che stai insegnando e quelli finiti.</p>

            <!-- Corsi in Corso -->
            <div class="row mb-5">
                <div class="col-md-12">
                    <h3>Corsi in Corso</h3>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nome Corso</th>
                                <th>Categoria</th>
                                <th>Numero di Studenti</th>
                                <th>Azioni</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($ongoingCourses as $course): ?>
                                <tr>
                                    <td><?php echo $course['name']; ?></td>
                                    <td><?php echo $course['category']; ?></td>
                                    <td><?php echo count($course['students']); ?></td> <!-- Display student count -->
                                    <td>
                                        <!-- Button to toggle student details -->
                                        <button class="btn btn-primary btn-sm" onclick="toggleDetails('course-<?php echo urlencode($course['name']); ?>')">Dettagli</button>
                                    </td>
                                </tr>
                                <!-- Course Details Card -->
                                <tr id="course-<?php echo urlencode($course['name']); ?>" style="display:none;">
                                    <td colspan="4">
                                        <div class="card">
                                            <div class="card-body">
                                                <h5 class="card-title">Studenti Iscritti a <?php echo $course['name']; ?></h5>
                                                <form method="post" action="">
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th>Nome Studente</th>
                                                                <th>Email</th>
                                                                <th>Livello</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php foreach ($course['students'] as $index => $student): ?>
                                                                <tr>
                                                                    <td><?php echo $student['name']; ?></td>
                                                                    <td><?php echo $student['email']; ?></td>
                                                                    <td>
                                                                        <select class="form-control" name="studentLevels[<?php echo $index; ?>]">
                                                                            <option value="Base" <?php if ($student['level'] == "Base") echo "selected"; ?>>Base</option>
                                                                            <option value="Intermedio" <?php if ($student['level'] == "Intermedio") echo "selected"; ?>>Intermedio</option>
                                                                            <option value="Avanzato" <?php if ($student['level'] == "Avanzato") echo "selected"; ?>>Avanzato</option>
                                                                        </select>
                                                                    </td>
                                                                </tr>
                                                            <?php endforeach; ?>
                                                        </tbody>
                                                    </table>
                                                    <button type="submit" name="update_levels" class="btn btn-success">Aggiorna Livelli</button>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Corsi Completati -->
            <div class="row mb-5">
                <div class="col-md-12">
                    <h3>Corsi Completati</h3>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nome Corso</th>
                                <th>Categoria</th>
                                <th>Numero di Studenti</th>
                                <th>Azioni</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($completedCourses as $course): ?>
                                <tr>
                                    <td><?php echo $course['name']; ?></td>
                                    <td><?php echo $course['category']; ?></td>
                                    <td><?php echo count($course['students']); ?></td> <!-- Display student count -->
                                    <td>
                                        <!-- Button to show course details -->
                                        <button class="btn btn-primary btn-sm" onclick="toggleDetails('course-<?php echo urlencode($course['name']); ?>')">Dettagli</button>
                                    </td>
                                </tr>
                                <!-- Course Details Card (hidden for completed courses) -->
                                <tr id="course-<?php echo urlencode($course['name']); ?>" style="display:none;">
                                    <td colspan="4">
                                        <div class="card">
                                            <div class="card-body">
                                                <h5 class="card-title">Dettagli Corso <?php echo $course['name']; ?></h5>
                                                <p>Numero di studenti iscritti:</p>
                                                <ul>
                                                    <?php foreach ($course['students'] as $student): ?>
                                                        <li><?php echo $student['name']; ?> - <?php echo $student['email']; ?></li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            </div>
                                        </div>
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
        // Function to toggle the visibility of the course details card
        function toggleDetails(courseId) {
            var courseRow = document.getElementById(courseId);
            if (courseRow.style.display === "none" || courseRow.style.display === "") {
                courseRow.style.display = "table-row"; // Show the course details
            } else {
                courseRow.style.display = "none"; // Hide the course details
            }
        }
    </script>

<?php include('template_footer.php'); ?>
