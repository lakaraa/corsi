<?php
// Connessione al database
include('../config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recupero dei dati dal form
    $nome_corso = trim($_POST['nome_corso']);
    $durata = intval($_POST['durata']); // Durata in ore o giorni, secondo il tuo schema
    $data_inizio = $_POST['data_inizio'];
    $data_fine = $_POST['data_fine'];
    $id_istruttore = intval($_POST['id_istruttore']);
    $id_categoria = intval($_POST['id_categoria']);
    $id_amministratore = intval($_POST['id_amministratore']);

    // Validazione dei campi
    if (empty($nome_corso) || $durata <= 0 || empty($data_inizio) || empty($data_fine) || $id_istruttore <= 0 || $id_categoria <= 0 || $id_amministratore <= 0) {
        echo "Tutti i campi sono obbligatori e devono essere validi.";
        exit;
    }

    // Verifica della validità delle date
    if (strtotime($data_inizio) > strtotime($data_fine)) {
        echo "La data di inizio non può essere successiva alla data di fine.";
        exit;
    }

    try {
        // Preparazione della query
        $stmt = $pdo->prepare("
            INSERT INTO corso (Nome, Durata, DataInizio, DataFine, IdIstruttore, IdCategoria, IdAmministratore)
            VALUES (:nome_corso, :durata, :data_inizio, :data_fine, :id_istruttore, :id_categoria, :id_amministratore)
        ");
        // Bind dei parametri
        $stmt->bindParam(':nome_corso', $nome_corso, PDO::PARAM_STR);
        $stmt->bindParam(':durata', $durata, PDO::PARAM_INT);
        $stmt->bindParam(':data_inizio', $data_inizio, PDO::PARAM_STR);
        $stmt->bindParam(':data_fine', $data_fine, PDO::PARAM_STR);
        $stmt->bindParam(':id_istruttore', $id_istruttore, PDO::PARAM_INT);
        $stmt->bindParam(':id_categoria', $id_categoria, PDO::PARAM_INT);
        $stmt->bindParam(':id_amministratore', $id_amministratore, PDO::PARAM_INT);

        // Creazione della query SQL da inserire nel file .sql
        $sqlOperation = sprintf(
            // La query SQL viene formattata con i seguenti valori
            "INSERT INTO corso (Nome, Durata, DataInizio, DataFine, IdIstruttore, IdCategoria, IdAmministratore)
            VALUES ('%s', %d, '%s', '%s', %d, %d, %d);\n",
        
            // La funzione addslashes viene utilizzata per proteggere i valori stringa, evitando che caratteri speciali come ' e " possano interrompere la query SQL
            // La variabile $nome_corso viene trattata come stringa (per essere inserita tra apici nella query)
            addslashes($nome_corso),
            $durata,
            $data_inizio,
            $data_fine,
            $id_istruttore,
            $id_categoria,
            $id_amministratore
        );
        
        // La funzione file_put_contents scrive la query nel file 'sql_insert.sql'
        // FILE_APPEND significa che i dati vengono aggiunti alla fine del file esistente, senza sovrascriverlo
        file_put_contents('../sql_insert.sql', $sqlOperation, FILE_APPEND);
        
        
        // Esecuzione della query
        if ($stmt->execute()) {
            echo "Corso aggiunto con successo.";
            header("Location: amministratoreDashboard.php");
            exit;
        } else {
            echo "Errore durante l'aggiunta del corso.";
        }
    } catch (PDOException $e) {
        echo "Errore di database: " . $e->getMessage();
    }
} else {
    echo "Metodo non supportato.";
}
?>
