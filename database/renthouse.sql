-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:8080
-- Generation Time: Dec 10, 2024 at 05:01 PM
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
-- Database: `renthouse`
--

-- --------------------------------------------------------

--
-- Table structure for table `add_property`
--

CREATE TABLE `add_property` (
  `property_id` int(10) NOT NULL,
  `country` varchar(50) NOT NULL,
  `province` varchar(50) NOT NULL,
  `zone` varchar(50) NOT NULL,
  `district` varchar(50) NOT NULL,
  `city` varchar(100) NOT NULL,
  `vdc_municipality` varchar(50) NOT NULL,
  `ward_no` int(10) NOT NULL,
  `tole` varchar(100) NOT NULL,
  `contact_no` bigint(10) NOT NULL,
  `property_type` varchar(50) NOT NULL,
  `estimated_price` bigint(10) NOT NULL,
  `total_rooms` int(10) NOT NULL,
  `bedroom` int(10) NOT NULL,
  `living_room` int(10) NOT NULL,
  `kitchen` int(10) NOT NULL,
  `bathroom` int(10) NOT NULL,
  `booked` varchar(5) DEFAULT NULL,
  `description` varchar(2000) NOT NULL,
  `latitude` decimal(9,6) NOT NULL,
  `longitude` decimal(9,6) NOT NULL,
  `owner_id` int(10) NOT NULL,
  `approved` int(11) DEFAULT 0,
  `blocked` int(11) DEFAULT 0,
  `listing_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `add_property`
--

INSERT INTO `add_property` (`property_id`, `country`, `province`, `zone`, `district`, `city`, `vdc_municipality`, `ward_no`, `tole`, `contact_no`, `property_type`, `estimated_price`, `total_rooms`, `bedroom`, `living_room`, `kitchen`, `bathroom`, `booked`, `description`, `latitude`, `longitude`, `owner_id`, `approved`, `blocked`, `listing_date`) VALUES
(132, 'India', 'Gujarat', 'South', 'Ahmedabad', 'Ahmedabad', 'Municipality', 3, 'near river front', 7845128956, 'Flat Rent', 25000, 4, 3, 1, 1, 3, 'Yes', 'It is a lovely place.', 22.698000, 72.856700, 2, 1, 0, '2024-11-13 00:00:12'),
(133, 'India', 'Maharashtra', 'North', 'Udaipur', 'Udaipur', 'Municipality', 8, 'Beautiful', 7845128956, 'Flat Rent', 20000, 7, 4, 4, 4, 4, 'Yes', 'sdfg', 22.988100, 72.514300, 2, 1, 0, '2024-11-13 00:00:12'),
(134, 'India', 'Rajasthan', 'South', 'Jodhpur', 'Jodhpur', 'Municipality', 5, 'nice place', 7845128956, 'Full House Rent', 12000, 6, 4, 2, 2, 4, 'Yes', 'it&#039;s meet&#039;s house', 23.012900, 72.555700, 15, 1, 0, '2024-11-13 00:00:12'),
(236, 'India', 'Maharashtra', 'West', 'Mumbai', 'Mumbai', 'Municipality', 12, 'Tole-1', 9123456701, 'Full House Rent', 1200000, 5, 3, 1, 1, 2, 'No', 'Spacious house in Mumbai', 19.076000, 72.877700, 2, 1, 0, '2024-11-13 00:00:12'),
(237, 'India', 'Gujarat', 'West', 'Ahmedabad', 'Ahmedabad', 'Municipality', 15, 'Tole-2', 9123456702, 'Flat Rent', 2200000, 4, 2, 1, 1, 2, 'Yes', 'Modern flat in Ahmedabad', 23.022500, 72.571400, 2, 1, 0, '2024-11-13 00:00:12'),
(238, 'India', 'Rajasthan', 'North', 'Jaipur', 'Jaipur', 'Municipality', 10, 'Tole-3', 9123456703, 'Room Rent', 500000, 3, 1, 0, 0, 1, 'No', 'Cozy room in Jaipur', 26.912400, 75.787300, 2, 1, 0, '2024-11-13 00:00:12'),
(239, 'India', 'Karnataka', 'South', 'Bangalore', 'Bangalore', 'Municipality', 8, 'Tole-4', 9123456704, 'Full House Rent', 1800000, 6, 3, 1, 1, 2, 'No', 'Spacious house in Bangalore', 12.971600, 77.594600, 2, 1, 0, '2024-11-13 00:00:12'),
(240, 'India', 'Tamil Nadu', 'South', 'Chennai', 'Chennai', 'Municipality', 6, 'Tole-5', 9123456705, 'Flat Rent', 1400000, 4, 2, 1, 1, 1, 'No', 'Modern flat in Chennai', 13.082700, 80.270700, 2, 1, 1, '2024-11-13 00:00:12'),
(241, 'India', 'Uttar Pradesh', 'North', 'Lucknow', 'Lucknow', 'Municipality', 5, 'Tole-6', 9123456706, 'Room Rent', 600000, 2, 1, 0, 0, 1, 'No', 'Cozy room in Lucknow', 26.846700, 80.946200, 2, 1, 1, '2024-11-13 00:00:12'),
(242, 'India', 'West Bengal', 'East', 'Kolkata', 'Kolkata', 'Municipality', 9, 'Tole-7', 9123456707, 'Full House Rent', 1500000, 5, 3, 1, 1, 2, 'No', 'Spacious house in Kolkata', 22.572600, 88.363900, 2, 1, 0, '2024-11-13 00:00:12'),
(243, 'India', 'Maharashtra', 'West', 'Pune', 'Pune', 'Municipality', 11, 'Tole-8', 9123456708, 'Flat Rent', 1700000, 4, 2, 1, 1, 1, 'No', 'Modern flat in Pune', 18.520400, 73.856700, 2, 1, 0, '2024-11-13 00:00:12'),
(244, 'India', 'Gujarat', 'West', 'Surat', 'Surat', 'Municipality', 7, 'Tole-9', 9123456709, 'Room Rent', 700000, 3, 1, 0, 0, 1, 'No', 'Cozy room in Surat', 21.170200, 72.831100, 2, 1, 0, '2024-11-13 00:00:12'),
(245, 'India', 'Rajasthan', 'North', 'Udaipur', 'Udaipur', 'Municipality', 4, 'Tole-10', 9123456710, 'Full House Rent', 1900000, 6, 3, 1, 1, 2, 'No', 'Spacious house in Udaipur', 24.585400, 73.712500, 2, 1, 0, '2024-11-13 00:00:12'),
(246, 'India', 'Karnataka', 'South', 'Mysore', 'Mysore', 'Municipality', 3, 'Tole-11', 9123456711, 'Flat Rent', 1500000, 3, 2, 1, 1, 1, 'Yes', 'Modern flat in Mysore', 12.295800, 76.639400, 2, 1, 0, '2024-11-13 00:00:12'),
(247, 'India', 'Tamil Nadu', 'South', 'Coimbatore', 'Coimbatore', 'Municipality', 4, 'Tole-12', 9123456712, 'Room Rent', 550000, 2, 1, 0, 0, 1, 'No', 'Cozy room in Coimbatore', 11.016800, 76.955800, 2, 1, 0, '2024-11-13 00:00:12'),
(248, 'India', 'Maharashtra', 'West', 'Nagpur', 'Nagpur', 'Municipality', 5, 'Tole-13', 9123456713, 'Full House Rent', 1300000, 5, 3, 1, 1, 2, 'Yes', 'Spacious house in Nagpur', 21.145800, 79.088200, 2, 1, 0, '2024-11-13 00:00:12'),
(249, 'India', 'Gujarat', 'West', 'Vadodara', 'Vadodara', 'Municipality', 6, 'Tole-14', 9123456714, 'Flat Rent', 1800000, 4, 2, 1, 1, 1, 'No', 'Modern flat in Vadodara', 22.307200, 73.181200, 2, 1, 0, '2024-11-13 00:00:12'),
(250, 'India', 'Rajasthan', 'North', 'Jodhpur', 'Jodhpur', 'Municipality', 7, 'Tole-15', 9123456715, 'Room Rent', 650000, 3, 1, 0, 0, 1, 'Yes', 'Cozy room in Jodhpur', 26.238900, 73.024300, 2, 1, 0, '2024-11-13 00:00:12'),
(251, 'India', 'Karnataka', 'South', 'Mangalore', 'Mangalore', 'Municipality', 8, 'Tole-16', 9123456716, 'Full House Rent', 1250000, 6, 3, 1, 1, 2, 'No', 'Spacious house with beautiful sea view in Mangalore', 12.914100, 74.856000, 2, 1, 0, '2024-11-13 00:00:12'),
(252, 'India', 'Tamil Nadu', 'South', 'Madurai', 'Madurai', 'Municipality', 9, 'Tole-17', 9123456717, 'Flat Rent', 1600000, 4, 2, 1, 1, 1, 'No', 'Modern flat near the Meenakshi Temple in Madurai', 9.925200, 78.119800, 2, 1, 0, '2024-11-13 00:00:12'),
(253, 'India', 'Uttar Pradesh', 'North', 'Varanasi', 'Varanasi', 'Municipality', 10, 'Tole-18', 9123456718, 'Room Rent', 400000, 2, 1, 0, 0, 1, 'No', 'Affordable room close to the Ganges in Varanasi', 25.317600, 82.973900, 2, 1, 0, '2024-11-13 00:00:12'),
(254, 'India', 'West Bengal', 'East', 'Darjeeling', 'Darjeeling', 'Municipality', 5, 'Tole-19', 9123456719, 'Full House Rent', 1350000, 5, 3, 1, 1, 2, 'No', 'Charming house with a mountain view in Darjeeling', 27.041000, 88.266300, 2, 1, 0, '2024-11-13 00:00:12'),
(255, 'India', 'Maharashtra', 'West', 'Aurangabad', 'Aurangabad', 'Municipality', 3, 'Tole-20', 9123456720, 'Flat Rent', 1400000, 4, 2, 1, 1, 1, 'No', 'Well-maintained flat near Ajanta Caves in Aurangabad', 19.876200, 75.343300, 2, 1, 0, '2024-11-13 00:00:12'),
(256, 'India', 'Gujarat', 'West', 'Bhavnagar', 'Bhavnagar', 'Municipality', 7, 'Tole-21', 9123456721, 'Room Rent', 450000, 2, 1, 0, 0, 1, 'No', 'Small room available in Bhavnagar', 21.764500, 72.151900, 2, 1, 0, '2024-11-13 00:00:12'),
(257, 'India', 'Rajasthan', 'North', 'Ajmer', 'Ajmer', 'Municipality', 6, 'Tole-22', 9123456722, 'Full House Rent', 1600000, 5, 3, 1, 1, 2, 'No', 'House available in the heart of Ajmer', 26.449900, 74.639900, 2, 1, 0, '2024-11-13 00:00:12'),
(258, 'India', 'Karnataka', 'South', 'Hubli', 'Hubli', 'Municipality', 11, 'Tole-23', 9123456723, 'Flat Rent', 1150000, 3, 2, 1, 1, 1, 'No', 'Flat located in the commercial center of Hubli', 15.364700, 75.124000, 2, 1, 0, '2024-11-13 00:00:12'),
(259, 'India', 'Tamil Nadu', 'South', 'Tiruchirappalli', 'Tiruchirappalli', 'Municipality', 8, 'Tole-24', 9123456724, 'Room Rent', 500000, 3, 1, 0, 0, 1, 'No', 'Comfortable room near the river Kaveri in Tiruchirappalli', 10.790500, 78.704700, 2, 1, 0, '2024-11-13 00:00:12'),
(260, 'India', 'Uttar Pradesh', 'North', 'Agra', 'Agra', 'Municipality', 4, 'Tole-25', 9123456725, 'Full House Rent', 1800000, 6, 3, 1, 1, 2, 'No', 'Spacious house near the Taj Mahal in Agra', 27.176700, 78.008100, 2, 1, 0, '2024-11-13 00:00:12'),
(261, 'India', 'West Bengal', 'East', 'Siliguri', 'Siliguri', 'Municipality', 3, 'Tole-26', 9123456726, 'Flat Rent', 1300000, 4, 2, 1, 1, 1, 'No', 'Modern flat near the foothills in Siliguri', 26.727100, 88.395300, 2, 1, 0, '2024-11-13 00:00:12'),
(262, 'India', 'Maharashtra', 'West', 'Nashik', 'Nashik', 'Municipality', 7, 'Tole-27', 9123456727, 'Room Rent', 600000, 3, 1, 0, 0, 1, 'No', 'Affordable room in Nashik', 19.997500, 73.789800, 2, 1, 0, '2024-11-13 00:00:12'),
(263, 'India', 'Gujarat', 'West', 'Rajkot', 'Rajkot', 'Municipality', 6, 'Tole-28', 9123456728, 'Full House Rent', 2000000, 7, 4, 1, 1, 3, 'No', 'Luxury house with ample space in Rajkot', 22.303900, 70.802200, 2, 1, 0, '2024-11-13 00:00:12'),
(264, 'India', 'Rajasthan', 'North', 'Bikaner', 'Bikaner', 'Municipality', 9, 'Tole-29', 9123456729, 'Flat Rent', 1100000, 3, 2, 1, 1, 1, 'No', 'Affordable flat in Bikaner', 28.022900, 73.311900, 15, 1, 0, '2024-11-13 00:00:12'),
(265, 'India', 'Karnataka', 'South', 'Belgaum', 'Belgaum', 'Municipality', 10, 'Tole-30', 9123456730, 'Room Rent', 300000, 1, 1, 0, 0, 1, 'No', 'Small room in Belgaum', 15.849700, 74.497700, 15, 1, 0, '2024-11-13 00:00:12'),
(266, 'India', 'Tamil Nadu', 'South', 'Salem', 'Salem', 'Municipality', 5, 'Tole-31', 9123456731, 'Full House Rent', 1100000, 5, 3, 1, 1, 2, 'No', 'Spacious house in the suburbs of Salem', 11.664300, 78.146000, 15, 1, 0, '2024-11-13 00:00:12'),
(267, 'India', 'Uttar Pradesh', 'North', 'Meerut', 'Meerut', 'Municipality', 8, 'Tole-32', 9123456732, 'Flat Rent', 800000, 3, 2, 1, 1, 1, 'No', 'Flat with modern amenities in Meerut', 28.984500, 77.706400, 15, 1, 0, '2024-11-13 00:00:12'),
(268, 'India', 'West Bengal', 'East', 'Durgapur', 'Durgapur', 'Municipality', 2, 'Tole-33', 9123456733, 'Room Rent', 350000, 1, 1, 0, 0, 1, 'No', 'Cozy room in Durgapur', 23.520400, 87.311900, 15, 1, 0, '2024-11-13 00:00:12'),
(269, 'India', 'Maharashtra', 'West', 'Nagpur', 'Nagpur', 'Municipality', 1, 'Tole-34', 9123456734, 'Full House Rent', 1500000, 6, 3, 1, 1, 2, 'No', 'House near the Orange City area in Nagpur', 21.145800, 79.088200, 15, 1, 0, '2024-11-13 00:00:12'),
(274, 'India', 'Uttar Pradesh', 'North', 'Kanpur', 'Kanpur', 'Municipality', 2, 'Tole-39', 9123456739, 'Room Rent', 300000, 1, 1, 0, 0, 1, 'No', 'Budget-friendly room in the city center of Kanpur', 26.449900, 80.331900, 15, 1, 0, '2024-11-13 00:00:12'),
(276, 'India', 'Maharashtra', 'West', 'Mumbai', 'Mumbai', 'Municipality', 6, 'Tole-41', 9123456741, 'Flat Rent', 1800000, 3, 2, 1, 1, 1, 'No', 'Sea-facing flat in the heart of Mumbai', 19.076000, 72.877700, 15, 1, 0, '2024-11-13 00:00:12'),
(277, 'India', 'Gujarat', 'West', 'Ahmedabad', 'Ahmedabad', 'Municipality', 3, 'Tole-42', 9123456742, 'Room Rent', 400000, 2, 1, 0, 0, 1, 'No', 'Affordable room near the Sabarmati Riverfront in Ahmedabad', 23.022500, 72.571400, 15, 0, 0, '2024-11-13 00:00:12'),
(278, 'India', 'Rajasthan', 'North', 'Udaipur', 'Udaipur', 'Municipality', 4, 'Tole-43', 9123456743, 'Full House Rent', 1450000, 6, 3, 1, 1, 2, 'No', 'Beautiful house near the lakes of Udaipur', 24.585400, 73.712500, 15, 0, 0, '2024-11-13 00:00:12'),
(279, 'India', 'Karnataka', 'South', 'Mysore', 'Mysore', 'Municipality', 8, 'Tole-44', 9123456744, 'Flat Rent', 1400000, 4, 2, 1, 1, 1, 'No', 'Flat near the palace grounds of Mysore', 12.295800, 76.639400, 15, 1, 0, '2024-11-13 00:00:12'),
(280, 'India', 'Tamil Nadu', 'South', 'Coimbatore', 'Coimbatore', 'Municipality', 7, 'Tole-45', 9123456745, 'Room Rent', 350000, 1, 1, 0, 0, 1, 'No', 'Cozy room near the textile hub of Coimbatore', 11.016800, 76.955800, 15, 0, 0, '2024-11-13 00:00:12'),
(281, 'India', 'Uttar Pradesh', 'North', 'Lucknow', 'Lucknow', 'Municipality', 5, 'Tole-46', 9123456746, 'Full House Rent', 1700000, 6, 3, 1, 1, 2, 'No', 'Heritage house in the historic city of Lucknow', 26.846700, 80.946200, 15, 0, 0, '2024-11-13 00:00:12'),
(282, 'India', 'West Bengal', 'East', 'Kolkata', 'Kolkata', 'Municipality', 10, 'Tole-47', 9123456747, 'Flat Rent', 1900000, 5, 2, 1, 1, 1, 'No', 'Spacious flat near Park Street in Kolkata', 22.572600, 88.363900, 15, 0, 0, '2024-11-13 00:00:12'),
(283, 'India', 'Maharashtra', 'West', 'Pune', 'Pune', 'Municipality', 4, 'Tole-48', 9123456748, 'Room Rent', 600000, 2, 1, 0, 0, 1, 'No', 'Room near the IT park in Pune', 18.520400, 73.856700, 15, 0, 0, '2024-11-13 00:00:12'),
(284, 'India', 'Gujarat', 'West', 'Vadodara', 'Vadodara', 'Municipality', 7, 'Tole-49', 9123456749, 'Full House Rent', 1700000, 6, 3, 1, 1, 2, 'No', 'House in the cultural city of Vadodara', 22.307200, 73.181200, 15, 0, 0, '2024-11-13 00:00:12'),
(285, 'India', 'Rajasthan', 'North', 'Jaipur', 'Jaipur', 'Municipality', 8, 'Tole-50', 9123456750, 'Flat Rent', 1600000, 4, 2, 1, 1, 1, 'No', 'Flat with pink city vibes in Jaipur', 26.912400, 75.787300, 15, 0, 0, '2024-11-13 00:00:12'),
(286, 'India', 'Karnataka', 'South', 'Hubli', 'Hubli', 'Municipality', 9, 'Tole-51', 9123456751, 'Room Rent', 500000, 1, 1, 0, 0, 1, 'No', 'Compact room in Hubli commercial area', 15.364700, 75.124000, 15, 0, 0, '2024-11-13 00:00:12'),
(287, 'India', 'Tamil Nadu', 'South', 'Salem', 'Salem', 'Municipality', 4, 'Tole-52', 9123456752, 'Full House Rent', 1200000, 5, 3, 1, 1, 2, 'No', 'House located in Salem peaceful locality', 11.664300, 78.146000, 15, 0, 0, '2024-11-13 00:00:12'),
(288, 'India', 'Uttar Pradesh', 'North', 'Meerut', 'Meerut', 'Municipality', 5, 'Tole-53', 9123456753, 'Flat Rent', 850000, 3, 2, 1, 1, 1, 'No', 'Comfortable flat in the fast-growing city of Meerut', 28.984500, 77.706400, 15, 0, 0, '2024-11-13 00:00:12'),
(289, 'India', 'West Bengal', 'East', 'Durgapur', 'Durgapur', 'Municipality', 6, 'Tole-54', 9123456754, 'Room Rent', 450000, 2, 1, 0, 0, 1, 'No', 'Room with access to the steel city facilities in Durgapur', 23.520400, 87.311900, 15, 0, 0, '2024-11-13 00:00:12'),
(290, 'India', 'Maharashtra', 'West', 'Nashik', 'Nashik', 'Municipality', 7, 'Tole-55', 9123456755, 'Full House Rent', 1600000, 6, 3, 1, 1, 2, 'No', 'House located in the grape city of Nashik', 19.997500, 73.789800, 15, 0, 0, '2024-11-13 00:00:12'),
(291, 'India', 'Karnataka', 'South', 'Belgaum', 'Belgaum', 'Municipality', 8, 'Tole-56', 9123456756, 'Room Rent', 400000, 1, 1, 0, 0, 1, 'No', 'Single room in Belgaum serene locality', 15.849700, 74.497700, 15, 0, 0, '2024-11-13 00:00:12'),
(292, 'India', 'Tamil Nadu', 'South', 'Madurai', 'Madurai', 'Municipality', 5, 'Tole-57', 9123456757, 'Flat Rent', 1400000, 4, 2, 1, 1, 1, 'No', 'Spacious flat in the temple city of Madurai', 9.925200, 78.119800, 15, 0, 0, '2024-11-13 00:00:12'),
(293, 'India', 'Uttar Pradesh', 'North', 'Varanasi', 'Varanasi', 'Municipality', 6, 'Tole-58', 9123456758, 'Full House Rent', 1550000, 5, 3, 1, 1, 2, 'No', 'House with Ganges view in the spiritual city of Varanasi', 25.317600, 82.973900, 15, 0, 0, '2024-11-13 00:00:12'),
(294, 'India', 'West Bengal', 'East', 'Darjeeling', 'Darjeeling', 'Municipality', 4, 'Tole-59', 9123456759, 'Room Rent', 600000, 2, 1, 0, 0, 1, 'No', 'Cozy room with mountain view in Darjeeling', 27.041000, 88.266300, 15, 0, 0, '2024-11-13 00:00:12'),
(295, 'India', 'Maharashtra', 'West', 'Aurangabad', 'Aurangabad', 'Municipality', 3, 'Tole-60', 9123456760, 'Full House Rent', 1700000, 6, 3, 1, 1, 2, 'No', 'Spacious house near Ajanta Caves in Aurangabad', 19.876200, 75.343300, 15, 0, 0, '2024-11-13 00:00:12'),
(296, 'India', 'Gujarat', 'West', 'Rajkot', 'Rajkot', 'Municipality', 7, 'Tole-61', 9123456761, 'Flat Rent', 1300000, 3, 2, 1, 1, 1, 'No', 'Modern flat in the industrial city of Rajkot', 22.303900, 70.802200, 15, 0, 0, '2024-11-13 00:00:12'),
(297, 'India', 'Rajasthan', 'North', 'Bikaner', 'Bikaner', 'Municipality', 8, 'Tole-62', 9123456762, 'Room Rent', 350000, 1, 1, 0, 0, 1, 'No', 'Traditional room in the desert city of Bikaner', 28.022900, 73.311900, 15, 0, 0, '2024-11-13 00:00:12'),
(298, 'India', 'Karnataka', 'South', 'Mangalore', 'Mangalore', 'Municipality', 9, 'Tole-63', 9123456763, 'Full House Rent', 1600000, 5, 3, 1, 1, 2, 'No', 'House near the beaches of Mangalore', 12.914100, 74.856000, 15, 0, 0, '2024-11-13 00:00:12'),
(299, 'India', 'Tamil Nadu', 'South', 'Tiruchirappalli', 'Tiruchirappalli', 'Municipality', 10, 'Tole-64', 9123456764, 'Flat Rent', 1250000, 4, 2, 1, 1, 1, 'No', 'Flat close to the Rockfort Temple in Tiruchirappalli', 10.790500, 78.704700, 15, 0, 0, '2024-11-13 00:00:12'),
(300, 'India', 'Uttar Pradesh', 'North', 'Agra', 'Agra', 'Municipality', 6, 'Tole-65', 9123456765, 'Room Rent', 300000, 1, 1, 0, 0, 1, 'No', 'Room with a view of the Taj Mahal in Agra', 27.176700, 78.008100, 15, 0, 0, '2024-11-13 00:00:12'),
(301, 'India', 'West Bengal', 'East', 'Siliguri', 'Siliguri', 'Municipality', 7, 'Tole-66', 9123456766, 'Full House Rent', 1300000, 5, 3, 1, 1, 2, 'No', 'House in the gateway to the northeast, Siliguri', 26.727100, 88.395300, 15, 0, 0, '2024-11-13 00:00:12'),
(302, 'India', 'Maharashtra', 'West', 'Nagpur', 'Nagpur', 'Municipality', 4, 'Tole-67', 9123456767, 'Flat Rent', 1450000, 4, 2, 1, 1, 1, 'No', 'Flat in the Orange City of Nagpur', 21.145800, 79.088200, 15, 0, 0, '2024-11-13 00:00:12'),
(303, 'India', 'Gujarat', 'West', 'Bhavnagar', 'Bhavnagar', 'Municipality', 5, 'Tole-68', 9123456768, 'Room Rent', 400000, 2, 1, 0, 0, 1, 'No', 'Room in the coastal city of Bhavnagar', 21.764500, 72.151900, 15, 0, 0, '2024-11-13 00:00:12'),
(304, 'India', 'Rajasthan', 'North', 'Ajmer', 'Ajmer', 'Municipality', 3, 'Tole-69', 9123456769, 'Full House Rent', 1600000, 6, 3, 1, 1, 2, 'No', 'House near the Dargah Sharif in Ajmer', 26.449900, 74.639900, 15, 0, 0, '2024-11-13 00:00:12'),
(305, 'India', 'Karnataka', 'South', 'Belgaum', 'Belgaum', 'Municipality', 8, 'Tole-70', 9123456770, 'Flat Rent', 1200000, 3, 2, 1, 1, 1, 'No', 'Flat in the educational hub of Belgaum', 15.849700, 74.497700, 15, 0, 0, '2024-11-13 00:00:12'),
(306, 'India', 'Tamil Nadu', 'South', 'Salem', 'Salem', 'Municipality', 9, 'Tole-71', 9123456771, 'Room Rent', 400000, 1, 1, 0, 0, 1, 'No', 'Affordable room in the steel city of Salem', 11.664300, 78.146000, 15, 0, 0, '2024-11-13 00:00:12'),
(307, 'India', 'Uttar Pradesh', 'North', 'Meerut', 'Meerut', 'Municipality', 5, 'Tole-72', 9123456772, 'Full House Rent', 1500000, 5, 3, 1, 1, 2, 'No', 'House with easy access to Delhi in Meerut', 28.984500, 77.706400, 15, 0, 0, '2024-11-13 00:00:12'),
(308, 'India', 'West Bengal', 'East', 'Kolkata', 'Kolkata', 'Municipality', 7, 'Tole-73', 9123456773, 'Flat Rent', 1900000, 4, 2, 1, 1, 1, 'No', 'Luxury flat in the Salt Lake area of Kolkata', 22.572600, 88.363900, 15, 0, 0, '2024-11-13 00:00:12'),
(309, 'India', 'Maharashtra', 'West', 'Nashik', 'Nashik', 'Municipality', 6, 'Tole-74', 9123456774, 'Room Rent', 700000, 2, 1, 0, 0, 1, 'No', 'Comfortable room near the vineyards of Nashik', 19.997500, 73.789800, 15, 0, 0, '2024-11-13 00:00:12'),
(310, 'India', 'Gujarat', 'West', 'Surat', 'Surat', 'Municipality', 4, 'Tole-75', 9123456775, 'Full House Rent', 1600000, 5, 3, 1, 1, 2, 'No', 'House in the diamond city of Surat', 21.170200, 72.831100, 15, 0, 0, '2024-11-13 00:00:12'),
(311, 'India', 'Rajasthan', 'North', 'Jodhpur', 'Jodhpur', 'Municipality', 9, 'Tole-76', 9123456776, 'Flat Rent', 1300000, 3, 2, 1, 1, 1, 'No', 'Flat with a beautiful view of the Mehrangarh Fort in Jodhpur', 26.238900, 73.024300, 15, 0, 0, '2024-11-13 00:00:12'),
(312, 'India', 'Karnataka', 'South', 'Hubli', 'Hubli', 'Municipality', 5, 'Tole-77', 9123456777, 'Room Rent', 450000, 1, 1, 0, 0, 1, 'No', 'Affordable room in the commercial hub of Hubli', 15.364700, 75.123900, 15, 0, 0, '2024-11-13 00:00:12'),
(313, 'India', 'Tamil Nadu', 'South', 'Coimbatore', 'Coimbatore', 'Municipality', 7, 'Tole-78', 9123456778, 'Full House Rent', 1750000, 6, 3, 1, 1, 2, 'No', 'House in the textile city of Coimbatore', 11.016800, 76.955800, 15, 0, 0, '2024-11-13 00:00:12'),
(314, 'India', 'Uttar Pradesh', 'North', 'Kanpur', 'Kanpur', 'Municipality', 6, 'Tole-79', 9123456779, 'Flat Rent', 1200000, 3, 2, 1, 1, 1, 'No', 'Spacious flat in the industrial city of Kanpur', 26.449900, 80.331900, 15, 0, 0, '2024-11-13 00:00:12'),
(315, 'India', 'West Bengal', 'East', 'Durgapur', 'Durgapur', 'Municipality', 4, 'Tole-80', 9123456780, 'Room Rent', 550000, 2, 1, 0, 0, 1, 'No', 'Comfortable room in the steel city of Durgapur', 23.520400, 87.311900, 15, 0, 0, '2024-11-13 00:00:12'),
(316, 'India', 'Maharashtra', 'West', 'Mumbai', 'Mumbai', 'Municipality', 7, 'Tole-81', 9123456781, 'Full House Rent', 2500000, 7, 4, 1, 1, 3, 'No', 'Luxury house near the Gateway of India in Mumbai', 19.076000, 72.877700, 2, 0, 0, '2024-11-13 00:00:12'),
(317, 'India', 'Gujarat', 'West', 'Ahmedabad', 'Ahmedabad', 'Municipality', 5, 'Tole-82', 9123456782, 'Flat Rent', 1500000, 4, 2, 1, 1, 1, 'No', 'Modern flat in the business hub of Ahmedabad', 23.022500, 72.571400, 2, 0, 0, '2024-11-13 00:00:12'),
(318, 'India', 'Rajasthan', 'North', 'Jaipur', 'Jaipur', 'Municipality', 6, 'Tole-83', 9123456783, 'Room Rent', 650000, 1, 1, 0, 0, 1, 'No', 'Room with a view of the Pink City Jaipur', 26.912400, 75.787300, 2, 0, 0, '2024-11-13 00:00:12'),
(319, 'India', 'Karnataka', 'South', 'Mysore', 'Mysore', 'Municipality', 8, 'Tole-84', 9123456784, 'Full House Rent', 1800000, 5, 3, 1, 1, 2, 'No', 'House near the Mysore Palace', 12.295800, 76.639400, 2, 0, 0, '2024-11-13 00:00:12'),
(320, 'India', 'Tamil Nadu', 'South', 'Chennai', 'Chennai', 'Municipality', 4, 'Tole-85', 9123456785, 'Flat Rent', 1600000, 3, 2, 1, 1, 1, 'No', 'Flat in the heart of Chennai city', 13.082700, 80.270700, 2, 0, 0, '2024-11-13 00:00:12'),
(321, 'India', 'Uttar Pradesh', 'North', 'Lucknow', 'Lucknow', 'Municipality', 9, 'Tole-86', 9123456786, 'Room Rent', 500000, 1, 1, 0, 0, 1, 'No', 'Room with access to Lucknow famous markets', 26.846700, 80.946200, 2, 0, 0, '2024-11-13 00:00:12'),
(322, 'India', 'West Bengal', 'East', 'Howrah', 'Howrah', 'Municipality', 5, 'Tole-87', 9123456787, 'Full House Rent', 1700000, 6, 3, 1, 1, 2, 'No', 'House with a view of the Howrah Bridge', 22.589700, 88.310300, 2, 0, 0, '2024-11-13 00:00:12'),
(323, 'India', 'Maharashtra', 'West', 'Pune', 'Pune', 'Municipality', 6, 'Tole-88', 9123456788, 'Flat Rent', 1700000, 4, 2, 1, 1, 1, 'No', 'Flat in the educational hub of Pune', 18.520400, 73.856700, 2, 0, 0, '2024-11-13 00:00:12'),
(324, 'India', 'Gujarat', 'West', 'Surat', 'Surat', 'Municipality', 7, 'Tole-89', 9123456789, 'Room Rent', 300000, 1, 1, 0, 0, 1, 'No', 'Room in the diamond city of Surat', 21.170200, 72.831100, 2, 0, 0, '2024-11-13 00:00:12'),
(325, 'India', 'Rajasthan', 'North', 'Udaipur', 'Udaipur', 'Municipality', 8, 'Tole-90', 9123456790, 'Full House Rent', 1900000, 7, 4, 1, 1, 3, 'No', 'House with a view of Lake Pichola in Udaipur', 24.585400, 73.712500, 2, 0, 0, '2024-11-13 00:00:12'),
(326, 'India', 'Karnataka', 'South', 'Bangalore', 'Bangalore', 'Municipality', 10, 'Tole-91', 9123456791, 'Flat Rent', 1850000, 3, 2, 1, 1, 1, 'No', 'Modern flat in the IT hub of Bangalore', 12.971600, 77.594600, 2, 0, 0, '2024-11-13 00:00:12'),
(327, 'India', 'Tamil Nadu', 'South', 'Chennai', 'Chennai', 'Municipality', 7, 'Tole-92', 9123456792, 'Room Rent', 650000, 1, 1, 0, 0, 1, 'No', 'Affordable room near Marina Beach in Chennai', 13.082700, 80.270700, 2, 0, 0, '2024-11-13 00:00:12'),
(328, 'India', 'Uttar Pradesh', 'North', 'Agra', 'Agra', 'Municipality', 5, 'Tole-93', 9123456793, 'Full House Rent', 1800000, 5, 3, 1, 1, 2, 'No', 'House close to the Taj Mahal in Agra', 27.176700, 78.008100, 2, 0, 0, '2024-11-13 00:00:12'),
(329, 'India', 'West Bengal', 'East', 'Darjeeling', 'Darjeeling', 'Municipality', 6, 'Tole-94', 9123456794, 'Flat Rent', 1400000, 3, 2, 1, 1, 1, 'No', 'Flat with a view of the tea gardens in Darjeeling', 27.041000, 88.266300, 2, 0, 0, '2024-11-13 00:00:12'),
(330, 'India', 'Maharashtra', 'West', 'Aurangabad', 'Aurangabad', 'Municipality', 8, 'Tole-95', 9123456795, 'Room Rent', 500000, 2, 1, 0, 0, 1, 'No', 'Cozy room near the Bibi Ka Maqbara in Aurangabad', 19.876200, 75.343300, 2, 0, 0, '2024-11-13 00:00:12'),
(331, 'India', 'Gujarat', 'West', 'Vadodara', 'Vadodara', 'Municipality', 6, 'Tole-96', 9123456796, 'Full House Rent', 1600000, 6, 3, 1, 1, 2, 'No', 'House in the cultural city of Vadodara', 22.307200, 73.181200, 2, 0, 0, '2024-11-13 00:00:12'),
(332, 'India', 'Rajasthan', 'North', 'Bharatpur', 'Bharatpur', 'Municipality', 7, 'Tole-97', 9123456797, 'Flat Rent', 1200000, 3, 2, 1, 1, 1, 'No', 'Flat in the city of bird sanctuaries, Bharatpur', 27.217300, 77.489500, 2, 0, 0, '2024-11-13 00:00:12'),
(333, 'India', 'Karnataka', 'South', 'Gulbarga', 'Gulbarga', 'Municipality', 5, 'Tole-98', 9123456798, 'Room Rent', 350000, 1, 1, 0, 0, 1, 'No', 'Affordable room in the city of Gulbarga', 17.329700, 76.834300, 2, 0, 0, '2024-11-13 00:00:12'),
(334, 'India', 'Tamil Nadu', 'South', 'Erode', 'Erode', 'Municipality', 8, 'Tole-99', 9123456799, 'Full House Rent', 1500000, 5, 3, 1, 1, 2, 'No', 'House in the textile city of Erode', 11.341000, 77.717200, 2, 0, 0, '2024-11-13 00:00:12'),
(335, 'India', 'Uttar Pradesh', 'North', 'Aligarh', 'Aligarh', 'Municipality', 4, 'Tole-100', 9123456800, 'Flat Rent', 1100000, 2, 1, 1, 1, 1, 'No', 'Flat in the university city of Aligarh', 27.897400, 78.088000, 2, 0, 0, '2024-11-13 00:00:12'),
(336, 'India', 'West Bengal', 'East', 'Kharagpur', 'Kharagpur', 'Municipality', 5, 'Tole-101', 9123456801, 'Room Rent', 600000, 2, 1, 0, 0, 1, 'No', 'Room in the city with IIT campus, Kharagpur', 22.346000, 87.231900, 2, 0, 0, '2024-11-13 00:00:12');

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(10) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `email`, `password`) VALUES
(1, 'dmchauhan720@gmail.com', '12345');

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `property_id` int(10) NOT NULL,
  `tenant_id` int(10) NOT NULL,
  `booking_id` int(10) NOT NULL,
  `payment_mode` varchar(10) DEFAULT NULL,
  `amount_paid` decimal(10,2) DEFAULT NULL,
  `payment_due` decimal(10,2) DEFAULT NULL,
  `payment_timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `next_payment_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking`
--

INSERT INTO `booking` (`property_id`, `tenant_id`, `booking_id`, `payment_mode`, `amount_paid`, `payment_due`, `payment_timestamp`, `next_payment_date`) VALUES
(132, 23, 14, 'Y', 25000.00, 0.00, '2024-10-10 17:11:08', '2024-12-10 19:02:25'),
(133, 23, 15, 'Y', 20000.00, 0.00, '2024-10-10 17:11:08', '2024-12-10 19:02:25'),
(134, 23, 25, 'M', 6000.00, 6000.00, '2024-10-16 16:23:43', '2025-04-16 12:53:43'),
(237, 23, 26, 'M', 366666.67, 1833333.33, '2024-11-12 15:44:08', '2025-01-12 11:14:08'),
(248, 23, 30, 'M', 108333.33, 1191666.67, '2024-11-13 18:19:03', '2024-12-13 13:49:03'),
(250, 23, 31, 'M', 325000.00, 325000.00, '2024-11-28 03:29:15', '2025-05-27 22:59:15'),
(246, 23, 32, 'M', 500000.00, 1000000.00, '2024-12-07 06:39:06', '2025-04-07 02:09:06');

-- --------------------------------------------------------

--
-- Table structure for table `chat`
--

CREATE TABLE `chat` (
  `message` text DEFAULT NULL,
  `owner_id` int(10) NOT NULL,
  `tenant_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chat`
--

INSERT INTO `chat` (`message`, `owner_id`, `tenant_id`) VALUES
('', 0, 0),
('hello can you lower the price ? ? （*＾-＾*）', 2, 23),
('Hello are you even listening ?? ╰（‵□′）╯', 2, 23),
('Hello, I am asking you last time here ?? Hello ?? (►__◄)', 2, 23),
('hello There ??', 2, 23),
('Hii brother ,,,,', 2, 23),
('', 2, 23),
('', 2, 23),
('', 2, 23),
('', 2, 23),
('', 2, 23),
('Hello moto', 2, 23);

-- --------------------------------------------------------

--
-- Table structure for table `owner`
--

CREATE TABLE `owner` (
  `owner_id` int(10) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `phone_no` bigint(10) NOT NULL,
  `address` varchar(200) NOT NULL,
  `id_type` varchar(100) NOT NULL,
  `id_photo` varchar(1000) NOT NULL,
  `approved` int(11) DEFAULT 0,
  `blocked` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `owner`
--

INSERT INTO `owner` (`owner_id`, `full_name`, `email`, `password`, `phone_no`, `address`, `id_type`, `id_photo`, `approved`, `blocked`) VALUES
(2, 'Harshil', 'hsavaliya0110@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 9537896441, 'Ahmedabad', 'Citizenship', 'owner-photo/sql_quiz.png', 1, 0),
(15, 'Dobariya Meet', 'max@gmail.com', '8a20a8621978632d76c43dfd28b67767', 9854763210, 'surat', 'Citizenship', 'owner-photo/TCS_Certificate.png', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `property_photo`
--

CREATE TABLE `property_photo` (
  `property_photo_id` int(12) NOT NULL,
  `p_photo` varchar(500) NOT NULL,
  `property_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `property_photo`
--

INSERT INTO `property_photo` (`property_photo_id`, `p_photo`, `property_id`) VALUES
(184, 'product-photo/OIP 2.jpeg', 132),
(185, 'product-photo/OIP.jpeg', 132),
(186, 'product-photo/house3.jpeg', 133),
(187, 'product-photo/OIP (2).jpeg', 134),
(188, 'product-photo/OIP (1).jpeg', 134),
(494, 'product-photo/photo_1.jpg', 236),
(495, 'product-photo/photo_2.jpg', 237),
(496, 'product-photo/photo_3.jpg', 238),
(497, 'product-photo/photo_4.jpg', 239),
(498, 'product-photo/photo_5.jpg', 240),
(499, 'product-photo/photo_6.jpg', 241),
(500, 'product-photo/photo_7.jpg', 242),
(501, 'product-photo/photo_8.jpg', 243),
(502, 'product-photo/photo_9.jpg', 244),
(503, 'product-photo/photo_10.jpg', 245),
(504, 'product-photo/photo_11.jpg', 246),
(505, 'product-photo/photo_12.jpg', 247),
(506, 'product-photo/photo_13.jpg', 248),
(507, 'product-photo/photo_14.jpg', 249),
(508, 'product-photo/photo_15.jpg', 250),
(509, 'product-photo/photo_16.jpg', 251),
(510, 'product-photo/photo_17.jpg', 252),
(511, 'product-photo/photo_18.jpg', 253),
(512, 'product-photo/photo_19.jpg', 254),
(513, 'product-photo/photo_20.jpg', 255),
(514, 'product-photo/photo_21.jpg', 256),
(515, 'product-photo/photo_22.jpg', 257),
(516, 'product-photo/photo_23.jpg', 258),
(517, 'product-photo/photo_24.jpg', 259),
(518, 'product-photo/photo_25.jpg', 260),
(519, 'product-photo/photo_26.jpg', 261),
(520, 'product-photo/photo_27.jpg', 262),
(521, 'product-photo/photo_28.jpg', 263),
(522, 'product-photo/photo_29.jpg', 264),
(523, 'product-photo/photo_30.jpg', 265),
(524, 'product-photo/photo_31.jpg', 266),
(525, 'product-photo/photo_32.jpg', 267),
(526, 'product-photo/photo_33.jpg', 268),
(527, 'product-photo/photo_34.jpg', 269),
(532, 'product-photo/photo_39.jpg', 274),
(534, 'product-photo/photo_41.jpg', 276),
(535, 'product-photo/photo_42.jpg', 277),
(536, 'product-photo/photo_43.jpg', 278),
(537, 'product-photo/photo_44.jpg', 279),
(538, 'product-photo/photo_45.jpg', 280),
(539, 'product-photo/photo_46.jpg', 281),
(540, 'product-photo/photo_47.jpg', 282),
(541, 'product-photo/photo_48.jpg', 283),
(542, 'product-photo/photo_49.jpg', 284),
(543, 'product-photo/photo_50.jpg', 285),
(544, 'product-photo/photo_51.jpg', 286),
(545, 'product-photo/photo_52.jpg', 287),
(546, 'product-photo/photo_53.jpg', 288),
(547, 'product-photo/photo_54.jpg', 289),
(548, 'product-photo/photo_55.jpg', 290),
(549, 'product-photo/photo_56.jpg', 291),
(550, 'product-photo/photo_57.jpg', 292),
(551, 'product-photo/photo_58.jpg', 293),
(552, 'product-photo/photo_59.jpg', 294),
(553, 'product-photo/photo_60.jpg', 295),
(554, 'product-photo/photo_61.jpg', 296),
(555, 'product-photo/photo_62.jpg', 297),
(556, 'product-photo/photo_63.jpg', 298),
(557, 'product-photo/photo_64.jpg', 299),
(558, 'product-photo/photo_65.jpg', 300),
(559, 'product-photo/photo_66.jpg', 301),
(560, 'product-photo/photo_67.jpg', 302),
(561, 'product-photo/photo_68.jpg', 303),
(562, 'product-photo/photo_69.jpg', 304),
(563, 'product-photo/photo_70.jpg', 305),
(564, 'product-photo/photo_71.jpg', 306),
(565, 'product-photo/photo_72.jpg', 307),
(566, 'product-photo/photo_73.jpg', 308),
(567, 'product-photo/photo_74.jpg', 309),
(568, 'product-photo/photo_75.jpg', 310),
(569, 'product-photo/photo_76.jpg', 311),
(570, 'product-photo/photo_77.jpg', 312),
(571, 'product-photo/photo_78.jpg', 313),
(572, 'product-photo/photo_79.jpg', 314),
(573, 'product-photo/photo_80.jpg', 315),
(574, 'product-photo/photo_81.jpg', 316),
(575, 'product-photo/photo_82.jpg', 317),
(576, 'product-photo/photo_83.jpg', 318),
(577, 'product-photo/photo_84.jpg', 319),
(578, 'product-photo/photo_85.jpg', 320),
(579, 'product-photo/photo_86.jpg', 321),
(580, 'product-photo/photo_87.jpg', 322),
(581, 'product-photo/photo_88.jpg', 323),
(582, 'product-photo/photo_89.jpg', 324),
(583, 'product-photo/photo_90.jpg', 325),
(584, 'product-photo/photo_91.jpg', 326),
(585, 'product-photo/photo_92.jpg', 327),
(586, 'product-photo/photo_93.jpg', 328),
(587, 'product-photo/photo_94.jpg', 329),
(588, 'product-photo/photo_95.jpg', 330),
(589, 'product-photo/photo_96.jpg', 331),
(590, 'product-photo/photo_97.jpg', 332),
(591, 'product-photo/photo_98.jpg', 333),
(592, 'product-photo/photo_99.jpg', 334),
(593, 'product-photo/photo_100.jpg', 335),
(594, 'product-photo/photo_101.jpg', 336);

-- --------------------------------------------------------

--
-- Table structure for table `review`
--

CREATE TABLE `review` (
  `review_id` int(10) NOT NULL,
  `comment` varchar(500) NOT NULL,
  `rating` int(5) NOT NULL,
  `property_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `review`
--

INSERT INTO `review` (`review_id`, `comment`, `rating`, `property_id`) VALUES
(24, 'It is realy very nice place', 3, 132),
(25, 'really very nice place.', 4, 132);

-- --------------------------------------------------------

--
-- Table structure for table `tenant`
--

CREATE TABLE `tenant` (
  `tenant_id` int(10) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `phone_no` bigint(10) NOT NULL,
  `address` varchar(200) NOT NULL,
  `id_type` varchar(100) NOT NULL,
  `id_photo` varchar(1000) NOT NULL,
  `approved` int(11) DEFAULT 0,
  `blocked` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tenant`
--

INSERT INTO `tenant` (`tenant_id`, `full_name`, `email`, `password`, `phone_no`, `address`, `id_type`, `id_photo`, `approved`, `blocked`) VALUES
(23, 'Durgesh Mali', 'dmchaudhary720@gmail.com', '202cb962ac59075b964b07152d234b70', 7845128986, 'Surat', 'Citizenship', 'tenant-photo/sql_quiz.png', 1, 0),
(24, 'max', 'patel@gmail.com', '8a20a8621978632d76c43dfd28b67767', 5874963210, 'surat', 'Driving Licence', 'tenant-photo/TCS_Certificate.png', 1, 0),
(26, 'Ridham Fulmaliya', 'ridham@gmail.com', '202cb962ac59075b964b07152d234b70', 9854623568, 'Junagadh', 'Citizenship', 'tenant-photo/Screenshot 2023-04-23 171123.png', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone_no` varchar(20) NOT NULL,
  `address` varchar(255) NOT NULL,
  `id_type` varchar(255) NOT NULL,
  `id_photo` varchar(255) DEFAULT NULL,
  `approved` tinyint(1) NOT NULL,
  `blocked` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `full_name`, `email`, `password`, `phone_no`, `address`, `id_type`, `id_photo`, `approved`, `blocked`) VALUES
(2, 'Harshil', 'hpatel2101@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', '9517896441', 'Ahmedabad', 'Citizenship', 'owner-photo/sql_quiz.png', 1, 0),
(15, 'Dobariya Meet', 'max@gmail.com', '8a20a8621978632d76c43dfd28b67767', '9854763210', 'surat', 'Citizenship', 'owner-photo/TCS_Certificate.png', 1, 0),
(23, 'Durgesh Mali', 'dmchaudhary720@gmail.com', '202cb962ac59075b964b07152d234b70', '7845128986', 'Surat', 'Citizenship', 'tenant-photo/sql_quiz.png', 1, 0),
(24, 'max', 'patel@gmail.com', '8a20a8621978632d76c43dfd28b67767', '5874963210', 'surat', 'Driving Licence', 'tenant-photo/TCS_Certificate.png', 1, 0),
(26, 'Ridham Fulmaliya', 'ridham@gmail.com', '202cb962ac59075b964b07152d234b70', '9854623568', 'Junagadh', 'Citizenship', 'tenant-photo/Screenshot 2023-04-23 171123.png', 1, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `add_property`
--
ALTER TABLE `add_property`
  ADD PRIMARY KEY (`property_id`),
  ADD KEY `owner_id` (`owner_id`);

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`booking_id`);

--
-- Indexes for table `owner`
--
ALTER TABLE `owner`
  ADD PRIMARY KEY (`owner_id`);

--
-- Indexes for table `property_photo`
--
ALTER TABLE `property_photo`
  ADD PRIMARY KEY (`property_photo_id`),
  ADD KEY `property_id` (`property_id`);

--
-- Indexes for table `review`
--
ALTER TABLE `review`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `property_id` (`property_id`);

--
-- Indexes for table `tenant`
--
ALTER TABLE `tenant`
  ADD PRIMARY KEY (`tenant_id`);

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
-- AUTO_INCREMENT for table `add_property`
--
ALTER TABLE `add_property`
  MODIFY `property_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=337;

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `booking_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `owner`
--
ALTER TABLE `owner`
  MODIFY `owner_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `property_photo`
--
ALTER TABLE `property_photo`
  MODIFY `property_photo_id` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=595;

--
-- AUTO_INCREMENT for table `review`
--
ALTER TABLE `review`
  MODIFY `review_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `tenant`
--
ALTER TABLE `tenant`
  MODIFY `tenant_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `add_property`
--
ALTER TABLE `add_property`
  ADD CONSTRAINT `add_property_ibfk_1` FOREIGN KEY (`owner_id`) REFERENCES `owner` (`owner_id`);

--
-- Constraints for table `property_photo`
--
ALTER TABLE `property_photo`
  ADD CONSTRAINT `property_photo_ibfk_1` FOREIGN KEY (`property_id`) REFERENCES `add_property` (`property_id`);

--
-- Constraints for table `review`
--
ALTER TABLE `review`
  ADD CONSTRAINT `review_ibfk_1` FOREIGN KEY (`property_id`) REFERENCES `add_property` (`property_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
