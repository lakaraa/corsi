<?php
include('template_header.php');
// Simulazione dati dinamici
$corsi = [
    ["id" => "webDev", "nome" => "Web Development", "studenti" => [
        ["nome" => "Giovanni Rossi", "livello" => ""],
        ["nome" => "Laura Bianchi", "livello" => ""]
    ]],
    ["id" => "dataScience", "nome" => "Data Science", "studenti" => [
        ["nome" => "Marco Neri", "livello" => ""],
        ["nome" => "Giulia Verdi", "livello" => ""]
    ]]
];

$corsiCompletati = [
    ["id" => "graphicDesign", "nome" => "Graphic Design", "studenti" => [
        ["nome" => "Giovanni Rossi", "livello" => "Base"],
        ["nome" => "Laura Bianchi", "livello" => "Avanzato"],
        ["nome" => "Marco Neri", "livello" => "Intermedio"]
    ]],
    ["id" => "marketing", "nome" => "Marketing Digitale", "studenti" => [
        ["nome" => "Andrea Galli", "livello" => "Avanzato"],
        ["nome" => "Maria Verdi", "livello" => "Base"],
        ["nome" => "Lucia Bianchini", "livello" => "Base"]
    ]]
];
?>

    <!-- Header Section -->
    <header class="header-bg">
        <div class="overlay"></div>
        <div class="container text-center text-white d-flex align-items-center justify-content-center flex-column">
            <h1 class="hero-title">Scopri i nostri corsi</h1>
            <p class="hero-subtext">Accedi a conoscenze di qualità ovunque ti trovi.</p>
        </div>
    </header>

    <!-- Instructor Dashboard Section -->
    <section class="section py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-4">Dashboard Istruttore</h2>
            <p class="text-center mb-5">Gestisci i tuoi corsi, vedi gli studenti e assegna i voti.</p>

            <!-- My Courses -->
            <h3>I miei Corsi</h3>
            <div class="list-group mb-5">
                <?php foreach ($corsi as $corso): ?>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <?= htmlspecialchars($corso['nome']) ?>
                        <button class="btn btn-info btn-sm" onclick="toggleCourseDetails('<?= $corso['id'] ?>Students')">Gestisci Studenti</button>
                    </div>
                    <div id="<?= $corso['id'] ?>Students" class="course-details" style="display:none;">
                        <div class="card mt-3">
                            <div class="card-body">
                                <h5>Elenco Studenti</h5>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Nome Studente</th>
                                            <th>Livello</th>
                                            <th>Assegna Livello</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($corso['studenti'] as $studente): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($studente['nome']) ?></td>
                                                <td><input type="text" class="form-control" placeholder="Livello"></td>
                                                <td><button class="btn btn-success btn-sm">Assegna</button></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Completed Courses -->
            <h3>Corsi Completati</h3>
            <div class="list-group">
                <?php foreach ($corsiCompletati as $corso): ?>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <?= htmlspecialchars($corso['nome']) ?>
                        <span class="badge badge-success">Completato</span>
                        <button class="btn btn-info btn-sm" onclick="toggleCourseDetails('<?= $corso['id'] ?>Details')">Gestisci Studenti</button>
                    </div>
                    <div id="<?= $corso['id'] ?>Details" class="course-details" style="display:none;">
                        <div class="card mt-3">
                            <div class="card-body">
                                <h5>Elenco Studenti e Voti</h5>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Nome Studente</th>
                                            <th>Livello</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($corso['studenti'] as $studente): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($studente['nome']) ?></td>
                                                <td><input type="text" class="form-control" value="<?= htmlspecialchars($studente['livello']) ?>" disabled></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <script>
        function toggleCourseDetails(courseId) {
    const courseDetails = document.getElementById(courseId);
    if (courseDetails) {
        courseDetails.style.display = courseDetails.style.display === "none" ? "block" : "none";
    }
}

    </script>
<?php include('template_footer.php');

// Funzione per ottenere i corsi da un database simulato
function getCourses() {
    return [
        ["id" => 1, "name" => "Web Development", "students" => [
            ["name" => "Giovanni Rossi", "level" => ""],
            ["name" => "Laura Bianchi", "level" => ""]
        ]],
        ["id" => 2, "name" => "Data Science", "students" => [
            ["name" => "Marco Neri", "level" => ""],
            ["name" => "Giulia Verdi", "level" => ""]
        ]]
    ];
}

function getCompletedCourses() {
    return [
        ["id" => 1, "name" => "Graphic Design", "students" => [
            ["name" => "Giovanni Rossi", "level" => "Base"],
            ["name" => "Laura Bianchi", "level" => "Avanzato"]
        ]],
        ["id" => 2, "name" => "Marketing Digitale", "students" => [
            ["name" => "Andrea Galli", "level" => "Avanzato"],
            ["name" => "Maria Verdi", "level" => "Base"]
        ]]
    ];
}

$courses = getCourses();
$completedCourses = getCompletedCourses();
?>
