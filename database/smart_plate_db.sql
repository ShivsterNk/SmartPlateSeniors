-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 21, 2026 at 05:03 PM
-- Server version: 8.0.45
-- PHP Version: 8.2.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `smart_plate_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `favorites`
--

CREATE TABLE `favorites` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `meal_id` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `meal_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `meal_thumb` varchar(500) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `meal_category` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `meal_area` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `saved_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `favorites`
--

INSERT INTO `favorites` (`id`, `user_id`, `meal_id`, `meal_name`, `meal_thumb`, `meal_category`, `meal_area`, `saved_at`) VALUES
(1, 4, '53329', 'Arepa pelua', 'https://www.themealdb.com/images/media/meals/jgl9qq1764437635.jpg', '', '', '2026-03-20 21:52:56'),
(2, 4, '53068', 'Beef Mechado', 'https://www.themealdb.com/images/media/meals/cgl60b1683206581.jpg', '', '', '2026-03-20 23:44:26');

-- --------------------------------------------------------

--
-- Table structure for table `foods`
--

CREATE TABLE `foods` (
  `food_id` int NOT NULL,
  `fdc_id` int DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `calories` decimal(6,2) DEFAULT NULL,
  `protein` decimal(6,2) DEFAULT NULL,
  `carbs` decimal(6,2) DEFAULT NULL,
  `fat` decimal(6,2) DEFAULT NULL,
  `fiber` decimal(6,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `meal_plans`
--

CREATE TABLE `meal_plans` (
  `meal_plan_id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `days` int DEFAULT '7',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `survey`
--

CREATE TABLE `survey` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `meal_preference` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `meals_per_day` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `cooking_level` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `flexibility` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `dietary_restrictions` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `foods_to_avoid` text COLLATE utf8mb4_general_ci,
  `submitted_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `email`, `password_hash`, `created_at`) VALUES
(2, 'Kemi Akinmade', 'akinmadeascol2019@gmail.com', '$2y$10$grvBPAUKo2uxGLwA2j0lPOsRfzIwjbhYw/NaE4JblMwrsXqjYbMoC', '2026-02-20 00:04:56'),
(3, 'Sivakumar Nirmalakumar', 'siva.nirmalakumar@gmail.com', '$2y$10$TfgK9cH/h6sU48a188jyuuWp9grP5kWO0ER6NbzWXGh8CvCFgmvpa', '2026-03-04 14:46:17'),
(4, 'Lauryn Akinmade', 'laurynakinmade@gmail.com', '$2y$10$7RNrci8XQPASSbV/ODLbnucR7osFtMwcIroQ1pv/oEwmcQyaE0eAW', '2026-03-20 03:47:33');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_favorite` (`user_id`,`meal_id`);

--
-- Indexes for table `foods`
--
ALTER TABLE `foods`
  ADD PRIMARY KEY (`food_id`),
  ADD UNIQUE KEY `fdc_id` (`fdc_id`);

--
-- Indexes for table `meal_plans`
--
ALTER TABLE `meal_plans`
  ADD PRIMARY KEY (`meal_plan_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `survey`
--
ALTER TABLE `survey`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_survey` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `favorites`
--
ALTER TABLE `favorites`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `foods`
--
ALTER TABLE `foods`
  MODIFY `food_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `meal_plans`
--
ALTER TABLE `meal_plans`
  MODIFY `meal_plan_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `survey`
--
ALTER TABLE `survey`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `favorites`
--
ALTER TABLE `favorites`
  ADD CONSTRAINT `favorites_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `meal_plans`
--
ALTER TABLE `meal_plans`
  ADD CONSTRAINT `meal_plans_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `survey`
--
ALTER TABLE `survey`
  ADD CONSTRAINT `survey_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
