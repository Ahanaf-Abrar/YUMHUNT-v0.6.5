-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:8889
-- Generation Time: Sep 20, 2024 at 09:01 AM
-- Server version: 5.7.31
-- PHP Version: 7.3.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


--
-- Database: `yumhunt_db`
--


-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

-- DROP TABLE IF EXISTS `admin`;
CREATE TABLE `admin` (
  `admin_id` int(222) NOT NULL AUTO_INCREMENT,
  `username` varchar(222) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(222) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `role` varchar(222) NOT NULL DEFAULT 'admin',
  PRIMARY KEY (`admin_id`)  
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `username`, `password`, `email`, `date`, `role`) VALUES
(1, 'admin', 'admin', 'admin@gmail.com', '2024-02-20 00:00:00', 'admin');

-- --------------------------------------------------------


--
-- Table structure for table `user`
--

-- DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(244) NOT NULL,
  `lastname` varchar(244) NOT NULL,
  `email` varchar(250) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `signup`
--

-- INSERT INTO `user` (`user_id`, `firstname`, `lastname`, `email`, `password_hash`) VALUES
-- (1, 'Ahanaf', 'Abrar', 'ahanaf@example.com', 'duckduck'),
-- (2, 'Nahian', 'Fiza', 'fiza@example.com', 'cutiefiza'),
-- (3, 'Munira', 'Rawshan', 'munira@example.com', 'realraw');

INSERT INTO `user` (`user_id`, `firstname`, `lastname`, `email`, `password_hash`) VALUES
(1, 'Ahanaf', 'Abrar', 'ahanaf@example.com', '$2y$10$abcdefghijklmnopqrstuv'),  
(2, 'Nahian', 'Fiza', 'fiza@example.com', '$2y$10$wxyzabcdefghijklmnopqrs'),
(3, 'Munira', 'Rawshan', 'munira@example.com', '$2y$10$tuvwxyzabcdefghijklmnop');

-- --------------------------------------------------------

--
-- Table structure for table `recipe`
--

