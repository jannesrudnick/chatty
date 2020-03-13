-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 12. Mrz 2020 um 11:54
-- Server-Version: 10.4.11-MariaDB
-- PHP-Version: 7.4.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `mos`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `is_empfang`
--

CREATE TABLE `is_empfang` (
  `uid` int(11) NOT NULL,
  `mid` int(11) NOT NULL,
  `gelesen` enum('n','j') NOT NULL DEFAULT 'n'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `is_message`
--

CREATE TABLE `is_message` (
  `mid` int(11) NOT NULL,
  `betreff` varchar(50) NOT NULL,
  `nachricht` text NOT NULL,
  `datumzeit` datetime NOT NULL DEFAULT current_timestamp(),
  `senderuid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `is_user`
--

CREATE TABLE `is_user` (
  `uid` int(11) NOT NULL,
  `anrede` enum('Herr','Frau') NOT NULL,
  `nachname` varchar(20) NOT NULL,
  `vorname` varchar(20) NOT NULL,
  `username` varchar(6) NOT NULL,
  `passwort` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `is_user`
--

INSERT INTO `is_user` (`uid`, `anrede`, `nachname`, `vorname`, `username`, `passwort`) VALUES
(1, 'Frau', 'Hurtig', 'Frieda', 'frihur', 'b325f1e7c436577f07700ffea219ff03'),
(3, 'Herr', 'Müller', 'Klaus', 'klamue', '098f6bcd4621d373cade4e832627b4f6');

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `is_empfang`
--
ALTER TABLE `is_empfang`
  ADD PRIMARY KEY (`uid`,`mid`);

--
-- Indizes für die Tabelle `is_message`
--
ALTER TABLE `is_message`
  ADD PRIMARY KEY (`mid`);

--
-- Indizes für die Tabelle `is_user`
--
ALTER TABLE `is_user`
  ADD PRIMARY KEY (`uid`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `is_message`
--
ALTER TABLE `is_message`
  MODIFY `mid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `is_user`
--
ALTER TABLE `is_user`
  MODIFY `uid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
