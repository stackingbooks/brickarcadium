-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 06, 2024 at 07:04 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `brickhill`
--

-- --------------------------------------------------------

--
-- Table structure for table `avatars`
--

CREATE TABLE `avatars` (
  `ID` int(11) NOT NULL,
  `USER_ID` int(11) NOT NULL,
  `HAT` int(11) NOT NULL,
  `HAT_2` int(11) NOT NULL,
  `FACE` int(11) NOT NULL,
  `HEADC` varchar(7) NOT NULL,
  `TORSOC` varchar(7) NOT NULL,
  `LARMC` varchar(7) NOT NULL,
  `RARMC` varchar(7) NOT NULL,
  `LLEGC` varchar(7) NOT NULL,
  `RLEGC` varchar(7) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bans`
--

CREATE TABLE `bans` (
  `ID` int(11) NOT NULL,
  `USER_ID` int(11) NOT NULL,
  `BANNED_AT` bigint(20) NOT NULL,
  `DURATION` bigint(20) NOT NULL,
  `REASON` varchar(200) NOT NULL,
  `ACTIVE` enum('Y','N') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `beta_keys`
--

CREATE TABLE `beta_keys` (
  `ID` int(11) NOT NULL,
  `BETA_KEY` varchar(255) NOT NULL,
  `USED` enum('Y','N') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `beta_keys`
--

INSERT INTO `beta_keys` (`ID`, `BETA_KEY`, `USED`) VALUES
(1, 'BETAKEY', 'N');

-- --------------------------------------------------------

--
-- Table structure for table `forum_categories`
--

CREATE TABLE `forum_categories` (
  `ID` int(11) NOT NULL,
  `NAME` varchar(30) NOT NULL,
  `DESCRIPTION` varchar(100) NOT NULL,
  `THREADS` int(11) NOT NULL,
  `POSTS` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `forum_categories`
--

INSERT INTO `forum_categories` (`ID`, `NAME`, `DESCRIPTION`, `THREADS`, `POSTS`) VALUES
(1, 'Brickarcadium Center', 'Talk about Brickarcadium here!', 14, 17),
(2, 'Offtopic', 'Your topic hasn\'t got a category? This is the category for you!', 8, 7);

-- --------------------------------------------------------

--
-- Table structure for table `forum_replies`
--

CREATE TABLE `forum_replies` (
  `ID` int(11) NOT NULL,
  `BODY` varchar(400) NOT NULL,
  `USER_ID` int(11) NOT NULL,
  `THREAD_ID` int(11) NOT NULL,
  `TIME` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `forum_threads`
--

CREATE TABLE `forum_threads` (
  `ID` int(11) NOT NULL,
  `TITLE` varchar(40) NOT NULL,
  `BODY` varchar(1000) NOT NULL,
  `PINNED` enum('Y','N') NOT NULL,
  `LOCKED` enum('Y','N') NOT NULL,
  `REPLIES` int(11) NOT NULL,
  `VIEWS` int(11) NOT NULL,
  `RECENT_REPLY` int(11) NOT NULL,
  `USER_ID` int(11) NOT NULL,
  `TIME` bigint(20) NOT NULL,
  `UPDATED` bigint(20) NOT NULL,
  `CATEGORY_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `ID` int(11) NOT NULL,
  `USER_ID` int(11) NOT NULL,
  `ITEM_ID` int(11) NOT NULL,
  `SERIAL` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `ID` int(11) NOT NULL,
  `NAME` varchar(40) NOT NULL,
  `DESCRIPTION` varchar(500) NOT NULL,
  `BUX` int(11) NOT NULL,
  `COINS` int(11) NOT NULL,
  `GLB` varchar(255) NOT NULL,
  `TEXTURE` varchar(255) NOT NULL,
  `PREVIEWIMG` varchar(255) NOT NULL,
  `SALES` int(11) NOT NULL,
  `CATEGORY` enum('HAT','HAT_2','FACE') NOT NULL,
  `USER_ID` int(11) NOT NULL,
  `UPLOADED` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tokens`
--

CREATE TABLE `tokens` (
  `ID` int(11) NOT NULL,
  `USER_ID` int(11) NOT NULL,
  `TOKEN` varchar(80) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `ID` int(11) NOT NULL,
  `USERNAME` varchar(60) NOT NULL,
  `PWD` varchar(2000) NOT NULL,
  `STATUS` varchar(100) NOT NULL,
  `BIO` varchar(5000) NOT NULL,
  `FORUM_POSTS` int(11) NOT NULL,
  `JOIN_DATE` bigint(20) NOT NULL,
  `RANK` enum('USER','ADMIN','MOD') NOT NULL,
  `BUX` int(11) NOT NULL,
  `COINS` int(11) NOT NULL,
  `LAST_ONLINE` bigint(20) NOT NULL,
  `PROFILE_VIEWS` int(11) NOT NULL DEFAULT 0,
  `BANNED` enum('N','Y') NOT NULL,
  `AVATARIMG` varchar(255) NOT NULL,
  `HEADSHOTIMG` varchar(255) NOT NULL,
  `ISRENDER` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `avatars`
--
ALTER TABLE `avatars`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `bans`
--
ALTER TABLE `bans`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `beta_keys`
--
ALTER TABLE `beta_keys`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `forum_categories`
--
ALTER TABLE `forum_categories`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `forum_replies`
--
ALTER TABLE `forum_replies`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `forum_threads`
--
ALTER TABLE `forum_threads`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tokens`
--
ALTER TABLE `tokens`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `avatars`
--
ALTER TABLE `avatars`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bans`
--
ALTER TABLE `bans`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `beta_keys`
--
ALTER TABLE `beta_keys`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `forum_categories`
--
ALTER TABLE `forum_categories`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `forum_replies`
--
ALTER TABLE `forum_replies`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `forum_threads`
--
ALTER TABLE `forum_threads`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tokens`
--
ALTER TABLE `tokens`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
