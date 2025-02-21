<?php
include('../config.php');

// Funzione per ottenere i nomi visibili delle colonne
function getColumnDisplayName($columnName) {
    $displayNames = [
        'IdIstruttore' => 'Nome Istruttore',
        'IdStudente' => 'Nome Studente',
        'IdCorso' => 'Nome Corso',
        'IdCategoria' => 'Nome Categoria',
        'Durata' => 'Durata (ore)',
        // Aggiungi qui altre colonne che vuoi rinominare
    ];

    return isset($displayNames[$columnName]) ? $displayNames[$columnName] : $columnName;
}

// Funzione per eseguire la ricerca su tutte le tabelle e colonne
function searchDatabase($searchQuery) {
    global $pdo; // Usa la variabile $pdo definita nel file principale

    // Esegui il file sql_insert.sql (per esempio, per inserire dati nel database)
    $sqlInsertQuery = file_get_contents('./sql_insert.sql');
    if ($sqlInsertQuery) {
        $pdo->exec($sqlInsertQuery); // Esegui la query di inserimento
    }

    // Recupera tutte le tabelle del database
    $tablesQuery = "SHOW TABLES";
    $tablesStmt = $pdo->query($tablesQuery);
    $tables = $tablesStmt->fetchAll(PDO::FETCH_COLUMN);

    $results = [];

    // Itera su tutte le tabelle
    foreach ($tables as $table) {
        // Recupera le colonne della tabella
        $columnsQuery = "DESCRIBE `$table`"; // Aggiungi i backtick intorno al nome della tabella
        $columnsStmt = $pdo->query($columnsQuery);
        
        $columns = [];
        $primaryKey = null; // Variabile per memorizzare la primary key
        $foreignKeys = []; // Array per memorizzare le chiavi esterne
        foreach ($columnsStmt as $row) {
            // Verifica se la colonna è la primary key
            if ($row['Key'] === 'PRI') {
                $primaryKey = $row['Field']; // Memorizza la primary key
            }

            // Verifica se la colonna è una chiave esterna
            if ($row['Key'] === 'MUL') { 
                // Se la colonna è una chiave esterna, possiamo ottenere il nome della tabella di riferimento
                $foreignKeys[$row['Field']] = getForeignKeyReferenceTable($table, $row['Field']);
            }

            $columns[] = $row['Field']; // Aggiungi la colonna alla lista
        }

        // Costruisci la query di ricerca per questa tabella
        $sql = "SELECT * FROM `$table` WHERE ";

        // Crea una condizione per ogni colonna, ma escludi la primary key e la colonna 'Password'
        $conditions = [];
        foreach ($columns as $column) {
            if ($column != $primaryKey && $column != 'Password') { // Escludiamo la colonna PK e la colonna Password
                $conditions[] = "`$column` LIKE ?"; // Usa il simbolo "?" invece di :search_query
            }
        }

        // Se ci sono condizioni (ovvero non è vuota la tabella), costruisci la query
        if (!empty($conditions)) {
            // Unisce le condizioni con OR
            $sql .= implode(" OR ", $conditions);

            // Prepara e esegui la query
            $stmt = $pdo->prepare($sql);
            $searchTerm = '%' . $searchQuery . '%';
            
            // Passa i parametri correttamente
            $stmt->execute(array_fill(0, count($conditions), $searchTerm));

            // Aggiungi i risultati per questa tabella all'array dei risultati
            $tableResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (!empty($tableResults)) {
                // Rimuoviamo la chiave primaria dai risultati e sostituiamo le chiavi esterne con i nomi delle tabelle
                foreach ($tableResults as &$row) {
                    unset($row[$primaryKey]); // Rimuovi la chiave primaria

                    // Rimuovi la colonna 'Password' dai risultati, se presente
                    if (isset($row['Password'])) {
                        unset($row['Password']);
                    }

                    // Per ogni chiave esterna, recuperiamo il nome e cognome dalla tabella di riferimento
                    foreach ($foreignKeys as $foreignKeyColumn => $referencedTable) {
                        if (isset($row[$foreignKeyColumn])) {
                            // Recupera il valore del campo 'Nome' e 'Cognome' dalla tabella di riferimento
                            $referencedValue = getReferencedNameAndSurname($referencedTable, $row[$foreignKeyColumn]);
                            if ($referencedValue) {
                                // Concatenamo nome e cognome
                                $row[$foreignKeyColumn] = $referencedValue; // Sostituisci l'ID con il nome concatenato
                            }
                        }
                    }
                }
                unset($row); // Termina il riferimento alla variabile $row

                $results[$table] = $tableResults; // Aggiungi i risultati per la tabella corrente
            }
        }
    }

    return $results;
}

// Funzione per ottenere il nome della tabella di riferimento di una chiave esterna
function getForeignKeyReferenceTable($table, $column) {
    global $pdo;

    // Ottieni le informazioni sulla chiave esterna
    $query = "SELECT REFERENCED_TABLE_NAME 
              FROM information_schema.KEY_COLUMN_USAGE
              WHERE TABLE_NAME = :table AND COLUMN_NAME = :column AND REFERENCED_TABLE_NAME IS NOT NULL";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['table' => $table, 'column' => $column]);

    // Restituisci il nome della tabella di riferimento
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result ? $result['REFERENCED_TABLE_NAME'] : null;
}

// Funzione per ottenere il valore del campo 'Nome' e 'Cognome' dalla tabella di riferimento
// Gestisce anche i casi per le tabelle "corso" e "categoria"
function getReferencedNameAndSurname($referencedTable, $foreignKeyValue) {
    global $pdo;

    // Caso per la tabella "corso"
    if ($referencedTable === 'corso') {
        $query = "SELECT Nome FROM `$referencedTable` WHERE Id$referencedTable = :foreignKeyValue";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['foreignKeyValue' => $foreignKeyValue]);

        // Restituisci il nome del corso
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['Nome'] : null;
    }

    // Caso per la tabella "categoria"
    if ($referencedTable === 'categoria') {
        $query = "SELECT NomeCategoria FROM `$referencedTable` WHERE Id$referencedTable = :foreignKeyValue";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['foreignKeyValue' => $foreignKeyValue]);

        // Restituisci il nome della categoria
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['NomeCategoria'] : null;
    }

    // Recupera tutte le colonne della tabella di riferimento per determinare se esistono le colonne 'Nome' e 'Cognome'
    $query = "DESCRIBE `$referencedTable`";
    $stmt = $pdo->query($query);
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Definiamo le colonne 'Nome' e 'Cognome' come riferimento
    $columnName = 'Nome';
    $columnSurname = 'Cognome';
    
    // Verifica se le colonne 'Nome' e 'Cognome' esistono nella tabella
    $nameExists = false;
    $surnameExists = false;

    foreach ($columns as $column) {
        if ($column['Field'] === $columnName) {
            $nameExists = true;
        }
        if ($column['Field'] === $columnSurname) {
            $surnameExists = true;
        }
    }

    // Se entrambe le colonne 'Nome' e 'Cognome' esistono, esegui la query per ottenere i valori
    if ($nameExists && $surnameExists) {
        $query = "SELECT $columnName, $columnSurname FROM `$referencedTable` WHERE Id$referencedTable = :foreignKeyValue";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['foreignKeyValue' => $foreignKeyValue]);

        // Restituisci nome e cognome concatenati
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result[$columnName] . ' ' . $result[$columnSurname] : null;
    }

    // Se le colonne non esistono, restituisci null
    return null;
}
?>
