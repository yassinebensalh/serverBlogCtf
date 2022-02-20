-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 17, 2021 at 01:43 PM
-- Server version: 10.4.19-MariaDB
-- PHP Version: 7.4.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `socialcerttn`
--

-- --------------------------------------------------------

--
-- Table structure for table `articles`
--

CREATE TABLE `articles` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `total_quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `articles`
--

INSERT INTO `articles` (`id`, `user_id`, `name`, `quantity`, `price`, `total_quantity`) VALUES
(5, 44, 'ballon-yassine', 70, 10, 99),
(6, 49, 'camera', 10, 15, 94),
(7, 49, 'voiture', 45, 100, 100),
(8, 49, 'portable', 33, 74, 40),
(9, 49, 'Iphone', 22, 1000, 70),
(10, 44, 'Samsung', 42, 365, 128),
(11, 49, 'clavier', 200, 269, 200),
(12, 49, 'souris', 10, 123, 18),
(17, 45, '4g box', 111, 223, 111);

-- --------------------------------------------------------

--
-- Table structure for table `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20210708205925', '2021-07-08 22:59:59', 37),
('DoctrineMigrations\\Version20210708210104', '2021-07-08 23:01:09', 27),
('DoctrineMigrations\\Version20210708215504', '2021-07-08 23:55:13', 74),
('DoctrineMigrations\\Version20210708215809', '2021-07-08 23:58:17', 24),
('DoctrineMigrations\\Version20210709194817', '2021-07-09 22:14:49', 27),
('DoctrineMigrations\\Version20210718164338', '2021-07-18 18:46:37', 177),
('DoctrineMigrations\\Version20210720195946', '2021-07-20 21:59:55', 144),
('DoctrineMigrations\\Version20210722131940', '2021-07-22 15:20:13', 159),
('DoctrineMigrations\\Version20210722230536', '2021-07-23 01:05:48', 28),
('DoctrineMigrations\\Version20210723145543', '2021-07-23 16:58:05', 100),
('DoctrineMigrations\\Version20210723154109', '2021-07-23 17:41:23', 25),
('DoctrineMigrations\\Version20210726001543', '2021-07-26 02:16:15', 475),
('DoctrineMigrations\\Version20210726011757', '2021-07-26 03:18:11', 26),
('DoctrineMigrations\\Version20210727003059', '2021-07-27 02:31:31', 148),
('DoctrineMigrations\\Version20210727180148', '2021-07-27 20:02:10', 841),
('DoctrineMigrations\\Version20210730185504', '2021-07-30 20:56:58', 145),
('DoctrineMigrations\\Version20210801121022', '2021-08-01 14:11:17', 172);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:json)',
  `firstname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lastname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `activation_token` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reset_token` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_login_date` datetime DEFAULT NULL,
  `disable_token` varchar(65) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phoneNumber` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `email`, `password`, `roles`, `firstname`, `lastname`, `activation_token`, `reset_token`, `last_login_date`, `disable_token`, `phoneNumber`) VALUES
(44, 'yassine.bensalha@esprit.tn', '$2y$13$sprEy8r8SGyAWzI5kgO/FeQUbyBdHdoid9hpDdRgOi/bPEOx/i6Im', '{\"0\":\"ROLE_ADMIN\",\"2\":\"ROLE_EDITOR\"}', 'yassine', 'ben salha', NULL, 'NULL', '2021-08-01 16:33:42', NULL, 54870189),
(45, 'bensalhaasma@esprit.tn', '$2y$13$AbBJDOSTB1UmJ/kKuHrrA.UoWb5NTElNbS9pd/ZBt/385.nAG.PdO', '[\"ROLE_USER\",\"ROLE_EDITOR\"]', 'asma', 'ben salha', NULL, NULL, '2021-07-24 22:28:10', NULL, 54870188),
(46, 'bensalhabdelhak@esprit.tn', '$2y$13$gTl684syXqiPL7CovnytuuPnJRu2GvdbaA852STVijgTmd4SAM8la', '[\"ROLE_USER\",\"ROLE_EDITOR\"]', 'abdelhak', 'ben salha', NULL, NULL, '2021-07-24 23:02:57', NULL, 54870187),
(49, 'yassine.bs125@gmail.com', '$2y$13$75bCH9CG9TnPckVA8KFzNu4J/0XMYAAuH1xjdiE.uo9KKZtdnNa7S', '[\"ROLE_USER\",\"ROLE_EDITOR\"]', 'mohamed', 'ben salha', NULL, NULL, '2021-08-01 16:43:26', NULL, 54870186),
(50, 'bensalhayassine@esprit.tn', '$2y$13$k/jnh1qD2aooS/LJ91ThTOI0cFRUp2i.gQ6VAKoeVUZXahtfDamNi', '[\"ROLE_USER\",\"ROLE_EDITOR\"]', 'benjhk', 'salha', NULL, NULL, NULL, NULL, 11112222);

