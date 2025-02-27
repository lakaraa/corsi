<?php


// Definizione delle credenziali del database solo se non sono già definite
if (!defined('DB_SERVER')) {
    define('DB_SERVER', $_ENV['AZURE_MYSQL_HOST']);
}
if (!defined('DB_USERNAME')) {
    define('DB_USERNAME', $_ENV['AZURE_MYSQL_USERNAME']);
}
if (!defined('DB_PASSWORD')) {
    define('DB_PASSWORD', $_ENV['AZURE_MYSQL_PASSWORD']);
}
if (!defined('DB_NAME')) {
    define('DB_NAME', $_ENV['AZURE_MYSQL_DBNAME']);
}
if (!defined('SSL_CA')) {
    define('SSL_CA', 'DigiCertGlobalRootG2.crt.pem');
}
try {
    // Stringa DSN per la connessione PDO
    $dsn = "mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME;

    // Opzioni di connessione PDO, con supporto per SSL
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,  // Modalità di gestione degli errori tramite eccezioni
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,  // Tipo di fetch dei risultati
        PDO::ATTR_EMULATE_PREPARES => false,  // Disabilita la preparazione emulata delle query
        PDO::MYSQL_ATTR_SSL_CA => SSL_CA,  // Certificato CA
        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,  // Disabilita la verifica del certificato del server
    ];

    // Creazione della connessione PDO
    $pdo = new PDO($dsn, DB_USERNAME, DB_PASSWORD, $options);

    //$createTablesSQLPath = 'sql_create_tables.sql';

    $createTablesSQLPath = realpath(__DIR__ . '/sql_create_tables.sql');
    //echo "Percorso del file SQL: " . $createTablesSQLPath;
    if (file_exists($createTablesSQLPath)) {
        $createTablesSQL = file_get_contents($createTablesSQLPath);
        $pdo->exec($createTablesSQL);
    } else {
        //die("Errore: Il file sql_create_tables.sql non è stato trovato.");
    }

    
    //$insertDataSQLPath = 'sql_insert.sql';
    $insertDataSQLPath = realpath(__DIR__ . '/sql_insert.sql');
    if (file_exists($insertDataSQLPath)) {
        $insertDataSQL = file_get_contents($insertDataSQLPath);
        $pdo->exec($insertDataSQL);
        //echo "Dati inseriti con successo.";

    } else {
        //die("Errore: Il file sql_insert.sql non è stato trovato.");
    }
    

} catch (PDOException $e) {
    // In caso di errore, mostra il messaggio di errore
    die("ERROR: Could not connect. " . $e->getMessage());
}

?>

