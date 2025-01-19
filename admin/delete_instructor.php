<?php
// Include la connessione al database
include('../config.php');

// Verifica che l'ID dell'istruttore sia stato inviato
if (isset($_POST['instructorId'])) {
    $instructorId = $_POST['instructorId'];

    // Inizia una transazione
    $pdo->beginTransaction();

    try {
        // Prima rimuovi i corsi assegnati all'istruttore
        $deleteCoursesQuery = "UPDATE corso SET IdIstruttore = NULL WHERE IdIstruttore = :instructorId";
        $deleteCoursesStmt = $pdo->prepare($deleteCoursesQuery);
        $deleteCoursesStmt->bindParam(':instructorId', $instructorId, PDO::PARAM_INT);
        $deleteCoursesStmt->execute();

        // Ora elimina l'istruttore
        $deleteInstructorQuery = "DELETE FROM istruttore WHERE IdIstruttore = :instructorId";
        $deleteInstructorStmt = $pdo->prepare($deleteInstructorQuery);
        $deleteInstructorStmt->bindParam(':instructorId', $instructorId, PDO::PARAM_INT);
        $deleteInstructorStmt->execute();

        // Creazione della query SQL da inserire nel file .sql
        $sqlOperation = sprintf(
            "UPDATE corso SET IdIstruttore = NULL WHERE IdIstruttore = %d;\nDELETE FROM istruttore WHERE IdIstruttore = %d;\n",
            $instructorId,
            $instructorId
        );

        // Scrive la query nel file 'sql_insert.sql'
        file_put_contents('../sql_insert.sql', $sqlOperation, FILE_APPEND);

        // Completare la transazione
        $pdo->commit();

        // Completare la transazione
        $pdo->commit();

        // Reindirizza alla pagina di gestione istruttori
        header('Location: amministratoreDashboard.php');
        exit();
    } catch (Exception $e) {
        // Annulla la transazione in caso di errore
        $pdo->rollBack();
        echo "Errore durante l'eliminazione dell'istruttore: " . $e->getMessage();
    }
} else {
    echo "ID istruttore non fornito.";
}
?>
