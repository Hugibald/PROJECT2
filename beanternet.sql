-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 18, 2025 at 10:33 AM
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
-- Database: `beanternet`
--
CREATE DATABASE IF NOT EXISTS `beanternet` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `beanternet`;

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart_items`
--

INSERT INTO `cart_items` (`user_id`, `product_id`, `quantity`) VALUES
(1, 5, 1),
(1, 8, 3),
(1, 12, 2);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `order_date` date NOT NULL,
  `order_status` enum('pending','shipped','delivered','paid') NOT NULL DEFAULT 'pending',
  `total_cost` decimal(8,2) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `order_date`, `order_status`, `total_cost`, `user_id`) VALUES
(1, '2025-12-02', 'delivered', 351.90, 3),
(2, '2025-12-15', 'shipped', 64.95, 1),
(3, '2025-12-18', 'paid', 25.74, 1);

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `order_item_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `price` decimal(10,2) NOT NULL,
  `discount` int(11) DEFAULT NULL,
  `supplier_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`order_item_id`, `order_id`, `product_id`, `quantity`, `price`, `discount`, `supplier_id`) VALUES
(1, 1, 10, 10, 119.50, NULL, 4),
(2, 1, 11, 10, 127.50, NULL, 4),
(3, 1, 12, 10, 104.90, NULL, 4),
(4, 2, 1, 5, 64.95, NULL, 1),
(5, 2, 2, 1, 12.49, NULL, 3),
(6, 2, 7, 1, 13.25, NULL, 4);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `product_picture` varchar(255) NOT NULL,
  `strength` enum('mild','balanced','bold') NOT NULL,
  `aroma` enum('spicy','chocolaty','nutty','fruity','dark','light') NOT NULL,
  `price` decimal(6,2) NOT NULL,
  `discount` decimal(4,2) DEFAULT NULL,
  `supplier_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `name`, `description`, `product_picture`, `strength`, `aroma`, `price`, `discount`, `supplier_id`) VALUES
(1, 'Andean Sunrise Roast', 'A vibrant Colombian coffee from the Sierra Alta Cooperative. Notes of caramel, citrus, and a smooth medium body.', 'andean_sunrise.png', 'balanced', 'fruity', 12.99, NULL, 1),
(2, 'Condor Crest Medium Roast', 'A smooth and balanced Colombian roast boasting cocoa warmth and subtle stone-fruit sweetness.', 'condor_crest.png', 'balanced', 'chocolaty', 12.49, NULL, 1),
(3, 'Golden Valley Caramel Blend', 'A naturally sweet Colombian blend featuring caramel richness and mellow acidity.', 'golden_valley.png', 'mild', 'light', 11.99, NULL, 1),
(4, 'Abyssinian Night Bloom', 'A rich Ethiopian single-origin coffee with floral aromatics and bright blueberry notes.', 'abyssinian_night_bloom.png', 'bold', 'spicy', 14.49, NULL, 2),
(5, 'Solstice Berry Reverie', 'A fruity Ethiopian roast bursting with crisp berry notes, silky florals, and a refreshing finish.', 'solstice_berry_reverie.png', 'bold', 'nutty', 14.25, NULL, 2),
(6, 'Highland Floral Whisper', 'A delicate light roast with jasmine aroma and honey sweetness from Ethiopian highland micro-lots.', 'highland_floral_whisper.png', 'mild', 'light', 13.95, NULL, 2),
(7, 'Mayan Twilight Roast', 'A smooth Guatemalan medium roast with notes of milk chocolate, orange zest, and gentle nutty sweetness sourced from high-altitude farms.', 'mayan_twilight.png', 'balanced', 'chocolaty', 13.25, NULL, 3),
(8, 'Cumbre Volcánica Dark Blend', 'A bold, full-bodied dark roast grown in volcanic soil. Expect smoky depth, dark cocoa, and a clean finish.', 'cumbre_volcanica.png', 'bold', 'dark', 13.99, NULL, 3),
(9, 'Guatemalan Jade Reserve', 'A premium high-elevation Guatemalan coffee offering bright green-apple acidity, caramel sweetness, and a silky mouthfeel.', 'guatemalan_jade_reserve.png', 'bold', 'fruity', 15.75, NULL, 3),
(10, 'Lotus Crest Dark Roast', 'A bold Vietnamese roast crafted from Central Highlands beans. Expect deep earthy flavors with dark cocoa and subtle spice.', 'lotus_crest_dark.png', 'bold', 'dark', 11.95, NULL, 4),
(11, 'Saigon Silk Medium Roast', 'A smooth and refined Arabica-forward roast with silky mouthfeel, gentle fruit sweetness, and a balanced finish.', 'saigon_silk.png', 'balanced', 'fruity', 12.75, NULL, 4),
(12, 'Highland Morning Blossom', 'A gentle, mild Vietnamese roast highlighting floral aromatics, soft sweetness, and a bright yet delicate character.', 'highland_morning_blossom.png', 'mild', 'light', 10.49, NULL, 4);

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `question_id` int(11) NOT NULL,
  `question_date` date DEFAULT NULL,
  `question` text DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `answer` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`question_id`, `question_date`, `question`, `user_id`, `product_id`, `answer`) VALUES
