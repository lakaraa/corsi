<?php
include('../config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recupera il nome della categoria dal form
    $nomeCategoria = $_POST['nome_categoria'];

    // Prepara la query per inserire la nuova categoria
    $query = $pdo->prepare("INSERT INTO categoria (NomeCategoria) VALUES (:nomeCategoria)");
    $query->execute(['nomeCategoria' => $nomeCategoria]);

    // Creazione della query SQL da inserire nel file .sql
    $sqlOperation = sprintf(
        "INSERT INTO categoria (NomeCategoria) VALUES ('%s');\n",
        $nomeCategoria
    );
    
    // Scrive la query nel file 'sql_insert.sql'
    file_put_contents('../sql_insert.sql', $sqlOperation, FILE_APPEND);

    
    // Reindirizza alla pagina dei corsi (o dove vuoi tu)
    header('Location: ../admin/amministratoreDashboard.php'); // Puoi cambiare la destinazione della redirezione
    exit;
}
