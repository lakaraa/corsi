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

-- Indici e chiavi esterne
ALTER TABLE `amministratore`
    ADD PRIMARY KEY (`IdAmministratore`),
    ADD UNIQUE KEY `Email` (`Email`);

ALTER TABLE `categoria`
    ADD PRIMARY KEY (`IdCategoria`);

ALTER TABLE `corso`
    ADD PRIMARY KEY (`IdCorso`),
    ADD KEY `fk_Corso_Istruttore` (`IdIstruttore`),
    ADD KEY `fk_Corso_Categoria` (`IdCategoria`),
    ADD KEY `fk_Corso_Amministratore` (`Idamministratore`);

ALTER TABLE `iscrizione`
    ADD PRIMARY KEY (`IdIscrizione`),
    ADD KEY `fk_Iscrizione_Corso` (`IdCorso`),
    ADD KEY `fk_Iscrizione_Studente` (`IdStudente`);

ALTER TABLE `istruttore`
    ADD PRIMARY KEY (`IdIstruttore`),
    ADD UNIQUE KEY `Email` (`Email`);

ALTER TABLE `messaggi`
    ADD PRIMARY KEY (`id`);

ALTER TABLE `studente`
    ADD PRIMARY KEY (`IdStudente`),
    ADD UNIQUE KEY `Email` (`Email`);

-- Auto-incremento
ALTER TABLE `amministratore`
    MODIFY `IdAmministratore` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

ALTER TABLE `categoria`
    MODIFY `IdCategoria` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

ALTER TABLE `corso`
    MODIFY `IdCorso` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=116;

ALTER TABLE `iscrizione`
    MODIFY `IdIscrizione` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=108;

ALTER TABLE `istruttore`
    MODIFY `IdIstruttore` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

ALTER TABLE `messaggi`
    MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

ALTER TABLE `studente`
    MODIFY `IdStudente` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

-- Foreign Key Constraints
ALTER TABLE `corso`
    ADD CONSTRAINT `fk_Corso_Amministratore` FOREIGN KEY (`Idamministratore`) REFERENCES `amministratore` (`IdAmministratore`),
    ADD CONSTRAINT `fk_Corso_Categoria` FOREIGN KEY (`IdCategoria`) REFERENCES `categoria` (`IdCategoria`),
    ADD CONSTRAINT `fk_Corso_Istruttore` FOREIGN KEY (`IdIstruttore`) REFERENCES `istruttore` (`IdIstruttore`);

ALTER TABLE `iscrizione`
    ADD CONSTRAINT `fk_Iscrizione_Corso` FOREIGN KEY (`IdCorso`) REFERENCES `corso` (`IdCorso`),
    ADD CONSTRAINT `fk_Iscrizione_Studente` FOREIGN KEY (`IdStudente`) REFERENCES `studente` (`IdStudente`);

COMMIT;
