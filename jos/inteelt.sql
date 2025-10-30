-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Gegenereerd op: 30 okt 2025 om 17:40
-- Serverversie: 10.4.32-MariaDB
-- PHP-versie: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `inteelt`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `burgers`
--

CREATE TABLE `burgers` (
  `id` int(11) NOT NULL,
  `naam` varchar(100) NOT NULL,
  `vader` int(11) NOT NULL,
  `moeder` int(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `burgers`
--

INSERT INTO `burgers` (`id`, `naam`, `vader`, `moeder`) VALUES
(1, 'Snerben van der Nerf', 2, 3),
(2, 'Gerrit van der Nerf', 4, 5),
(3, 'Snerda van Bladeren', 8, 9),
(4, 'Jonas van der Nerf', 6, 7),
(5, 'Snerdonia van den Bos', 10, 11),
(6, 'Kobus van der Nerf', 12, 13),
(7, 'Snerbelien Rozegeur', 14, 15),
(8, 'Johan van Bladeren', 16, 17),
(9, 'Bep van\'t Woud', 18, 19),
(10, 'Kobus van den Bos', 20, 21),
(11, 'Gerdonia  van het Woud', 22, 23);

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `burgers`
--
ALTER TABLE `burgers`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `burgers`
--
ALTER TABLE `burgers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
