-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Czas generowania: 24 Gru 2016, 13:30
-- Wersja serwera: 10.1.16-MariaDB
-- Wersja PHP: 7.0.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `osadnicy`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `buildings`
--

CREATE TABLE `buildings` (
  `building_id` int(11) NOT NULL,
  `type` varchar(20) COLLATE utf8_polish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `buildings`
--

INSERT INTO `buildings` (`building_id`, `type`) VALUES
(1, 'House'),
(2, 'Sawmill'),
(3, 'Mine'),
(4, 'Forge'),
(5, 'Farm'),
(6, 'Castle'),
(44, 'Castle'),
(45, 'Sawmill'),
(46, 'Farm'),
(47, 'Sawmill'),
(48, 'Sawmill'),
(49, 'Stone-Pit'),
(50, 'Farm'),
(51, 'Farm'),
(52, 'Farm'),
(53, 'Farm');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `gs_buildingstypes`
--

CREATE TABLE `gs_buildingstypes` (
  `id` int(11) NOT NULL,
  `Type` varchar(20) COLLATE utf8_polish_ci NOT NULL,
  `Cost` int(11) NOT NULL,
  `technology_requirements_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `gs_buildingstypes`
--

INSERT INTO `gs_buildingstypes` (`id`, `Type`, `Cost`, `technology_requirements_id`) VALUES
(1, 'House', 1, NULL),
(2, 'Sawmill', 2, NULL),
(3, 'Mine', 3, NULL),
(4, 'Forge', 4, NULL),
(5, 'Farm', 5, NULL),
(6, 'Castle', 6, NULL);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `gs_costs`
--

CREATE TABLE `gs_costs` (
  `id` int(11) NOT NULL,
  `Wood` int(10) NOT NULL DEFAULT '0',
  `Food` int(10) NOT NULL DEFAULT '0',
  `Iron` int(10) NOT NULL DEFAULT '0',
  `Stone` int(10) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `gs_costs`
--

INSERT INTO `gs_costs` (`id`, `Wood`, `Food`, `Iron`, `Stone`) VALUES
(1, 50, 0, 20, 40),
(2, 40, 0, 30, 20),
(3, 40, 0, 30, 20),
(4, 40, 0, 30, 20),
(5, 40, 0, 30, 20),
(6, 400, 500, 100, 700);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `map`
--

CREATE TABLE `map` (
  `x_coord` smallint(6) NOT NULL,
  `y_coord` smallint(6) NOT NULL,
  `id_owner` int(11) DEFAULT NULL,
  `biome` varchar(20) COLLATE utf8_polish_ci NOT NULL,
  `building_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `map`
--

INSERT INTO `map` (`x_coord`, `y_coord`, `id_owner`, `biome`, `building_id`) VALUES
(-3, 1, NULL, 'Desert', NULL),
(-1, 0, NULL, 'Desert', NULL),
(-1, 1, NULL, 'Plains', NULL),
(-1, 3, NULL, 'Desert', NULL),
(0, 0, NULL, 'Desert', NULL),
(0, 1, NULL, 'Desert', NULL),
(0, 2, NULL, 'Forest', NULL),
(0, 3, NULL, 'Swamp', NULL),
(0, 4, NULL, 'Swamp', NULL),
(0, 5, NULL, 'Plains', NULL),
(0, 6, NULL, 'Swamp', NULL),
(0, 7, NULL, 'Forest', NULL),
(1, 0, NULL, 'Forest', NULL),
(1, 1, 21, 'Forest', 2),
(1, 2, NULL, 'Forest', NULL),
(1, 3, NULL, 'Forest', NULL),
(1, 4, 12, 'Forest', 47),
(1, 5, 12, 'Desert', 1),
(1, 6, NULL, 'Swamp', NULL),
(1, 7, NULL, 'Plains', NULL),
(1, 8, NULL, 'Forest', NULL),
(2, 0, NULL, 'Swamp', NULL),
(2, 1, NULL, 'Forest', NULL),
(2, 2, 12, 'Swamp', NULL),
(2, 3, 12, 'Mountains', 3),
(2, 4, 12, 'Swamp', 44),
(2, 5, 12, 'Plains', NULL),
(2, 6, NULL, 'Plains', NULL),
(2, 7, NULL, 'Plains', NULL),
(2, 8, NULL, 'Plains', NULL),
(2, 9, NULL, 'Forest', NULL),
(3, 0, NULL, 'Swamp', NULL),
(3, 1, NULL, 'Forest', NULL),
(3, 2, 12, 'Forest', NULL),
(3, 3, NULL, 'Swamp', NULL),
(3, 4, 12, 'Swamp', 49),
(3, 5, 12, 'Plains', 46),
(3, 6, NULL, 'Swamp', NULL),
(3, 7, NULL, 'Plains', NULL),
(3, 8, NULL, 'Swamp', NULL),
(3, 9, NULL, 'Plains', NULL),
(4, 0, NULL, 'Forest', NULL),
(4, 1, NULL, 'Swamp', NULL),
(4, 2, 12, 'Desert', NULL),
(4, 3, NULL, 'Plains', NULL),
(4, 4, 12, 'Plains', 50),
(4, 5, 12, 'Forest', 48),
(4, 6, 12, 'Desert', NULL),
(4, 7, NULL, 'Swamp', NULL),
(4, 8, NULL, 'Forest', NULL),
(4, 9, NULL, 'Swamp', NULL),
(5, 0, NULL, 'Forest', NULL),
(5, 1, NULL, 'Swamp', NULL),
(5, 2, 12, 'Forest', NULL),
(5, 3, 12, 'Swamp', NULL),
(5, 4, 12, 'Swamp', NULL),
(5, 5, 12, 'Plains', 51),
(5, 6, 12, 'Swamp', NULL),
(5, 7, NULL, 'Desert', NULL),
(5, 8, NULL, 'Plains', NULL),
(5, 9, NULL, 'Swamp', NULL),
(6, 0, NULL, 'Desert', NULL),
(6, 1, NULL, 'Plains', NULL),
(6, 2, 12, 'Desert', 52),
(6, 3, 12, 'Desert', 53),
(6, 4, 12, 'Desert', NULL),
(6, 5, 12, 'Plains', NULL),
(6, 6, NULL, 'Desert', NULL),
(6, 7, NULL, 'Forest', NULL),
(6, 8, NULL, 'Desert', NULL),
(6, 9, NULL, 'Plains', NULL),
(7, 0, NULL, 'Desert', NULL),
(7, 1, NULL, 'Forest', NULL),
(7, 2, NULL, 'Forest', NULL),
(7, 3, NULL, 'Swamp', NULL),
(7, 4, NULL, 'Forest', NULL),
(7, 5, NULL, 'Forest', NULL),
(7, 6, NULL, 'Forest', NULL),
(7, 7, NULL, 'Forest', NULL),
(7, 8, NULL, 'Swamp', NULL),
(7, 9, NULL, 'Forest', NULL),
(8, 1, NULL, 'Forest', NULL),
(8, 2, NULL, 'Forest', NULL),
(8, 3, NULL, 'Plains', NULL),
(8, 4, NULL, 'Desert', NULL),
(8, 5, NULL, 'Forest', NULL),
(8, 6, NULL, 'Swamp', NULL),
(8, 7, NULL, 'Plains', NULL),
(8, 8, NULL, 'Swamp', NULL),
(8, 9, NULL, 'Forest', NULL),
(9, 2, NULL, 'Forest', NULL),
(9, 3, NULL, 'Forest', NULL),
(9, 4, NULL, 'Swamp', NULL),
(9, 5, NULL, 'Swamp', NULL),
(9, 6, NULL, 'Forest', NULL),
(9, 7, NULL, 'Desert', NULL),
(9, 8, NULL, 'Desert', NULL),
(9, 9, NULL, 'Desert', NULL);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `user` text COLLATE utf8_polish_ci NOT NULL,
  `pass` text COLLATE utf8_polish_ci NOT NULL,
  `email` text COLLATE utf8_polish_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `users`
--

INSERT INTO `users` (`id`, `user`, `pass`, `email`) VALUES
(12, 'Szymon', '$2y$10$vTv8VjWSOaU/Z96xp.un2.YCauEQUTDbqrCpiYx.3rnCfIUUT2.pC', 'gm@gmail.com'),
(13, 'Blyp', '$2y$10$VQ8eZTjq0OGDHfK1jqz7JuzRRHnA/yfQLh2LHhBNmOoEBStNnUyOu', 'blyp@gmail.com'),
(14, 'Toran', '$2y$10$AjfqvJN4rf/X1gaBHnevvufQYa6s5wDjTPyXPROSA6N3HAEjnP8KK', 'gornioczek.szymon@gmail.com');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `user_resources`
--

CREATE TABLE `user_resources` (
  `user_id` int(11) NOT NULL,
  `Wood` int(10) NOT NULL DEFAULT '0',
  `Stone` int(10) NOT NULL DEFAULT '0',
  `Iron` int(10) NOT NULL DEFAULT '0',
  `Food` int(10) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `user_resources`
--

INSERT INTO `user_resources` (`user_id`, `Wood`, `Stone`, `Iron`, `Food`) VALUES
(12, 549593, 312810, 222282, 1096459);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `user_resources_income`
--

CREATE TABLE `user_resources_income` (
  `user_id` int(11) NOT NULL,
  `Wood_income` int(10) NOT NULL DEFAULT '0',
  `Stone_income` int(10) NOT NULL DEFAULT '0',
  `Iron_income` int(10) NOT NULL DEFAULT '0',
  `Food_income` int(10) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `user_resources_income`
--

INSERT INTO `user_resources_income` (`user_id`, `Wood_income`, `Stone_income`, `Iron_income`, `Food_income`) VALUES
(12, 8, 5, 4, 30);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `user_resources_update`
--

CREATE TABLE `user_resources_update` (
  `user_id` int(11) NOT NULL,
  `last_update` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `user_resources_update`
--

INSERT INTO `user_resources_update` (`user_id`, `last_update`) VALUES
(12, 1482582478);

--
-- Indeksy dla zrzutów tabel
--

--
-- Indexes for table `buildings`
--
ALTER TABLE `buildings`
  ADD PRIMARY KEY (`building_id`);

--
-- Indexes for table `gs_buildingstypes`
--
ALTER TABLE `gs_buildingstypes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gs_costs`
--
ALTER TABLE `gs_costs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `map`
--
ALTER TABLE `map`
  ADD UNIQUE KEY `x_coord` (`x_coord`,`y_coord`),
  ADD KEY `coord` (`x_coord`,`y_coord`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `user_resources`
--
ALTER TABLE `user_resources`
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `user_resources_income`
--
ALTER TABLE `user_resources_income`
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `user_resources_update`
--
ALTER TABLE `user_resources_update`
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT dla tabeli `buildings`
--
ALTER TABLE `buildings`
  MODIFY `building_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;
--
-- AUTO_INCREMENT dla tabeli `gs_buildingstypes`
--
ALTER TABLE `gs_buildingstypes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT dla tabeli `gs_costs`
--
ALTER TABLE `gs_costs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT dla tabeli `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
