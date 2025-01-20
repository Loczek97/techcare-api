-- phpMyAdmin SQL Dump
-- version 5.2.1-5.fc41
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Sty 20, 2025 at 08:00 AM
-- Wersja serwera: 10.11.10-MariaDB
-- Wersja PHP: 8.3.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `techcare_db`
--
CREATE DATABASE IF NOT EXISTS `techcare_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `techcare_db`;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `complaints`
--

CREATE TABLE `complaints` (
  `complaint_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `complaint_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `complaint_description` text NOT NULL,
  `complaint_status` varchar(50) NOT NULL,
  `complaints_return_message` varchar(100) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `technician_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `complaints`
--

INSERT INTO `complaints` (`complaint_id`, `order_id`, `complaint_date`, `complaint_description`, `complaint_status`, `complaints_return_message`, `updated_at`, `technician_id`) VALUES
(12, 17, '2025-01-14 15:57:59', 'Nie dziala', 'Zakończone', 'U nas dziala masz pan problem', '2025-01-16 13:42:14', 15),
(13, 16, '2025-01-14 15:59:02', 'Nie wlacza sie', 'W trakcie realizacji', 'Wyslane do serwisu producenta\r\n', '2025-01-16 13:49:12', 15);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `invoices`
--

CREATE TABLE `invoices` (
  `invoice_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `invoice_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `file_path` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`invoice_id`, `order_id`, `invoice_date`, `file_path`) VALUES
(13, 14, '2025-01-16 15:29:43', '/var/www/html/techcare-api/controllers/tech_controllers/../../invoices/faktura_14.pdf'),
(14, 15, '2025-01-16 15:29:46', '/var/www/html/techcare-api/controllers/tech_controllers/../../invoices/faktura_15.pdf'),
(15, 24, '2025-01-16 15:55:13', '/var/www/html/techcare-api/controllers/tech_controllers/../../invoices/faktura_24.pdf');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` varchar(50) NOT NULL,
  `problem_description` text DEFAULT NULL,
  `device_type` varchar(50) NOT NULL,
  `short_specification` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `technician_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `status`, `problem_description`, `device_type`, `short_specification`, `created_at`, `updated_at`, `technician_id`) VALUES
(14, 16, 'Zakończone', 'pierwsze', 'Laptop', 'bmw 12v2', '2025-01-02 15:38:11', '2025-01-04 20:12:19', 15),
(15, 16, 'Zakończone', 'drugie', 'Laptop', 'gdfgdfgdfg', '2025-01-02 15:38:18', '2025-01-05 17:46:05', 15),
(16, 16, 'Zakończone', 'trzecie', 'Konsola do gier', 'dgfdfgdfg', '2025-01-02 15:38:27', '2025-01-02 19:04:02', 15),
(17, 16, 'Zakończone', 'czwarte', 'Laptop', 'i5/8GB/512SSD', '2025-01-02 15:38:35', '2025-01-16 13:50:28', 15),
(18, 16, 'Zakończone', 'piate', 'Komputer stacjonarny', 'dgfdfgdfg', '2025-01-02 15:38:41', '2025-01-05 18:12:49', 15),
(19, 16, 'Zakończone', 'Dodaje zlecenie', 'Komputer stacjonarny', 'Zlecenie testowe', '2025-01-11 21:36:18', '2025-01-16 13:50:41', 15),
(20, 16, 'Zakończone', 'do opinii', 'Komputer stacjonarny', 'bmw 12v2', '2025-01-11 22:12:53', '2025-01-11 22:13:17', 15),
(24, 16, 'Zakończone', 'Laptop sie nie wlacza', 'Laptop', 'Lenovo ideapad 3', '2025-01-16 15:48:24', '2025-01-16 15:55:13', 15);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `order_parts`
--

