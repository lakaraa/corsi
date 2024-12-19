<?php
// Include la connessione al database
include('../config/config.php');

// Avvia la sessione per poter usare i messaggi
session_start();

// Verifica che l'ID del corso sia stato inviato
if (isset($_POST['courseId'])) {
    $courseId = $_POST['courseId'];

    // Inizia una transazione per garantire che entrambe le operazioni siano atomiche
    $pdo->beginTransaction();

    try {
        // 1. Elimina prima i record dalla tabella iscrizione che fanno riferimento a questo corso
        $deleteIscrizioneQuery = "DELETE FROM iscrizione WHERE IdCorso = :courseId";
        $deleteIscrizioneStmt = $pdo->prepare($deleteIscrizioneQuery);
        $deleteIscrizioneStmt->bindParam(':courseId', $courseId, PDO::PARAM_INT);
        $deleteIscrizioneStmt->execute();

        // 2. Ora elimina il corso dalla tabella corso
        $deleteCourseQuery = "DELETE FROM corso WHERE IdCorso = :courseId";
        $deleteCourseStmt = $pdo->prepare($deleteCourseQuery);
        $deleteCourseStmt->bindParam(':courseId', $courseId, PDO::PARAM_INT);
        $deleteCourseStmt->execute();

        // Commit della transazione se entrambe le operazioni sono andate a buon fine
        $pdo->commit();

        // Messaggio di successo
        $_SESSION['message'] = 'Corso eliminato con successo!';
        header('Location: ../admin/amministratoreDashboard.php');
        exit();

    } catch (PDOException $e) {
        // In caso di errore, rollback della transazione
        $pdo->rollBack();
        $_SESSION['message'] = 'Errore durante l\'eliminazione del corso: ' . $e->getMessage();
        header('Location: ../admin/amministratoreDashboard.php');
        exit();
    }
} else {
    $_SESSION['message'] = 'ID corso non fornito.';
    header('Location: ../admin/amministratoreDashboard.php');
    exit();
}
?>
