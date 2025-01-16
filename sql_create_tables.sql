SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- Database: `corsi`

CREATE TABLE IF NOT EXISTS `amministratore` (
    `IdAmministratore` int AUTO_INCREMENT PRIMARY KEY,
    `Nome` varchar(100) DEFAULT NULL,
    `Cognome` varchar(100) DEFAULT NULL,
    `Telefono` varchar(15) DEFAULT NULL,
    `Email` varchar(100) DEFAULT NULL,
    `Password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE IF NOT EXISTS `categoria` (
    `IdCategoria` int AUTO_INCREMENT PRIMARY KEY,
    `NomeCategoria` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE IF NOT EXISTS `corso` (
    `IdCorso` int AUTO_INCREMENT PRIMARY KEY,
    `Nome` varchar(100) DEFAULT NULL,
    `Durata` int DEFAULT NULL,
    `DataInizio` date DEFAULT NULL,
    `IdIstruttore` int DEFAULT NULL,
    `IdCategoria` int DEFAULT NULL,
    `DataFine` date DEFAULT NULL,
    `Idamministratore` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE IF NOT EXISTS `iscrizione` (
    `IdIscrizione` int AUTO_INCREMENT PRIMARY KEY,
    `DataIscrizione` date DEFAULT NULL,
    `Livello` varchar(50) DEFAULT NULL,
    `IdCorso` int DEFAULT NULL,
    `IdStudente` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE IF NOT EXISTS `istruttore` (
    `IdIstruttore` int AUTO_INCREMENT PRIMARY KEY,
    `Nome` varchar(100) DEFAULT NULL,
    `Cognome` varchar(100) DEFAULT NULL,
    `Telefono` varchar(15) DEFAULT NULL,
    `Email` varchar(100) DEFAULT NULL,
    `Password` varchar(255) DEFAULT NULL,
    `Specializzazione` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE IF NOT EXISTS `messaggi` (
    `id` int AUTO_INCREMENT PRIMARY KEY,
    `name` varchar(255) NOT NULL,
    `email` varchar(255) NOT NULL,
    `oggetto` varchar(255) NOT NULL,
    `message` text NOT NULL,
    `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE IF NOT EXISTS `studente` (
    `IdStudente` int AUTO_INCREMENT PRIMARY KEY,
    `Nome` varchar(100) DEFAULT NULL,
    `Cognome` varchar(100) DEFAULT NULL,
    `Telefono` varchar(15) DEFAULT NULL,
    `Email` varchar(100) DEFAULT NULL,
    `Password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Rimuovi i trigger esistenti
DROP TRIGGER IF EXISTS `ValidazioneDataInizio`;
DROP TRIGGER IF EXISTS `calcola_datafine`;
DROP TRIGGER IF EXISTS `calcola_datafine_update`;

-- Trigger per validare la data di inizio
CREATE TRIGGER `ValidazioneDataInizio` 
BEFORE INSERT ON `corso`
FOR EACH ROW 
BEGIN
    -- Controllo che la data di inizio non sia nel passato
    IF NEW.DataInizio < CURDATE() THEN
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'La data di inizio non può essere nel passato';
    END IF;

    -- Controllo che la data di inizio non sia durante il weekend
    IF DAYOFWEEK(NEW.DataInizio) IN (1, 7) THEN
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'La data di inizio non può essere durante il weekend';
    END IF;
END;

-- Trigger per calcolare la data di fine durante l'inserimento
CREATE TRIGGER `calcola_datafine` 
BEFORE INSERT ON `corso`
FOR EACH ROW 
BEGIN
    DECLARE giorni INT;
    DECLARE giorni_aggiunti INT DEFAULT 0;
    DECLARE giorno_corrente DATE;

    SET giorni = NEW.Durata;
    SET giorno_corrente = NEW.DataInizio;

    -- Aggiungi giorni lavorativi (escludendo weekend)
    WHILE giorni_aggiunti < giorni DO
        SET giorno_corrente = DATE_ADD(giorno_corrente, INTERVAL 1 DAY);
        IF DAYOFWEEK(giorno_corrente) NOT IN (1, 7) THEN
            SET giorni_aggiunti = giorni_aggiunti + 1;
        END IF;
    END WHILE;

    SET NEW.DataFine = giorno_corrente;
END;

-- Trigger per calcolare la data di fine durante l'aggiornamento
CREATE TRIGGER `calcola_datafine_update` 
BEFORE UPDATE ON `corso`
FOR EACH ROW 
BEGIN
    IF NEW.DataFine IS NULL THEN
        DECLARE giorni INT;
        DECLARE giorni_aggiunti INT DEFAULT 0;
        DECLARE giorno_corrente DATE;

        SET giorni = NEW.Durata;
        SET giorno_corrente = NEW.DataInizio;

        -- Aggiungi giorni lavorativi (escludendo weekend)
        WHILE giorni_aggiunti < giorni DO
            SET giorno_corrente = DATE_ADD(giorno_corrente, INTERVAL 1 DAY);
            IF DAYOFWEEK(giorno_corrente) NOT IN (1, 7) THEN
                SET giorni_aggiunti = giorni_aggiunti + 1;
            END IF;
        END WHILE;

        SET NEW.DataFine = giorno_corrente;
    END IF;
END;

-- Indici e chiavi esterne
ALTER TABLE `corso`
    ADD KEY `fk_Corso_Istruttore` (`IdIstruttore`),
    ADD KEY `fk_Corso_Categoria` (`IdCategoria`),
    ADD KEY `fk_Corso_Amministratore` (`Idamministratore`);

ALTER TABLE `iscrizione`
    ADD KEY `fk_Iscrizione_Corso` (`IdCorso`),
    ADD KEY `fk_Iscrizione_Studente` (`IdStudente`);

ALTER TABLE `istruttore`
    ADD UNIQUE KEY `Email` (`Email`);

ALTER TABLE `studente`
    ADD UNIQUE KEY `Email` (`Email`);

-- Foreign Key Constraints
ALTER TABLE `corso`
    ADD CONSTRAINT `fk_Corso_Amministratore` FOREIGN KEY (`Idamministratore`) REFERENCES `amministratore` (`IdAmministratore`),
    ADD CONSTRAINT `fk_Corso_Categoria` FOREIGN KEY (`IdCategoria`) REFERENCES `categoria` (`IdCategoria`),
    ADD CONSTRAINT `fk_Corso_Istruttore` FOREIGN KEY (`IdIstruttore`) REFERENCES `istruttore` (`IdIstruttore`);

ALTER TABLE `iscrizione`
    ADD CONSTRAINT `fk_Iscrizione_Corso` FOREIGN KEY (`IdCorso`) REFERENCES `corso` (`IdCorso`),
    ADD CONSTRAINT `fk_Iscrizione_Studente` FOREIGN KEY (`IdStudente`) REFERENCES `studente` (`IdStudente`);

COMMIT;