-- DROP TABLE IF EXISTS `recipe`;
CREATE TABLE IF NOT EXISTS `recipe` (
  `recipe_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(222) NOT NULL,
  `description` text NOT NULL,
  `cooking_time` int(11) NOT NULL,
  `image` varchar(222) NOT NULL,
  `instructions` text NOT NULL,
  `video_url` varchar(222) NOT NULL,
  `nutrition_info` json NOT NULL, 
  PRIMARY KEY (`recipe_id`) 
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4;


--
-- Dumping data for table `recipe`
--

INSERT INTO `recipe` (`recipe_id`, `title`, `description`, `cooking_time`, `image`, `instructions`, `video_url`, `nutrition_info`) VALUES
(1, 'Spaghetti Carbonara', 'A classic Italian dish with creamy sauce and bacon.', 30, 'spaghetti_carbonara.jpg', 'Boil spaghetti. Cook bacon. Mix in sauce. Serve.', 'https://www.youtube.com/watch?v=1234567890', 
'{"calories": 600, "macronutrients": {"carbs": {"total": 75, "fiber": 4, "sugar": 3}, "protein": 25, "fat": {"total": 30, "saturated": 12, "unsaturated": 18}}, "micronutrients": {"vitamins": {"A": 5, "C": 2, "D": 0, "E": 3, "K": 10}, "minerals": {"calcium": 15, "iron": 10, "potassium": 6, "sodium": 20}}, "serving_size": "1 plate (350g)", "servings_per_recipe": 4}'),

(2, 'Chicken Parmesan', 'A comforting dish with breaded chicken and marinara sauce.', 45, 'chicken_parmesan.jpg', 'Bread chicken. Bake. Serve with sauce.', 'https://www.youtube.com/watch?v=2345678901', 
'{"calories": 550, "macronutrients": {"carbs": {"total": 40, "fiber": 3, "sugar": 6}, "protein": 45, "fat": {"total": 25, "saturated": 8, "unsaturated": 17}}, "micronutrients": {"vitamins": {"A": 15, "C": 20, "D": 0, "E": 8, "K": 5}, "minerals": {"calcium": 20, "iron": 15, "potassium": 10, "sodium": 25}}, "serving_size": "1 piece (300g)", "servings_per_recipe": 4}'),

(3, 'Beef Tacos', 'A quick and easy taco recipe with seasoned beef and soft tortillas.', 20, 'beef_tacos.jpg', 'Cook beef. Warm tortillas. Serve with toppings.', 'https://www.youtube.com/watch?v=3456789012', 
'{"calories": 450, "macronutrients": {"carbs": {"total": 35, "fiber": 3, "sugar": 2}, "protein": 28, "fat": {"total": 22, "saturated": 9, "unsaturated": 13}}, "micronutrients": {"vitamins": {"A": 10, "C": 8, "D": 0, "E": 5, "K": 15}, "minerals": {"calcium": 10, "iron": 15, "potassium": 8, "sodium": 18}}, "serving_size": "2 tacos (250g)", "servings_per_recipe": 4}'),

(4, 'Vegetable Stir-Fry', 'A healthy and colorful stir-fry with a variety of vegetables.', 15, 'vegetable_stir_fry.jpg', 'Stir-fry vegetables. Serve with sauce.', 'https://www.youtube.com/watch?v=4567890123', 
'{"calories": 250, "macronutrients": {"carbs": {"total": 30, "fiber": 8, "sugar": 10}, "protein": 10, "fat": {"total": 12, "saturated": 2, "unsaturated": 10}}, "micronutrients": {"vitamins": {"A": 80, "C": 100, "D": 0, "E": 15, "K": 70}, "minerals": {"calcium": 8, "iron": 15, "potassium": 20, "sodium": 10}}, "serving_size": "1 cup (200g)", "servings_per_recipe": 4}'),

(5, 'Grilled Salmon with Lemon-Dill Sauce', 'A light and refreshing dish with grilled salmon and a lemon-dill sauce.', 25, 'grilled_salmon.jpg', 'Grill salmon. Mix sauce. Serve.', 'https://www.youtube.com/watch?v=5678901234', 
'{"calories": 400, "macronutrients": {"carbs": {"total": 5, "fiber": 1, "sugar": 2}, "protein": 40, "fat": {"total": 25, "saturated": 5, "unsaturated": 20}}, "micronutrients": {"vitamins": {"A": 5, "C": 15, "D": 100, "E": 10, "K": 5}, "minerals": {"calcium": 5, "iron": 8, "potassium": 15, "sodium": 12}}, "serving_size": "1 fillet (180g)", "servings_per_recipe": 4}'),

(6, 'Mushroom Risotto', 'A creamy Italian rice dish with savory mushrooms.', 40, 'mushroom_risotto.jpg', 'Sauté mushrooms. Cook rice slowly with broth. Stir until creamy.', 'https://www.youtube.com/watch?v=6789012345', 
'{"calories": 450, "macronutrients": {"carbs": {"total": 65, "fiber": 3, "sugar": 2}, "protein": 10, "fat": {"total": 18, "saturated": 7, "unsaturated": 11}}, "micronutrients": {"vitamins": {"A": 5, "C": 2, "D": 0, "E": 2, "K": 8}, "minerals": {"calcium": 5, "iron": 10, "potassium": 5, "sodium": 15}}, "serving_size": "1 cup (250g)", "servings_per_recipe": 4}'),

(7, 'Quinoa Salad with Roasted Vegetables', 'A nutritious and colorful salad with protein-rich quinoa and roasted veggies.', 35, 'quinoa_salad.jpg', 'Cook quinoa. Roast vegetables. Mix and serve with dressing.', 'https://www.youtube.com/watch?v=7890123456', 
'{"calories": 350, "macronutrients": {"carbs": {"total": 45, "fiber": 7, "sugar": 5}, "protein": 12, "fat": {"total": 18, "saturated": 2, "unsaturated": 16}}, "micronutrients": {"vitamins": {"A": 50, "C": 60, "D": 0, "E": 20, "K": 80}, "minerals": {"calcium": 8, "iron": 20, "potassium": 15, "sodium": 5}}, "serving_size": "1 bowl (300g)", "servings_per_recipe": 4}'),

(8, 'Beef and Broccoli Stir-Fry', 'A quick and flavorful Asian-inspired dish with tender beef and crisp broccoli.', 25, 'beef_broccoli.jpg', 'Slice beef. Stir-fry with broccoli. Add sauce and serve over rice.', 'https://www.youtube.com/watch?v=8901234567', 
'{"calories": 400, "macronutrients": {"carbs": {"total": 30, "fiber": 4, "sugar": 6}, "protein": 35, "fat": {"total": 20, "saturated": 6, "unsaturated": 14}}, "micronutrients": {"vitamins": {"A": 40, "C": 120, "D": 0, "E": 10, "K": 100}, "minerals": {"calcium": 10, "iron": 25, "potassium": 20, "sodium": 15}}, "serving_size": "1 plate (350g)", "servings_per_recipe": 4}'),

(9, 'Lemon Garlic Roasted Chicken', 'A classic roasted chicken infused with zesty lemon and aromatic garlic.', 75, 'lemon_chicken.jpg', 'Season chicken. Stuff with lemon and garlic. Roast until golden.', 'https://www.youtube.com/watch?v=9012345678', 
'{"calories": 300, "macronutrients": {"carbs": {"total": 5, "fiber": 1, "sugar": 2}, "protein": 40, "fat": {"total": 15, "saturated": 4, "unsaturated": 11}}, "micronutrients": {"vitamins": {"A": 6, "C": 10, "D": 0, "E": 2, "K": 5}, "minerals": {"calcium": 2, "iron": 10, "potassium": 12, "sodium": 8}}, "serving_size": "1 chicken breast (180g)", "servings_per_recipe": 4}'),

(10, 'Vegetarian Chili', 'A hearty and spicy chili packed with beans and vegetables.', 50, 'vegetarian_chili.jpg', 'Sauté vegetables. Add beans and tomatoes. Simmer with spices.', 'https://www.youtube.com/watch?v=0123456789', 
'{"calories": 280, "macronutrients": {"carbs": {"total": 45, "fiber": 12, "sugar": 8}, "protein": 15, "fat": {"total": 8, "saturated": 1, "unsaturated": 7}}, "micronutrients": {"vitamins": {"A": 30, "C": 40, "D": 0, "E": 10, "K": 20}, "minerals": {"calcium": 15, "iron": 25, "potassium": 20, "sodium": 10}}, "serving_size": "1 bowl (300g)", "servings_per_recipe": 6}'),

(11, 'Shrimp Pad Thai', 'A popular Thai noodle dish with succulent shrimp and a tangy sauce.', 30, 'shrimp_pad_thai.jpg', 'Cook noodles. Stir-fry shrimp and vegetables. Toss with sauce and noodles.', 'https://www.youtube.com/watch?v=1234509876', 
'{"calories": 420, "macronutrients": {"carbs": {"total": 55, "fiber": 3, "sugar": 10}, "protein": 25, "fat": {"total": 16, "saturated": 3, "unsaturated": 13}}, "micronutrients": {"vitamins": {"A": 15, "C": 20, "D": 0, "E": 8, "K": 30}, "minerals": {"calcium": 10, "iron": 15, "potassium": 8, "sodium": 25}}, "serving_size": "1 plate (350g)", "servings_per_recipe": 4}'),

(12, 'Spinach and Feta Stuffed Chicken Breast', 'Juicy chicken breast filled with a creamy spinach and feta mixture.', 40, 'stuffed_chicken.jpg', 'Butterfly chicken breasts. Stuff with spinach and feta. Bake until cooked through.', 'https://www.youtube.com/watch?v=2345610987', 
'{"calories": 350, "macronutrients": {"carbs": {"total": 5, "fiber": 2, "sugar": 1}, "protein": 45, "fat": {"total": 18, "saturated": 6, "unsaturated": 12}}, "micronutrients": {"vitamins": {"A": 70, "C": 15, "D": 0, "E": 10, "K": 120}, "minerals": {"calcium": 15, "iron": 20, "potassium": 10, "sodium": 18}}, "serving_size": "1 stuffed breast (200g)", "servings_per_recipe": 4}');


-- --------------------------------------------------------

--
-- Table structure for table `ingredient`
--

-- DROP TABLE IF EXISTS `ingredient`;
CREATE TABLE IF NOT EXISTS `ingredient` (
  `ingredient_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(222) NOT NULL,
  `quantity` varchar(222) NOT NULL,
  `unit` varchar(222) NOT NULL,
  `category` varchar(255) NOT NULL,
  PRIMARY KEY (`ingredient_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `ingredient`
--

INSERT INTO `ingredient` (`ingredient_id`, `name`, `quantity`, `unit`, `category`) VALUES
(1, 'Spaghetti', '400', 'g', 'Carbohydrate'),
(2, 'Pancetta', '150', 'g', 'Protein'),
(3, 'Eggs', '4', 'piece', 'Protein'),
(4, 'Parmesan cheese', '50', 'g', 'Cheese'),
(5, 'Black pepper', '1', 'tsp', 'Spice'),
(6, 'Chicken breast', '4', 'piece', 'Protein'),
(7, 'Breadcrumbs', '200', 'g', 'Carbohydrate'),
(8, 'Marinara sauce', '500', 'ml', 'Sauce'),
(9, 'Mozzarella cheese', '200', 'g', 'Cheese'), 
(10, 'Olive oil', '2', 'tbsp', 'Oil'),

(11, 'Ground beef', '500', 'g', 'Protein'),
(12, 'Taco seasoning', '2', 'tbsp', 'Spice'),
(13, 'Tortillas', '8', 'piece', 'Carbohydrate'),

(14, 'Lettuce', '1', 'head', 'Vegetable'),
(15, 'Tomatoes', '2', 'piece', 'Vegetable'),
(16, 'Mixed vegetables', '500', 'g', 'Vegetable'),

(17, 'Soy sauce', '3', 'tbsp', 'Sauce'),
(18, 'Garlic', '3', 'clove', 'Spice'),
(19, 'Ginger', '1', 'tbsp', 'Spice'),

(20, 'Sesame oil', '1', 'tbsp', 'Oil'),
(21, 'Salmon fillet', '4', 'piece', 'Protein'),
(22, 'Lemon', '2', 'piece', 'Fruit'),

(23, 'Dill', '2', 'tbsp', 'Spice'),
(24, 'Greek yogurt', '200', 'g', 'Dairy'),
(25, 'Olive oil', '2', 'tbsp', 'Oil'),

(26, 'Arborio rice', '300', 'g', 'Carbohydrate'),
(27, 'Mushrooms', '300', 'g', 'Vegetable'),
(28, 'Vegetable broth', '1', 'l', 'Liquid'),

(29, 'Onion', '1', 'piece', 'Vegetable'),
(30, 'Parmesan cheese', '50', 'g', 'Cheese'),
(31, 'Quinoa', '200', 'g', 'Carbohydrate'),

(32, 'Mixed vegetables', '400', 'g', 'Vegetable'),
(33, 'Olive oil', '3', 'tbsp', 'Oil'),
(34, 'Lemon juice', '2', 'tbsp', 'Liquid'),

(35, 'Feta cheese', '100', 'g', 'Cheese'),
(36, 'Beef sirloin', '400', 'g', 'Protein'),
(37, 'Broccoli', '300', 'g', 'Vegetable'),

(38, 'Soy sauce', '3', 'tbsp', 'Liquid'),
(39, 'Cornstarch', '1', 'tbsp', 'Spice'),
(40, 'Garlic', '2', 'clove', 'Spice'),

(41, 'Whole chicken', '1', 'piece', 'Protein'),
(42, 'Lemon', '2', 'piece', 'Fruit'),
(43, 'Garlic', '1', 'head', 'Spice'),

(44, 'Rosemary', '2', 'sprig', 'Spice'),
(45, 'Olive oil', '3', 'tbsp', 'Oil'),
(46, 'Mixed beans', '500', 'g', 'Carbohydrate'),

(47, 'Diced tomatoes', '400', 'g', 'Vegetable'),
(48, 'Onion', '1', 'piece', 'Vegetable'),
(49, 'Bell pepper', '2', 'piece', 'Vegetable'),

(50, 'Chili powder', '2', 'tbsp', 'Spice'),
(51, 'Rice noodles', '250', 'g', 'Carbohydrate'),
(52, 'Shrimp', '300', 'g', 'Protein'),

(53, 'Eggs', '2', 'piece', 'Protein'),
(54, 'Bean sprouts', '100', 'g', 'Vegetable'),
(55, 'Peanuts', '50', 'g', 'Protein'),

(56, 'Chicken breast', '4', 'piece', 'Protein'),
(57, 'Spinach', '200', 'g', 'Vegetable'),
(58, 'Feta cheese', '150', 'g', 'Cheese'),

(59, 'Garlic', '2', 'clove', 'Spice'),
(60, 'Olive oil', '2', 'tbsp', 'Oil');

-- --------------------------------------------------------

--
-- Table structure for table `recipe_ingredient`
--

-- DROP TABLE IF EXISTS `recipe_ingredient`;
CREATE TABLE IF NOT EXISTS `recipe_ingredient` (
  `recipe_id` int(11) NOT NULL,
  `ingredient_id` int(11) NOT NULL,
  `quantity` decimal(10,2) NOT NULL,        
  `unit` varchar(50) NOT NULL,
  PRIMARY KEY (`recipe_id`,`ingredient_id`),
  FOREIGN KEY (`recipe_id`) REFERENCES `recipe`(`recipe_id`) ON DELETE CASCADE,
  FOREIGN KEY (`ingredient_id`) REFERENCES `ingredient`(`ingredient_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--  
-- Dumping data for table `recipe_ingredient`
--  

INSERT INTO `recipe_ingredient` (`recipe_id`, `ingredient_id`, `quantity`, `unit`) VALUES
(1, 1, 400.00, 'g'),
(1, 2, 150.00, 'g'),
(1, 3, 4.00, 'piece'),
(1, 4, 50.00, 'g'),
(1, 5, 1.00, 'tsp'),

(2, 6, 4.00, 'piece'),
(2, 7, 200.00, 'g'),
(2, 8, 500.00, 'ml'),
(2, 9, 200.00, 'g'),
(2, 10, 2.00, 'tbsp'),

(3, 11, 500.00, 'g'),
(3, 12, 2.00, 'tbsp'),
(3, 13, 8.00, 'piece'),
(3, 14, 1.00, 'head'),
(3, 15, 2.00, 'piece'),

(4, 16, 500.00, 'g'),
(4, 17, 3.00, 'tbsp'),
(4, 18, 3.00, 'clove'),
(4, 19, 1.00, 'tbsp'),
(4, 20, 1.00, 'tbsp'),

(5, 21, 4.00, 'piece'),
(5, 22, 2.00, 'piece'),
(5, 23, 2.00, 'tbsp'),
(5, 24, 200.00, 'g'),
(5, 25, 2.00, 'tbsp'),

(6, 26, 300.00, 'g'),
(6, 27, 300.00, 'g'),  
(6, 28, 1.00, 'l'),
(6, 29, 1.00, 'piece'),
(6, 30, 50.00, 'g'),

(7, 31, 200.00, 'g'),
(7, 32, 400.00, 'g'),
(7, 33, 3.00, 'tbsp'),
(7, 34, 2.00, 'tbsp'),
(7, 35, 100.00, 'g'),

(8, 36, 400.00, 'g'),
(8, 37, 300.00, 'g'),
(8, 38, 3.00, 'tbsp'),
(8, 39, 1.00, 'tbsp'),
(8, 40, 2.00, 'clove'),

(9, 41, 1.00, 'piece'),
(9, 42, 2.00, 'piece'),
(9, 43, 1.00, 'head'),
(9, 44, 2.00, 'sprig'),
(9, 45, 3.00, 'tbsp'),

(10, 46, 500.00, 'g'),
(10, 47, 400.00, 'g'),
(10, 48, 1.00, 'piece'),
(10, 49, 2.00, 'piece'),
(10, 50, 2.00, 'tbsp'),

(11, 51, 250.00, 'g'),
(11, 52, 300.00, 'g'),
(11, 53, 2.00, 'piece'),
(11, 54, 100.00, 'g'),
(11, 55, 50.00, 'g'),

(12, 56, 4.00, 'piece'),
(12, 57, 200.00, 'g'),
(12, 58, 150.00, 'g'),
(12, 59, 2.00, 'clove'),
(12, 60, 2.00, 'tbsp');

-- --------------------------------------------------------

--
-- Table structure for table `meal_plan`
--

-- DROP TABLE IF EXISTS `meal_plan`;
CREATE TABLE IF NOT EXISTS `meal_plan` (
  `meal_plan_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `start_date` date NOT NULL,       
  `end_date` date NOT NULL,
  PRIMARY KEY (`meal_plan_id`),
  FOREIGN KEY (`user_id`) REFERENCES `user`(`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -------------------------------------------------------- 

--
-- Table structure for table `meal_plan_recipe`
--

-- DROP TABLE IF EXISTS `meal_plan_recipe`;
CREATE TABLE IF NOT EXISTS `meal_plan_recipe` (
  `meal_plan_id` int(11) NOT NULL,
  `recipe_id` int(11) NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`meal_plan_id`,`recipe_id`),
  FOREIGN KEY (`meal_plan_id`) REFERENCES `meal_plan`(`meal_plan_id`) ON DELETE CASCADE,
  FOREIGN KEY (`recipe_id`) REFERENCES `recipe`(`recipe_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `ingredient_inventory`
--  

-- DROP TABLE IF EXISTS `ingredient_inventory`;
CREATE TABLE IF NOT EXISTS `ingredient_inventory` (
  `ingredient_id` int(11) NOT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `unit` varchar(50) NOT NULL,
  PRIMARY KEY (`ingredient_id`),
  FOREIGN KEY (`ingredient_id`) REFERENCES `ingredient`(`ingredient_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    
-- --------------------------------------------------------

--
-- Table structure for table `shopping_list`
--

-- DROP TABLE IF EXISTS `shopping_list`;
CREATE TABLE IF NOT EXISTS `shopping_list` (
  `ingredient_id` int(11) NOT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `unit` varchar(50) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`user_id`, `ingredient_id`),
  FOREIGN KEY (`ingredient_id`) REFERENCES `ingredient`(`ingredient_id`) ON DELETE CASCADE,
  FOREIGN KEY (`user_id`) REFERENCES `user`(`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- --------------------------------------------------------

--
-- Table structure for table `user_preferences`
--

-- DROP TABLE IF EXISTS `user_preferences`;
CREATE TABLE IF NOT EXISTS `user_preferences` (
  `user_id` int(11) NOT NULL,
  `cuisine_preference` varchar(255) NOT NULL,
  `dietary_preference` varchar(255) NOT NULL,   
  PRIMARY KEY (`user_id`),
  FOREIGN KEY (`user_id`) REFERENCES `user`(`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `user_activity`
--

-- DROP TABLE IF EXISTS `user_activity`;
CREATE TABLE IF NOT EXISTS `user_activity` (
  `user_id` int(11) NOT NULL,
  `activity_date` date NOT NULL,
  `activity_type` varchar(255) NOT NULL,
  `calories_burned` decimal(10,2) NOT NULL,
  PRIMARY KEY (`user_id`,`activity_date`),
  FOREIGN KEY (`user_id`) REFERENCES `user`(`user_id`) ON DELETE CASCADE   
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `recipe_reviews`
--

-- DROP TABLE IF EXISTS `recipe_reviews`;  
CREATE TABLE IF NOT EXISTS `recipe_reviews` (
  `review_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `recipe_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `review_text` text NOT NULL,
  `review_date` date NOT NULL,
  PRIMARY KEY (`review_id`),    
  FOREIGN KEY (`user_id`) REFERENCES `user`(`user_id`) ON DELETE CASCADE,
  FOREIGN KEY (`recipe_id`) REFERENCES `recipe`(`recipe_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `commentbar`
--

-- DROP TABLE IF EXISTS `commentbar`;
-- CREATE TABLE IF NOT EXISTS `commentbar` (
--   `comment_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
--   `user_id` int(11) NOT NULL,
--   `text` text NOT NULL,
--   `date_time` datetime NOT NULL,
--   `recipe_id` int(11) NOT NULL,
--   PRIMARY KEY (`comment_id`),
--   KEY `user_id` (`user_id`),
--   KEY `recipe_id` (`recipe_id`),
--   FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE,
--   FOREIGN KEY (`recipe_id`) REFERENCES `recipe` (`recipe_id`) ON DELETE CASCADE
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- --
-- -- Dumping data for table `commentbar`
-- --

-- INSERT INTO `commentbar` (`comment_id`, `user_id`, `text`, `date_time`, `recipe_id`) VALUES
-- (1, 1, 'This recipe is amazing! I love how easy it is to follow.', '2024-03-01 14:30:00', 1),
-- (2, 2, 'I made this for my family and they loved it. Will definitely make it again!', '2024-03-02 18:45:00', 1),
-- (3, 1, 'Great flavors, but I found it a bit too spicy. Maybe I''ll use less chili next time.', '2024-03-03 20:15:00', 2),
-- (4, 3, 'Perfect for a quick weeknight dinner. Thanks for sharing!', '2024-03-04 19:00:00', 3),
-- (5, 2, 'I substituted chicken for tofu and it turned out great. Very versatile recipe.', '2024-03-05 12:30:00', 4);


-- --------------------------------------------------------