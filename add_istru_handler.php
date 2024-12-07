<?php
// Includi la configurazione del database
include('config.php');

// Verifica se il form è stato inviato
if (isset($_POST['instructorName']) && isset($_POST['instructorSurname']) && isset($_POST['instructorEmail']) && isset($_POST['instructorPhone']) && isset($_POST['instructorSpecializzazione']) && isset($_POST['instructorPassword'])) {
    
    // Recupera i dati inviati dal form
    $instructorName = $_POST['instructorName'];
    $instructorSurname = $_POST['instructorSurname'];
    $instructorEmail = $_POST['instructorEmail'];
    $instructorPhone = $_POST['instructorPhone'];
    $instructorSpecializzazione = $_POST['instructorSpecializzazione'];
    $instructorPassword = password_hash($_POST['instructorPassword'], PASSWORD_BCRYPT); // Cripta la password

    // Verifica se l'email è già presente nel database
    $checkEmailQuery = "SELECT COUNT(*) FROM istruttore WHERE Email = :instructorEmail";
    $checkEmailStmt = $pdo->prepare($checkEmailQuery);
    $checkEmailStmt->bindParam(':instructorEmail', $instructorEmail);
    $checkEmailStmt->execute();
    $emailExists = $checkEmailStmt->fetchColumn();

    if ($emailExists > 0) {
        // Se l'email esiste già, mostra un messaggio di errore
        echo "Errore: l'email inserita è già associata a un altro istruttore.";
    } else {
        // Se l'email non esiste, inserisci il nuovo istruttore
        $insertQuery = "INSERT INTO istruttore (Nome, Cognome, Email, Telefono, Specializzazione, Password) 
                        VALUES (:instructorName, :instructorSurname, :instructorEmail, :instructorPhone, :instructorSpecializzazione, :instructorPassword)";
        
        $insertStmt = $pdo->prepare($insertQuery);
        
        // Associa i parametri alla query
        $insertStmt->bindParam(':instructorName', $instructorName);
        $insertStmt->bindParam(':instructorSurname', $instructorSurname);
        $insertStmt->bindParam(':instructorEmail', $instructorEmail);
        $insertStmt->bindParam(':instructorPhone', $instructorPhone);
        $insertStmt->bindParam(':instructorSpecializzazione', $instructorSpecializzazione);
        $insertStmt->bindParam(':instructorPassword', $instructorPassword);

        // Esegui la query per l'inserimento
        if ($insertStmt->execute()) {
            // Se l'inserimento ha successo, redirigi l'utente alla pagina di gestione
            header('Location: amministratoreDashboard.php');
            exit();
        } else {
            // Se si verifica un errore nell'inserimento, mostra un messaggio di errore
            echo "Errore durante l'inserimento dell'istruttore. Riprova.";
        }
    }
} else {
    // Se i dati non sono stati inviati, mostra un messaggio di errore
    echo "Errore: dati mancanti.";
}
?>
