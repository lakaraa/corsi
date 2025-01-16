DROP TABLE IF EXISTS `iscrizione`;
DROP TABLE IF EXISTS `corso`;
DROP TABLE IF EXISTS `istruttore`;
DROP TABLE IF EXISTS `studente`;
DROP TABLE IF EXISTS `categoria`;
DROP TABLE IF EXISTS `amministratore`;
DROP TABLE IF EXISTS `messaggi`;

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

-- Foreign Key Constraints
ALTER TABLE `corso`
    ADD CONSTRAINT `fk_Corso_Amministratore` FOREIGN KEY (`Idamministratore`) REFERENCES `amministratore` (`IdAmministratore`),
    ADD CONSTRAINT `fk_Corso_Categoria` FOREIGN KEY (`IdCategoria`) REFERENCES `categoria` (`IdCategoria`),
    ADD CONSTRAINT `fk_Corso_Istruttore` FOREIGN KEY (`IdIstruttore`) REFERENCES `istruttore` (`IdIstruttore`);

ALTER TABLE `iscrizione`
    ADD CONSTRAINT `fk_Iscrizione_Corso` FOREIGN KEY (`IdCorso`) REFERENCES `corso` (`IdCorso`),
    ADD CONSTRAINT `fk_Iscrizione_Studente` FOREIGN KEY (`IdStudente`) REFERENCES `studente` (`IdStudente`);

COMMIT;
