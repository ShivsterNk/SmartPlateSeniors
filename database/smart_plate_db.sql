-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 29, 2026 at 07:14 PM
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
                                      `title` varchar(255) COLLATE utf8mb4_general_ci DEFAULT 'New Chat',
                                      `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
                                      `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chat_conversations`
--

INSERT INTO `chat_conversations` (`id`, `user_id`, `title`, `created_at`, `updated_at`) VALUES
                                                                                            (1, 1, 'New Chat', '2026-03-23 19:25:10', '2026-03-23 19:25:10'),
                                                                                            (2, 1, 'New Chat', '2026-03-23 20:41:07', '2026-03-23 20:41:07'),
                                                                                            (3, 1, 'New Chat', '2026-03-24 04:24:00', '2026-03-24 04:24:00'),
                                                                                            (4, 1, 'New Chat', '2026-03-24 21:50:47', '2026-03-24 21:50:47'),
                                                                                            (5, 1, 'New Chat', '2026-03-25 05:00:41', '2026-03-25 05:00:41'),
                                                                                            (6, 1, 'New Chat', '2026-03-25 05:01:50', '2026-03-25 05:01:50'),
                                                                                            (7, 1, 'New Chat', '2026-03-25 14:05:26', '2026-03-25 14:05:26'),
                                                                                            (8, 1, 'New Chat', '2026-03-25 14:06:36', '2026-03-25 14:06:36'),
                                                                                            (9, 1, 'New Chat', '2026-03-25 14:14:07', '2026-03-25 14:14:07');

-- --------------------------------------------------------

--
-- Table structure for table `chat_messages`
--

CREATE TABLE `chat_messages` (
                                 `id` int NOT NULL,
                                 `conversation_id` int NOT NULL,
                                 `role` enum('user','assistant','system') COLLATE utf8mb4_general_ci NOT NULL,
                                 `content` text COLLATE utf8mb4_general_ci NOT NULL,
                                 `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chat_messages`
--

INSERT INTO `chat_messages` (`id`, `conversation_id`, `role`, `content`, `created_at`) VALUES
                                                                                           (1, 1, 'user', 'Create a 3-day meal plan for me', '2026-03-23 19:25:10'),
                                                                                           (2, 1, 'assistant', '# 🌱 Your 3-Day Vegetarian Meal Plan\n\nHere\'s a plan tailored to your **2,000 calorie goal** with **150g protein, 200g carbs, and 65g fat** — all nut-free!\n\n---\n\n## 📅 Day 1\n| Meal | Food | Cal | Protein | Carbs | Fat |\n|------|------|-----|---------|-------|-----|\n| Breakfast | Greek yogurt parfait with berries & granola | 420 | 28g | 52g | 10g |\n| Lunch | Lentil soup + whole grain bread + side salad | 520 | 32g | 68g | 12g |\n| Dinner | Tofu stir-fry with brown rice & vegetables | 580 | 42g | 62g | 18g |\n| Snack | Cottage cheese + apple slices | 280 | 24g | 32g | 5g |\n| **Total** | | **~1,800** | **~126g** | **~214g** | **~45g** |\n\n---\n\n## 📅 Day 2\n| Meal | Food | Cal | Protein | Carbs | Fat |\n|------|------|-----|---------|-------|-----|\n| Breakfast | Veggie omelette (3 eggs) + whole wheat toast | 480 | 32g | 38g | 18g |\n| Lunch | Black bean burrito bowl with quinoa & avocado | 560 | 28g | 65g | 20g |\n| Dinner | Chickpea curry with basmati rice & naan | 620 | 38g | 78g | 16g |\n| Snack | Edamame + hummus with veggie sticks | 340 | 22g | 32g | 12g |\n| **Total** | | **~2,000** | **~120g** | **~213g** | **~66g** |\n\n---\n\n## 📅 Day 3\n| Meal | Food | Cal | Protein | Carbs | Fat |\n|------|------|-----|---------|-------|-----|\n| Breakfast | Protein smoothie (whey/pea protein, banana, spinach, oat milk) | 380 | 35g | 48g | 8g |\n| Lunch | Caprese wrap + lentil side salad | 490 | 28g | 52g | 18g |\n| Dinner | Black bean tacos (3) with cheese, salsa & Greek yogurt | 620 | 42g | 68g | 22g |\n| Snack | String cheese + fruit cup | 280 | 18g | 28g | 8g |\n| **Total** | | **~1,770** | **~123g** | **~196g** | **~56g** |\n\n---\n\n## 💡 Quick Tips\n- **Protein gap:** Consider adding a protein shake or extra legumes to hit 150g daily\n- **All recipes are nut-free** ✅ — just double-check store-bought items like granola for hidden nuts!\n- **Meal prep tip:** Cook a big batch of quinoa/rice on Sunday to save time\n\nWant me to **swap any meals**, add **recipes**, or adjust the plan? 😊', '2026-03-23 19:25:24'),
                                                                                           (3, 2, 'user', 'Make me a three day plan', '2026-03-23 20:41:07'),
                                                                                            (4, 2, 'assistant', '# 🌿 Your 3-Day Vegetarian Meal Plan\n\nHere\'s a plan designed to hit your **2,000 cal | 150g protein | 200g carbs | 65g fat** goals daily. All meals are vegetarian and nut-free!\n\n---\n\n## 📅 Day 1\n| Meal | Food | Cal | P | C | F |\n|------|------|-----|---|---|---|\n| Breakfast | Greek yogurt + berries + granola | 420 | 28g | 52g | 10g |\n| Lunch | Lentil soup + whole grain bread | 520 | 32g | 68g | 8g |\n| Dinner | Tofu stir-fry + brown rice + veggies | 580 | 42g | 60g | 22g |\n| Snack | Cottage cheese + apple | 280 | 24g | 32g | 6g |\n| **Totals** | | **1,800** | **126g** | **212g** | **46g** |\n\n---\n\n## 📅 Day 2\n| Meal | Food | Cal | P | C | F |\n|------|------|-----|---|---|---|\n| Breakfast | Egg white omelette + whole wheat toast | 380 | 34g | 38g | 8g |\n| Lunch | Black bean burrito bowl + quinoa | 560 | 38g | 72g | 12g |\n| Dinner | Chickpea curry + basmati rice | 620 | 38g | 75g | 18g |\n| Snack | String cheese + grapes | 280 | 18g | 32g | 12g |\n| **Totals** | | **1,840** | **128g** | **217g** | **50g** |\n\n---\n\n## 📅 Day 3\n| Meal | Food | Cal | P | C | F |\n|------|------|-----|---|---|---|\n| Breakfast | Protein smoothie (whey + banana + oat milk) | 420 | 42g | 52g | 8g |\n| Lunch | Caprese salad + lentil pasta | 500 | 36g | 58g | 16g |\n| Dinner | Black bean veggie burgers + sweet potato fries | 620 | 38g | 68g | 18g |\n| Snack | Edamame + hummus + veggies | 320 | 22g | 28g | 14g |\n| **Totals** | | **1,860** | **138g** | **206g** | **56g** |\n\n---\n\n## 💡 Quick Tips\n- **Protein gap:** You\'re slightly under the 150g goal — consider adding a scoop of protein powder to smoothies or an extra serving of Greek yogurt\n- **Calorie gap:** Add a drizzle of olive oil to meals or an extra snack to reach 2,000\n- ✅ All meals are **nut-free** and **vegetarian**\n\nWant me to swap any meals or add recipes for any of these? 😊', '2026-03-23 20:41:19'),
(5, 3, 'user', 'test', '2026-03-24 04:24:00'),
                                                                                             (6, 3, 'assistant', 'Hey there, Test User! 👋\n\nLooks like everything is working great! I\'m **PlateBot**, your nutrition assistant here on Smart Plate.\n\nI can see your profile is all set up:\n- 🥗 **Diet:** Vegetarian\n- ⚠️ **Allergies:** Nuts\n- 🎯 **Calorie Goal:** 2,000 cal/day\n- 💪 **Macros:** 150g protein | 200g carbs | 65g fat\n\nHere are some things I can help you with:\n- 🍽️ **Meal planning** tailored to your goals\n- 📊 **Nutrition questions** and advice\n- 🛒 **Food suggestions** that fit your dietary needs\n- 💡 **Healthy eating tips**\n\nWhat would you like to explore today? 😊', '2026-03-24 04:24:05'),
(7, 4, 'user', 'test', '2026-03-24 21:50:47'),
(8, 4, 'assistant', 'Hey there! 👋 Welcome to **Smart Plate**!\n\nI\'m PlateBot, your personal nutrition assistant. I\'m here to help you with things like:\n\n- 🥗 **Meal planning** tailored to your vegetarian lifestyle\n- 📊 **Tracking macros** and hitting your daily goals\n- 💡 **Nutrition tips** and healthy eating advice\n- 🍽️ **Recipe ideas** that fit your preferences\n\nI can see you\'re working toward **2,000 calories/day** with goals for protein, carbs, and healthy fats — and I\'ll always keep your **vegetarian diet** and **nut allergy** in mind!\n\nWhat can I help you with today? 😊', '2026-03-24 21:50:52'),
(9, 5, 'user', 'test', '2026-03-25 05:00:41'),
(10, 5, 'assistant', 'Hey there! 👋 I\'m **PlateBot**, your nutrition assistant from Smart Plate!\n\nIt looks like your profile is all set up. I can see you\'re:\n- 🥗 **Vegetarian**\n- ⚠️ **Nut-free**\n- 🎯 Aiming for **2,000 calories/day**\n\nI\'m here to help you with things like:\n- 🍽️ **Meal planning**\n- 📊 **Nutrition questions**\n- 💡 **Healthy eating tips**\n- 🔍 **Food suggestions** that fit your goals\n\nWhat can I help you with today? 😊', '2026-03-25 05:00:44'),
                                                                                             (11, 6, 'user', '\"What should I eat for breakfast?\"', '2026-03-25 05:01:50'),
                                                                                             (12, 6, 'assistant', '# Good Morning! ☀️ Here are some great breakfast ideas for you!\n\nSince you\'re vegetarian and avoiding nuts, here are some options that fit your goals:\n\n## Quick & Easy Options\n\n**🥚 Protein-Packed Scramble**\n- 3 whole eggs + spinach + cheese\n- ~350 calories | 24g protein | 2g carbs | 22g fat\n\n**🥣 Greek Yogurt Bowl**\n- 1 cup Greek yogurt + berries + granola (nut-free)\n- ~380 calories | 20g protein | 45g carbs | 6g fat\n\n**🫓 Cottage Cheese Toast**\n- 2 slices whole grain bread + ½ cup cottage cheese + sliced tomato\n- ~320 calories | 22g protein | 35g carbs | 6g fat\n\n## Why These Work For You\n- Each option gives you a **solid protein start** toward your 150g daily goal\n- They leave plenty of room for lunch and dinner in your **2000 calorie budget**\n- All are **100% vegetarian and nut-free** ✅\n\n## Pro Tips 💡\n- Eating protein at breakfast helps keep you fuller longer\n- Pairing protein with fiber (fruits, veggies) stabilizes energy levels\n\nWould you like a **full day meal plan**, or want me to adjust any of these options to better fit your taste? 😊', '2026-03-25 05:01:58'),
(13, 6, 'user', 'thank you', '2026-03-25 05:02:03'),
(14, 6, 'assistant', 'You\'re so welcome! 😊 \n\nWhenever you\'re ready to plan out more meals, track your macros, or just need some food inspiration — I\'m right here to help!\n\nHappy eating! 🥗✨', '2026-03-25 05:02:06'),
                                                                                             (15, 7, 'user', 'test', '2026-03-25 14:05:26'),
                                                                                             (16, 7, 'assistant', 'Hey there! 👋 Welcome to **Smart Plate**!\n\nI\'m PlateBot, your personal nutrition assistant. I\'m here to help you with:\n\n- 🥗 **Meal planning** tailored to your vegetarian lifestyle\n- 📊 **Nutrition tracking** to hit your daily goals\n- 💡 **Healthy eating tips** and recipe ideas\n- ❓ **Answering nutrition questions**\n\nI can see you\'re working toward **2,000 calories/day** with goals of 150g protein, 200g carbs, and 65g fat — and I\'ll always keep your **vegetarian diet** and **nut allergy** in mind!\n\nWhat can I help you with today? 😊', '2026-03-25 14:05:31'),
                                                                                             (17, 8, 'user', 'make me a 3 day meal plan', '2026-03-25 14:06:36'),
                                                                                             (18, 8, 'assistant', '# 🌱 Your 3-Day Vegetarian Meal Plan\n\nHere\'s a plan tailored to your goals (~2000 cal | 150g protein | 200g carbs | 65g fat) — and completely nut-free!\n\n---\n\n## 📅 Day 1\n| Meal | Food | Cal | P | C | F |\n|------|------|-----|---|---|---|\n| 🌅 Breakfast | Greek yogurt + berries + granola | 420 | 28g | 52g | 10g |\n| 🌞 Lunch | Lentil soup + whole grain bread | 480 | 28g | 68g | 8g |\n| 🌙 Dinner | Tofu stir-fry + brown rice + veggies | 580 | 42g | 58g | 22g |\n| 🍎 Snack | Cottage cheese + apple | 280 | 22g | 32g | 6g |\n| **Total** | | **1,760** | **120g** | **210g** | **46g** |\n\n---\n\n## 📅 Day 2\n| Meal | Food | Cal | P | C | F |\n|------|------|-----|---|---|---|\n| 🌅 Breakfast | Egg scramble + spinach + whole wheat toast | 450 | 32g | 38g | 16g |\n| 🌞 Lunch | Chickpea wrap + hummus + veggies | 520 | 24g | 72g | 14g |\n| 🌙 Dinner | Black bean tacos (3) + guacamole | 580 | 28g | 68g | 22g |\n| 🍎 Snack | Edamame + string cheese | 280 | 24g | 18g | 12g |\n| **Total** | | **1,830** | **108g** | **196g** | **64g** |\n\n---\n\n## 📅 Day 3\n| Meal | Food | Cal | P | C | F |\n|------|------|-----|---|---|---|\n| 🌅 Breakfast | Protein smoothie (whey + banana + oats + milk) | 480 | 42g | 58g | 8g |\n| 🌞 Lunch | Quinoa Buddha bowl + roasted veggies + feta | 520 | 28g | 62g | 18g |\n| 🌙 Dinner | Veggie pasta + marinara + ricotta | 580 | 36g | 72g | 16g |\n| 🍎 Snack | Hard boiled eggs (2) + cucumber | 220 | 14g | 6g | 10g |\n| **Total** | | **1,800** | **120g** | **198g** | **52g** |\n\n---\n\n## 💡 Quick Tips\n- **Protein gap?** Add a scoop of protein powder to smoothies or yogurt\n- **Still hungry?** Bulk up meals with extra non-starchy veggies (very low cal!)\n- **Meal prep** the lentil soup and quinoa on Day 1 to save time\n\nWould you like **detailed recipes** for any of these meals, or adjustments to better hit your macros? 😊', '2026-03-25 14:06:48'),
(19, 9, 'user', 'How much potassium is in a banana', '2026-03-25 14:14:07'),
                                                                                              (20, 9, 'assistant', '# Potassium in a Banana 🍌\n\nA **medium banana** (about 118g) contains approximately:\n\n| Nutrient | Amount |\n|----------|--------|\n| Potassium | **422 mg** |\n| Calories | ~105 |\n| Carbs | ~27g |\n| Protein | ~1.3g |\n| Fat | ~0.4g |\n\n## Good to Know\n- The daily recommended potassium intake is about **3,500–4,700 mg**\n- One banana gives you roughly **9–12%** of your daily needs\n- Larger bananas can contain up to **500+ mg** of potassium\n\n## Other Great Vegetarian Potassium Sources 🥬\nSince you\'re vegetarian, here are some even higher potassium options:\n- 🥔 **Baked potato** – ~900 mg\n- 🫘 **White beans (1 cup)** – ~1,000 mg\n- 🥑 **Avocado (half)** – ~500 mg\n- 🍠 **Sweet potato** – ~540 mg\n\nBananas are a convenient and healthy snack that fit nicely into your daily goals! Would you like help incorporating them into your meal plan? 😊', '2026-03-25 14:14:15'),
(21, 9, 'user', 'make me a 3 day plan', '2026-03-25 14:14:48'),
(22, 9, 'assistant', '# 3-Day Vegetarian Meal Plan 🌱\n\nTailored to your goals: **2,000 cal | 150g protein | 200g carbs | 65g fat** (nut-free!)\n\n---\n\n## Day 1\n\n| Meal | Food | Cal | Protein | Carbs | Fat |\n|------|------|-----|---------|-------|-----|\n| 🌅 Breakfast | Greek yogurt parfait with berries & granola | 400 | 25g | 45g | 12g |\n| 🌤️ Lunch | Chickpea & veggie wrap with hummus | 500 | 22g | 60g | 15g |\n| 🌙 Dinner | Lentil curry with brown rice | 600 | 28g | 75g | 14g |\n| 🍎 Snacks | Banana + cottage cheese | 250 | 20g | 30g | 3g |\n| **Totals** | | **~1,950** | **~95g** | **~210g** | **~44g** |\n\n---\n\n## Day 2\n\n| Meal | Food | Cal | Protein | Carbs | Fat |\n|------|------|-----|---------|-------|-----|\n| 🌅 Breakfast | Veggie omelette (3 eggs) + whole wheat toast | 420 | 28g | 35g | 18g |\n| 🌤️ Lunch | Black bean soup + side salad | 480 | 24g | 58g | 12g |\n| 🌙 Dinner | Tofu stir-fry with quinoa & vegetables | 620 | 35g | 65g | 18g |\n| 🍎 Snacks | Greek yogurt + apple | 280 | 18g | 38g | 4g |\n| **Totals** | | **~1,800** | **~105g** | **~196g** | **~52g** |\n\n---\n\n## Day 3\n\n| Meal | Food | Cal | Protein | Carbs | Fat |\n|------|------|-----|---------|-------|-----|\n| 🌅 Breakfast | Protein smoothie (whey, banana, oat milk) | 380 | 30g | 48g | 8g |\n| 🌤️ Lunch | Caprese panini + lentil soup | 520 | 28g | 55g | 18g |\n| 🌙 Dinner | Black bean tacos (3) with avocado & salsa | 650 | 28g | 72g | 22g |\n| 🍎 Snacks | Cottage cheese + rice cakes | 260 | 22g | 28g | 4g |\n| **Totals** | | **~1,810** | **~108g** | **~203g** | **~52g** |\n\n---\n\n## ⚠️ A Few Notes\n\n- **Protein gap** — These meals fall short of your 150g protein goal. Consider adding a **protein shake** or extra **eggs/tofu/legumes** daily\n- **All meals are nut-free** ✅\n- **All meals are vegetarian** ✅\n- Portions can be adjusted to hit calories more precisely\n\n---\n\n## 💡 Tips to Boost Protein\n- Add **hemp seeds** to smoothies or yogurt\n- Use **edamame** as a snack\n- Try **tempeh** as a meat alternative\n\nWould you like me to expand on any recipes, create a shopping list, or adjust the plan? 😊', '2026-03-25 14:15:01');

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

-- --------------------------------------------------------

--
-- Indexes for table `users`
--
ALTER TABLE `users`
    ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_preferences`
