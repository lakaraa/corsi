<?php
session_start();
require_once 'config.php';  // Includi il file di configurazione con la connessione PDO
include('template_header.php');

// Funzione per ottenere le categorie dinamicamente
try {
    $stmt = $pdo->query("SELECT idCategoria, NomeCategoria FROM Categoria");
    $categorie = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $categorie = [];
    echo "<p>Errore nel recupero delle categorie: " . $e->getMessage() . "</p>";
}

// Includi la navbar dinamica
if (!isset($_SESSION['user_id'])) {
    include('navbar_guest.php');
} else {
    include('navbar.php');
}
?>

<!-- Header Section -->
<header class="header-bg">
    <div class="overlay"></div>
    <div class="container text-center text-white d-flex align-items-center justify-content-center flex-column">
        <h1 class="hero-title">Corsi Online - Scopri il corso giusto per te</h1>
        <p class="hero-subtext">Esplora corsi con filtri personalizzati in modo rapido e semplice.</p>
    </div>
</header>

<!-- Sezione Ricerca -->
<div class="container my-4">
    <form method="GET" action="">
        <div class="row">
            <!-- Campo per cercare il nome del corso -->
            <div class="col-md-3 mb-2">
                <input type="text" id="searchName" name="nome" class="form-control" placeholder="Cerca per nome del corso">
            </div>
            <!-- Menu a tendina per categoria -->
            <div class="col-md-3 mb-2">
                <select id="searchCategory" name="categoria" class="form-control">
                    <option value="">Seleziona una categoria</option>
                    <?php foreach ($categorie as $categoria): ?>
                        <option value="<?= htmlspecialchars($categoria['idCategoria']) ?>">
                            <?= htmlspecialchars($categoria['NomeCategoria']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <!-- Campo per cercare la durata -->
            <div class="col-md-3 mb-2">
                <input type="number" id="searchDuration" name="durata" class="form-control" placeholder="Durata (max ore)">
            </div>
            <div class="col-md-3 mb-2 text-md-right">
                <button type="button" class="btn btn-primary btn-ricerca" onclick="fetchCourses()">Cerca</button>
            </div>
        </div>
    </form>
</div>

<!-- Corsi Section -->
<section class="section py-5 bg-light" id="courses">
    <div class="container">
        <h3 class="text-center mb-4">I nostri Corsi</h3>
        <div class="row" id="coursesContainer">
            <!-- I corsi saranno caricati dinamicamente qui -->
        </div>
    </div>
</section>

<script>
    // Funzione per effettuare la chiamata AJAX dinamica
    function fetchCourses() {
        const name = document.getElementById('searchName').value;
        const category = document.getElementById('searchCategory').value;
        const duration = document.getElementById('searchDuration').value;

        const url = `fetch_courses.php?nome=${encodeURIComponent(name)}&categoria=${encodeURIComponent(category)}&durata=${encodeURIComponent(duration)}`;

        fetch(url)
            .then(response => response.text())
            .then(html => {
                document.getElementById('coursesContainer').innerHTML = html;

                // Aggiunge evento click per ogni corso
                document.querySelectorAll('.course-link').forEach(link => {
                    link.addEventListener('click', function(event) {
                        event.preventDefault();
                        window.location.href = this.getAttribute('href');
                    });
                });
            })
            .catch(error => console.error('Errore nella richiesta dei corsi:', error));
    }

    // Carica corsi di default senza filtri all'avvio
    document.addEventListener("DOMContentLoaded", function () {
        fetchCourses();
    });
    
    function redirectToCourse(courseId) {
        var studentId = '<?= isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '' ?>';

        if (!studentId) {
            window.location.href = "login.php";
        } else {
            window.location.href = "iscrizione_corso.php?corso_id=" + courseId;
        }
    }


</script>

<?php
include('template_footer.php');
?>
<style>
.btn-ricerca {
    width: 100%;  
    height: 40px;
    font-size: 16px;
    padding: 0; 
    display: flex; 
    align-items: center; 
    justify-content: center; 
    transition: background-color 0.3s ease; 
}

.btn-ricerca:hover {
    background-color: #0056b3;
}
</style>