CREATE TABLE `order_parts` (
  `order_id` int(11) NOT NULL,
  `part_id` int(11) NOT NULL,
  `part_quantity` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_parts`
--

INSERT INTO `order_parts` (`order_id`, `part_id`, `part_quantity`) VALUES
(14, 3, 2),
(15, 1, 1),
(15, 16, 1),
(17, 1, 1),
(17, 4, 1),
(18, 2, 1),
(18, 6, 1),
(19, 1, 1),
(19, 5, 1),
(20, 1, 1),
(24, 4, 1),
(24, 6, 1);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `order_services`
--

CREATE TABLE `order_services` (
  `order_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_services`
--

INSERT INTO `order_services` (`order_id`, `service_id`) VALUES
(14, 20),
(15, 1),
(15, 3),
(15, 7),
(17, 5),
(17, 21),
(18, 12),
(18, 21),
(19, 3),
(19, 21),
(20, 1),
(24, 1);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `parts`
--

CREATE TABLE `parts` (
  `part_id` int(11) NOT NULL,
  `part_name` varchar(100) NOT NULL,
  `category` varchar(255) DEFAULT NULL,
  `quantity_in_stock` int(11) NOT NULL,
  `selling_price` decimal(10,2) NOT NULL,
  `purchase_price` decimal(10,2) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `parts`
--

INSERT INTO `parts` (`part_id`, `part_name`, `category`, `quantity_in_stock`, `selling_price`, `purchase_price`, `updated_at`) VALUES
(1, 'Procesor Intel Core i7', 'Procesory', 44, 1500.00, 1000.00, '2025-01-16 12:21:40'),
(2, 'Procesor AMD Ryzen 5', 'Procesory', 38, 1200.00, 850.00, '2025-01-16 11:05:35'),
(3, 'Pamięć RAM 8GB DDR4', 'Pamięci RAM', 0, 350.00, 250.00, '2025-01-05 15:43:39'),
(4, 'Pamięć RAM 16GB DDR4', 'Pamięci RAM', 27, 700.00, 500.00, '2025-01-16 15:50:49'),
(5, 'Dysk SSD 500GB', 'Dyski SSD', 79, 400.00, 250.00, '2025-01-11 21:39:37'),
(6, 'Dysk SSD 1TB', 'Dyski SSD', 38, 750.00, 500.00, '2025-01-16 15:50:49'),
(7, 'Karta graficzna Nvidia GTX 1660', 'Karty graficzne', 25, 2000.00, 1500.00, '2025-01-05 15:29:59'),
(8, 'Karta graficzna AMD Radeon RX 5700', 'Karty graficzne', 20, 2200.00, 1700.00, '2025-01-05 18:03:39'),
(9, 'Zasilacz 650W', 'Zasilacze', 55, 350.00, 250.00, '2025-01-05 15:29:59'),
(10, 'Zasilacz 750W', 'Zasilacze', 35, 450.00, 350.00, '2025-01-05 18:03:39'),
(11, 'Chłodzenie procesora Cooler Master', 'Chłodzenia', 70, 200.00, 150.00, '2025-01-16 12:37:03'),
(12, 'Chłodzenie CPU Noctua NH-D15', 'Chłodzenia', 15, 700.00, 500.00, '2024-11-29 10:55:09'),
(13, 'Obudowa PC Corsair 4000D', 'Obudowy', 45, 350.00, 250.00, '2024-11-29 10:55:09'),
(14, 'Obudowa PC NZXT H510', 'Obudowy', 60, 400.00, 300.00, '2024-11-29 10:55:09'),
(15, 'Monitor 24\" IPS Full HD', 'Monitory', 100, 600.00, 450.00, '2024-11-29 10:55:09'),
(16, 'Monitor 27\" 4K', 'Monitory', 29, 1500.00, 1200.00, '2025-01-02 19:11:28'),
(17, 'Klawiatura mechaniczna Logitech', 'Akcesoria', 80, 350.00, 250.00, '2024-11-29 10:55:09'),
(19, 'Laptop HP Pavilion 15', 'Laptopy', 10, 2500.00, 2000.00, '2024-11-29 10:55:09'),
(20, 'Laptop Lenovo ThinkPad X1 Carbon', 'Laptopy', 5, 4000.00, 3200.00, '2024-11-29 10:55:09'),
(21, 'Ryzen 5 3600', 'Procesory', 14, 1500.00, 1000.00, '2025-01-16 12:41:52');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `permissions`
--

CREATE TABLE `permissions` (
  `permission_id` int(11) NOT NULL,
  `permission_name` varchar(100) NOT NULL,
  `permission_level` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`permission_id`, `permission_name`, `permission_level`) VALUES
(1, 'klient', 1),
(2, 'technik', 3),
(3, 'administrator', 5);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `rating`
--

CREATE TABLE `rating` (
  `rating_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating_text` text DEFAULT NULL,
  `rating_score` int(11) DEFAULT NULL CHECK (`rating_score` between 1 and 5),
  `order_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rating`
--

INSERT INTO `rating` (`rating_id`, `user_id`, `rating_text`, `rating_score`, `order_id`) VALUES
(3, 16, 'polecam', 5, 19),
(4, 16, 'fajna obsługa, dobra kawka', 5, 20);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `services`
--

CREATE TABLE `services` (
  `service_id` int(11) NOT NULL,
  `service_name` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `is_available` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`service_id`, `service_name`, `price`, `is_available`) VALUES
(1, 'Serwis komputerowy', 150.00, 1),
(2, 'Naprawa laptopa', 200.00, 1),
(3, 'Wymiana dysku twardego', 250.00, 1),
(4, 'Instalacja systemu operacyjnego', 100.00, 1),
(5, 'Czyszczenie komputera', 80.00, 1),
(6, 'Wymiana ekranu laptopa', 300.00, 0),
(7, 'Konfiguracja sieci', 120.00, 1),
(8, 'Usunięcie wirusów', 90.00, 1),
(9, 'Instalacja oprogramowania', 70.00, 1),
(10, 'Odzyskiwanie danych', 400.00, 0),
(11, 'Diagnoza problemów sprzętowych', 50.00, 0),
(12, 'Optymalizacja komputera', 110.00, 1),
(13, 'Naprawa płyty głównej', 350.00, 0),
(14, 'Wymiana klawiatury laptopa', 150.00, 1),
(15, 'Wymiana baterii', 130.00, 1),
(16, 'Modernizacja komputera', 180.00, 1),
(17, 'Naprawa portu USB', 60.00, 1),
(18, 'Konfiguracja routera', 90.00, 1),
(19, 'Naprawa gniazda zasilania', 160.00, 0),
(20, 'Wymiana pamięci RAM', 140.00, 1),
(21, 'Wymiana procesora', 120.00, 1),
(24, 'Stwierdzenie, że RGB ładnie świeci', 21.37, 1);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` varchar(100) NOT NULL,
  `permission_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `first_name`, `last_name`, `email`, `password`, `phone`, `address`, `permission_id`, `created_at`, `updated_at`) VALUES
(15, 'Michał', 'Szymański', 'szymanski.m698@gmail.com', '$2y$10$dy/PCtdYNGQil5LwN8KZwevA0ar/PPSo/rHx2owIUExy3xprafja.', '785733501', 'Stary Grodków 23, 48-320 Skoroszyce', 3, '2025-01-02 15:36:56', '2025-01-16 15:46:16'),
(16, 'Michał', 'Szymański', 'test@example.us', '$2y$10$v1zNG/g.HCB0BP3VDPApy.Hs7.dm5H8yLihhzjeafGuAzgvx0n1A2', '666111222', 'Wrocławska 10, 49-200 Grodków', 1, '2025-01-02 15:37:59', '2025-01-16 15:19:14'),
(18, 'Technik', 'Kowalczyk', 'kowalczyk@gmail.com', '$2y$10$5djFVOYRW5e8FTmVnd/TEutu1vCfPVf4Ao5WzSMef.1okQNpMqUZO', '608150879', 'Opawska 24/3 48-304 Nysa', 1, '2025-01-16 09:33:03', '2025-01-16 17:48:46');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `user_permissions`
--

CREATE TABLE `user_permissions` (
  `user_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_permissions`
--

INSERT INTO `user_permissions` (`user_id`, `permission_id`) VALUES
(15, 3),
(16, 1),
(18, 1);

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `complaints`
--
ALTER TABLE `complaints`
  ADD PRIMARY KEY (`complaint_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `fk_technician_id` (`technician_id`);

--
-- Indeksy dla tabeli `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`invoice_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indeksy dla tabeli `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `orders_technician_id_fk` (`technician_id`);

--
-- Indeksy dla tabeli `order_parts`
--
ALTER TABLE `order_parts`
  ADD PRIMARY KEY (`order_id`,`part_id`),
  ADD KEY `part_id` (`part_id`);

--
-- Indeksy dla tabeli `order_services`
--
ALTER TABLE `order_services`
  ADD PRIMARY KEY (`order_id`,`service_id`),
  ADD KEY `service_id` (`service_id`);

--
-- Indeksy dla tabeli `parts`
--
ALTER TABLE `parts`
  ADD PRIMARY KEY (`part_id`);

--
-- Indeksy dla tabeli `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`permission_id`);

