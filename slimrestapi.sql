-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 13 Ağu 2022, 15:52:48
-- Sunucu sürümü: 10.4.24-MariaDB
-- PHP Sürümü: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `slimrestapi`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `category`
--

CREATE TABLE `category` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(11) NOT NULL,
  `category_status` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Tablo döküm verisi `category`
--

INSERT INTO `category` (`category_id`, `category_name`, `category_status`) VALUES
(1, 'Elektronik', 1),
(2, 'Gıda', 1);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `orders`
--

CREATE TABLE `orders` (
  `orders_id` int(11) NOT NULL,
  `users_id` int(11) NOT NULL,
  `orders_discount` varchar(250) DEFAULT NULL,
  `orders_description` varchar(500) DEFAULT NULL,
  `orders_date` datetime NOT NULL DEFAULT current_timestamp(),
  `orders_status` int(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Tablo döküm verisi `orders`
--

INSERT INTO `orders` (`orders_id`, `users_id`, `orders_discount`, `orders_description`, `orders_date`, `orders_status`) VALUES
(1, 1, NULL, '', '2022-08-13 14:30:08', 1),
(2, 2, NULL, '', '2022-08-13 14:31:54', 1),
(4, 3, NULL, '', '2022-08-13 14:35:01', 1);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `orders_products`
--

CREATE TABLE `orders_products` (
  `id` int(11) NOT NULL,
  `orders_id` int(11) NOT NULL,
  `products_id` int(11) NOT NULL,
  `quantity` int(5) NOT NULL,
  `unit_price` varchar(250) NOT NULL,
  `total_price` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Tablo döküm verisi `orders_products`
--

INSERT INTO `orders_products` (`id`, `orders_id`, `products_id`, `quantity`, `unit_price`, `total_price`) VALUES
(1, 1, 3, 10, '11.28', '112.8'),
(2, 2, 2, 2, '49.50', '99'),
(3, 2, 1, 1, '120.50', '120.5'),
(4, 4, 2, 6, '11.28', '67.68'),
(5, 4, 1, 10, '120.75', '1207.5');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `products`
--

CREATE TABLE `products` (
  `products_id` int(11) NOT NULL,
  `products_name` varchar(250) NOT NULL,
  `products_description` text NOT NULL,
  `category_id` int(11) NOT NULL,
  `products_price` varchar(250) NOT NULL,
  `stock` int(11) NOT NULL,
  `products_status` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Tablo döküm verisi `products`
--

INSERT INTO `products` (`products_id`, `products_name`, `products_description`, `category_id`, `products_price`, `stock`, `products_status`) VALUES
(1, 'Black&Decker A7062 40 Parça Cırcırlı Tornavida Seti', 'Black&Decker A7062 40 Parça Cırcırlı Tornavida Seti', 1, '120.75', 10, 1),
(2, 'Reko Mini Tamir Hassas Tornavida Seti 32\'li', 'Reko Mini Tamir Hassas Tornavida Seti 32\'li', 1, '49.50', 10, 1),
(3, 'Viko Karre Anahtar - Beyaz', 'Viko Karre Anahtar - Beyaz', 2, '11.28', 10, 1),
(4, 'Legrand Salbei Anahtar, Alüminyum', 'Legrand Salbei Anahtar, Alüminyum', 2, '22.80', 10, 1),
(5, 'Schneider Asfora Beyaz Komütatör', 'Schneider Asfora Beyaz Komütatör', 2, '12.95', 10, 1);

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`category_id`);

--
-- Tablo için indeksler `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`orders_id`);

--
-- Tablo için indeksler `orders_products`
--
ALTER TABLE `orders_products`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`products_id`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `category`
--
ALTER TABLE `category`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Tablo için AUTO_INCREMENT değeri `orders`
--
ALTER TABLE `orders`
  MODIFY `orders_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Tablo için AUTO_INCREMENT değeri `orders_products`
--
ALTER TABLE `orders_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Tablo için AUTO_INCREMENT değeri `products`
--
ALTER TABLE `products`
  MODIFY `products_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