-- --------------------------------------------------------

--
-- Table structure for table `user_login_date`
--

CREATE TABLE `user_login_date` (
  `id` int(11) NOT NULL,
  `id_user_id` int(11) NOT NULL,
  `day_name` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `login_date` datetime NOT NULL,
  `week` int(11) NOT NULL,
  `color` varchar(7) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_login_date`
--

INSERT INTO `user_login_date` (`id`, `id_user_id`, `day_name`, `login_date`, `week`, `color`) VALUES
(9, 44, 'Sun', '2021-07-25 23:32:15', 1, '#cc0099'),
(11, 44, 'Tue', '2021-07-27 18:38:55', 1, '#cccc00'),
(12, 44, 'Tue', '2021-07-27 19:50:33', 1, '#cccc00'),
(13, 44, 'Wed', '2021-07-28 19:50:33', 1, '#00cc00'),
(14, 44, 'Mon', '2021-07-26 19:54:11', 1, '#cc0000'),
(15, 44, 'Mon', '2021-07-26 19:54:11', 1, '#cc0000'),
(16, 44, 'Mon', '2021-07-26 19:54:11', 1, '#cc0000'),
(17, 44, 'Mon', '2021-07-26 19:54:11', 1, '#cc0000'),
(18, 44, 'Wed', '2021-07-28 19:54:11', 1, '#00cc00'),
(19, 44, 'Wed', '2021-07-28 19:54:11', 1, '#00cc00'),
(20, 44, 'Fri', '2021-07-30 19:54:11', 1, '#0033cc'),
(21, 44, 'Sat', '2021-07-31 19:56:45', 1, '#6600cc'),
(22, 44, 'Tue', '2021-07-27 18:59:04', 1, '#cccc00'),
(23, 45, 'Sat', '2021-07-31 20:47:33', 1, '#6600cc'),
(24, 44, 'Sun', '2021-07-25 21:20:10', 1, '#cc0099'),
(26, 44, 'Thu', '2021-07-29 22:41:59', 1, NULL),
(27, 46, 'Thu', '2021-07-29 13:58:09', 1, NULL),
(28, 44, 'Thu', '2021-07-29 23:03:57', 1, NULL),
(29, 46, 'Fri', '2021-07-30 00:19:15', 1, NULL),
(30, 44, 'Fri', '2021-07-30 15:13:45', 1, NULL),
(31, 46, 'Fri', '2021-07-30 18:52:48', 1, NULL),
(32, 46, 'Fri', '2021-07-30 18:53:15', 1, NULL),
(33, 44, 'Fri', '2021-07-30 21:04:34', 1, NULL),
(36, 49, 'Sun', '2021-08-01 04:48:37', 1, NULL),
(37, 49, 'Sun', '2021-08-01 13:50:04', 1, NULL),
(38, 49, 'Sun', '2021-08-01 13:52:50', 1, NULL),
(40, 49, 'Sun', '2021-08-01 16:34:20', 1, NULL),
(41, 49, 'Sun', '2021-08-01 16:40:29', 1, NULL),
(42, 49, 'Sun', '2021-08-01 16:43:26', 1, NULL),
(45, 49, 'Sun', '2021-08-01 19:29:10', 1, NULL),
(47, 49, 'Sun', '2021-08-01 19:44:08', 1, NULL),
(52, 44, 'Sun', '2021-08-01 23:16:14', 1, NULL),
(54, 49, 'Mon', '2021-08-02 18:11:55', 2, NULL),
(56, 44, 'Tue', '2021-08-17 11:37:48', 2, NULL),
(57, 44, 'Tue', '2021-08-17 12:06:50', 3, NULL),
(58, 45, 'Tue', '2021-08-17 12:10:16', 2, NULL),
(59, 44, 'Tue', '2021-08-17 12:11:43', 4, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_BFDD3168A76ED395` (`user_id`);

--
-- Indexes for table `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`);

--
-- Indexes for table `user_login_date`
--
ALTER TABLE `user_login_date`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_CB8B7AD379F37AE5` (`id_user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `articles`
--
ALTER TABLE `articles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `user_login_date`
--
ALTER TABLE `user_login_date`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `articles`
--
ALTER TABLE `articles`
  ADD CONSTRAINT `FK_BFDD3168A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `user_login_date`
--
ALTER TABLE `user_login_date`
  ADD CONSTRAINT `FK_CB8B7AD379F37AE5` FOREIGN KEY (`id_user_id`) REFERENCES `user` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
