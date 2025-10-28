-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Gegenereerd op: 27 okt 2025 om 18:55
-- Serverversie: 10.4.32-MariaDB
-- PHP-versie: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bevolkingsregister_db`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `burgers`
--

CREATE TABLE `burgers` (
  `Burger_id` int(100) NOT NULL,
  `Voornaam` varchar(100) NOT NULL,
  `Achternaam` varchar(100) NOT NULL,
  `Geboortedatum` date NOT NULL,
  `DNA_code` varchar(10) NOT NULL,
  `Familie_code` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `huwelijken`
--

CREATE TABLE `huwelijken` (
  `Huwelijk_id` int(11) NOT NULL,
  `Partner1_id` int(11) NOT NULL,
  `Partner2_id` int(11) NOT NULL,
  `Datum` date NOT NULL,
  `Familie_code` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `huwelijken`
--

INSERT INTO `huwelijken` (`Huwelijk_id`, `Partner1_id`, `Partner2_id`, `Datum`, `Familie_code`) VALUES

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `kinderen`
--

CREATE TABLE `kinderen` (
  `Kind_id` int(11) NOT NULL,
  `Voornaam` varchar(100) NOT NULL,
  `Achternaam` varchar(100) NOT NULL,
  `Geboortedatum` date NOT NULL,
  `Ouder1_id` int(11) NOT NULL,
  `Ouder2_id` int(11) NOT NULL,
  `DNA_code` varchar(10) NOT NULL,
  `Familie_code` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `burgers`
--
ALTER TABLE `burgers`
  ADD PRIMARY KEY (`Burger_id`);

--
-- Indexen voor tabel `huwelijken`
--
ALTER TABLE `huwelijken`
  ADD PRIMARY KEY (`Huwelijk_id`);

--
-- Indexen voor tabel `kinderen`
--
ALTER TABLE `kinderen`
  ADD PRIMARY KEY (`Kind_id`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `burgers`
--
ALTER TABLE `burgers`
  MODIFY `Burger_id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT voor een tabel `huwelijken`
--
ALTER TABLE `huwelijken`
  MODIFY `Huwelijk_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT voor een tabel `kinderen`
--
ALTER TABLE `kinderen`
  MODIFY `Kind_id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
