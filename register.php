<?php
include('template_header.php');
// Dati dinamici del sito
$title = "Registrati | Online Courses";
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
?>
    <!-- Header Section -->
    <header class="header-bg">
        <div class="overlay"></div>
        <div class="container text-center text-white d-flex align-items-center justify-content-center flex-column">
            <h1 class="hero-title">Crea il tuo account</h1>
            <p class="hero-subtext">Unisciti alla nostra piattaforma e inizia ad apprendere oggi stesso.</p>
        </div>
    </header>

    <!-- Register Section -->
    <section class="section py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-4">Registrati</h2>
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card shadow">
                        <div class="card-body">
                            <form action="register_handler.php" method="POST">
                                <div class="form-group">
                                    <label for="name">Nome</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                                <div class="form-group">
                                    <label for="surname">Cognome</label>
                                    <input type="text" class="form-control" id="surname" name="surname" required>
                                </div>
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                                <div class="form-group">
                                    <label for="tel">Telefono</label>
                                    <input type="tel" class="form-control" id="tel" name="tel" required>
                                </div>
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>
                                <div class="form-group">
                                    <label for="confirm_password">Conferma Password</label>
                                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                                </div>
                                <button type="submit" class="btn btn-primary btn-lg btn-block">Registrati</button>
                            </form>
                            <div class="text-center mt-3">
                                <p>Hai già un account? <a href="login.php">Accedi qui</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<? include('template_footer.php');?>