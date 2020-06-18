-- phpMyAdmin SQL Dump
-- version 3.5.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 08. Nov 2013 um 20:39
-- Server Version: 5.5.25a
-- PHP-Version: 5.4.4

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Datenbank: `kurswahl`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `kurswahl_jahrgang`
--

CREATE TABLE IF NOT EXISTS `kurswahl_jahrgang` (
  `schulnr` varchar(5) NOT NULL,
  `jahr` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `kurswahl_jahrgang`
--

INSERT INTO `kurswahl_jahrgang` (`schulnr`, `jahr`) VALUES
('sndbx', 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `kurswahl_schule`
--

CREATE TABLE IF NOT EXISTS `kurswahl_schule` (
  `schulnr` varchar(5) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `kurswahl_schule`
--

INSERT INTO `kurswahl_schule` (`schulnr`, `name`) VALUES
('sndbx', 'Buddelkasten-Schule');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `sndbx_0_admin`
--

CREATE TABLE IF NOT EXISTS `sndbx_0_admin` (
  `login` varchar(40) NOT NULL,
  `pass` varchar(40) NOT NULL,
  PRIMARY KEY (`login`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `sndbx_0_admin`
--

INSERT INTO `sndbx_0_admin` (`login`, `pass`) VALUES
('timo', 'b7a875fc1ea228b9061041b7cec4bd3c52ab3ce3'),
('kai', 'b7a875fc1ea228b9061041b7cec4bd3c52ab3ce3'),
('robert', 'b7a875fc1ea228b9061041b7cec4bd3c52ab3ce3'),
('pelz', 'b7a875fc1ea228b9061041b7cec4bd3c52ab3ce3');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `sndbx_0_einstellungen`
--

CREATE TABLE IF NOT EXISTS `sndbx_0_einstellungen` (
  `schluessel` varchar(200) NOT NULL,
  `wert` text NOT NULL,
  PRIMARY KEY (`schluessel`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `sndbx_0_einstellungen`
--

INSERT INTO `sndbx_0_einstellungen` (`schluessel`, `wert`) VALUES
('sys_motd', 'Hier stehen Hinweise zur Nutzung.'),
('pdf_footer', 'PDF-Fußzeile.');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `sndbx_0_fach`
--

CREATE TABLE IF NOT EXISTS `sndbx_0_fach` (
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
  `kannGK` tinyint(1) NOT NULL,
  PRIMARY KEY (`kurz`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `sndbx_0_fach`
--

INSERT INTO `sndbx_0_fach` (`kurz`, `lang`, `fachgr`, `ord`, `kannLK1`, `kannPF3`, `kannPF4`, `kann5PK`, `istFS`, `istNW`, `kannLK2`, `semwaehlbar`, `kannGK`) VALUES
('DE', 'Deutsch', 'AF1', 101, 1, 1, 1, 1, 0, 0, 1, '44', 1),
('MA', 'Mathematik', 'AF3', 301, 1, 1, 1, 1, 0, 0, 1, '44', 1),
('BI', 'Biologie', 'NW', 304, 1, 1, 1, 1, 0, 0, 1, '12,34,44', 1),
('KU', 'Bildende Kunst', 'KF', 106, 0, 1, 1, 1, 0, 0, 1, '12,34,44', 1),
('CH', 'Chemie', 'NW', 303, 1, 1, 1, 1, 0, 0, 1, '12,44', 1),
('CN', 'Chinesisch', 'FS', 107, 0, 1, 1, 1, 0, 0, 0, '12,44', 1),
('DS', 'Darstellendes Spiel', 'KF', 108, 0, 0, 0, 0, 0, 0, 0, '12', 1),
('EK', 'Geografie', 'AF2', 203, 0, 1, 1, 1, 0, 0, 1, '12,34,44', 1),
('EN', 'Englisch', 'FS', 102, 1, 1, 1, 1, 0, 0, 1, '12,44', 1),
('FR', 'Französisch', 'FS', 104, 1, 1, 1, 1, 0, 0, 1, '12,44', 1),
('GE', 'Geschichte', 'AF2', 202, 0, 1, 1, 1, 0, 0, 1, '12,34,44', 1),
('IN', 'Informatik', 'AF3', 305, 0, 1, 0, 1, 0, 0, 1, '12,34,44', 1),
('LA', 'Latein', 'FS', 103, 1, 1, 1, 1, 0, 0, 1, '12,44', 1),
('ME', 'Musik-Ensemble', 'ZK', 403, 0, 0, 0, 0, 0, 0, 0, '12,34,44', 1),
('LZ', 'med. Latein', 'ZK', 404, 0, 0, 0, 0, 0, 0, 0, '11,22', 1),
('MU', 'Musik', 'KF', 105, 0, 1, 1, 1, 0, 0, 1, '12,34,44', 1),
('PL', 'Philosophie', 'AF2', 205, 0, 1, 1, 1, 0, 0, 1, '12,34,44', 1),
('PH', 'Physik', 'NW', 302, 1, 1, 1, 1, 0, 0, 1, '12,44', 1),
('PW', 'Politikwissenschaft', 'AF2', 201, 0, 1, 1, 1, 0, 0, 1, '12,34,44', 1),
('EZ', 'Cambridge Proficiency', 'ZK', 499, 0, 0, 0, 0, 0, 0, 0, '12,44', 1),
('SP', 'Sport', 'SP', 306, 0, 0, 1, 1, 0, 0, 0, '44,0', 1),
('ST', 'Sport-Theorie', 'SP', 307, 0, 0, 0, 0, 0, 0, 0, '34', 1),
('WL', 'Wirtschaftswiss.', 'AF2', 204, 0, 1, 0, 1, 0, 0, 1, '44', 0),
('SKA', 'Film', 'ZK', 406, 0, 0, 0, 0, 0, 0, 0, '22', 1),
('SKR', 'Rhetorik', 'ZK', 421, 0, 0, 0, 0, 0, 0, 0, '22', 1),
('SKB', 'Theaterbesuche', 'ZK', 431, 0, 0, 0, 0, 0, 0, 0, '22', 1),
('SKD', 'Chemie & Medizin', 'ZK', 411, 0, 0, 0, 0, 0, 0, 0, '22', 1),
('SKH', 'Gesund & Entspannt', 'ZK', 413, 0, 0, 0, 0, 0, 0, 0, '11', 1),
('SKC', 'Wissensch. Arbeiten', 'ZK', 414, 0, 0, 0, 0, 0, 0, 0, '12,1,2', 1),
('SKV', 'Relativitätstheorie', 'ZK', 423, 0, 0, 0, 0, 0, 0, 0, '22', 1),
('SKL', 'Lit. u. Psychologie', 'ZK', 420, 0, 0, 0, 0, 0, 0, 0, '22', 1),
('FZ', 'DELF', 'ZK', 401, 0, 0, 0, 0, 0, 0, 0, '12,44', 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `sndbx_0_schueler`
--

CREATE TABLE IF NOT EXISTS `sndbx_0_schueler` (
  `snr` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `vorname` varchar(100) NOT NULL,
  `pw` char(40) NOT NULL,
  `prof1` varchar(5) NOT NULL,
  `prof2` varchar(5) NOT NULL,
  `klasse` varchar(3) NOT NULL,
  `kwfehler` varchar(200) NOT NULL,
  `realpw` varchar(30) NOT NULL,
  PRIMARY KEY (`snr`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `sndbx_0_schueler`
--

INSERT INTO `sndbx_0_schueler` (`snr`, `name`, `vorname`, `pw`, `prof1`, `prof2`, `klasse`, `kwfehler`, `realpw`) VALUES
(1, 'Benjamin', 'Blümchen', 'bb21158c733229347bd4e681891e213d94c685be', 'C', 'D', '10a', '', 'blabla');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `sndbx_0_sportkurs`
--

CREATE TABLE IF NOT EXISTS `sndbx_0_sportkurs` (
  `kuerzel` varchar(4) NOT NULL,
  `langname` varchar(40) NOT NULL,
  `hat2ls` int(11) NOT NULL,
  PRIMARY KEY (`kuerzel`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `sndbx_0_waehlt`
--

CREATE TABLE IF NOT EXISTS `sndbx_0_waehlt` (
  `snr` int(11) NOT NULL,
  `fachkurz` varchar(5) NOT NULL,
  `sem` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `sndbx_0_waehlt`
--

INSERT INTO `sndbx_0_waehlt` (`snr`, `fachkurz`, `sem`) VALUES
(1, 'PW', 4),
(1, 'PW', 3),
(1, 'PW', 2),
(1, 'PW', 1),
(1, 'GE', 1),
(1, 'GE', 2);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `sndbx_0_waehltpf`
--

CREATE TABLE IF NOT EXISTS `sndbx_0_waehltpf` (
  `snr` int(11) NOT NULL,
  `fachkurz` varchar(3) NOT NULL,
  `pf` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `sndbx_0_waehltpf`
--

INSERT INTO `sndbx_0_waehltpf` (`snr`, `fachkurz`, `pf`) VALUES
(1, 'no', 7),
(1, 'PRS', 6),
(1, 'PW', 5),
(1, 'PH', 4),
(1, 'CN', 3),
(1, 'EN', 2),
(1, 'DE', 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `sndbx_0_waehltsp`
--

CREATE TABLE IF NOT EXISTS `sndbx_0_waehltsp` (
  `kuerzel` varchar(4) NOT NULL,
  `sNummer` int(11) NOT NULL,
  `sem` int(11) NOT NULL,
  `lstufe` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
