-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Czas generowania: 01 Lis 2016, 13:10
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
-- Struktura tabeli dla tabeli `map`
--

CREATE TABLE `map` (
  `x_coord` smallint(6) NOT NULL,
  `y_coord` smallint(6) NOT NULL,
  `id_owner` int(11) DEFAULT NULL,
  `biome` enum('Fosret','Plains','Desert','Swamp') COLLATE utf8_polish_ci NOT NULL,
  `building` enum('House','Sawmill','Mine','') COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `user` text COLLATE utf8_polish_ci NOT NULL,
  `pass` text COLLATE utf8_polish_ci NOT NULL,
  `email` text COLLATE utf8_polish_ci NOT NULL,
  `dnipremium` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `users`
--

INSERT INTO `users` (`id`, `user`, `pass`, `email`, `dnipremium`) VALUES
(1, 'adam', '$2y$10$PicCZ7IRCczG6zTxld.c.udgkOKGWo4uboOwpCfivligKsbrPZZ4m', 'adam@gmail.com', 0),
(2, 'marek', 'asdfg', 'marek@gmail.com', 15),
(3, 'anna', 'zxcvb', 'anna@gmail.com', 25),
(4, 'andrzej', 'asdfg', 'andrzej@gmail.com', 0),
(5, 'justyna', 'yuiop', 'justyna@gmail.com', 0),
(6, 'kasia', 'hjkkl', 'kasia@gmail.com', 12),
(7, 'beata', 'fgthj', 'beata@gmail.com', 77),
(8, 'jakub', 'ertyu', 'jakub@gmail.com', 0),
(9, 'janusz', 'cvbnm', 'janusz@gmail.com', 0),
(10, 'roman', 'dfghj', 'roman@gmail.com', 23),
(12, 'Szymon', '$2y$10$vTv8VjWSOaU/Z96xp.un2.YCauEQUTDbqrCpiYx.3rnCfIUUT2.pC', 'gm@gmail.com', 14);

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
(12, 25361, 22161, 18961, 44321);

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
(12, 3, 2, 1, 4);

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
(12, 1478001538);

--
-- Indeksy dla zrzutów tabel
--

--
-- Indexes for table `map`
--
ALTER TABLE `map`
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
-- AUTO_INCREMENT dla tabeli `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
