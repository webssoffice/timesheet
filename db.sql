-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Creato il: Giu 10, 2022 alle 21:14
-- Versione del server: 10.2.43-MariaDB-cll-lve
-- Versione PHP: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `webss_work`
--

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `employees`
--

INSERT INTO `employees` (`id`, `name`, `email`, `password`, `level`, `employee_rate`, `csrf`, `registration_date`) VALUES
(1, 'test', 'test@test.test', '$5$5crHBIc6qyFtq66V$OkDJBlTJMbcahaXIbNF.Tcx5is1Cf4jur99W9lupkCD', 2, '1', '$5$5crHBIc6qyFtq66V$QnzWuy.gN/s3bQ/STtPeYM3CJerlC9qUKTEHsGKdoI.', '2022-05-20 02:21:12');

-- --------------------------------------------------------

--
-- Struttura della tabella `projects`
--

CREATE TABLE `projects` (
  `id` int(11) NOT NULL,
  `project` mediumtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `projects`
--

INSERT INTO `projects` (`id`, `project`) VALUES
(1, 'Test');

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
  `paid` varchar(255) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `works`
--

INSERT INTO `works` (`id`, `related_employee`, `related_project`, `start_time`, `end_time`, `comment`, `paid`) VALUES
(1, 2, 1, '2022-01-19 10:41:51', '2022-01-19 12:52:01', 'Test', '1');

--
-- Indici per le tabelle scaricate
--

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
-- AUTO_INCREMENT per la tabella `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT per la tabella `projects`
--
ALTER TABLE `projects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT per la tabella `works`
--
ALTER TABLE `works`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
