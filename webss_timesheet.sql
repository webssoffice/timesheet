-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Feb 17, 2023 alle 09:37
-- Versione del server: 10.4.27-MariaDB
-- Versione PHP: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `test`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `agenda`
--

CREATE TABLE `agenda` (
  `id` int(11) NOT NULL,
  `related_employee` int(11) DEFAULT NULL,
  `agenda_date` date DEFAULT NULL,
  `agenda_time` time DEFAULT NULL,
  `event` longtext DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `employees`
--

CREATE TABLE `employees` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `level` int(11) NOT NULL,
  `employee_rate` varchar(255) DEFAULT NULL,
  `csrf` varchar(255) DEFAULT NULL,
  `registration_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dump dei dati per la tabella `employees`
--

INSERT INTO `employees` (`id`, `name`, `email`, `password`, `level`, `employee_rate`, `csrf`, `registration_date`) VALUES
(9, 'User', 'user@webss.ro', '$5$5crHBIc6qyFtq66V$xSSdOB3VC9DNocvtkEiFyBJ6ZNeEG2MsrgUq/yV7KzA', 2, '1', '$5$5crHBIc6qyFtq66V$xSSdOB3VC9DNocvtkEiFyBJ6ZNeEG2MsrgUq/yV7KzA', '2023-02-01 18:29:17'),
(11, 'Admin', 'admin@webss.ro', '$5$5crHBIc6qyFtq66V$bcnu4Qf9xvN6qU1ktGKliED3/D/eNESZkD4a9oTUac/', 1, '1', '$5$5crHBIc6qyFtq66V$bcnu4Qf9xvN6qU1ktGKliED3/D/eNESZkD4a9oTUac/', '2023-02-17 10:30:55');

-- --------------------------------------------------------

--
-- Struttura della tabella `projects`
--

CREATE TABLE `projects` (
  `id` int(11) NOT NULL,
  `project` mediumtext DEFAULT NULL,
  `details` longtext DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `works`
--

CREATE TABLE `works` (
  `id` int(11) NOT NULL,
  `related_employee` int(11) DEFAULT NULL,
  `related_project` int(11) DEFAULT NULL,
  `start_time` datetime DEFAULT NULL,
  `end_time` datetime DEFAULT NULL,
  `comment` longtext DEFAULT NULL,
  `rate` varchar(255) DEFAULT NULL,
  `paid` varchar(255) NOT NULL DEFAULT '0',
  `edit_by` int(11) DEFAULT NULL,
  `last_edit` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `agenda`
--
ALTER TABLE `agenda`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indici per le tabelle `employees`
--
ALTER TABLE `employees`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indici per le tabelle `projects`
--
ALTER TABLE `projects`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indici per le tabelle `works`
--
ALTER TABLE `works`
  ADD UNIQUE KEY `id` (`id`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `agenda`
--
ALTER TABLE `agenda`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT per la tabella `projects`
--
ALTER TABLE `projects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `works`
--
ALTER TABLE `works`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
