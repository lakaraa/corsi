<?php
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
                <form id="createCourseForm">
                    <div class="form-group">
                        <label for="courseName">Nome Corso</label>
                        <input type="text" class="form-control" id="courseName" name="courseName" required>
                    </div>
                    <div class="form-group">
                        <label for="courseCategory">Categoria Corso</label>
                        <select class="form-control" id="courseCategory" name="courseCategory" required></select>
                    </div>
                    <div class="form-group">
                        <label for="courseInstructor">Istruttore</label>
                        <select class="form-control" id="courseInstructor" name="courseInstructor" required></select>
                    </div>
                    <button type="button" class="btn btn-primary" onclick="createCourse()">Crea Corso</button>
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
                    <tbody id="userTableBody"></tbody>
                </table>
            </div>
        </div>

        <!-- Creazione Nuovi Utenti (Amministratore e Istruttore) -->
        <div class="row mb-5">
            <div class="col-md-6">
                <h3>Aggiungi un Amministratore</h3>
                <form id="createAdminForm">
                    <div class="form-group">
                        <label for="adminName">Nome</label>
                        <input type="text" class="form-control" id="adminName" name="adminName" required>
                    </div>
                    <div class="form-group">
                        <label for="adminSurname">Cognome</label>
                        <input type="text" class="form-control" id="adminSurname" name="adminSurname" required>
                    </div>
                    <div class="form-group">
                        <label for="adminTelefono">Telefono</label>
                        <input type="tel" class="form-control" id="adminTelefono" name="adminTelefono" required>
                    </div>
                    <div class="form-group">
                        <label for="adminEmail">Email</label>
                        <input type="email" class="form-control" id="adminEmail" name="adminEmail" required>
                    </div>
                    <div class="form-group">
                        <label for="adminPassword">Password</label>
                        <input type="password" class="form-control" id="adminPassword" name="adminPassword" required>
                    </div>
                    <button type="button" class="btn btn-success" onclick="createAdmin()">Aggiungi Amministratore</button>
                </form>
            </div>

            <div class="col-md-6">
                <h3>Aggiungi un Istruttore</h3>
                <form id="createInstructorForm">
                    <div class="form-group">
                        <label for="instructorName">Nome</label>
                        <input type="text" class="form-control" id="instructorName" name="instructorName" required>
                    </div>
                    <div class="form-group">
                        <label for="instructorSurname">Cognome</label>
                        <input type="text" class="form-control" id="instructorSurname" name="instructorSurname" required>
                    </div>
                    <div class="form-group">
                        <label for="instructorTelefono">Telefono</label>
                        <input type="tel" class="form-control" id="instructorTelefono" name="instructorTelefono" required>
                    </div>
                    <div class="form-group">
                        <label for="instructorEmail">Email</label>
                        <input type="email" class="form-control" id="instructorEmail" name="instructorEmail" required>
                    </div>
                    <div class="form-group">
                        <label for="instructorPassword">Password</label>
                        <input type="password" class="form-control" id="instructorPassword" name="instructorPassword" required>
                    </div>
                    <button type="button" class="btn btn-info" onclick="createInstructor()">Aggiungi Istruttore</button>
                </form>
            </div>
        </div>

    </div>
</section>
<script>
    const apiUrl = "http://localhost/api"; // Sostituisci con il tuo URL base API

    // Funzione per creare un nuovo corso
    document.getElementById('createCourseForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(e.target);

        const data = {
            nome: formData.get('courseName'),
            idCategoria: formData.get('courseCategory'),
            idIstruttore: formData.get('courseInstructor'),
        };

        try {
            const response = await fetch(`${apiUrl}/corsi`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data),
            });

            if (response.ok) {
                alert("Corso creato con successo!");
                location.reload();
            } else {
                const errorData = await response.json();
                alert(`Errore nella creazione del corso: ${errorData.message}`);
            }
        } catch (error) {
            console.error("Errore:", error);
            alert("Errore nella connessione all'API.");
        }
    });

    // Funzione per eliminare un corso
    async function deleteCourse(courseId) {
        if (!confirm("Sei sicuro di voler eliminare questo corso?")) return;

        try {
            const response = await fetch(`${apiUrl}/corsi/${courseId}`, { method: 'DELETE' });

            if (response.ok) {
                alert("Corso eliminato con successo!");
                location.reload();
            } else {
                const errorData = await response.json();
                alert(`Errore nell'eliminazione del corso: ${errorData.message}`);
            }
        } catch (error) {
            console.error("Errore:", error);
            alert("Errore nella connessione all'API.");
        }
    }

    // Funzione per eliminare un utente
    async function deleteUser(userId) {
        if (!confirm("Sei sicuro di voler eliminare questo utente?")) return;

        try {
            const response = await fetch(`${apiUrl}/utenti/${userId}`, { method: 'DELETE' });

            if (response.ok) {
                alert("Utente eliminato con successo!");
                location.reload();
            } else {
                const errorData = await response.json();
                alert(`Errore nell'eliminazione dell'utente: ${errorData.message}`);
            }
        } catch (error) {
            console.error("Errore:", error);
            alert("Errore nella connessione all'API.");
        }
    }
</script>
