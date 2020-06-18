-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 18. Jun 2020 um 15:01
-- Server-Version: 10.4.13-MariaDB
-- PHP-Version: 7.4.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `kurswahl`
--

CREATE DATABASE `kurswahl`;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `hum_20_admin`
--

CREATE TABLE `hum_20_admin` (
  `login` varchar(40) NOT NULL,
  `pass` varchar(40) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `hum_20_admin`
--

INSERT INTO `hum_20_admin` (`login`, `pass`) VALUES
('admin', 'd033e22ae348aeb5660fc2140aec35850c4da997');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `hum_20_einstellungen`
--

CREATE TABLE `hum_20_einstellungen` (
  `schluessel` varchar(200) NOT NULL,
  `wert` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `hum_20_einstellungen`
--

INSERT INTO `hum_20_einstellungen` (`schluessel`, `wert`) VALUES
('sys_motd', 'Diese Software speichert Ihre Kurswahl ohne eine Gültigkeitsprüfung\r\ndurchzuführen. Sie sind selbst für die Richtigkeit Ihrer Eingaben\r\nverantwortlich. Ihre Eingabe wird im Laufe des Halbjahrs von den Pädagogischen\r\nKoordinatoren überprüft. Falls dort Fehler festgestellt werden, werden Sie\r\ndavon in Kenntnis gesetzt.<br><br>\r\nSportkurse werden als belegt vorausgesetzt, deshalb können Sie in der Auswahltabelle\r\nimmer vier Kurse weniger sehen, als sie belegt haben (Kurszähler ist mindestens 4).<br><br>\r\nFächer, die Sie als Prüfungsfach wählen, werden automatisch mit vier belegten\r\nSemestern berechnet. Sie brauchen die Fächer in der Grundkurstabelle nicht erneut auszuwählen.'),
('pdf_footer', 'Die Kurswahl bitte kontrollieren, für die eigenen Unterlagen kopieren und bis zum 12. Juni 2015 unterschrieben beim Klassenlehrer abgeben. Änderungen können nur in begründeten Fällen, im Rahmen der organisatorischen Möglichkeiten und nach schriftlichem Antrag genehmigt werden. In wenigen Ausnahmefällen kann es noch immer zu notwendigen Änderungen kommen. ');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `hum_20_fach`
--

CREATE TABLE `hum_20_fach` (
  `kurz` varchar(5) NOT NULL,
  `lang` varchar(50) NOT NULL,
  `fachgr` varchar(5) NOT NULL,
  `ord` int(11) NOT NULL,
  `kannLK1` tinyint(1) NOT NULL,
  `kannPF3` tinyint(1) NOT NULL,
  `kannPF4` tinyint(1) NOT NULL,
  `kann5PK` tinyint(1) NOT NULL,
  `istFS` tinyint(1) NOT NULL,
  `istNW` tinyint(1) NOT NULL,
  `kannLK2` tinyint(1) NOT NULL,
  `semwaehlbar` varchar(10) NOT NULL,
  `kannGK` tinyint(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `hum_20_fach`
--

INSERT INTO `hum_20_fach` (`kurz`, `lang`, `fachgr`, `ord`, `kannLK1`, `kannPF3`, `kannPF4`, `kann5PK`, `istFS`, `istNW`, `kannLK2`, `semwaehlbar`, `kannGK`) VALUES
('DE', 'Deutsch', 'AF1', 101, 1, 1, 1, 1, 0, 0, 1, '44', 1),
('MA', 'Mathematik', 'AF3', 301, 1, 1, 1, 1, 0, 0, 1, '44', 1),
('BI', 'Biologie', 'NW', 304, 1, 1, 1, 1, 0, 0, 1, '12,34,44', 1),
('KU', 'Bildende Kunst', 'KF', 106, 0, 1, 1, 1, 0, 0, 1, '12,34,44', 1),
('CH', 'Chemie', 'NW', 303, 1, 1, 1, 1, 0, 0, 1, '12,44', 1),
('CN', 'Chinesisch', 'FS', 107, 1, 1, 1, 1, 0, 0, 1, '12,44', 1),
('DS', 'Theater', 'KF', 108, 0, 0, 1, 1, 0, 0, 0, '12,44', 1),
('EK', 'Geografie', 'AF2', 203, 0, 1, 1, 1, 0, 0, 1, '12,34,44', 1),
('EN', 'Englisch', 'FS', 102, 1, 1, 1, 1, 0, 0, 1, '12,44', 1),
('FR', 'Französisch', 'FS', 104, 1, 1, 1, 1, 0, 0, 1, '12,44', 1),
('GE', 'Geschichte', 'AF2', 202, 0, 1, 1, 1, 0, 0, 1, '12,34,44', 1),
('IN', 'Informatik', 'AF3', 305, 0, 1, 1, 1, 0, 0, 1, '12,34,44', 1),
('LA', 'Latein', 'FS', 103, 1, 1, 1, 1, 0, 0, 1, '12,44', 1),
('SKK', 'Kunst u. Design', 'ZK', 422, 0, 0, 0, 0, 0, 0, 0, '22', 1),
('LZ', 'Lat. für Mediziner', 'ZK', 498, 0, 0, 0, 0, 0, 0, 0, '22', 1),
('MU', 'Musik', 'KF', 105, 0, 1, 1, 1, 0, 0, 1, '12,34,44', 1),
('PL', 'Philosophie', 'AF2', 205, 0, 1, 1, 1, 0, 0, 1, '12,34,44', 1),
('PH', 'Physik', 'NW', 302, 1, 1, 1, 1, 0, 0, 1, '12,44', 1),
('PW', 'Politikwissenschaft', 'AF2', 201, 0, 1, 1, 1, 0, 0, 1, '12,34,44', 1),
('EZ', 'Zusatzk. Englisch', 'ZK', 499, 0, 0, 0, 0, 0, 0, 0, '22', 1),
('SP', 'Sport', 'SP', 306, 0, 0, 1, 1, 0, 0, 0, '44,0', 1),
('ST', 'Sport-Theorie', 'SP', 307, 0, 0, 0, 0, 0, 0, 0, '34', 1),
('WL', 'Wirtschaftswiss.', 'AF2', 204, 0, 1, 1, 1, 0, 0, 1, '44', 0),
('SKR', 'Rhetorik', 'ZK', 421, 0, 0, 0, 0, 0, 0, 0, '22', 1),
('SKY', 'Coaching', 'ZK', 429, 0, 0, 0, 0, 0, 0, 0, '22', 1),
('SKD', 'Chemie & Medizin', 'ZK', 411, 0, 0, 0, 0, 0, 0, 0, '22', 1),
('SKH', 'Gesund & Entspannt', 'ZK', 413, 0, 0, 0, 0, 0, 0, 0, '11', 1),
('SKC', 'Studium & Beruf', 'ZK', 414, 0, 0, 0, 0, 0, 0, 0, '12,1,2', 1),
('SKV', 'Relativitätstheorie', 'ZK', 423, 0, 0, 0, 0, 0, 0, 0, '22', 1),
('SKL', 'Psychoanalyse Lit.', 'ZK', 420, 0, 0, 0, 0, 0, 0, 0, '22', 1),
('MEC', 'Chor-Ensemble', 'ZK', 430, 0, 0, 0, 0, 0, 0, 0, '12,34,44', 1),
('SKU', 'Mathematik für Profis', 'ZK', 425, 0, 0, 0, 0, 0, 0, 0, '22', 1),
('SKX', 'Experimentieren', 'ZK', 426, 0, 0, 0, 0, 0, 0, 0, '22', 1),
('SKO', 'Robotik', 'ZK', 428, 0, 0, 0, 0, 0, 0, 0, '22', 1),
('MEJ', 'Jazz-Ensemble', 'ZK', 431, 0, 0, 0, 0, 0, 0, 0, '12,34,44', 1),
('MEO', 'Orchester-Ens.', 'ZK', 432, 0, 0, 0, 0, 0, 0, 0, '12,34,44', 1),
('SKI', 'Digitale Welten', 'ZK', 403, 0, 0, 0, 0, 0, 0, 0, '22,44', 1),
('SKG', 'Gut beraten', 'ZK', 404, 0, 0, 0, 0, 0, 0, 0, '22', 1),
('SKQ', 'Globale Herausford.', 'ZK', 405, 0, 0, 0, 0, 0, 0, 0, '22', 1),
('SKF', 'Fachtexte', 'ZK', 415, 0, 0, 0, 0, 0, 0, 0, '22', 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `hum_20_schueler`
--

CREATE TABLE `hum_20_schueler` (
  `snr` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `vorname` varchar(100) NOT NULL,
  `pw` char(40) NOT NULL,
  `prof1` varchar(5) NOT NULL,
  `prof2` varchar(5) NOT NULL,
  `klasse` varchar(3) NOT NULL,
  `kwfehler` varchar(200) NOT NULL,
  `realpw` varchar(30) NOT NULL,
  `mail` varchar(300) NOT NULL DEFAULT '',
  `kursadd` int(11) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `hum_20_sportkurs`
--

CREATE TABLE `hum_20_sportkurs` (
  `kuerzel` varchar(4) NOT NULL,
  `langname` varchar(40) NOT NULL,
  `hat2ls` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `hum_20_sportkurs`
--

INSERT INTO `hum_20_sportkurs` (`kuerzel`, `langname`, `hat2ls`) VALUES
('TT', 'Tischtennis', 1),
('BM', 'Badminton', 1),
('AF', 'Fitness', 1),
('FB', 'Fussball', 1),
('HB', 'Handball', 1),
('VB', 'Volleyball', 1),
('LA', 'Leichtathletik', 1),
('TU', 'Turnen', 1),
('SW', 'Schwimmen', 1),
('TA', 'Gymnastik/Tanz', 1),
('RU', 'Rudern', 1),
('BB', 'Basketball', 1),
('KL', 'Klettern', 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `hum_20_waehlt`
--

CREATE TABLE `hum_20_waehlt` (
  `snr` int(11) NOT NULL,
  `fachkurz` varchar(5) NOT NULL,
  `sem` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `hum_20_waehltpf`
--

CREATE TABLE `hum_20_waehltpf` (
  `snr` int(11) NOT NULL,
  `fachkurz` varchar(3) NOT NULL,
  `pf` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `hum_20_waehltsp`
--

CREATE TABLE `hum_20_waehltsp` (
  `kuerzel` varchar(4) NOT NULL,
  `sNummer` int(11) NOT NULL,
  `sem` int(11) NOT NULL,
  `lstufe` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `kurswahl_jahrgang`
--

CREATE TABLE `kurswahl_jahrgang` (
  `schulnr` varchar(5) NOT NULL,
  `jahr` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `kurswahl_jahrgang`
--

INSERT INTO `kurswahl_jahrgang` (`schulnr`, `jahr`) VALUES
('hum', 20);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `kurswahl_schule`
--

CREATE TABLE `kurswahl_schule` (
  `schulnr` varchar(5) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `kurswahl_schule`
--

INSERT INTO `kurswahl_schule` (`schulnr`, `name`) VALUES
('hum', 'Hummel-Schule');

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `hum_20_admin`
--
ALTER TABLE `hum_20_admin`
  ADD PRIMARY KEY (`login`);

--
-- Indizes für die Tabelle `hum_20_einstellungen`
--
ALTER TABLE `hum_20_einstellungen`
  ADD PRIMARY KEY (`schluessel`);

--
-- Indizes für die Tabelle `hum_20_fach`
--
ALTER TABLE `hum_20_fach`
  ADD PRIMARY KEY (`kurz`);

--
-- Indizes für die Tabelle `hum_20_schueler`
--
ALTER TABLE `hum_20_schueler`
  ADD PRIMARY KEY (`snr`);

--
-- Indizes für die Tabelle `hum_20_sportkurs`
--
ALTER TABLE `hum_20_sportkurs`
  ADD PRIMARY KEY (`kuerzel`);

--
-- Indizes für die Tabelle `kurswahl_schule`
--
ALTER TABLE `kurswahl_schule`
  ADD PRIMARY KEY (`schulnr`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
