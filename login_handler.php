<?php
// Include la connessione al database
require_once 'config.php'; // Assicurati che il percorso sia corretto

// Avvia la sessione
session_start();

// Verifica se il form è stato inviato
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recupera i dati del form
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validazione dei dati
    if (empty($email) || empty($password)) {
        echo "<script>alert('Entrambi i campi sono obbligatori!'); window.location.href='login.php';</script>";
        exit;
    }

    try {
        // Array di tabelle da verificare con priorità
        $user_types = [
            'amministratore' => ['id_col' => 'IdAmministratore', 'dashboard' => 'amministratoreDashboard.php'],
            'istruttore' => ['id_col' => 'IdIstruttore', 'dashboard' => 'istruttoreDashboard.php'],
            'studente' => ['id_col' => 'IdStudente', 'dashboard' => 'studentDashboard.php']
        ];

        foreach ($user_types as $type => $info) {
            $query = "SELECT * FROM $type WHERE Email = :email LIMIT 1";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                // Controlla se la password è hashata o in chiaro
                if (password_verify($password, $user['Password']) || $password === $user['Password']) {
                    // Imposta la sessione
                    $_SESSION['user_id'] = $user[$info['id_col']];
                    $_SESSION['user_email'] = $user['Email'];
                    $_SESSION['user_name'] = $user['Nome'];
                    $_SESSION['user_role'] = $type;
                    
                    // Redirigi al dashboard appropriato
                    header("Location: {$info['dashboard']}");
                    exit;
                } else {
                    echo "<script>alert('Password errata per $type!'); window.location.href='login.php';</script>";
                    exit;
                }
            }
        }

        // Nessun utente trovato con quell'email
        echo "<script>alert('Nessun utente trovato con questa email!'); window.location.href='login.php';</script>";
        exit;

    } catch (PDOException $e) {
        // Gestisci eventuali errori
        echo "<script>alert('Errore: " . addslashes($e->getMessage()) . "'); window.location.href='login.php';</script>";
        exit;
    }
} else {
    // Se il form non è stato inviato, redirigi alla pagina di login
    header('Location: login.php');
    exit;
}
?>
