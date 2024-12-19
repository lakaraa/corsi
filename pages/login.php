<?php
session_start(); 
include('../config/config.php');
include('../templates/template_header.php'); 

// Includi la navbar dinamica
if (!isset($_SESSION['user_id'])) {
    include('../pages/navbar_guest.php'); // Navbar per gli utenti non loggati
} else {
    include('../pages/navbar.php'); // Navbar per gli utenti loggati
}
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
?>
    <!-- Header Section -->
    <header class="header-bg">
        <div class="overlay"></div>
        <div class="container text-center text-white d-flex align-items-center justify-content-center flex-column">
            <h1 class="hero-title">Scopri i nostri corsi</h1>
            <p class="hero-subtext">Accedi a conoscenze di qualità ovunque ti trovi.</p>
        </div>
    </header>

    <!-- Login Section -->
    <section class="section py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-4">Accedi al Tuo Account</h2>
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card shadow">
                        <div class="card-body">
                            <form action="../auth/login_handler.php" method="post">
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>
                                <button type="submit" class="btn btn-primary btn-lg btn-block">Accedi</button>
                            </form>
                            <div class="text-center mt-3">
                                <p>Non hai un account? <a href="../pages/register.php">Registrati qui</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php include('../templates/template_footer.php'); ?>