(1, '2025-12-05', 'How much Coffee is there per package?', 4, 7, 'The packages are 500g each.'),
(2, '2025-12-08', 'How does shipping work? Will the coffee be packed in a carton-box?', 4, 6, 'Yes, we pack our coffee very carefully in suitable cardboard-boxes, so that it arrives at your location in good shape and ready to be enjoyed by you.'),
(3, '2025-12-18', 'How do you know so much about your suppliers?', 1, 11, '');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `review_id` int(11) NOT NULL,
  `rating` enum('1','2','3','4','5') NOT NULL,
  `rating_text` text DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `order_item_id` int(11) NOT NULL,
  `review_date` date NOT NULL,
  `review_ok` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`review_id`, `rating`, `rating_text`, `user_id`, `order_item_id`, `review_date`, `review_ok`) VALUES
(1, '5', 'I didn\'t know coffee can taste this good', 3, 2, '2025-12-09', 1),
(2, '4', 'I can\'t wait for my delivery!', 1, 1, '2025-12-15', 0);

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `supplier_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `location` enum('Africa','South America','Asia') NOT NULL,
  `story` text NOT NULL,
  `supplier_picture` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`supplier_id`, `name`, `location`, `story`, `supplier_picture`) VALUES
(1, 'Sierra Alta Cooperative', 'South America', 'A family-owned Colombian coffee cooperative dedicated to sustainable, high-quality Arabica beans from the Andes.', 'sierra_alta.jpg'),
(2, 'Terra Nera Estates', 'Africa', 'An Ethiopian estate producing single-origin coffees with vibrant floral and fruity notes, harvested from highland micro-lots.', 'terra_nera.jpg'),
(3, 'Finca Cielo Claro', 'South America', 'A Guatemalan farm in the highlands, cultivating premium Arabica beans with unique flavors influenced by volcanic soils.', 'finca_cielo_claro.jpg'),
(4, 'Red Lotus Highlands', 'Asia', 'A Vietnamese coffee estate nestled in the misty Central Highlands, known for rich, earthy Robusta and increasingly refined Arabica micro-lots.', 'red_lotus_highlands.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(150) NOT NULL,
  `user_password` varchar(255) NOT NULL,
  `user_picture` varchar(255) DEFAULT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  `address` varchar(255) DEFAULT NULL,
  `ZIP` varchar(15) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `country` varchar(50) DEFAULT NULL,
  `user_status` enum('free','warned','blocked','banned') NOT NULL DEFAULT 'free'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `first_name`, `last_name`, `email`, `user_password`, `user_picture`, `role`, `address`, `ZIP`, `city`, `country`, `user_status`) VALUES
(1, 'user', 'user', 'user@user.user', '$2y$10$S55qlmfgLK1WuJOmitjYuuzAAdw4F7633hsaLw3Nkd./2aQ9V3tFa', '69416112947a2.png', 'user', 'userstreet', '1234', 'Vienna', 'Österreich', 'free'),
(2, 'admin', 'admin', 'admin@admin.admin', '$2y$10$KmstbV8nY2zyr2NeYErAZO2YuBko3m46UBbAlH/QI1E.5wgXtdyKG', '694160f94d758.png', 'admin', '', '', '', '', 'free'),
(3, 'blocked', 'blocked', 'blocked@blocked.blocked', '$2y$10$gBLXaFKFEErHnM0MiF1F.O1PsY0pehCKsTPcG9ZsIgmNw10eHoppC', 'avatar.png', 'user', NULL, NULL, NULL, NULL, 'blocked'),
(4, 'banned', 'banned', 'banned@banned.banned', '$2y$10$maCEOBlaNckYoLvYLa0HueLxgAs6Yg2K.rp5O3uT0F6zYKeoJWM9a', 'avatar.png', 'user', NULL, NULL, NULL, NULL, 'banned');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`user_id`,`product_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `fk_order_items_supplier` (`supplier_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD UNIQUE KEY `product_picture` (`product_picture`),
  ADD KEY `supplier_id` (`supplier_id`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`question_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `order_item_id` (`order_item_id`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`supplier_id`);

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
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `question_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `supplier_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `cart_items_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cart_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `fk_order_items_supplier` FOREIGN KEY (`supplier_id`) REFERENCES `supplier` (`supplier_id`),
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `supplier` (`supplier_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `questions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `questions_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`order_item_id`) REFERENCES `order_items` (`order_item_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
