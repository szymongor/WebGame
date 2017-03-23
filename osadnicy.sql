-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Czas generowania: 23 Mar 2017, 15:33
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
-- Struktura tabeli dla tabeli `army`
--

CREATE TABLE `army` (
  `id` int(11) NOT NULL,
  `Swordman` int(10) NOT NULL DEFAULT '0',
  `Bowman` int(10) NOT NULL DEFAULT '0',
  `Shieldbearer` int(10) NOT NULL DEFAULT '0',
  `Shaman` int(11) NOT NULL DEFAULT '0',
  `Wizard` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `army`
--

INSERT INTO `army` (`id`, `Swordman`, `Bowman`, `Shieldbearer`, `Shaman`, `Wizard`) VALUES
(1, 14, 5, 6, 4, 1),
(8, 90, 120, 40, 20, 50),
(9, 10, 10, 0, 0, 0),
(10, 10, 9, 10, 8, 10);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `buildings`
--

CREATE TABLE `buildings` (
  `building_id` int(11) NOT NULL,
  `type` varchar(40) COLLATE utf8_polish_ci DEFAULT NULL,
  `level` int(10) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `buildings`
--

INSERT INTO `buildings` (`building_id`, `type`, `level`) VALUES
(1, 'House', 1),
(2, 'Sawmill', 1),
(3, 'Mine', 1),
(4, 'Forge', 1),
(5, 'Farm', 1),
(6, 'Castle', 1),
(44, 'Castle', 1),
(45, 'Sawmill', 1),
(46, 'Farm', 1),
(47, 'Sawmill', 1),
(48, 'Sawmill', 1),
(49, 'Stone-Pit', 1),
(50, 'Farm', 1),
(51, 'Farm', 1),
(52, 'Farm', 1),
(53, 'Farm', 1),
(54, 'Farm', 1),
(55, 'Barrack', 1),
(56, 'Forge', 1),
(67, 'Workshop', 1),
(68, 'Barrack', 3),
(69, 'Workshop', 1),
(84, 'Farm', 1),
(86, 'Forge', 1),
(87, 'House', 1),
(88, 'Sawmill', 1),
(89, 'Castle', 1),
(90, 'Castle', 1),
(91, 'House', 1),
(92, 'House', 1),
(93, 'Castle', 1),
(94, 'House', 1),
(95, 'House', 1),
(98, 'Mine', 1),
(99, 'Mine', 1),
(100, 'Sawmill', 1),
(101, 'Farm', 1),
(102, 'Forge Construction', 1),
(103, 'Sawmill Construction', 1),
(104, 'Stone-Pit Construction', 1);

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
  `building_id` int(11) DEFAULT NULL,
  `army_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `map`
--

INSERT INTO `map` (`x_coord`, `y_coord`, `id_owner`, `biome`, `building_id`, `army_id`) VALUES
(-14, -14, NULL, 'Plains', NULL, NULL),
(-14, -13, NULL, 'Swamp', NULL, NULL),
(-14, -12, NULL, 'Plains', NULL, NULL),
(-14, -11, NULL, 'Plains', NULL, NULL),
(-14, -10, NULL, 'Plains', NULL, NULL),
(-14, -9, NULL, 'Forest', NULL, NULL),
(-14, -8, NULL, 'Plains', NULL, NULL),
(-14, -7, NULL, 'Forest', NULL, NULL),
(-14, -6, NULL, 'Forest', NULL, NULL),
(-13, -14, NULL, 'Desert', NULL, NULL),
(-13, -13, 15, 'Plains', NULL, NULL),
(-13, -12, 15, 'Desert', NULL, NULL),
(-13, -11, 15, 'Mountains', NULL, NULL),
(-13, -10, 15, 'Forest', NULL, NULL),
(-13, -9, 15, 'Forest', NULL, NULL),
(-13, -8, 15, 'Swamp', NULL, NULL),
(-13, -7, 15, 'Swamp', NULL, NULL),
(-13, -6, NULL, 'Desert', NULL, NULL),
(-12, -14, NULL, 'Desert', NULL, NULL),
(-12, -13, 15, 'Forest', NULL, NULL),
(-12, -12, 15, 'Swamp', NULL, NULL),
(-12, -11, 15, 'Mountains', NULL, NULL),
(-12, -10, 15, 'Mountains', NULL, NULL),
(-12, -9, 15, 'Forest', 100, NULL),
(-12, -8, 15, 'Swamp', NULL, NULL),
(-12, -7, 15, 'Plains', NULL, NULL),
(-12, -6, NULL, 'Forest', NULL, NULL),
(-11, -14, NULL, 'Plains', NULL, NULL),
(-11, -13, 15, 'Swamp', NULL, NULL),
(-11, -12, 15, 'Forest', NULL, NULL),
(-11, -11, 15, 'Desert', 91, NULL),
(-11, -10, 15, 'Desert', NULL, NULL),
(-11, -9, 15, 'Forest', NULL, NULL),
(-11, -8, 15, 'Mountains', NULL, NULL),
(-11, -7, 15, 'Desert', NULL, NULL),
(-11, -6, NULL, 'Swamp', NULL, NULL),
(-10, -14, NULL, 'Plains', NULL, NULL),
(-10, -13, 15, 'Forest', NULL, NULL),
(-10, -12, 15, 'Mountains', NULL, NULL),
(-10, -11, 15, 'Plains', NULL, NULL),
(-10, -10, 15, 'Forest', 90, NULL),
(-10, -9, 15, 'Plains', NULL, NULL),
(-10, -8, 15, 'Mountains', NULL, NULL),
(-10, -7, 15, 'Swamp', NULL, NULL),
(-10, -6, NULL, 'Forest', NULL, NULL),
(-9, -14, NULL, 'Forest', NULL, NULL),
(-9, -13, 15, 'Plains', NULL, NULL),
(-9, -12, 15, 'Mountains', NULL, NULL),
(-9, -11, 15, 'Mountains', NULL, NULL),
(-9, -10, 15, 'Desert', NULL, NULL),
(-9, -9, 15, 'Forest', 92, NULL),
(-9, -8, 15, 'Mountains', NULL, NULL),
(-9, -7, 15, 'Swamp', NULL, NULL),
(-9, -6, NULL, 'Swamp', NULL, NULL),
(-8, -14, NULL, 'Mountains', NULL, NULL),
(-8, -13, 15, 'Mountains', NULL, NULL),
(-8, -12, 15, 'Swamp', NULL, NULL),
(-8, -11, 15, 'Swamp', NULL, NULL),
(-8, -10, 15, 'Mountains', NULL, NULL),
(-8, -9, 15, 'Plains', NULL, NULL),
(-8, -8, 15, 'Desert', NULL, NULL),
(-8, -7, 15, 'Desert', NULL, NULL),
(-8, -6, NULL, 'Plains', NULL, NULL),
(-7, -14, NULL, 'Mountains', NULL, NULL),
(-7, -13, 15, 'Plains', NULL, NULL),
(-7, -12, 15, 'Desert', NULL, NULL),
(-7, -11, 15, 'Mountains', NULL, NULL),
(-7, -10, 15, 'Swamp', NULL, NULL),
(-7, -9, 15, 'Mountains', NULL, NULL),
(-7, -8, 15, 'Forest', NULL, NULL),
(-7, -7, 15, 'Plains', NULL, NULL),
(-7, -6, NULL, 'Forest', NULL, NULL),
(-6, -14, NULL, 'Mountains', NULL, NULL),
(-6, -13, NULL, 'Forest', NULL, NULL),
(-6, -12, NULL, 'Mountains', NULL, NULL),
(-6, -11, NULL, 'Mountains', NULL, NULL),
(-6, -10, NULL, 'Desert', NULL, NULL),
(-6, -9, NULL, 'Forest', NULL, NULL),
(-6, -8, NULL, 'Desert', NULL, NULL),
(-6, -7, NULL, 'Desert', NULL, NULL),
(-6, -6, NULL, 'Plains', NULL, NULL),
(-3, 1, NULL, 'Desert', NULL, NULL),
(-1, 0, NULL, 'Desert', NULL, NULL),
(-1, 1, NULL, 'Plains', NULL, NULL),
(-1, 3, NULL, 'Desert', NULL, NULL),
(0, 0, NULL, 'Desert', NULL, NULL),
(0, 1, NULL, 'Desert', NULL, NULL),
(0, 2, NULL, 'Forest', NULL, NULL),
(0, 3, NULL, 'Swamp', NULL, NULL),
(0, 4, NULL, 'Swamp', NULL, NULL),
(0, 5, NULL, 'Plains', NULL, NULL),
(0, 6, NULL, 'Swamp', NULL, NULL),
(0, 7, NULL, 'Forest', NULL, NULL),
(1, 0, NULL, 'Forest', NULL, NULL),
(1, 1, 21, 'Forest', 2, NULL),
(1, 2, NULL, 'Forest', NULL, NULL),
(1, 3, NULL, 'Forest', NULL, NULL),
(1, 4, 12, 'Forest', 47, 1),
(1, 5, 12, 'Desert', 1, NULL),
(1, 6, NULL, 'Swamp', NULL, NULL),
(1, 7, NULL, 'Plains', NULL, NULL),
(1, 8, NULL, 'Forest', NULL, NULL),
(2, 0, NULL, 'Swamp', NULL, NULL),
(2, 1, NULL, 'Forest', NULL, NULL),
(2, 2, 12, 'Swamp', 55, NULL),
(2, 3, 12, 'Mountains', 3, NULL),
(2, 4, 12, 'Swamp', 44, NULL),
(2, 5, 12, 'Plains', 88, NULL),
(2, 6, 12, 'Plains', NULL, NULL),
(2, 7, NULL, 'Plains', NULL, NULL),
(2, 8, NULL, 'Plains', NULL, NULL),
(2, 9, NULL, 'Forest', NULL, NULL),
(3, 0, NULL, 'Swamp', NULL, NULL),
(3, 1, NULL, 'Forest', NULL, NULL),
(3, 2, 12, 'Forest', 56, NULL),
(3, 3, NULL, 'Swamp', NULL, NULL),
(3, 4, 12, 'Swamp', 49, NULL),
(3, 5, 12, 'Plains', 46, NULL),
(3, 6, 12, 'Swamp', 89, 10),
(3, 7, NULL, 'Plains', NULL, NULL),
(3, 8, NULL, 'Swamp', NULL, NULL),
(3, 9, NULL, 'Plains', NULL, NULL),
(4, 0, NULL, 'Forest', NULL, NULL),
(4, 1, NULL, 'Swamp', NULL, NULL),
(4, 2, 12, 'Desert', 67, NULL),
(4, 3, NULL, 'Plains', NULL, NULL),
(4, 4, 12, 'Plains', 50, NULL),
(4, 5, 12, 'Forest', 48, NULL),
(4, 6, 12, 'Desert', 87, NULL),
(4, 7, NULL, 'Swamp', NULL, NULL),
(4, 8, NULL, 'Forest', NULL, NULL),
(4, 9, NULL, 'Swamp', NULL, NULL),
(5, 0, NULL, 'Forest', NULL, NULL),
(5, 1, NULL, 'Swamp', NULL, NULL),
(5, 2, 12, 'Forest', 68, NULL),
(5, 3, 12, 'Swamp', NULL, NULL),
(5, 4, 12, 'Swamp', 54, NULL),
(5, 5, 12, 'Plains', 51, NULL),
(5, 6, 12, 'Swamp', 86, NULL),
(5, 7, NULL, 'Desert', NULL, NULL),
(5, 8, NULL, 'Plains', NULL, NULL),
(5, 9, NULL, 'Swamp', NULL, NULL),
(6, 0, NULL, 'Desert', NULL, NULL),
(6, 1, NULL, 'Plains', NULL, 9),
(6, 2, 12, 'Desert', 52, NULL),
(6, 3, 12, 'Desert', 53, NULL),
(6, 4, 12, 'Desert', 69, NULL),
(6, 5, 12, 'Plains', 84, NULL),
(6, 6, NULL, 'Desert', NULL, NULL),
(6, 7, NULL, 'Forest', NULL, NULL),
(6, 8, NULL, 'Desert', NULL, NULL),
(6, 9, NULL, 'Plains', NULL, NULL),
(6, 10, NULL, 'Swamp', NULL, NULL),
(6, 11, NULL, 'Plains', NULL, NULL),
(6, 12, NULL, 'Forest', NULL, NULL),
(6, 13, NULL, 'Forest', NULL, NULL),
(6, 14, NULL, 'Mountains', NULL, NULL),
(7, 0, NULL, 'Desert', NULL, NULL),
(7, 1, NULL, 'Forest', NULL, NULL),
(7, 2, NULL, 'Forest', NULL, NULL),
(7, 3, NULL, 'Swamp', NULL, NULL),
(7, 4, NULL, 'Forest', NULL, NULL),
(7, 5, NULL, 'Forest', NULL, NULL),
(7, 6, NULL, 'Forest', NULL, NULL),
(7, 7, 16, 'Forest', NULL, NULL),
(7, 8, 16, 'Swamp', NULL, NULL),
(7, 9, 16, 'Forest', NULL, NULL),
(7, 10, 16, 'Mountains', NULL, NULL),
(7, 11, 16, 'Forest', NULL, NULL),
(7, 12, 16, 'Swamp', NULL, NULL),
(7, 13, 16, 'Forest', NULL, NULL),
(7, 14, NULL, 'Plains', NULL, NULL),
(8, 1, NULL, 'Forest', NULL, NULL),
(8, 2, NULL, 'Forest', NULL, NULL),
(8, 3, NULL, 'Plains', NULL, NULL),
(8, 4, NULL, 'Desert', NULL, NULL),
(8, 5, NULL, 'Forest', NULL, NULL),
(8, 6, NULL, 'Swamp', NULL, NULL),
(8, 7, 16, 'Plains', NULL, NULL),
(8, 8, 16, 'Swamp', NULL, NULL),
(8, 9, 16, 'Forest', NULL, NULL),
(8, 10, 16, 'Desert', NULL, NULL),
(8, 11, 16, 'Mountains', NULL, NULL),
(8, 12, 16, 'Plains', NULL, NULL),
(8, 13, 16, 'Swamp', NULL, NULL),
(8, 14, NULL, 'Forest', NULL, NULL),
(9, 2, NULL, 'Forest', NULL, NULL),
(9, 3, NULL, 'Forest', NULL, NULL),
(9, 4, NULL, 'Swamp', NULL, NULL),
(9, 5, NULL, 'Swamp', NULL, NULL),
(9, 6, NULL, 'Forest', NULL, NULL),
(9, 7, 16, 'Desert', NULL, NULL),
(9, 8, 16, 'Desert', NULL, NULL),
(9, 9, 16, 'Desert', 94, NULL),
(9, 10, 16, 'Swamp', 102, NULL),
(9, 11, 16, 'Mountains', 104, NULL),
(9, 12, 16, 'Mountains', 98, NULL),
(9, 13, 16, 'Mountains', NULL, NULL),
(9, 14, NULL, 'Desert', NULL, NULL),
(10, 6, NULL, 'Plains', NULL, NULL),
(10, 7, 16, 'Desert', NULL, NULL),
(10, 8, 16, 'Swamp', NULL, NULL),
(10, 9, 16, 'Desert', NULL, NULL),
(10, 10, 16, 'Forest', 93, NULL),
(10, 11, 16, 'Plains', NULL, NULL),
(10, 12, 16, 'Mountains', 99, NULL),
(10, 13, 16, 'Forest', NULL, NULL),
(10, 14, NULL, 'Mountains', NULL, NULL),
(11, 6, NULL, 'Mountains', NULL, NULL),
(11, 7, 16, 'Desert', NULL, NULL),
(11, 8, 16, 'Forest', NULL, NULL),
(11, 9, 16, 'Desert', NULL, NULL),
(11, 10, 16, 'Plains', NULL, NULL),
(11, 11, 16, 'Desert', 95, NULL),
(11, 12, 16, 'Mountains', NULL, NULL),
(11, 13, 16, 'Desert', NULL, NULL),
(11, 14, NULL, 'Desert', NULL, NULL),
(12, 6, NULL, 'Swamp', NULL, NULL),
(12, 7, 16, 'Plains', NULL, NULL),
(12, 8, 16, 'Forest', 103, NULL),
(12, 9, 16, 'Swamp', NULL, NULL),
(12, 10, 16, 'Plains', NULL, NULL),
(12, 11, 16, 'Swamp', 101, NULL),
(12, 12, 16, 'Swamp', NULL, NULL),
(12, 13, 16, 'Swamp', NULL, NULL),
(12, 14, NULL, 'Swamp', NULL, NULL),
(13, 6, NULL, 'Desert', NULL, NULL),
(13, 7, 16, 'Forest', NULL, NULL),
(13, 8, 16, 'Forest', NULL, NULL),
(13, 9, 16, 'Desert', NULL, NULL),
(13, 10, 16, 'Desert', NULL, NULL),
(13, 11, 16, 'Plains', NULL, NULL),
(13, 12, 16, 'Swamp', NULL, NULL),
(13, 13, 16, 'Mountains', NULL, NULL),
(13, 14, NULL, 'Mountains', NULL, NULL),
(14, 6, NULL, 'Plains', NULL, NULL),
(14, 7, NULL, 'Forest', NULL, NULL),
(14, 8, NULL, 'Plains', NULL, NULL),
(14, 9, NULL, 'Mountains', NULL, NULL),
(14, 10, NULL, 'Mountains', NULL, NULL),
(14, 11, NULL, 'Desert', NULL, NULL),
(14, 12, NULL, 'Swamp', NULL, NULL),
(14, 13, NULL, 'Swamp', NULL, NULL),
(14, 14, NULL, 'Forest', NULL, NULL);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `tasks`
--

CREATE TABLE `tasks` (
  `task_id` int(11) NOT NULL,
  `owner_id` int(11) NOT NULL,
  `task_building` int(11) NOT NULL,
  `task_effect` blob NOT NULL,
  `timeEnd` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `tasks`
--

INSERT INTO `tasks` (`task_id`, `owner_id`, `task_building`, `task_effect`, `timeEnd`) VALUES
(4, 16, 102, 0x7b226275696c64223a7b226275696c64696e674964223a22313032222c226275696c64696e6744617461223a7b2274797065223a22466f726765227d7d7d, 1490279753),
(5, 16, 103, 0x7b226275696c64223a7b226275696c64696e674964223a22313033222c226275696c64696e6744617461223a7b2274797065223a225361776d696c6c227d7d7d, 1490279570),
(6, 16, 104, 0x7b226275696c64223a7b226275696c64696e674964223a22313034222c226275696c64696e6744617461223a7b2274797065223a2253746f6e652d506974227d7d7d, 1490279824);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `technologies`
--

CREATE TABLE `technologies` (
  `technology_id` int(11) NOT NULL,
  `owner_id` int(11) NOT NULL,
  `technology` varchar(22) COLLATE utf8_polish_ci NOT NULL,
  `level` int(5) NOT NULL DEFAULT '1',
  `currently_upgraded` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `technologies`
--

INSERT INTO `technologies` (`technology_id`, `owner_id`, `technology`, `level`, `currently_upgraded`) VALUES
(1, 12, 'Seasoning', 1, 0),
(2, 12, 'Hardening', 1, 0),
(7, 67, 'Engineering', 1, 0);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `user` text COLLATE utf8_polish_ci NOT NULL,
  `pass` text COLLATE utf8_polish_ci NOT NULL,
  `email` text COLLATE utf8_polish_ci NOT NULL,
  `xCoordHQ` smallint(6) NOT NULL,
  `yCoordHQ` smallint(6) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `users`
--

INSERT INTO `users` (`id`, `user`, `pass`, `email`, `xCoordHQ`, `yCoordHQ`) VALUES
(12, 'Szymon', '$2y$10$vTv8VjWSOaU/Z96xp.un2.YCauEQUTDbqrCpiYx.3rnCfIUUT2.pC', 'gm@gmail.com', 2, 4),
(13, 'Blyp', '$2y$10$VQ8eZTjq0OGDHfK1jqz7JuzRRHnA/yfQLh2LHhBNmOoEBStNnUyOu', 'blyp@gmail.com', 0, 0),
(14, 'Toran', '$2y$10$AjfqvJN4rf/X1gaBHnevvufQYa6s5wDjTPyXPROSA6N3HAEjnP8KK', 'gornioczek.szymon@gmail.com', 0, 0),
(15, 'Olad', '$2y$10$HArAYiYe/ROKAxRmvbR8guYkQn0OXjrzL.mlaIBhqCAOYYJvhGida', 'lol@gmail.com', -10, -10),
(16, 'Dybuk', '$2y$10$f4IjLNWOtiCR4B7YdFcz3uxwi7G4Cd8v6GFAQPFssQNIZYi5k9gYe', 'lol123@gmail.com', 10, 10);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `user_army`
--

CREATE TABLE `user_army` (
  `user_id` int(11) NOT NULL,
  `army_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `user_army`
--

INSERT INTO `user_army` (`user_id`, `army_id`) VALUES
(12, 8);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `user_items`
--

CREATE TABLE `user_items` (
  `user_id` int(11) NOT NULL,
  `Tools` int(10) NOT NULL DEFAULT '0',
  `Swords` int(10) NOT NULL DEFAULT '0',
  `Bows` int(10) NOT NULL DEFAULT '0',
  `Armors` int(10) NOT NULL DEFAULT '0',
  `Runes` int(10) NOT NULL DEFAULT '0',
  `Wands` int(10) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `user_items`
--

INSERT INTO `user_items` (`user_id`, `Tools`, `Swords`, `Bows`, `Armors`, `Runes`, `Wands`) VALUES
(12, 374, 15, 0, 0, 0, 0);

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
(12, 352, 328, 220, 704),
(13, 0, 0, 0, 0),
(15, 76, 106, 43, 189),
(16, 66, 166, 800, 800);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `user_resources_capacity`
--

CREATE TABLE `user_resources_capacity` (
  `User_id` int(11) NOT NULL,
  `Wood` int(10) NOT NULL,
  `Stone` int(10) NOT NULL,
  `Iron` int(10) NOT NULL,
  `Food` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `user_resources_capacity`
--

INSERT INTO `user_resources_capacity` (`User_id`, `Wood`, `Stone`, `Iron`, `Food`) VALUES
(12, 1200, 600, 500, 2600),
(15, 600, 300, 200, 500),
(16, 300, 300, 800, 800);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `user_resources_income`
--

CREATE TABLE `user_resources_income` (
  `user_id` int(11) NOT NULL,
  `Wood` int(10) NOT NULL DEFAULT '0',
  `Stone` int(10) NOT NULL DEFAULT '0',
  `Iron` int(10) NOT NULL DEFAULT '0',
  `Food` int(10) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `user_resources_income`
--

INSERT INTO `user_resources_income` (`user_id`, `Wood`, `Stone`, `Iron`, `Food`) VALUES
(12, 13, 7, 5, 51),
(15, 14, 2, 1, 3),
(16, 2, 2, 31, 12);

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
(12, 1490213878),
(13, 1483516160),
(14, 1484847486),
(15, 1490214057),
(16, 1490279380);

--
-- Indeksy dla zrzutów tabel
--

--
-- Indexes for table `army`
--
ALTER TABLE `army`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`task_id`);

--
-- Indexes for table `technologies`
--
ALTER TABLE `technologies`
  ADD PRIMARY KEY (`technology_id`),
  ADD UNIQUE KEY `technology_id` (`technology_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `user_army`
--
ALTER TABLE `user_army`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `user_items`
--
ALTER TABLE `user_items`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `user_resources`
--
ALTER TABLE `user_resources`
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `user_resources_capacity`
--
ALTER TABLE `user_resources_capacity`
  ADD PRIMARY KEY (`User_id`);

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
-- AUTO_INCREMENT dla tabeli `army`
--
ALTER TABLE `army`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT dla tabeli `buildings`
--
ALTER TABLE `buildings`
  MODIFY `building_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=105;
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
-- AUTO_INCREMENT dla tabeli `tasks`
--
ALTER TABLE `tasks`
  MODIFY `task_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT dla tabeli `technologies`
--
ALTER TABLE `technologies`
  MODIFY `technology_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT dla tabeli `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
