SET FOREIGN_KEY_CHECKS = 0;

-- DROP TABLES
DROP TABLE IF EXISTS chat_messages;
DROP TABLE IF EXISTS chat_conversations;
DROP TABLE IF EXISTS favorites;
DROP TABLE IF EXISTS nutrition_logs;
DROP TABLE IF EXISTS meal_plans;
DROP TABLE IF EXISTS ingredients;
DROP TABLE IF EXISTS ready_meals;
DROP TABLE IF EXISTS survey;
DROP TABLE IF EXISTS user_preferences;
DROP TABLE IF EXISTS foods;
DROP TABLE IF EXISTS users;

-- USERS
CREATE TABLE users (
                       user_id INT AUTO_INCREMENT PRIMARY KEY,
                       name VARCHAR(100) NOT NULL,
                       email VARCHAR(255) NOT NULL UNIQUE,
                       password_hash VARCHAR(255) NOT NULL,
                       created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO users VALUES
                      (2,'Kemi Akinmade','akinmadeascol2019@gmail.com','$2y$10$grvBPAUKo2uxGLwA2j0lPOsRfzIwjbhYw/NaE4JblMwrsXqjYbMoC','2026-02-20 00:04:56'),
                      (3,'Sivakumar Nirmalakumar','siva.nirmalakumar@gmail.com','$2y$10$TfgK9cH/h6sU48a188jyuuWp9grP5kWO0ER6NbzWXGh8CvCFgmvpa','2026-03-04 14:46:17'),
                      (4,'Lauryn Akinmade','laurynakinmade@gmail.com','$2y$10$7RNrci8XQPASSbV/ODLbnucR7osFtMwcIroQ1pv/oEwmcQyaE0eAW','2026-03-20 03:47:33'),
                      (5,'Graci Medina','gracie.medina9@gmail.com','$2y$10$8ievwOb89Ck2mpelqpzU5eBPACGkbvTf6Df9sJWRTC11ppipTGY2.','2026-03-25 13:47:59'),
                      (6,'Chris Gaguancela','gaguancelac@gmail.com','$2y$10$QfzuUMAfsS.39kf4RfVZDO7eVQgbIfxtF54z1WQVfX6PVXhnZHG4u','2026-03-25 14:20:10');

-- CHAT CONVERSATIONS
CREATE TABLE chat_conversations (
                                    id INT AUTO_INCREMENT PRIMARY KEY,
                                    user_id INT NOT NULL,
                                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                                    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                                    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

INSERT INTO chat_conversations VALUES
                                   (1,4,'2026-04-09 15:05:19','2026-04-09 15:05:19'),
                                   (2,4,'2026-04-09 15:09:36','2026-04-09 15:09:36'),
                                   (3,4,'2026-04-09 15:15:27','2026-04-09 15:15:27'),
                                   (4,4,'2026-04-09 15:20:30','2026-04-09 15:20:30'),
                                   (5,4,'2026-04-09 15:25:44','2026-04-09 15:25:44'),
                                   (6,4,'2026-04-09 15:26:45','2026-04-09 15:26:45'),
                                   (7,4,'2026-04-09 15:35:16','2026-04-09 15:35:16');

-- CHAT MESSAGES
CREATE TABLE chat_messages (
                               id INT AUTO_INCREMENT PRIMARY KEY,
                               conversation_id INT NOT NULL,
                               role ENUM('user','assistant') NOT NULL,
                               content TEXT NOT NULL,
                               created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                               FOREIGN KEY (conversation_id) REFERENCES chat_conversations(id) ON DELETE CASCADE
);

INSERT INTO chat_messages VALUES
                              (1,1,'user','dietary advice','2026-04-09 15:05:19'),
                              (2,2,'user','dietary advice','2026-04-09 15:09:36'),
                              (3,3,'user','dietary advice','2026-04-09 15:15:27'),
                              (4,4,'user','recipe','2026-04-09 15:20:30'),
                              (5,5,'user','recipe','2026-04-09 15:25:44'),
                              (6,6,'user','food','2026-04-09 15:26:45'),
                              (7,7,'user','recipe','2026-04-09 15:35:16'),
                              (8,7,'assistant','Hi Lauryn! 👋 I\'d love to help you find a recipe!','2026-04-09 15:35:21');

-- FAVORITES
CREATE TABLE favorites (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    meal_id VARCHAR(20) NOT NULL,
    meal_name VARCHAR(255) NOT NULL,
    meal_thumb VARCHAR(500),
    meal_category VARCHAR(100),
    meal_area VARCHAR(100),
    saved_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE (user_id, meal_id),
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

INSERT INTO favorites VALUES
(1,4,'53329','Arepa pelua','https://www.themealdb.com/images/media/meals/jgl9qq1764437635.jpg','','','2026-03-20 21:52:56'),
(2,4,'53068','Beef Mechado','https://www.themealdb.com/images/media/meals/cgl60b1683206581.jpg','','','2026-03-20 23:44:26'),
(3,4,'53234','Salmon noodle soup','https://www.themealdb.com/images/media/meals/ikizdm1763760862.jpg','','','2026-03-23 20:31:35'),
(4,4,'53147','Arroz con gambas y calamar','https://www.themealdb.com/images/media/meals/jc6oub1763196663.jpg','Seafood','','2026-03-31 23:06:42'),
(5,4,'52848','Bean & Sausage Hotpot','https://www.themealdb.com/images/media/meals/vxuyrx1511302687.jpg','Miscellaneous','','2026-04-01 02:13:47');

-- READY MEALS
CREATE TABLE ready_meals (
    meal_id INT AUTO_INCREMENT PRIMARY KEY,
    meal_name VARCHAR(255),
    meal_image VARCHAR(500)
);

INSERT INTO ready_meals VALUES
(1,'Apple Cinnamon Oatmeal','AppleSmartPlate.jpg'),
(2,'Barbecue Grilled Chicken','BBQSmartPlate.jpg'),
(3,'Chicken Caesar Salad','CaesarSmartPlate.jpg'),
(4,'Roasted Carrots','CarrotSmartPlate.jpg'),
(5,'Mac and Cheese','CheeseSmartPlate.jpg'),
(6,'Mediterranean Chicken Bowl','ChickenSmartPlate.jpg'),
(7,'Egg Roll','EggSmartPlate.jpg'),
(8,'Lasagna','LasagnaSmartPlate.jpg'),
(9,'Lemon-Spiced Salmon','LemonSmartPlate.jpg'),
(10,'Vegetarian Omelette','OmeletteSmartPlate.jpg'),
(11,'Protein Pancakes','PancakeSmartPlate.jpg'),
(12,'Chicken Quesadilla','QuesadillaSmartPlate.jpg'),
(13,'Protein Rice Bowl','RiceSmartPlate.jpg'),
(14,'Beef Tacos','TacoSmartPlate.jpg'),
(15,'Quinoa/Roasted Veg Bowl','VegetableSmartPlate.jpg'),
(16,'Fruit/Yogurt Bowl','YogurtSmartPlate.jpg'),
(17,'Healthy Baked Ziti','ZitiSmartPlate.jpg');

-- INGREDIENTS
CREATE TABLE ingredients (
    ingredient_id INT AUTO_INCREMENT PRIMARY KEY,
    meal_id INT NOT NULL,
    ingredient_name VARCHAR(255) NOT NULL,
    amount VARCHAR(100),
    FOREIGN KEY (meal_id) REFERENCES ready_meals(meal_id) ON DELETE CASCADE
);

INSERT INTO ingredients (meal_id, ingredient_name, amount) VALUES
(1,'Mixed Greens','2 cups'),(1,'Cherry Tomatoes','1/2 cup'),(1,'Balsamic Glaze','1 tbsp'),
(2,'Gala Apples','2 whole'),(2,'Cinnamon','1 tsp'),
(3,'Chicken Breast','6 oz'),(3,'BBQ Sauce','2 tbsp'),(3,'Roasted Corn','1/4 cup'),
(4,'Romaine Lettuce','3 cups'),(4,'Parmesan','2 tbsp'),(4,'Croutons','1/2 cup'),
(5,'Baby Carrots','1 lb'),(5,'Honey','2 tbsp'),(5,'Fresh Parsley','1 tsp');

-- MEAL PLANS
CREATE TABLE meal_plans (
    meal_plan_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    start_date DATE,
    days INT DEFAULT 7,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    plan_date DATE,
    meal_type VARCHAR(50),
    meal_name VARCHAR(255),
    description TEXT,
    emoji VARCHAR(10),
    UNIQUE (user_id, plan_date, meal_type),
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

INSERT INTO meal_plans VALUES
(17,4,NULL,7,'2026-04-15 00:58:57','2026-04-15','Breakfast','Greek Yogurt Parfait','Layered yogurt','🥣');

-- NUTRITION LOGS
CREATE TABLE nutrition_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    log_date DATE,
    meal_type VARCHAR(50),
    food_name VARCHAR(255),
    calories DECIMAL(8,2),
    carbs_g DECIMAL(8,2),
    protein_g DECIMAL(8,2),
    fat_g DECIMAL(8,2),
    source VARCHAR(20),
    logged_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

INSERT INTO nutrition_logs VALUES
(5,4,'2026-04-15','Manual','BANANA',0,0,0,0,'meal_plan','2026-04-15 00:59:25');

-- USER PREFERENCES
CREATE TABLE user_preferences (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    dietary_restrictions VARCHAR(255),
    allergies TEXT,
    calorie_goal INT,
    protein_goal INT,
    carbs_goal INT,
    fat_goal INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

SET FOREIGN_KEY_CHECKS = 1;