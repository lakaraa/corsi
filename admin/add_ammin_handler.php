<?php
// Connessione al database
include('../config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Prendi i dati inviati dal form
    $adminName = $_POST['adminName'];
    $adminSurname = $_POST['adminSurname'];
    $adminEmail = $_POST['adminEmail'];
    $adminPhone = $_POST['adminPhone'];
    $adminPassword = $_POST['adminPassword'];

    // Verifica che i dati siano validi (puoi aggiungere altre validazioni)
    if (!empty($adminName) && !empty($adminSurname) && !empty($adminEmail) && !empty($adminPhone) && !empty($adminPassword)) {
        // Cripta la password
        $hashedPassword = password_hash($adminPassword, PASSWORD_DEFAULT);

        // Prepara la query di inserimento
        $insertQuery = "INSERT INTO amministratore (Nome, Cognome, Email, Telefono, Password) 
                        VALUES (:adminName, :adminSurname, :adminEmail, :adminPhone, :adminPassword)";
        $stmt = $pdo->prepare($insertQuery);

        // Bind dei parametri
        $stmt->bindParam(':adminName', $adminName);
        $stmt->bindParam(':adminSurname', $adminSurname);
        $stmt->bindParam(':adminEmail', $adminEmail);
        $stmt->bindParam(':adminPhone', $adminPhone);
        $stmt->bindParam(':adminPassword', $hashedPassword);

        // Esegui la query
        if ($stmt->execute()) {
            // Successo, reindirizza alla pagina degli amministratori
            header('Location: amministratoreDashboard.php');
        } else {
            echo "Errore durante la creazione dell'amministratore.";
        }
    } else {
        echo "Tutti i campi sono obbligatori.";
    }
}
?>
