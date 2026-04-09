-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 09, 2026 at 08:13 PM
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
-- Table structure for table `chat_conversations`
--

CREATE TABLE `chat_conversations` (
                                      `id` int NOT NULL,
                                      `user_id` int NOT NULL,
                                      `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
                                      `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chat_conversations`
--

INSERT INTO `chat_conversations` (`id`, `user_id`, `created_at`, `updated_at`) VALUES
                                                                                   (1, 4, '2026-04-09 15:05:19', '2026-04-09 15:05:19'),
                                                                                   (2, 4, '2026-04-09 15:09:36', '2026-04-09 15:09:36'),
                                                                                   (3, 4, '2026-04-09 15:15:27', '2026-04-09 15:15:27'),
                                                                                   (4, 4, '2026-04-09 15:20:30', '2026-04-09 15:20:30'),
                                                                                   (5, 4, '2026-04-09 15:25:44', '2026-04-09 15:25:44'),
                                                                                   (6, 4, '2026-04-09 15:26:45', '2026-04-09 15:26:45'),
                                                                                   (7, 4, '2026-04-09 15:35:16', '2026-04-09 15:35:16');

-- --------------------------------------------------------

--
-- Table structure for table `chat_messages`
--

CREATE TABLE `chat_messages` (
                                 `id` int NOT NULL,
                                 `conversation_id` int NOT NULL,
                                 `role` enum('user','assistant') COLLATE utf8mb4_general_ci NOT NULL,
                                 `content` text COLLATE utf8mb4_general_ci NOT NULL,
                                 `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chat_messages`
--

INSERT INTO `chat_messages` (`id`, `conversation_id`, `role`, `content`, `created_at`) VALUES
                                                                                           (1, 1, 'user', 'dietary advice', '2026-04-09 15:05:19'),
                                                                                           (2, 2, 'user', 'dietary advice', '2026-04-09 15:09:36'),
                                                                                           (3, 3, 'user', 'dietary advice', '2026-04-09 15:15:27'),
                                                                                           (4, 4, 'user', 'recipe', '2026-04-09 15:20:30'),
                                                                                           (5, 5, 'user', 'recipe', '2026-04-09 15:25:44'),
                                                                                           (6, 6, 'user', 'food', '2026-04-09 15:26:45'),
                                                                                           (7, 7, 'user', 'recipe', '2026-04-09 15:35:16'),
                                                                                           (8, 7, 'assistant', 'Hi Lauryn! 👋 I\'d love to help you find a recipe!\n\nCould you give me a little more detail? For example:\n\n- 🍽️ **What type of meal** are you looking for? (breakfast, lunch, dinner, snack?)\n- 🥗 **Any cuisine preference?** (Italian, Asian, Mexican, etc.)\n- ⏱️ **How much time** do you have to cook?\n- 🥦 **Any ingredients** you want to use up?\n- 🎯 **Any nutrition goals** in mind? (high protein, low carb, etc.)\n\nThe more you tell me, the better I can match you with something you\'ll love! 😊', '2026-04-09 15:35:21');

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
                                                                                                                              (2, 4, '53068', 'Beef Mechado', 'https://www.themealdb.com/images/media/meals/cgl60b1683206581.jpg', '', '', '2026-03-20 23:44:26'),
                                                                                                                              (3, 4, '53234', 'Salmon noodle soup', 'https://www.themealdb.com/images/media/meals/ikizdm1763760862.jpg', '', '', '2026-03-23 20:31:35'),
                                                                                                                              (4, 4, '53147', 'Arroz con gambas y calamar', 'https://www.themealdb.com/images/media/meals/jc6oub1763196663.jpg', 'Seafood', '', '2026-03-31 23:06:42'),
                                                                                                                              (5, 4, '52848', 'Bean & Sausage Hotpot', 'https://www.themealdb.com/images/media/meals/vxuyrx1511302687.jpg', 'Miscellaneous', '', '2026-04-01 02:13:47');

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
-- Table structure for table `ready_meals`
--

CREATE TABLE `ready_meals` (
                               `meal_id` int NOT NULL,
                               `meal_name` varchar(255) NOT NULL,
                               `meal_image` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `ready_meals`
--

INSERT INTO `ready_meals` (`meal_id`, `meal_name`, `meal_image`) VALUES
                                                                     (1, 'Apple Cinnamon Oatmeal', 'AppleSmartPlate.jpg'),
                                                                     (2, 'Barbecue Grilled Chicken', 'BBQSmartPlate.jpg'),
                                                                     (3, 'Chicken Caesar Salad', 'CaesarSmartPlate.jpg'),
                                                                     (4, 'Roasted Carrots', 'CarrotSmartPlate.jpg'),
                                                                     (5, 'Mac and Cheese', 'CheeseSmartPlate.jpg'),
                                                                     (6, 'Mediterranean Chicken Bowl', 'ChickenSmartPlate.jpg'),
                                                                     (7, 'Egg Roll', 'EggSmartPlate.jpg'),
                                                                     (8, 'Lasagna', 'LasagnaSmartPlate.jpg'),
                                                                     (9, 'Lemon-Spiced Salmon', 'LemonSmartPlate.jpg'),
                                                                     (10, 'Vegetarian Omelette', 'OmeletteSmartPlate.jpg'),
                                                                     (11, 'Protein Pancakes', 'PancakeSmartPlate.jpg'),
                                                                     (12, 'Chicken Quesadilla', 'QuesadillaSmartPlate.jpg'),
                                                                     (13, 'Protein Rice Bowl', 'RiceSmartPlate.jpg'),
                                                                     (14, 'Beef Tacos', 'TacoSmartPlate.jpg'),
                                                                     (15, 'Quinoa/Roasted Veg Bowl', 'VegetableSmartPlate.jpg'),
                                                                     (16, 'Fruit/Yogurt Bowl', 'YogurtSmartPlate.jpg'),
                                                                     (17, 'Healthy Baked Ziti', 'ZitiSmartPlate.jpg');

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

--
-- Dumping data for table `survey`
--

INSERT INTO `survey` (`id`, `user_id`, `meal_preference`, `meals_per_day`, `cooking_level`, `flexibility`, `dietary_restrictions`, `foods_to_avoid`, `submitted_at`) VALUES
                                                                                                                                                                         (1, 4, 'Mostly Pre-Made', '3 Main Meals', 'Some Cooking', 'Some Structure', 'None', '', '2026-03-25 13:51:29'),
                                                                                                                                                                         (2, 5, 'Mix', '3 Main Meals', 'Some Cooking', 'Some Structure', 'None', '', '2026-03-25 13:57:01'),
                                                                                                                                                                         (3, 6, 'Mix', '3 Main Meals', 'Some Cooking', 'Some Structure', 'Halal, Kosher', 'na', '2026-03-25 14:21:20');

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
                                                                                    (4, 'Lauryn Akinmade', 'laurynakinmade@gmail.com', '$2y$10$7RNrci8XQPASSbV/ODLbnucR7osFtMwcIroQ1pv/oEwmcQyaE0eAW', '2026-03-20 03:47:33'),
                                                                                    (5, 'Graci Medina', 'gracie.medina9@gmail.com', '$2y$10$8ievwOb89Ck2mpelqpzU5eBPACGkbvTf6Df9sJWRTC11ppipTGY2.', '2026-03-25 13:47:59'),
                                                                                    (6, 'Chris Gaguancela', 'gaguancelac@gmail.com', '$2y$10$QfzuUMAfsS.39kf4RfVZDO7eVQgbIfxtF54z1WQVfX6PVXhnZHG4u', '2026-03-25 14:20:10');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `chat_conversations`
--
ALTER TABLE `chat_conversations`
    ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `chat_messages`
--
ALTER TABLE `chat_messages`
    ADD PRIMARY KEY (`id`),
  ADD KEY `conversation_id` (`conversation_id`);

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
-- Indexes for table `ready_meals`
--
ALTER TABLE `ready_meals`
    ADD PRIMARY KEY (`meal_id`);

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
-- AUTO_INCREMENT for table `chat_conversations`
--
ALTER TABLE `chat_conversations`
    MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `chat_messages`
--
ALTER TABLE `chat_messages`
    MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `favorites`
--
ALTER TABLE `favorites`
    MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
-- AUTO_INCREMENT for table `ready_meals`
--
ALTER TABLE `ready_meals`
    MODIFY `meal_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `survey`
--
ALTER TABLE `survey`
    MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
    MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `chat_conversations`
--
ALTER TABLE `chat_conversations`
    ADD CONSTRAINT `chat_conversations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `chat_messages`
--
ALTER TABLE `chat_messages`
    ADD CONSTRAINT `chat_messages_ibfk_1` FOREIGN KEY (`conversation_id`) REFERENCES `chat_conversations` (`id`) ON DELETE CASCADE;

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