--
-- Indeksy dla tabeli `rating`
--
ALTER TABLE `rating`
  ADD PRIMARY KEY (`rating_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `fk_rating_order_id` (`order_id`);

--
-- Indeksy dla tabeli `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`service_id`);

--
-- Indeksy dla tabeli `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `unique_email` (`email`);

--
-- Indeksy dla tabeli `user_permissions`
--
ALTER TABLE `user_permissions`
  ADD PRIMARY KEY (`user_id`,`permission_id`),
  ADD KEY `permission_id` (`permission_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `complaints`
--
ALTER TABLE `complaints`
  MODIFY `complaint_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `invoice_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `parts`
--
ALTER TABLE `parts`
  MODIFY `part_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `permission_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `rating`
--
ALTER TABLE `rating`
  MODIFY `rating_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `service_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `complaints`
--
ALTER TABLE `complaints`
  ADD CONSTRAINT `complaints_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_order_id` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_technician_id` FOREIGN KEY (`technician_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL;

--
-- Constraints for table `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `invoices_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `orders_technician_id_fk` FOREIGN KEY (`technician_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL;

--
-- Constraints for table `order_parts`
--
ALTER TABLE `order_parts`
  ADD CONSTRAINT `fk_order_parts_order_id` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_order_parts_part_id` FOREIGN KEY (`part_id`) REFERENCES `parts` (`part_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `order_parts_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_parts_ibfk_2` FOREIGN KEY (`part_id`) REFERENCES `parts` (`part_id`) ON DELETE CASCADE;

--
-- Constraints for table `order_services`
--
ALTER TABLE `order_services`
  ADD CONSTRAINT `order_services_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
  ADD CONSTRAINT `order_services_ibfk_2` FOREIGN KEY (`service_id`) REFERENCES `services` (`service_id`);

--
-- Constraints for table `rating`
--
ALTER TABLE `rating`
  ADD CONSTRAINT `fk_rating_order_id` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `rating_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `user_permissions`
--
ALTER TABLE `user_permissions`
  ADD CONSTRAINT `user_permissions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_permissions_ibfk_2` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`permission_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
