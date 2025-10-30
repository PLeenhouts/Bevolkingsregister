-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Gegenereerd op: 30 okt 2025 om 17:00
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
-- Database: `bevolkingsregister_db`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `burger`
--

CREATE TABLE `burger` (
  `id_code` int(11) NOT NULL,
  `voornaam` varchar(50) DEFAULT NULL,
  `achternaam` varchar(50) DEFAULT NULL,
  `geboortedatum` date DEFAULT NULL,
  `vader_id` int(11) DEFAULT NULL,
  `moeder_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `burger`
--

INSERT INTO `burger` (`id_code`, `voornaam`, `achternaam`, `geboortedatum`, `vader_id`, `moeder_id`) VALUES
(1, '', '', '0000-00-00', NULL, NULL),
(2, 'janneke', 'piet ', '0000-00-00', NULL, NULL),
(3, 'miranda', 'vos', '2025-09-30', NULL, NULL),
(4, 'Jan', 'Opa', '1940-01-01', NULL, NULL),
(5, 'Marie', 'Oma', '1942-02-02', NULL, NULL),
(6, 'Piet', 'Vader', '1965-03-03', 1, 2),
(7, 'Els', 'Moeder', '1967-04-04', 1, 2),
(8, 'Tom', 'Zoon', '1990-05-05', 3, 4),
(9, 'Lisa', 'Dochter', '1992-06-06', 3, 4),
(10, 'Anna', 'Vrij', '1993-07-07', NULL, NULL);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `huwelijk`
--

CREATE TABLE `huwelijk` (
  `id` int(11) NOT NULL,
  `partner1_id` int(11) DEFAULT NULL,
  `partner2_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `huwelijk`
--

INSERT INTO `huwelijk` (`id`, `partner1_id`, `partner2_id`) VALUES
(1, 8, 10),
(2, 2, 3),
(3, 3, 4),
(4, 3, 4),
(5, 3, 4),
(6, 8, 10);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `kind`
--

CREATE TABLE `kind` (
  `id` int(11) NOT NULL,
  `voornaam` varchar(50) DEFAULT NULL,
  `geboortedatum` date DEFAULT NULL,
  `vader_id` int(11) DEFAULT NULL,
  `moeder_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `kind`
--

INSERT INTO `kind` (`id`, `voornaam`, `geboortedatum`, `vader_id`, `moeder_id`) VALUES
(1, 'jan ', '2025-10-09', 1, 2);

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `burger`
--
ALTER TABLE `burger`
  ADD PRIMARY KEY (`id_code`);

--
-- Indexen voor tabel `huwelijk`
--
ALTER TABLE `huwelijk`
  ADD PRIMARY KEY (`id`),
  ADD KEY `partner1_id` (`partner1_id`),
  ADD KEY `partner2_id` (`partner2_id`);

--
-- Indexen voor tabel `kind`
--
ALTER TABLE `kind`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vader_id` (`vader_id`),
  ADD KEY `moeder_id` (`moeder_id`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `burger`
--
ALTER TABLE `burger`
  MODIFY `id_code` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT voor een tabel `huwelijk`
--
ALTER TABLE `huwelijk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT voor een tabel `kind`
--
ALTER TABLE `kind`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Beperkingen voor geëxporteerde tabellen
--

--
-- Beperkingen voor tabel `huwelijk`
--
ALTER TABLE `huwelijk`
  ADD CONSTRAINT `huwelijk_ibfk_1` FOREIGN KEY (`partner1_id`) REFERENCES `burger` (`id_code`),
  ADD CONSTRAINT `huwelijk_ibfk_2` FOREIGN KEY (`partner2_id`) REFERENCES `burger` (`id_code`);

--
-- Beperkingen voor tabel `kind`
--
ALTER TABLE `kind`
  ADD CONSTRAINT `kind_ibfk_1` FOREIGN KEY (`vader_id`) REFERENCES `burger` (`id_code`),
  ADD CONSTRAINT `kind_ibfk_2` FOREIGN KEY (`moeder_id`) REFERENCES `burger` (`id_code`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
