-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 23, 2024 at 06:31 AM
-- Server version: 10.4.14-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ujian`
--

-- --------------------------------------------------------

--
-- Table structure for table `category_limits`
--

CREATE TABLE `category_limits` (
  `category_id` int(11) NOT NULL,
  `question_limit` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `category_limits`
--

INSERT INTO `category_limits` (`category_id`, `question_limit`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1);

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `id` int(11) NOT NULL,
  `question` text NOT NULL,
  `option_a` varchar(255) NOT NULL,
  `option_b` varchar(255) NOT NULL,
  `option_c` varchar(255) NOT NULL,
  `option_d` varchar(255) NOT NULL,
  `correct_option` char(1) NOT NULL,
  `kategori` char(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`id`, `question`, `option_a`, `option_b`, `option_c`, `option_d`, `correct_option`, `kategori`) VALUES
(11, 'C1 no 1', '1', '1', '1', '1', 'a', '1'),
(12, 'C1 no 2', 'a', 'a', 'a', 'a', 'a', '1'),
(13, 'C1 no 3', 'a', 'a', 'a', 'a', 'a', '1'),
(14, 'C1 no 4', 'a', 'a', 'a', 'a', 'a', '1'),
(15, 'C1 no 5', 'a', 'a', 'a', 'a', 'a', '1'),
(16, 'C2 no 1', 'a', 'a', 'a', 'a', 'a', '2'),
(17, 'C2 no 2', 'a', 'a', 'a', 'a', 'a', '2'),
(18, 'C2 no 3', 'a', 'a', 'a', 'a', 'a', '2'),
(19, 'C3 no 1', 'a', 'a', 'a', 'a', 'a', '3'),
(20, 'C3 no 2', 'a', 'a', 'a', 'a', 'a', '3'),
(21, 'C3 no 3', 'a', 'a', 'a', 'a', 'a', '3'),
(22, 'C4 no 1', 'a', 'a', 'a', 'a', 'a', '4'),
(23, 'C4 no 2', 'a', 'a', 'a', 'a', 'a', '4'),
(24, 'C4 no 3', 'a', 'a', 'a', 'a', 'a', '4'),
(25, 'C5 no 1', 'a', 'a', 'a', 'a', 'a', '5'),
(26, 'C5 no 2', 'a', 'a', 'a', 'a', 'a', '5'),
(27, 'C5 no 3', 'a', 'a', 'a', 'a', 'a', '5'),
(28, 'C6 no 1', 'a', 'a', 'a', 'a', 'a', '6'),
(29, 'C6 no 2', 'a', 'a', 'a', 'a', 'a', '6'),
(30, 'C6 no 3', 'a', 'a', 'a', 'a', 'a', '6');

-- --------------------------------------------------------

--
-- Table structure for table `results`
--

CREATE TABLE `results` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `score` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `results`
--

INSERT INTO `results` (`id`, `user_id`, `score`) VALUES
(1, 6, 0),
(2, 6, 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `created_at`) VALUES
(1, 'ani', '6b86b273ff34fce19d6b804eff5a3f5747ada4eaa22f1d49c01e52ddb7875b4b', 'user', '2024-11-20 11:59:50'),
(2, 'na', '$2y$10$NZwixdaILdlVqUFb1rOy7.gv2SUiaAxVYPE0cZXW4giQHGf.w8Idu', 'user', '2024-11-20 12:43:21'),
(3, 'naa', '$2y$10$LTFVmhKr1RNtupkNZcgYHehlQ3J/lEolm8aR189FGMlnni3e8dA7W', 'user', '2024-11-20 12:45:23'),
(4, 'qw', '$2y$10$TwO48JlylvLfvtDmAVVo1uBwuYecoTnlw2I29yXyJZhLyrjNUv7CG', 'user', '2024-11-20 12:46:30'),
(5, 's', '$2y$10$ZqZskweRtIPGM8Aflpx79.Ylgk6xIuiPn6QkH.zHQShIp62xQhjqu', 'user', '2024-11-20 12:47:22'),
(6, '12', '$2y$10$W9pX6xICfaB1jyrsuDycWe5Zjl64VM7gGrh8aoeDJvvWQq/n3cQ9G', 'user', '2024-11-20 13:27:02'),
(7, 'nanang', '$2y$10$OjTH5iP13T8rvLA9dMJ05upowHmlgIVZuB9VjhOxfx2amiBkOCddi', 'user', '2024-11-20 13:29:40'),
(8, 'any', '$2y$10$a8z5ufIkGQ7AooduJDuhtOFPJjw8kZ1TNa8hyS.kVTEyLQshaW5eG', 'admin', '2024-11-20 18:22:45'),
(9, 'nanangs', '$2y$10$DdnmHeG9Tj14DoSw9/FvhO3SmxcpIibVf5K632ZQLk.2d4ahu1gx6', 'user', '2024-11-20 18:24:58'),
(10, 'res', '$2y$10$Rp9w4Hp7469L3dieM4FHp.NCVATz7vqQTWPdmeuQCdrYlVu0JoUwe', 'user', '2024-11-20 21:10:41'),
(11, 'a', '$2y$10$cILtJMIkSAKO7nEvA/aeKOxWsnKdPFOFuFhQkKVX7CoIRawOWyg7a', 'user', '2024-11-21 08:32:27'),
(12, 'c', '$2y$10$mj8pf1zg3x0oWV2/HKyYF.Ub7CutI0F/shqPlak0KMyccbRHQG0rK', 'user', '2024-11-21 12:52:48'),
(13, 'as', '$2y$10$3D9MhOpykhpPWb0dwCaA.OHsgPJsVOuXVmHdRR4E1UvFCZZsZZIbS', 'user', '2024-11-21 17:27:21'),
(14, 'b', '$2y$10$60xLHygKmwCpnc/9sJSeV.S97QM/a11KmhZcvMS9.5gd02DJZp6Pe', 'user', '2024-11-21 17:47:57');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category_limits`
--
ALTER TABLE `category_limits`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `results`
--
ALTER TABLE `results`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `results`
--
ALTER TABLE `results`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `results`
--
ALTER TABLE `results`
  ADD CONSTRAINT `results_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