--
ALTER TABLE `user_preferences`
    ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

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
-- Dumping data for table `user_preferences`
--
ALTER TABLE `foods`
    ADD PRIMARY KEY (`food_id`),
  ADD UNIQUE KEY `fdc_id` (`fdc_id`);

--
-- Indexes for dumped tables
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
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
    MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `user_preferences`
--
ALTER TABLE `user_preferences`
    MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `chat_conversations`
--
ALTER TABLE `chat_conversations`
    MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `chat_messages`
--
ALTER TABLE `chat_messages`
    MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- Indexes for table `user_preferences`
--
ALTER TABLE `favorites`
    MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for dumped tables
--
ALTER TABLE `foods`
    MODIFY `food_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `chat_conversations`
--
ALTER TABLE `meal_plans`
    MODIFY `meal_plan_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `survey`
--
ALTER TABLE `survey`
    MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `user_preferences`
--
ALTER TABLE `user_preferences`
    ADD CONSTRAINT `user_preferences_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

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
-- Constraints for table `chat_conversations`
--
ALTER TABLE `favorites`
    ADD CONSTRAINT `favorites_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `chat_messages`
--
ALTER TABLE `meal_plans`
    ADD CONSTRAINT `meal_plans_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `user_preferences`
--
ALTER TABLE `survey`
    ADD CONSTRAINT `survey_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
