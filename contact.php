<?php include('template_header.php'); ?>

    <!-- Header Section -->
    <header class="header-bg">
        <div class="overlay"></div>
        <div class="container text-center text-white d-flex align-items-center justify-content-center flex-column">
            <h1 class="hero-title">Scopri i nostri corsi</h1>
            <p class="hero-subtext">Accedi a conoscenze di qualità ovunque ti trovi.</p>
        </div>
    </header>

    <!-- Contact Us Section -->
    <section class="section py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-4">Contattaci</h2>
            <div class="row">
                <div class="col-md-6">
                    <h4 class="mb-3">Inviaci un Messaggio</h4>
                    <form action="submit_form.php" method="post">
                        <div class="form-group">
                            <label for="name">Nome</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="message">Messaggio</label>
                            <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg">Invia Messaggio</button>
                    </form>
                </div>
                <div class="col-md-6">
                    <h4 class="mb-3">Dove Trovarci</h4>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-phone-alt"></i> <a href="tel:+17189993939">+1 718-999-3939</a></li>
                        <li><i class="fas fa-envelope"></i> <a href="mailto:info@onlinelearning.com">info@onlinelearning.com</a></li>
                        <li><i class="fas fa-map-marker-alt"></i> 1234 Learning St. New York, NY 10001</li>
                    </ul>
                    <h4 class="mb-3">Orari di apertura</h4>
                    <ul class="list-unstyled">
                        <li>Lunedì - Venerdì: 9:00 AM - 6:00 PM</li>
                        <li>Sabato: 10:00 AM - 2:00 PM</li>
                        <li>Domenica: Chiuso</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

<?php include('template_footer.php');