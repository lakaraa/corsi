<?php
include('config.php'); // Connessione al database
include('template_header.php');
include('navbar.php');
?>

<!-- Header Section -->
<header class="header-bg">
    <div class="overlay"></div>
    <div class="container text-center text-white d-flex align-items-center justify-content-center flex-column">
        <h1 class="hero-title">Gestione Istruttori</h1>
        <p class="hero-subtext">Visualizza, modifica o elimina gli istruttori.</p>
    </div>
</header>

<!-- Spazio tra Header e Sezione -->
<section class="mt-5">
    <div class="container">
        <div class="row mb-5">
            <!-- Form Aggiungi un Amministratore -->
            <div class="col-md-6">
                <h3>Aggiungi un Amministratore</h3>
                <form action="add_ammin_handler.php" method="post" class="p-4 border rounded shadow-sm bg-light">
                    <div class="form-group mb-3">
                        <label for="adminName">Nome</label>
                        <input type="text" class="form-control" id="adminName" name="adminName" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="adminSurname">Cognome</label>
                        <input type="text" class="form-control" id="adminSurname" name="adminSurname" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="adminEmail">Email</label>
                        <input type="email" class="form-control" id="adminEmail" name="adminEmail" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="adminPhone">Telefono</label>
                        <input type="tel" class="form-control" id="adminPhone" name="adminPhone" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="adminPassword">Password</label>
                        <input type="password" class="form-control" id="adminPassword" name="adminPassword" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="confirmAdminPassword">Conferma Password</label>
                        <input type="password" class="form-control" id="confirmAdminPassword" name="confirmAdminPassword" required>
                    </div>
                    <button type="submit" class="btn btn-success btn-block">Aggiungi Amministratore</button>
                </form>
            </div>

            <!-- Form Aggiungi un Istruttore -->
            <div class="col-md-6">
                <h3>Aggiungi un Istruttore</h3>
                <form action="add_istru_handler.php" method="post" class="p-4 border rounded shadow-sm bg-light">
                    <div class="form-group mb-3">
                        <label for="instructorName">Nome</label>
                        <input type="text" class="form-control" id="instructorName" name="instructorName" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="instructorSurname">Cognome</label>
                        <input type="text" class="form-control" id="instructorSurname" name="instructorSurname" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="instructorEmail">Email</label>
                        <input type="email" class="form-control" id="instructorEmail" name="instructorEmail" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="instructorPhone">Telefono</label>
                        <input type="tel" class="form-control" id="instructorPhone" name="instructorPhone" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="instructorSpecializzazione">Specializzazione</label>
                        <input type="text" class="form-control" id="instructorSpecializzazione" name="instructorSpecializzazione" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="instructorPassword">Password</label>
                        <input type="password" class="form-control" id="instructorPassword" name="instructorPassword" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="confirmInstructorPassword">Conferma Password</label>
                        <input type="password" class="form-control" id="confirmInstructorPassword" name="confirmInstructorPassword" required>
                    </div>
                    <button type="submit" class="btn btn-info btn-block">Aggiungi Istruttore</button>
                </form>
            </div>
        </div>
    </div>
</section>

<?php include('template_footer.php'); ?>
