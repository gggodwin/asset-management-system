-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 06, 2025 at 05:34 AM
-- Server version: 10.4.8-MariaDB
-- PHP Version: 7.3.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dbpis`
--

-- --------------------------------------------------------

--
-- Table structure for table `dbpis_department`
--

CREATE TABLE `dbpis_department` (
  `dept_id` int(11) NOT NULL,
  `dept_name` varchar(255) DEFAULT NULL,
  `dept_group` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `dbpis_department`
--

INSERT INTO `dbpis_department` (`dept_id`, `dept_name`, `dept_group`) VALUES
(1, 'College Department', 'Teaching');

-- --------------------------------------------------------

--
-- Table structure for table `dbpis_df`
--

CREATE TABLE `dbpis_df` (
  `df_no` int(11) NOT NULL,
  `staff_id` varchar(50) NOT NULL,
  `dept_id` int(11) NOT NULL,
  `unit_id` int(11) NOT NULL,
  `df_date` date NOT NULL,
  `df_reqstby` varchar(100) DEFAULT NULL,
  `rr_no` int(11) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `dbpis_df`
--

INSERT INTO `dbpis_df` (`df_no`, `staff_id`, `dept_id`, `unit_id`, `df_date`, `df_reqstby`, `rr_no`, `updated_at`) VALUES
(10020, 'Elena D. Cruz, Sr.', 1, 102, '2025-04-28', 'Jane Smith', 128, '2025-04-28 04:55:42'),
(10021, 'John Doe', 1, 102, '2025-04-28', 'Jane Smith', 129, '2025-04-28 05:50:27'),
(10022, 'John Doe', 1, 101, '2025-05-07', 'Antonio C. Reyes', 130, '2025-05-07 03:28:49'),
(10023, 'John Doe', 1, 101, '2025-05-07', 'Antonio C. Reyes', 131, '2025-05-07 03:32:36'),
(10024, 'Elena D. Cruz Sr.', 1, 102, '2025-06-13', 'Jane Smith', 146, '2025-06-13 08:10:10'),
(10025, 'John Doe', 1, 102, '2025-06-13', 'Jane Smith', 146, '2025-06-13 08:21:57');

-- --------------------------------------------------------

--
-- Table structure for table `dbpis_df_details`
--

CREATE TABLE `dbpis_df_details` (
  `df_details_id` int(11) NOT NULL,
  `df_no` int(11) DEFAULT NULL,
  `it_no` int(11) DEFAULT NULL,
  `df_qty` int(11) DEFAULT NULL,
  `df_unit` varchar(50) DEFAULT NULL,
  `df_amount` decimal(10,2) DEFAULT NULL,
  `eq_no` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `dbpis_df_details`
--

INSERT INTO `dbpis_df_details` (`df_details_id`, `df_no`, `it_no`, `df_qty`, `df_unit`, `df_amount`, `eq_no`, `created_at`, `updated_at`) VALUES
(57, 10020, 4024017, 5, 'box', '1500.00', NULL, '2025-04-28 04:55:42', '2025-06-11 15:41:13'),
(58, 10021, 1021002, 1, 'pcs', '25000.00', '5032', '2025-04-28 05:50:27', '2025-04-28 05:50:27'),
(59, 10021, 1021002, 1, 'pcs', '25000.00', '5033', '2025-04-28 05:50:27', '2025-04-28 05:50:27'),
(60, 10021, 1021002, 1, 'pcs', '25000.00', '5034', '2025-04-28 05:50:27', '2025-04-28 05:50:27'),
(61, 10022, 2012026, 1, 'boxes', '150.00', '5035', '2025-05-07 03:28:49', '2025-05-07 03:28:49'),
(62, 10022, 2012026, 1, 'boxes', '150.00', '5036', '2025-05-07 03:28:49', '2025-05-07 03:28:49'),
(63, 10022, 2012026, 1, 'boxes', '150.00', '5037', '2025-05-07 03:28:49', '2025-05-07 03:28:49'),
(64, 10022, 2012026, 1, 'boxes', '150.00', '5038', '2025-05-07 03:28:49', '2025-05-07 03:28:49'),
(65, 10022, 2012026, 1, 'boxes', '150.00', '5039', '2025-05-07 03:28:49', '2025-05-07 03:28:49'),
(66, 10023, 1011021, 1, 'pcs', '2500.00', '5040', '2025-05-07 03:32:36', '2025-05-07 03:32:36'),
(67, 10023, 1011021, 1, 'pcs', '2500.00', '5041', '2025-05-07 03:32:36', '2025-05-07 03:32:36'),
(68, 10023, 1011021, 1, 'pcs', '2500.00', '5042', '2025-05-07 03:32:36', '2025-05-07 03:32:36'),
(69, 10023, 1011021, 1, 'pcs', '2500.00', '5043', '2025-05-07 03:32:36', '2025-05-07 03:32:36'),
(70, 10023, 1011021, 1, 'pcs', '2500.00', '5044', '2025-05-07 03:32:36', '2025-05-07 03:32:36'),
(71, 10024, 4024017, 5, 'rolls', '500.00', NULL, '2025-06-13 08:10:10', '2025-06-13 08:10:10'),
(72, 10025, 1021022, 1, 'pcs', '600.00', '5050', '2025-06-13 08:21:57', '2025-06-13 08:21:57'),
(73, 10025, 1021022, 1, 'pcs', '600.00', '5051', '2025-06-13 08:21:57', '2025-06-13 08:21:57'),
(74, 10025, 1021022, 1, 'pcs', '600.00', '5052', '2025-06-13 08:21:57', '2025-06-13 08:21:57'),
(75, 10025, 1021022, 1, 'pcs', '600.00', '5053', '2025-06-13 08:21:57', '2025-06-13 08:21:57'),
(76, 10025, 1021022, 1, 'pcs', '600.00', '5054', '2025-06-13 08:21:57', '2025-06-13 08:21:57');

-- --------------------------------------------------------

--
-- Table structure for table `dbpis_eq_tagging`
--

CREATE TABLE `dbpis_eq_tagging` (
  `eq_no` int(11) NOT NULL,
  `emp_id` varchar(50) DEFAULT NULL,
  `eq_loc` varchar(255) DEFAULT NULL,
  `eq_date` date DEFAULT NULL,
  `eq_dept` varchar(100) DEFAULT NULL,
  `rr_no` int(11) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Processing'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `dbpis_eq_tagging`
--

INSERT INTO `dbpis_eq_tagging` (`eq_no`, `emp_id`, `eq_loc`, `eq_date`, `eq_dept`, `rr_no`, `status`) VALUES
(5032, 'Jane Smith', '102', '2025-04-28', 'College Department', 129, 'Deployed'),
(5033, 'Jane Smith', 'Info Tech Lab', '2025-04-28', 'College Department', 129, 'Deployed'),
(5034, 'Jane Smith', 'Info Tech Lab', '2025-04-28', 'College Department', 129, 'Deployed'),
(5035, 'Antonio C. Reyes', 'Faculty Office', '2025-04-28', 'College Department', 130, 'Deployed'),
(5036, 'Antonio C. Reyes', 'Faculty Office', '2025-04-28', 'College Department', 130, 'Deployed'),
(5037, 'Antonio C. Reyes', 'Faculty Office', '2025-04-28', 'College Department', 130, 'Deployed'),
(5038, 'Antonio C. Reyes', 'Faculty Office', '2025-04-28', 'College Department', 130, 'Deployed'),
(5039, 'Antonio C. Reyes', 'Faculty Office', '2025-04-28', 'College Department', 130, 'Deployed'),
(5040, 'Antonio C. Reyes', 'Faculty Office', '2025-05-07', 'College Department', 131, 'Deployed'),
(5041, 'Antonio C. Reyes', 'Faculty Office', '2025-05-07', 'College Department', 131, 'Deployed'),
(5042, 'Antonio C. Reyes', 'Faculty Office', '2025-05-07', 'College Department', 131, 'Deployed'),
(5043, 'Antonio C. Reyes', 'Faculty Office', '2025-05-07', 'College Department', 131, 'Deployed'),
(5044, 'Antonio C. Reyes', 'Faculty Office', '2025-05-07', 'College Department', 131, 'Deployed'),
(5045, 'Jane Smith', 'Info Tech Lab', '2025-05-09', 'College Department', 138, 'Processing'),
(5046, 'Jane Smith', 'Info Tech Lab', '2025-05-09', 'College Department', 138, 'Processing'),
(5047, 'Jane Smith', 'Info Tech Lab', '2025-05-09', 'College Department', 138, 'Processing'),
(5048, 'Jane Smith', 'Info Tech Lab', '2025-05-09', 'College Department', 138, 'Processing'),
(5049, 'Jane Smith', 'Info Tech Lab', '2025-05-09', 'College Department', 138, 'Processing'),
(5050, 'Jane Smith', 'Info Tech Lab', '2025-06-13', 'College Department', 146, 'Deployed'),
(5051, 'Jane Smith', 'Info Tech Lab', '2025-06-13', 'College Department', 146, 'Deployed'),
(5052, 'Jane Smith', 'Info Tech Lab', '2025-06-13', 'College Department', 146, 'Deployed'),
(5053, 'Jane Smith', 'Info Tech Lab', '2025-06-13', 'College Department', 146, 'Deployed'),
(5054, 'Jane Smith', 'Info Tech Lab', '2025-06-13', 'College Department', 146, 'Deployed'),
(5055, 'Jane Smith', 'Info Tech Lab', '2025-07-31', 'College Department', 128, 'Processing'),
(5056, 'Jane Smith', 'Info Tech Lab', '2025-07-31', 'College Department', 128, 'Processing'),
(5057, 'Jane Smith', 'Faculty Office', '2025-08-05', 'College Department', 145, 'Processing');

-- --------------------------------------------------------

--
-- Table structure for table `dbpis_eq_tag_details`
--

CREATE TABLE `dbpis_eq_tag_details` (
  `eqd_id` int(11) NOT NULL,
  `eq_no` int(11) DEFAULT NULL,
  `it_no` varchar(255) DEFAULT NULL,
  `pr_code` varchar(255) DEFAULT NULL,
  `eq_remarks` text DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `expected_life_span` float(3,1) NOT NULL DEFAULT 1.0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `dbpis_eq_tag_details`
--

INSERT INTO `dbpis_eq_tag_details` (`eqd_id`, `eq_no`, `it_no`, `pr_code`, `eq_remarks`, `quantity`, `status`, `expected_life_span`) VALUES
(32, 5032, '1021002', '1021002', 'Good condition', 1, 'Good', 1.5),
(33, 5033, '1021002', '1021002', 'Good condition', 1, 'Good', 1.0),
(34, 5034, '1021002', '1021002', 'Good condition', 1, 'Good', 1.0),
(35, 5035, '2012026', '2012026', 'Good condition', 1, 'Good', 1.0),
(36, 5036, '2012026', '2012026', 'Good condition', 1, 'Good', 1.0),
(37, 5037, '2012026', '2012026', 'Good condition', 1, 'Good', 1.0),
(38, 5038, '2012026', '2012026', 'Good condition', 1, 'Good', 1.0),
(39, 5039, '2012026', '2012026', 'Good condition', 1, 'Good', 1.0),
(40, 5040, '1011021', '1011021', 'Good condition', 1, 'Good', 1.0),
(41, 5041, '1011021', '1011021', 'Good condition', 1, 'Good', 1.0),
(42, 5042, '1011021', '1011021', 'Good condition', 1, 'Good', 1.0),
(43, 5043, '1011021', '1011021', 'Good condition', 1, 'Good', 1.0),
(44, 5044, '1011021', '1011021', 'Good condition', 1, 'Good', 1.0),
(45, 5045, '2022007', '2022007', 'Good condition', 1, 'Good', 1.0),
(46, 5046, '2022007', '2022007', 'Good condition', 1, 'Good', 1.0),
(47, 5047, '2022007', '2022007', 'Good condition', 1, 'Good', 1.0),
(48, 5048, '2022007', '2022007', 'Good condition', 1, 'Good', 1.0),
(49, 5049, '2022007', '2022007', 'Good condition', 1, 'Good', 1.0),
(50, 5050, '1021022', '1021022', 'Good condition', 1, 'Good', 1.0),
(51, 5051, '1021022', '1021022', 'Good condition', 1, 'Good', 1.0),
(52, 5052, '1021022', '1021022', 'Good condition', 1, 'Good', 1.0),
(53, 5053, '1021022', '1021022', 'Good condition', 1, 'Good', 1.0),
(54, 5054, '1021022', '1021022', 'Good condition', 1, 'Good', 1.0),
(55, 5055, '1021002', '00624_DBCITL1021002', 'Good condition', 1, 'Good', 1.0),
(56, 5056, '1021002', '00624_DBCITL1021002', 'Good condition', 1, 'Good', 1.0),
(57, 5057, '1021002', '00624_DBCFAC1021002', 'Good condition', 1, 'Good', 3.0);

-- --------------------------------------------------------

--
-- Stand-in structure for view `dbpis_get_rr`
-- (See below for the actual view)
--
CREATE TABLE `dbpis_get_rr` (
`rr_no` int(11)
,`date_received` date
,`invoice_no` varchar(50)
,`invoice_date` date
,`received_by` varchar(255)
,`received_from` varchar(255)
,`department` varchar(255)
,`prs_id` int(11)
,`prs_no` varchar(50)
,`prs_date` date
,`po_no` varchar(50)
,`po_date` date
,`item_barcode` varchar(255)
,`item_brand` varchar(255)
,`item_particular` varchar(255)
,`quantity` int(11)
,`unit` varchar(50)
,`unit_price` decimal(10,2)
,`total_price` decimal(12,2)
,`status` varchar(50)
,`rr_created_at` timestamp
,`rr_updated_at` timestamp
,`detail_created_at` timestamp
,`detail_updated_at` timestamp
);

-- --------------------------------------------------------

--
-- Table structure for table `dbpis_itemcategory_group`
--

CREATE TABLE `dbpis_itemcategory_group` (
  `itemcatgrp_id` int(11) NOT NULL,
  `itemcatgrp_name` varchar(255) DEFAULT NULL,
  `for_tagging` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `dbpis_itemcategory_group`
--

INSERT INTO `dbpis_itemcategory_group` (`itemcatgrp_id`, `itemcatgrp_name`, `for_tagging`) VALUES
(1, 'Tools', 1),
(2, 'Equipment', 1),
(3, 'Tables and Fixtures', 1),
(4, 'Consumables', 0);

-- --------------------------------------------------------

--
-- Table structure for table `dbpis_items`
--

CREATE TABLE `dbpis_items` (
  `id` int(11) NOT NULL,
  `barcode` varchar(255) NOT NULL,
  `particular` varchar(255) NOT NULL,
  `brand` varchar(255) DEFAULT NULL,
  `quantity` int(11) DEFAULT 0,
  `units` varchar(20) DEFAULT NULL,
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `category` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `dbpis_items`
--

INSERT INTO `dbpis_items` (`id`, `barcode`, `particular`, `brand`, `quantity`, `units`, `last_updated`, `category`) VALUES
(435, '1011001', 'Stapler', 'Swingline', NULL, 'pcs', '2025-04-15 02:47:59', 101),
(436, '1021002', 'Laptop', 'Dell', NULL, 'pcs', '2025-04-15 02:47:59', 102),
(437, '1031003', 'Adjustable Wrench', 'Craftsman', NULL, 'pcs', '2025-04-15 02:47:59', 103),
(438, '1041004', 'Cordless Drill', 'Bosch', NULL, 'pcs', '2025-04-15 02:47:59', 104),
(439, '1051005', 'Digital Multimeter', 'Fluke', NULL, 'pcs', '2025-04-15 02:47:59', 105),
(440, '2012006', 'A4 Paper Ream', 'HP', NULL, 'reams', '2025-08-05 11:10:00', 401),
(441, '2022007', '24-inch Monitor', 'Samsung', NULL, 'pcs', '2025-04-15 02:47:59', 202),
(442, '2032008', 'Motor Oil (1L)', 'Castrol', NULL, 'liters', '2025-04-15 02:47:59', 203),
(443, '2042009', 'Metal Lathe', 'Grizzly', NULL, 'unit', '2025-04-15 02:47:59', 204),
(444, '2052010', 'Electrical Wire (100m)', 'Southwire', NULL, 'rolls', '2025-04-15 02:47:59', 205),
(445, '3013011', 'Office Desk', 'IKEA', NULL, 'pcs', '2025-04-15 02:47:59', 301),
(446, '3023012', 'Wireless Keyboard', 'Logitech', NULL, 'pcs', '2025-04-15 02:47:59', 302),
(447, '3033013', 'Car Tire', 'Bridgestone', NULL, 'pcs', '2025-04-15 02:47:59', 303),
(448, '3043014', 'Milling Machine', 'Haas', NULL, 'unit', '2025-04-15 02:47:59', 304),
(449, '3053015', 'Relay Switch', 'Omron', NULL, 'pcs', '2025-04-15 02:47:59', 305),
(450, '4014016', 'Printer Ink Cartridge', 'HP', NULL, 'pcs', '2025-04-15 02:47:59', 401),
(451, '4024017', 'Ethernet Cable (Roll)', 'Generic', NULL, 'rolls', '2025-04-15 02:47:59', 402),
(452, '4034018', 'Brake Fluid (1L)', 'Dot 4', NULL, 'liters', '2025-04-15 02:47:59', 403),
(453, '4044019', 'Cutting Oil (1L)', 'Mobil', 0, 'ltr', '2025-04-15 03:08:55', 404),
(454, '4054020', 'Solder Wire (Roll)', 'Kester', 0, 'roll', '2025-04-15 03:07:06', 405),
(455, '1011021', 'Office Chair', 'Steelcase', NULL, 'pcs', '2025-04-15 02:47:59', 101),
(456, '1021022', 'Wireless Router', 'Netgear', NULL, 'pcs', '2025-04-15 02:47:59', 102),
(457, '1031023', 'Car Battery', 'ACDelco', NULL, 'pcs', '2025-04-15 02:47:59', 103),
(458, '1041024', 'Welding Machine', 'Lincoln', NULL, 'pcs', '2025-04-15 02:47:59', 104),
(459, '1051025', 'Proximity Sensor', 'Keyence', NULL, 'pcs', '2025-04-15 02:47:59', 105),
(460, '2012026', 'File Folders (Box)', 'Oxford', NULL, 'boxes', '2025-04-15 02:47:59', 201),
(461, '2022027', 'Laser Printer', 'Brother', NULL, 'pcs', '2025-04-15 02:47:59', 202),
(462, '2032028', 'Hydraulic Jack', 'Torin', NULL, 'pcs', '2025-04-15 02:47:59', 203),
(463, '2042029', 'Hammer', 'Stanley', NULL, 'pcs', '2025-04-15 02:47:59', 204),
(464, '2052030', 'Electrical Contactor', 'Siemens', NULL, 'pcs', '2025-04-15 02:47:59', 205),
(473, '4054021', 'Copper Wire', 'Royu', 0, 'pcs', '2025-04-22 08:56:29', 405),
(502, '4054022', 'Light Bulb', 'Philips', 0, 'pcs', '2025-04-24 08:55:31', 405),
(503, '2022028', 'Mouse', 'A4 Tech', 0, 'pcs', '2025-04-25 02:01:35', 202),
(504, '2052031', 'sample', 'sample', 0, 'pack', '2025-05-11 09:49:38', 205),
(506, '1021023', 'Laptop', 'Any Brand', 0, 'pcs', '2025-06-14 02:16:04', 102);

-- --------------------------------------------------------

--
-- Table structure for table `dbpis_item_category`
--

CREATE TABLE `dbpis_item_category` (
  `itcat_id` int(11) NOT NULL,
  `itcat_name` varchar(255) DEFAULT NULL,
  `itemcatgrp_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `dbpis_item_category`
--

INSERT INTO `dbpis_item_category` (`itcat_id`, `itcat_name`, `itemcatgrp_id`) VALUES
(101, 'Office Assets', 1),
(102, 'Info Tech', 1),
(103, 'Automotive Tech', 1),
(104, 'Mechanical Tech', 1),
(105, 'Electromechanical', 1),
(201, 'Office Assets', 2),
(202, 'Info Tech', 2),
(203, 'Automotive Tech', 2),
(204, 'Mechanical Tech', 2),
(205, 'Electromechanical', 2),
(301, 'Office Assets', 3),
(302, 'Info Tech', 3),
(303, 'Automotive Tech', 3),
(304, 'Mechanical Tech', 3),
(305, 'Electromechanical', 3),
(401, 'Office Assets', 4),
(402, 'Info Tech', 4),
(403, 'Automotive Tech', 4),
(404, 'Mechanical Tech', 4),
(405, 'Electromechanical', 4);

-- --------------------------------------------------------

--
-- Table structure for table `dbpis_item_suppliers`
--

CREATE TABLE `dbpis_item_suppliers` (
  `item_id` int(11) NOT NULL,
  `supplier_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `dbpis_item_suppliers`
--

INSERT INTO `dbpis_item_suppliers` (`item_id`, `supplier_id`) VALUES
(435, 2),
(435, 3),
(436, 19),
(437, 1),
(438, 3),
(439, 4),
(440, 2),
(441, 19),
(442, 1),
(443, 3),
(444, 4),
(445, 2),
(446, 19),
(447, 1),
(448, 3),
(449, 4),
(450, 2),
(451, 19),
(452, 1),
(453, 3),
(454, 4),
(455, 2),
(456, 19),
(457, 1),
(458, 3),
(459, 4),
(460, 1),
(460, 2),
(461, 19),
(462, 1),
(463, 3),
(464, 4),
(473, 3),
(502, 1),
(503, 19);

-- --------------------------------------------------------

--
-- Table structure for table `dbpis_menusidebar`
--

CREATE TABLE `dbpis_menusidebar` (
  `menu_id` int(11) NOT NULL,
  `menu_name` varchar(255) NOT NULL,
  `menu_icon` longtext NOT NULL,
  `menu_link` varchar(255) DEFAULT NULL,
  `modid` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `dbpis_menusidebar`
--

INSERT INTO `dbpis_menusidebar` (`menu_id`, `menu_name`, `menu_icon`, `menu_link`, `modid`) VALUES
(1, 'Property Custodian', '<svg  xmlns=\"http://www.w3.org/2000/svg\"  width=\"24\"  height=\"24\"  viewBox=\"0 0 24 24\"  fill=\"none\"  stroke=\"currentColor\"  stroke-width=\"2\"  stroke-linecap=\"round\"  stroke-linejoin=\"round\"  class=\"icon icon-tabler icons-tabler-outline icon-tabler-clipboard-list\"><path stroke=\"none\" d=\"M0 0h24v24H0z\" fill=\"none\"/><path d=\"M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2\" /><path d=\"M9 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z\" /><path d=\"M9 12l.01 0\" /><path d=\"M13 12l2 0\" /><path d=\"M9 16l.01 0\" /><path d=\"M13 16l2 0\" /></svg>', '../custodian', 0),
(2, 'Requestor', '<svg  xmlns=\"http://www.w3.org/2000/svg\"  width=\"24\"  height=\"24\"  viewBox=\"0 0 24 24\"  fill=\"none\"  stroke=\"currentColor\"  stroke-width=\"2\"  stroke-linecap=\"round\"  stroke-linejoin=\"round\"  class=\"icon icon-tabler icons-tabler-outline icon-tabler-pencil-exclamation\"><path stroke=\"none\" d=\"M0 0h24v24H0z\" fill=\"none\"/><path d=\"M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4\" /><path d=\"M13.5 6.5l4 4\" /><path d=\"M19 16v3\" /><path d=\"M19 22v.01\" /></svg>', '../requestor', 0),
(3, 'Department Head', '<svg  xmlns=\"http://www.w3.org/2000/svg\"  width=\"24\"  height=\"24\"  viewBox=\"0 0 24 24\"  fill=\"none\"  stroke=\"currentColor\"  stroke-width=\"2\"  stroke-linecap=\"round\"  stroke-linejoin=\"round\"  class=\"icon icon-tabler icons-tabler-outline icon-tabler-users-group\"><path stroke=\"none\" d=\"M0 0h24v24H0z\" fill=\"none\"/><path d=\"M10 13a2 2 0 1 0 4 0a2 2 0 0 0 -4 0\" /><path d=\"M8 21v-1a2 2 0 0 1 2 -2h4a2 2 0 0 1 2 2v1\" /><path d=\"M15 5a2 2 0 1 0 4 0a2 2 0 0 0 -4 0\" /><path d=\"M17 10h2a2 2 0 0 1 2 2v1\" /><path d=\"M5 5a2 2 0 1 0 4 0a2 2 0 0 0 -4 0\" /><path d=\"M3 13v-1a2 2 0 0 1 2 -2h2\" /></svg>', '../dept_head', 0),
(4, 'Admin', 'cil-check', 'index.php', 4),
(5, 'Purchaser', '<svg  xmlns=\"http://www.w3.org/2000/svg\"  width=\"24\"  height=\"24\"  viewBox=\"0 0 24 24\"  fill=\"none\"  stroke=\"currentColor\"  stroke-width=\"2\"  stroke-linecap=\"round\"  stroke-linejoin=\"round\"  class=\"icon icon-tabler icons-tabler-outline icon-tabler-shopping-cart\"><path stroke=\"none\" d=\"M0 0h24v24H0z\" fill=\"none\"/><path d=\"M6 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0\" /><path d=\"M17 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0\" /><path d=\"M17 17h-11v-14h-2\" /><path d=\"M6 5l14 1l-1 7h-13\" /></svg>', '../purchaser', 0),
(6, 'Useraccounts', '<svg  xmlns=\"http://www.w3.org/2000/svg\"  width=\"24\"  height=\"24\"  viewBox=\"0 0 24 24\"  fill=\"none\"  stroke=\"currentColor\"  stroke-width=\"2\"  stroke-linecap=\"round\"  stroke-linejoin=\"round\"  class=\"icon icon-tabler icons-tabler-outline icon-tabler-password-user\"><path stroke=\"none\" d=\"M0 0h24v24H0z\" fill=\"none\"/><path d=\"M12 17v4\" /><path d=\"M10 20l4 -2\" /><path d=\"M10 18l4 2\" /><path d=\"M5 17v4\" /><path d=\"M3 20l4 -2\" /><path d=\"M3 18l4 2\" /><path d=\"M19 17v4\" /><path d=\"M17 20l4 -2\" /><path d=\"M17 18l4 2\" /><path d=\"M9 6a3 3 0 1 0 6 0a3 3 0 0 0 -6 0\" /><path d=\"M7 14a2 2 0 0 1 2 -2h6a2 2 0 0 1 2 2\" /></svg>', '../sys-admin', 0);

-- --------------------------------------------------------

--
-- Table structure for table `dbpis_pricelist`
--

CREATE TABLE `dbpis_pricelist` (
  `price_id` int(11) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `item_code` varchar(50) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `dbpis_pricelist`
--

INSERT INTO `dbpis_pricelist` (`price_id`, `supplier_id`, `item_code`, `unit_price`) VALUES
(1, 1, '100001', '4.99'),
(3, 1, '100003', '8.99'),
(4, 1, '100004', '59.99'),
(5, 1, '100005', '39.99'),
(6, 2, '100006', '19.99'),
(7, 2, '100007', '49.99'),
(8, 1, '100008', '240.00'),
(9, 2, '100009', '19.99'),
(10, 2, '100010', '99.99'),
(11, 1, '100011', '69.99'),
(12, 1, '100012', '39.99'),
(13, 1, '100013', '14.99'),
(14, 1, '100014', '25.99'),
(15, 1, '100015', '19.99'),
(16, 1, '100016', '29.99'),
(17, 1, '100017', '39.99'),
(18, 1, '100018', '24.99'),
(19, 1, '100019', '59.99'),
(20, 1, '100020', '29.99'),
(21, 1, '100021', '199.99'),
(22, 1, '100022', '29.99'),
(23, 1, '100023', '19.99'),
(24, 1, '100024', '89.99'),
(25, 1, '100025', '29.99'),
(26, 1, '100026', '49.99'),
(27, 1, '100027', '149.99'),
(28, 2, '100028', '19.99'),
(29, 2, '100029', '8.99'),
(30, 2, '100030', '12.99'),
(31, 2, '100031', '15.99'),
(32, 2, '100032', '19.99'),
(33, 2, '100033', '3.99'),
(34, 2, '100034', '4.99'),
(35, 2, '100035', '29.99'),
(36, 2, '100036', '49.99'),
(37, 2, '100037', '12.99'),
(38, 2, '100038', '14.99'),
(39, 2, '100039', '2.49'),
(40, 2, '100040', '15.99'),
(41, 2, '100041', '39.99'),
(42, 2, '100042', '24.99'),
(43, 1, '100043', '59.99'),
(44, 2, '100044', '29.99'),
(45, 2, '100045', '49.99'),
(46, 2, '100046', '69.99'),
(47, 2, '100047', '24.99'),
(48, 1, '100048', '299.99'),
(49, 2, '100049', '79.99'),
(50, 3, '100050', '15.99'),
(51, 3, '100051', '59.99'),
(52, 3, '100052', '8.99'),
(53, 4, '100053', '39.99'),
(54, 4, '100054', '12.99'),
(55, 4, '100055', '29.99'),
(56, 2, '100002', '24.00'),
(57, 4, '100004', '300.00'),
(58, 4, '100044', '21.00'),
(59, 2, '100056', '100.00');

-- --------------------------------------------------------

--
-- Table structure for table `dbpis_prs`
--

CREATE TABLE `dbpis_prs` (
  `prs_id` int(11) NOT NULL,
  `prs_code` varchar(50) NOT NULL,
  `requested_by` varchar(100) NOT NULL,
  `department` varchar(100) DEFAULT NULL,
  `date_requested` date NOT NULL,
  `dept_head` varchar(255) NOT NULL,
  `approval_status` enum('Pending','Approved','Rejected','Deployed','Received','Purchase') DEFAULT 'Pending',
  `approved_by` varchar(255) NOT NULL,
  `date_needed` date DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `unit_id` int(11) NOT NULL DEFAULT 101,
  `admin_remarks` text DEFAULT NULL,
  `purchase_remarks` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `dbpis_prs`
--

INSERT INTO `dbpis_prs` (`prs_id`, `prs_code`, `requested_by`, `department`, `date_requested`, `dept_head`, `approval_status`, `approved_by`, `date_needed`, `remarks`, `created_at`, `updated_at`, `unit_id`, `admin_remarks`, `purchase_remarks`) VALUES
(351, '20250001', 'Antonio C. Reyes', 'College Department', '2025-04-28', 'Arisu Johnson', 'Approved', 'Max Jones', '2025-04-29', 'faculty needed', '2025-04-28 04:24:43', '2025-04-28 04:37:01', 101, 'reduce the quantity of file to 5', '‘File Folders (Box) - Oxford’ quantity changed from 10 to 5'),
(352, '20250002', 'Antonio C. Reyes', 'College Department', '2025-04-28', 'Arisu Johnson', 'Rejected', '', '2025-04-29', 'sample', '2025-04-28 04:32:00', '2025-04-28 04:33:48', 103, NULL, NULL),
(353, '20250003', 'Antonio C. Reyes', 'College Department', '2025-04-28', 'Arisu Johnson', 'Rejected', 'Max Jones', '2025-04-29', 'sample', '2025-04-28 04:33:09', '2025-04-28 04:35:26', 105, 'not needed', NULL),
(354, '20250004', 'Jane Smith', 'College Department', '2025-04-28', 'Arisu Johnson', 'Approved', 'Max Jones', '2025-04-29', 'for nc II', '2025-04-28 04:39:04', '2025-06-09 08:03:11', 102, NULL, 'we still have 5 unused in the stockroom'),
(355, '20250005', 'Jane Smith', 'College Department', '2025-05-06', 'Arisu Johnson', 'Approved', 'Max Jones', '2025-05-07', 'sad', '2025-05-06 13:51:03', '2025-06-09 08:03:11', 102, 's', NULL),
(356, '20250006', 'Antonio C. Reyes', 'College Department', '2025-05-06', 'Arisu Johnson', 'Approved', 'Max Jones', '2025-05-07', 'sad', '2025-05-06 14:21:50', '2025-05-06 14:22:39', 102, NULL, NULL),
(357, '20250007', 'Jane Smith', 'College Department', '2025-05-07', 'Arisu Johnson', 'Approved', 'Max Jones', '2025-05-08', 'sample', '2025-05-07 02:58:17', '2025-06-09 08:03:11', 102, NULL, NULL),
(358, '20250008', 'Jane Smith', 'College Department', '2025-05-08', 'Arisu Johnson', 'Approved', 'Max Jones', '2025-05-09', 'sample', '2025-05-08 16:22:45', '2025-06-09 08:03:11', 101, NULL, NULL),
(359, '20250009', 'Jane Smith', 'College Department', '2025-05-09', 'Arisu Johnson', 'Approved', 'Max Jones', '2025-05-10', 'sample', '2025-05-09 04:05:01', '2025-06-09 08:03:11', 101, NULL, NULL),
(360, '20250010', 'Jane Smith', 'College Department', '2025-05-09', 'Arisu Johnson', 'Approved', 'Max Jones', '2025-06-01', 'No remarks', '2025-05-09 04:41:43', '2025-06-09 08:03:11', 101, NULL, NULL),
(361, '20250011', 'Jane Smith', 'College Department', '2025-05-09', 'Arisu Johnson', 'Approved', 'Max Jones', '2025-06-02', 'No remarks', '2025-05-09 04:41:43', '2025-06-09 08:03:11', 101, NULL, NULL),
(362, '20250012', 'Jane Smith', 'College Department', '2025-05-09', 'Arisu Johnson', 'Approved', 'Max Jones', '2025-06-03', 'No remarks', '2025-05-09 04:41:43', '2025-06-09 08:03:11', 101, NULL, NULL),
(363, '20250013', 'Jane Smith', 'College Department', '2025-05-09', 'Arisu Johnson', 'Approved', 'Max Jones', '2025-06-04', 'No remarks', '2025-05-09 04:41:43', '2025-06-09 08:03:11', 101, NULL, NULL),
(364, '20250014', 'Jane Smith', 'College Department', '2025-05-09', 'Arisu Johnson', 'Approved', 'Max Jones', '2025-06-05', 'No remarks', '2025-05-09 04:41:43', '2025-06-09 08:03:11', 101, NULL, NULL),
(365, '20250015', 'Jane Smith', 'College Department', '2025-05-09', 'Arisu Johnson', 'Approved', 'Max Jones', '2025-06-06', 'No remarks', '2025-05-09 04:41:43', '2025-06-09 08:03:11', 101, 'sample', NULL),
(366, '20250016', 'Jane Smith', 'College Department', '2025-05-09', 'Arisu Johnson', 'Approved', 'Max Jones', '2025-06-07', 'No remarks', '2025-05-09 04:41:43', '2025-06-09 08:03:11', 101, NULL, NULL),
(367, '20250017', 'Jane Smith', 'College Department', '2025-05-09', 'Arisu Johnson', 'Pending', '', '2025-06-08', 'No remarks', '2025-05-09 04:41:43', '2025-06-13 08:46:00', 101, NULL, NULL),
(368, '20250018', 'Jane Smith', 'College Department', '2025-05-09', '', 'Pending', '', '2025-06-09', 'No remarks', '2025-05-09 04:41:43', '2025-06-09 08:03:11', 101, NULL, NULL),
(369, '20250019', 'Jane Smith', 'College Department', '2025-05-09', '', 'Pending', '', '2025-06-10', 'No remarks', '2025-05-09 04:41:43', '2025-06-09 08:03:11', 101, NULL, NULL),
(370, '20250020', 'Jane Smith', 'College Department', '2025-05-09', '', 'Pending', '', '2025-06-11', 'No remarks', '2025-05-09 04:41:43', '2025-06-09 08:03:11', 101, NULL, NULL),
(371, '20250021', 'Jane Smith', 'College Department', '2025-05-09', '', 'Pending', '', '2025-06-12', 'No remarks', '2025-05-09 04:41:43', '2025-06-09 08:03:11', 101, NULL, NULL),
(372, '20250022', 'Jane Smith', 'College Department', '2025-05-09', '', 'Pending', '', '2025-06-13', 'No remarks', '2025-05-09 04:41:43', '2025-06-09 08:03:11', 101, NULL, NULL),
(373, '20250023', 'Jane Smith', 'College Department', '2025-05-09', '', 'Pending', '', '2025-06-14', 'No remarks', '2025-05-09 04:41:43', '2025-06-09 08:03:11', 101, NULL, NULL),
(374, '20250024', 'Jane Smith', 'College Department', '2025-05-09', '', 'Pending', '', '2025-06-15', 'No remarks', '2025-05-09 04:41:43', '2025-06-09 08:03:11', 101, NULL, NULL),
(375, '20250025', 'Jane Smith', 'College Department', '2025-05-09', '', 'Pending', '', '2025-06-16', 'No remarks', '2025-05-09 04:41:43', '2025-06-09 08:03:11', 101, NULL, NULL),
(376, '20250026', 'Jane Smith', 'College Department', '2025-05-09', '', 'Pending', '', '2025-06-17', 'No remarks', '2025-05-09 04:41:43', '2025-06-09 08:03:11', 101, NULL, NULL),
(377, '20250027', 'Jane Smith', 'College Department', '2025-05-09', '', 'Pending', '', '2025-06-18', 'No remarks', '2025-05-09 04:41:43', '2025-06-09 08:03:11', 101, NULL, NULL),
(378, '20250028', 'Jane Smith', 'College Department', '2025-05-09', '', 'Pending', '', '2025-06-19', 'No remarks', '2025-05-09 04:41:43', '2025-06-09 08:03:11', 101, NULL, NULL),
(379, '20250029', 'Jane Smith', 'College Department', '2025-05-09', '', 'Pending', '', '2025-06-20', 'No remarks', '2025-05-09 04:41:43', '2025-06-09 08:03:11', 101, NULL, NULL),
(380, '20250030', 'Jane Smith', 'College Department', '2025-05-09', '', 'Pending', '', '2025-06-21', 'No remarks', '2025-05-09 04:41:43', '2025-06-09 08:03:11', 101, NULL, NULL),
(381, '20250031', 'Jane Smith', 'College Department', '2025-05-09', '', 'Pending', '', '2025-06-22', 'No remarks', '2025-05-09 04:41:43', '2025-06-09 08:03:11', 101, NULL, NULL),
(382, '20250032', 'Jane Smith', 'College Department', '2025-05-09', '', 'Pending', '', '2025-06-23', 'No remarks', '2025-05-09 04:41:43', '2025-06-09 08:03:11', 101, NULL, NULL),
(383, '20250033', 'Jane Smith', 'College Department', '2025-05-09', '', 'Pending', '', '2025-06-24', 'No remarks', '2025-05-09 04:41:43', '2025-06-09 08:03:11', 101, NULL, NULL),
(384, '20250034', 'Jane Smith', 'College Department', '2025-05-09', '', 'Pending', '', '2025-06-25', 'No remarks', '2025-05-09 04:41:43', '2025-06-09 08:03:11', 101, NULL, NULL),
(385, '20250035', 'Jane Smith', 'College Department', '2025-05-09', '', 'Pending', '', '2025-06-26', 'No remarks', '2025-05-09 04:41:43', '2025-06-09 08:03:11', 101, NULL, NULL),
(386, '20250036', 'Jane Smith', 'College Department', '2025-05-09', '', 'Pending', '', '2025-06-27', 'No remarks', '2025-05-09 04:41:43', '2025-06-09 08:03:11', 101, NULL, NULL),
(387, '20250037', 'Jane Smith', 'College Department', '2025-05-09', '', 'Pending', '', '2025-06-28', 'No remarks', '2025-05-09 04:41:43', '2025-06-09 08:03:11', 101, NULL, NULL),
(388, '20250038', 'Jane Smith', 'College Department', '2025-05-09', '', 'Pending', '', '2025-06-29', 'No remarks', '2025-05-09 04:41:43', '2025-06-09 08:03:11', 101, NULL, NULL),
(389, '20250039', 'Jane Smith', 'College Department', '2025-05-09', '', 'Pending', '', '2025-06-30', 'No remarks', '2025-05-09 04:41:43', '2025-06-09 08:03:11', 101, NULL, NULL),
(390, '20250040', 'Jane Smith', 'College Department', '2025-05-09', '', 'Pending', '', '2025-07-01', 'No remarks', '2025-05-09 04:41:43', '2025-06-09 08:03:11', 101, NULL, NULL),
(391, '20250041', 'Jane Smith', 'College Department', '2025-05-09', '', 'Pending', '', '2025-07-02', 'No remarks', '2025-05-09 04:41:43', '2025-06-09 08:03:11', 101, NULL, NULL),
(392, '20250042', 'Jane Smith', 'College Department', '2025-05-09', '', 'Pending', '', '2025-07-03', 'No remarks', '2025-05-09 04:41:43', '2025-06-09 08:03:11', 101, NULL, NULL),
(393, '20250043', 'Jane Smith', 'College Department', '2025-05-09', '', 'Pending', '', '2025-07-04', 'No remarks', '2025-05-09 04:41:43', '2025-06-09 08:03:11', 101, NULL, NULL),
(394, '20250044', 'Jane Smith', 'College Department', '2025-05-09', '', 'Pending', '', '2025-07-05', 'No remarks', '2025-05-09 04:41:43', '2025-06-09 08:03:11', 101, NULL, NULL),
(395, '20250045', 'Jane Smith', 'College Department', '2025-05-09', '', 'Pending', '', '2025-07-06', 'No remarks', '2025-05-09 04:41:43', '2025-06-09 08:03:11', 101, NULL, NULL),
(396, '20250046', 'Jane Smith', 'College Department', '2025-05-09', '', 'Pending', '', '2025-07-07', 'No remarks', '2025-05-09 04:41:43', '2025-06-09 08:03:11', 101, NULL, NULL),
(397, '20250047', 'Jane Smith', 'College Department', '2025-05-09', '', 'Pending', '', '2025-07-08', 'No remarks', '2025-05-09 04:41:43', '2025-06-09 08:03:11', 101, NULL, NULL),
(398, '20250048', 'Jane Smith', 'College Department', '2025-05-09', '', 'Pending', '', '2025-07-09', 'No remarks', '2025-05-09 04:41:43', '2025-06-09 08:03:11', 101, NULL, NULL),
(399, '20250049', 'Jane Smith', 'College Department', '2025-05-09', '', 'Pending', '', '2025-07-10', 'No remarks', '2025-05-09 04:41:43', '2025-06-09 08:03:11', 101, NULL, NULL),
(400, '20250050', 'Jane Smith', 'College Department', '2025-05-09', '', 'Pending', '', '2025-07-11', 'No remarks', '2025-05-09 04:41:43', '2025-06-09 08:03:11', 101, NULL, NULL),
(401, '20250051', 'Jane Smith', 'College Department', '2025-05-09', 'Arisu Johnson', 'Rejected', '', '2025-07-12', 'No remarks', '2025-05-09 04:41:43', '2025-06-12 01:42:37', 101, NULL, NULL),
(402, '20250052', 'Jane Smith', 'College Department', '2025-05-09', '', 'Pending', '', '2025-07-13', 'No remarks', '2025-05-09 04:41:43', '2025-06-09 08:03:11', 101, NULL, NULL),
(403, '20250053', 'Jane Smith', 'College Department', '2025-05-09', '', 'Pending', '', '2025-07-14', 'No remarks', '2025-05-09 04:41:43', '2025-06-09 08:03:11', 101, NULL, NULL),
(404, '20250054', 'Jane Smith', 'College Department', '2025-05-09', '', 'Pending', '', '2025-07-15', 'No remarks', '2025-05-09 04:41:43', '2025-06-09 08:03:11', 101, NULL, NULL),
(405, '20250055', 'Jane Smith', 'College Department', '2025-05-09', '', 'Pending', '', '2025-07-16', 'No remarks', '2025-05-09 04:41:43', '2025-06-09 08:03:11', 101, NULL, NULL),
(406, '20250056', 'Jane Smith', 'College Department', '2025-05-09', '', 'Pending', '', '2025-07-17', 'No remarks', '2025-05-09 04:41:43', '2025-06-09 08:03:11', 101, NULL, NULL),
(407, '20250057', 'Jane Smith', 'College Department', '2025-05-09', '', 'Pending', '', '2025-07-18', 'No remarks', '2025-05-09 04:41:43', '2025-06-09 08:03:11', 101, NULL, NULL),
(408, '20250058', 'Jane Smith', 'College Department', '2025-05-09', '', 'Pending', '', '2025-07-19', 'No remarks', '2025-05-09 04:41:43', '2025-06-09 08:03:11', 101, NULL, NULL),
(409, '20250059', 'Antonio C. Reyes', 'College Department', '2025-05-09', '', 'Pending', '', '2025-05-10', 'kk', '2025-05-09 06:54:45', '2025-05-09 06:54:45', 103, NULL, NULL),
(410, '20250060', 'Antonio C. Reyes', 'College Department', '2025-05-09', '', 'Pending', '', '2025-05-10', 'asd', '2025-05-09 07:09:31', '2025-05-09 07:09:31', 105, NULL, NULL),
(411, '20250061', 'Antonio C. Reyes', 'College Department', '2025-05-09', '', 'Pending', '', '2025-05-10', 'sample', '2025-05-09 07:10:08', '2025-05-09 07:10:08', 106, NULL, NULL),
(412, '20250062', 'Antonio C. Reyes', 'College Department', '2025-05-09', '', 'Pending', '', '2025-05-10', 'sample', '2025-05-09 07:10:33', '2025-05-09 07:10:33', 101, NULL, NULL),
(413, '20250063', 'Antonio C. Reyes', 'College Department', '2025-05-09', '', 'Pending', '', '2025-05-10', 'purpose', '2025-05-09 07:10:58', '2025-05-09 07:10:58', 103, NULL, NULL),
(414, '20250064', 'Antonio C. Reyes', 'College Department', '2025-05-09', '', 'Pending', '', '2025-05-10', 'poster', '2025-05-09 07:12:15', '2025-05-09 07:12:15', 105, NULL, NULL),
(415, '20250065', 'Antonio C. Reyes', 'College Department', '2025-05-09', '', 'Pending', '', '2025-05-10', 'sample', '2025-05-09 07:13:06', '2025-05-09 07:13:06', 104, NULL, NULL),
(416, '20250066', 'Antonio C. Reyes', 'College Department', '2025-05-09', '', 'Pending', '', '2025-05-10', 'sample', '2025-05-09 07:13:56', '2025-05-09 07:13:56', 106, NULL, NULL),
(417, '20250067', 'Antonio C. Reyes', 'College Department', '2025-05-09', '', 'Pending', '', '2025-05-10', 'sample', '2025-05-09 07:23:11', '2025-05-09 07:23:11', 101, NULL, NULL),
(418, '20250068', 'Antonio C. Reyes', 'College Department', '2025-05-09', '', 'Pending', '', '2025-05-10', 'asd', '2025-05-09 07:23:35', '2025-05-09 07:23:35', 104, NULL, NULL),
(419, '20250069', 'Antonio C. Reyes', 'College Department', '2025-05-09', '', 'Pending', '', '2025-05-10', 'for educ', '2025-05-09 07:24:06', '2025-05-09 07:24:06', 105, NULL, NULL),
(420, '20250070', 'Antonio C. Reyes', 'College Department', '2025-05-09', '', 'Pending', '', '2025-05-10', 'sample', '2025-05-09 07:25:24', '2025-05-09 07:25:24', 103, NULL, NULL),
(421, '20250071', 'Antonio C. Reyes', 'College Department', '2025-05-09', '', 'Pending', '', '2025-05-10', 'sample', '2025-05-09 07:27:21', '2025-05-09 07:27:21', 103, NULL, NULL),
(422, '20250072', 'Antonio C. Reyes', 'College Department', '2025-05-09', '', 'Pending', '', '2025-05-10', 'asd', '2025-05-09 07:28:05', '2025-05-09 07:28:05', 101, NULL, NULL),
(423, '20250073', 'Antonio C. Reyes', 'College Department', '2025-05-09', '', 'Pending', '', '2025-05-10', 'sample', '2025-05-09 07:28:29', '2025-05-09 07:28:29', 104, NULL, NULL),
(424, '20250074', 'Antonio C. Reyes', 'College Department', '2025-05-09', '', 'Pending', '', '2025-05-10', 'sample', '2025-05-09 07:29:16', '2025-05-09 07:29:16', 104, NULL, NULL),
(425, '20250075', 'Antonio C. Reyes', 'College Department', '2025-05-09', '', 'Pending', '', '2025-05-10', 'sample', '2025-05-09 07:30:06', '2025-05-09 07:30:06', 101, NULL, NULL),
(426, '20250076', 'Antonio C. Reyes', 'College Department', '2025-05-09', '', 'Pending', '', '2025-05-10', 'nc2', '2025-05-09 07:30:38', '2025-05-09 07:30:38', 103, NULL, NULL),
(427, '20250077', 'Antonio C. Reyes', 'College Department', '2025-05-09', '', 'Pending', '', '2025-05-10', 'nc2', '2025-05-09 07:31:16', '2025-05-09 07:31:16', 105, NULL, NULL),
(428, '20250078', 'Antonio C. Reyes', 'College Department', '2025-05-09', '', 'Pending', '', '2025-05-10', 'nc2', '2025-05-09 07:31:34', '2025-05-09 07:31:34', 104, NULL, NULL),
(429, '20250079', 'Antonio C. Reyes', 'College Department', '2025-05-09', '', 'Pending', '', '2025-05-10', 'nc2', '2025-05-09 07:32:51', '2025-05-09 07:32:51', 101, NULL, NULL),
(430, '20250080', 'Antonio C. Reyes', 'College Department', '2025-05-09', '', 'Pending', '', '2025-05-10', 'nc2', '2025-05-09 07:33:09', '2025-05-09 07:33:09', 103, NULL, NULL),
(431, '20250081', 'Antonio C. Reyes', 'College Department', '2025-05-09', '', 'Pending', '', '2025-05-10', 'nc2', '2025-05-09 07:33:45', '2025-05-09 07:33:45', 105, NULL, NULL),
(432, '20250082', 'Antonio C. Reyes', 'College Department', '2025-05-09', '', 'Pending', '', '2025-05-10', 'nc2', '2025-05-09 07:34:16', '2025-05-09 07:34:16', 101, NULL, NULL),
(433, '20250083', 'Antonio C. Reyes', 'College Department', '2025-05-09', '', 'Pending', '', '2025-05-10', 'nc2', '2025-05-09 07:34:46', '2025-05-09 07:34:46', 103, NULL, NULL),
(434, '20250084', 'Antonio C. Reyes', 'College Department', '2025-05-09', '', 'Pending', '', '2025-05-10', 'sample2', '2025-05-09 07:35:07', '2025-05-09 07:35:07', 104, NULL, NULL),
(435, '20250085', 'Antonio C. Reyes', 'College Department', '2025-05-09', '', 'Pending', '', '2025-05-10', 'nc2', '2025-05-09 07:35:31', '2025-05-09 07:35:31', 104, NULL, NULL),
(436, '20250086', 'Antonio C. Reyes', 'College Department', '2025-05-09', '', 'Pending', '', '2025-05-10', 'nc2', '2025-05-09 07:36:09', '2025-05-09 07:36:09', 102, NULL, NULL),
(437, '20250087', 'Antonio C. Reyes', 'College Department', '2025-05-09', '', 'Pending', '', '2025-05-10', 'sample', '2025-05-09 07:36:32', '2025-05-09 07:36:32', 105, NULL, NULL),
(438, '20250088', 'Antonio C. Reyes', 'College Department', '2025-05-09', '', 'Pending', '', '2025-05-10', 'sample', '2025-05-09 07:37:54', '2025-05-09 07:37:54', 104, NULL, NULL),
(439, '20250089', 'Antonio C. Reyes', 'College Department', '2025-05-09', '', 'Pending', '', '2025-05-10', 'nc2', '2025-05-09 07:38:29', '2025-05-09 07:38:29', 104, NULL, NULL),
(440, '20250090', 'Antonio C. Reyes', 'College Department', '2025-05-09', '', 'Pending', '', '2025-05-10', 'nc2', '2025-05-09 07:38:56', '2025-05-09 07:38:56', 103, NULL, NULL),
(441, '20250091', 'Antonio C. Reyes', 'College Department', '2025-05-09', '', 'Pending', '', '2025-05-10', 'prs', '2025-05-09 07:39:34', '2025-05-09 07:39:34', 105, NULL, NULL),
(442, '20250092', 'Antonio C. Reyes', 'College Department', '2025-05-09', '', 'Pending', '', '2025-05-10', 'nc2', '2025-05-09 07:40:00', '2025-05-09 07:40:00', 105, NULL, NULL),
(443, '20250093', 'Antonio C. Reyes', 'College Department', '2025-05-09', '', 'Pending', '', '2025-05-10', '23', '2025-05-09 07:40:56', '2025-05-09 07:40:56', 103, NULL, NULL),
(444, '20250094', 'Antonio C. Reyes', 'College Department', '2025-05-09', '', 'Pending', '', '2025-05-10', 'nc2', '2025-05-09 07:41:13', '2025-05-09 07:41:13', 104, NULL, NULL),
(445, '20250095', 'Antonio C. Reyes', 'College Department', '2025-05-09', '', 'Pending', '', '2025-05-10', 'nc2', '2025-05-09 07:41:27', '2025-05-09 07:41:27', 104, NULL, NULL),
(446, '20250096', 'Antonio C. Reyes', 'College Department', '2025-05-09', '', 'Pending', '', '2025-05-10', 'nc2', '2025-05-09 07:42:09', '2025-05-09 07:42:09', 105, NULL, NULL),
(447, '20250097', 'Antonio C. Reyes', 'College Department', '2025-05-09', '', 'Pending', '', '2025-05-10', 'nc2', '2025-05-09 07:43:45', '2025-05-09 07:43:45', 103, NULL, NULL),
(448, '20250098', 'Antonio C. Reyes', 'College Department', '2025-05-09', '', 'Pending', '', '2025-05-10', '6', '2025-05-09 07:44:02', '2025-05-09 07:44:02', 102, NULL, NULL),
(449, '20250099', 'Antonio C. Reyes', 'College Department', '2025-05-09', '', 'Pending', '', '2025-05-10', 'nc2', '2025-05-09 07:44:25', '2025-05-09 07:44:25', 104, NULL, NULL),
(450, '20250100', 'Antonio C. Reyes', 'College Department', '2025-05-09', '', 'Pending', '', '2025-05-10', 'nc2', '2025-05-09 07:44:41', '2025-05-09 07:44:41', 104, NULL, NULL),
(451, '20250101', 'Antonio C. Reyes', 'College Department', '2025-05-09', '', 'Pending', '', '2025-05-10', 'nc2', '2025-05-09 07:45:12', '2025-05-09 07:45:12', 104, NULL, NULL),
(452, '20250102', 'Antonio C. Reyes', 'College Department', '2025-05-09', 'Arisu Johnson', 'Pending', '', '2025-05-10', 'asd', '2025-05-09 07:45:44', '2025-06-12 02:43:01', 105, NULL, NULL),
(453, '20250103', 'Antonio C. Reyes', 'College Department', '2025-05-09', '', 'Pending', '', '2025-05-10', 'nc2', '2025-05-09 07:46:18', '2025-05-09 07:46:18', 101, NULL, NULL),
(454, '20250104', 'Antonio C. Reyes', 'College Department', '2025-05-09', '', 'Pending', '', '2025-05-10', 'nc2', '2025-05-09 07:46:32', '2025-05-09 07:46:32', 103, NULL, NULL),
(455, '20250105', 'Antonio C. Reyes', 'College Department', '2025-05-09', 'Arisu Johnson', 'Approved', 'Max Jones', '2025-05-10', 'testing\r\n', '2025-05-09 09:15:41', '2025-05-09 09:21:11', 101, NULL, '‘Wireless Router - Netgear’ quantity changed from 1 to 2'),
(456, '20250106', 'Antonio C. Reyes', 'College Department', '2025-05-09', 'Arisu Johnson', 'Approved', 'Max Jones', '2025-05-10', 'testing', '2025-05-09 09:16:43', '2025-05-09 09:22:11', 101, 'test', '‘Wireless Router - Netgear’ quantity changed from 1 to 5'),
(457, '20250107', 'Jane Smith', 'College Department', '2025-05-13', '', 'Pending', '', '2025-05-14', 'as', '2025-05-13 06:10:25', '2025-06-11 07:36:05', 102, NULL, NULL),
(458, '20250108', 'Jane Smith', 'College Department', '2025-06-11', 'Arisu Johnson', 'Pending', '', '2025-06-12', 'sample', '2025-06-11 07:35:05', '2025-06-11 09:03:56', 102, NULL, NULL),
(459, '20250109', 'Jane Smith', 'College Department', '2025-06-13', 'Arisu Johnson', 'Approved', 'Max Jones', '2025-06-14', 'sda', '2025-06-13 08:04:57', '2025-06-13 08:05:35', 102, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `dbpis_prsdetails`
--

CREATE TABLE `dbpis_prsdetails` (
  `prsdetails_id` int(11) NOT NULL,
  `prs_code` varchar(50) NOT NULL,
  `item_code` int(11) NOT NULL,
  `item_description` varchar(255) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `supplier` varchar(255) DEFAULT NULL,
  `unit_price` decimal(10,2) DEFAULT NULL,
  `total_price` decimal(10,2) GENERATED ALWAYS AS (`quantity` * `unit_price`) STORED,
  `unit_type` varchar(50) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `original_quantity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `dbpis_prsdetails`
--

INSERT INTO `dbpis_prsdetails` (`prsdetails_id`, `prs_code`, `item_code`, `item_description`, `quantity`, `supplier`, `unit_price`, `unit_type`, `status`, `created_at`, `updated_at`, `original_quantity`) VALUES
(635, '20250001', 1011021, 'Office Chair - Steelcase', 5, 'Office Essentials', '2500.00', 'pcs', 2, '2025-04-28 04:24:43', '2025-05-09 01:17:57', 5),
(636, '20250001', 2012026, 'File Folders (Box) - Oxford', 5, 'Tech Supply Co.', '150.00', 'set', 2, '2025-04-28 04:24:43', '2025-05-06 14:18:44', 10),
(637, '20250001', 4014016, 'Printer Ink Cartridge - HP', 5, 'Office Essentials', '525.00', 'set', 2, '2025-04-28 04:24:43', '2025-05-06 14:18:44', 5),
(638, '20250002', 2032008, 'Motor Oil (1L) - Castrol', 5, NULL, NULL, 'pcs', 1, '2025-04-28 04:32:00', '2025-04-28 04:34:19', NULL),
(639, '20250003', 2022027, 'Laser Printer - Brother', 5, NULL, NULL, 'pcs', 1, '2025-04-28 04:33:09', '2025-04-28 04:34:34', NULL),
(640, '20250003', 4034018, 'Brake Fluid (1L) - Dot 4', 5, NULL, NULL, 'pcs', 1, '2025-04-28 04:33:09', '2025-04-28 04:34:37', NULL),
(644, '20250004', 1021002, 'Laptop - Dell', 5, 'PC TECZH', '25000.00', 'pcs', 2, '2025-04-28 04:39:28', '2025-05-06 14:18:44', 5),
(645, '20250004', 4024017, 'Ethernet Cable (Roll) - Generic', 5, 'PC TECZH', '1500.00', 'box', 2, '2025-04-28 04:39:28', '2025-05-06 14:18:44', 5),
(646, '20250004', 1021022, 'Wireless Router - Netgear', 8, 'PC TECZH', '2500.00', 'set', 0, '2025-04-28 04:39:28', '2025-04-28 04:45:42', 8),
(647, '20250005', 4024017, 'Ethernet Cable (Roll) - Generic', 5, 'PC TECZH', '100.00', 'pcs', 2, '2025-05-06 13:51:03', '2025-05-11 13:57:27', 5),
(648, '20250006', 4024017, 'Ethernet Cable (Roll) - Generic', 10, 'PC TECZH', '500.00', 'pcs', 2, '2025-05-06 14:21:50', '2025-05-08 16:09:05', 5),
(649, '20250007', 2022007, '24-inch Monitor - Samsung', 5, 'PC TECZH', '5000.00', 'pcs', 2, '2025-05-07 02:58:17', '2025-05-08 16:14:56', 5),
(650, '20250008', 1011001, 'Stapler - Swingline', 5, 'Office Essentials', '531.00', 'pcs', 1, '2025-05-08 16:22:45', '2025-05-09 01:35:45', 5),
(651, '20250009', 2032008, 'Motor Oil (1L) - Castrol', 5, 'Tech Supply Co.', '765.00', 'pcs', 1, '2025-05-09 04:05:01', '2025-05-09 08:49:45', 5),
(731, '20250010', 1011001, 'Stapler-Swingline', 3, 'Office Essentials', '765.00', 'pcs', 1, '2025-05-09 04:52:02', '2025-05-09 08:49:37', 3),
(732, '20250011', 1021002, 'Laptop-Dell', 2, 'PC TECZH', '7877.00', 'pcs', 3, '2025-05-09 04:52:02', '2025-05-11 13:58:12', 2),
(733, '20250012', 1031003, 'Adjustable Wrench-Craftsman', 5, 'Tech Supply Co.', '456.00', 'pcs', 1, '2025-05-09 04:52:02', '2025-05-09 08:49:23', 5),
(734, '20250013', 1041004, 'Cordless Drill-Bosch', 4, 'Warehouse Supplies Ltd.', '670.00', 'pcs', 1, '2025-05-09 04:52:02', '2025-05-09 08:49:08', 4),
(735, '20250014', 1051005, 'Digital Multimeter-Fluke', 6, 'Electro World', '350.00', 'pcs', 1, '2025-05-09 04:52:02', '2025-05-09 08:49:01', 6),
(736, '20250015', 2012006, 'A4 Paper Ream-HP', 12, 'Office Essentials', '250.00', 'reams', 2, '2025-05-09 04:52:02', '2025-05-11 09:50:13', 12),
(737, '20250016', 2022007, '24-inch Monitor-Samsung', 3, NULL, NULL, 'pcs', 1, '2025-05-09 04:52:02', '2025-05-09 04:52:02', NULL),
(738, '20250017', 2032008, 'Motor Oil (1L)-Castrol', 8, NULL, NULL, 'liters', 1, '2025-05-09 04:52:02', '2025-05-09 04:52:02', NULL),
(739, '20250018', 2042009, 'Metal Lathe-Grizzly', 2, NULL, NULL, 'unit', 1, '2025-05-09 04:52:02', '2025-05-09 04:52:02', NULL),
(740, '20250019', 2052010, 'Electrical Wire (100m)-Southwire', 5, NULL, NULL, 'rolls', 1, '2025-05-09 04:52:02', '2025-05-09 04:52:02', NULL),
(741, '20250020', 3013011, 'Office Desk-IKEA', 3, NULL, NULL, 'pcs', 1, '2025-05-09 04:52:02', '2025-05-09 04:52:02', NULL),
(742, '20250021', 3023012, 'Wireless Keyboard-Logitech', 2, NULL, NULL, 'pcs', 1, '2025-05-09 04:52:02', '2025-05-09 04:52:02', NULL),
(743, '20250022', 3033013, 'Car Tire-Bridgestone', 4, NULL, NULL, 'pcs', 1, '2025-05-09 04:52:02', '2025-05-09 04:52:02', NULL),
(744, '20250023', 3043014, 'Milling Machine-Haas', 1, NULL, NULL, 'unit', 1, '2025-05-09 04:52:02', '2025-05-09 04:52:02', NULL),
(745, '20250024', 3053015, 'Relay Switch-Omron', 7, NULL, NULL, 'pcs', 1, '2025-05-09 04:52:02', '2025-05-09 04:52:02', NULL),
(746, '20250025', 4014016, 'Printer Ink Cartridge-HP', 3, NULL, NULL, 'pcs', 1, '2025-05-09 04:52:02', '2025-05-09 04:52:02', NULL),
(747, '20250026', 4024017, 'Ethernet Cable (Roll)-Generic', 6, NULL, NULL, 'rolls', 1, '2025-05-09 04:52:02', '2025-05-09 04:52:02', NULL),
(748, '20250027', 4034018, 'Brake Fluid (1L)-Dot 4', 2, NULL, NULL, 'liters', 1, '2025-05-09 04:52:02', '2025-05-09 04:52:02', NULL),
(749, '20250028', 4044019, 'Cutting Oil (1L)-Mobil', 3, NULL, NULL, 'ltr', 1, '2025-05-09 04:52:02', '2025-05-09 04:52:02', NULL),
(750, '20250029', 4054020, 'Solder Wire (Roll)-Kester', 5, NULL, NULL, 'roll', 1, '2025-05-09 04:52:02', '2025-05-09 04:52:02', NULL),
(751, '20250030', 1011021, 'Office Chair-Steelcase', 2, NULL, NULL, 'pcs', 1, '2025-05-09 04:52:02', '2025-05-09 04:52:02', NULL),
(752, '20250031', 1021022, 'Wireless Router-Netgear', 7, NULL, NULL, 'pcs', 1, '2025-05-09 04:52:20', '2025-05-09 04:52:20', NULL),
(753, '20250032', 1031023, 'Car Battery-ACDelco', 5, NULL, NULL, 'pcs', 1, '2025-05-09 04:52:20', '2025-05-09 04:52:20', NULL),
(754, '20250033', 1041024, 'Welding Machine-Lincoln', 3, NULL, NULL, 'pcs', 1, '2025-05-09 04:52:20', '2025-05-09 04:52:20', NULL),
(755, '20250034', 1051025, 'Proximity Sensor-Keyence', 6, NULL, NULL, 'pcs', 1, '2025-05-09 04:52:20', '2025-05-09 04:52:20', NULL),
(756, '20250035', 2012026, 'File Folders (Box)-Oxford', 10, NULL, NULL, 'boxes', 1, '2025-05-09 04:52:20', '2025-05-09 04:52:20', NULL),
(757, '20250036', 2022027, 'Laser Printer-Brother', 4, NULL, NULL, 'pcs', 1, '2025-05-09 04:52:20', '2025-05-09 04:52:20', NULL),
(758, '20250037', 2032028, 'Hydraulic Jack-Torin', 2, NULL, NULL, 'pcs', 1, '2025-05-09 04:52:20', '2025-05-09 04:52:20', NULL),
(759, '20250038', 2042029, 'Hammer-Stanley', 6, NULL, NULL, 'pcs', 1, '2025-05-09 04:52:20', '2025-05-09 04:52:20', NULL),
(760, '20250039', 2052030, 'Electrical Contactor-Siemens', 4, NULL, NULL, 'pcs', 1, '2025-05-09 04:52:20', '2025-05-09 04:52:20', NULL),
(761, '20250040', 1011001, 'Stapler-Swingline', 8, NULL, NULL, 'pcs', 1, '2025-05-09 04:52:20', '2025-05-09 04:52:20', NULL),
(762, '20250041', 1021002, 'Laptop-Dell', 10, NULL, NULL, 'pcs', 1, '2025-05-09 04:52:20', '2025-05-09 04:52:20', NULL),
(763, '20250042', 1031003, 'Adjustable Wrench-Craftsman', 12, NULL, NULL, 'pcs', 1, '2025-05-09 04:52:20', '2025-05-09 04:52:20', NULL),
(764, '20250043', 1041004, 'Cordless Drill-Bosch', 6, NULL, NULL, 'pcs', 1, '2025-05-09 04:52:20', '2025-05-09 04:52:20', NULL),
(765, '20250044', 1051005, 'Digital Multimeter-Fluke', 14, NULL, NULL, 'pcs', 1, '2025-05-09 04:52:20', '2025-05-09 04:52:20', NULL),
(766, '20250045', 2012006, 'A4 Paper Ream-HP', 15, NULL, NULL, 'reams', 1, '2025-05-09 04:52:20', '2025-05-09 04:52:20', NULL),
(767, '20250046', 2022007, '24-inch Monitor-Samsung', 9, NULL, NULL, 'pcs', 1, '2025-05-09 04:52:20', '2025-05-09 04:52:20', NULL),
(768, '20250047', 2032008, 'Motor Oil (1L)-Castrol', 3, NULL, NULL, 'liters', 1, '2025-05-09 04:52:20', '2025-05-09 04:52:20', NULL),
(769, '20250048', 2042009, 'Metal Lathe-Grizzly', 2, NULL, NULL, 'unit', 1, '2025-05-09 04:52:20', '2025-05-09 04:52:20', NULL),
(770, '20250049', 2052010, 'Electrical Wire (100m)-Southwire', 9, NULL, NULL, 'rolls', 1, '2025-05-09 04:52:20', '2025-05-09 04:52:20', NULL),
(771, '20250050', 3013011, 'Office Desk-IKEA', 5, NULL, NULL, 'pcs', 1, '2025-05-09 04:52:20', '2025-05-09 04:52:20', NULL),
(772, '20250051', 3023012, 'Wireless Keyboard-Logitech', 8, NULL, NULL, 'pcs', 1, '2025-05-09 04:52:20', '2025-05-09 04:52:20', NULL),
(773, '20250052', 3033013, 'Car Tire-Bridgestone', 10, NULL, NULL, 'pcs', 1, '2025-05-09 04:52:20', '2025-05-09 04:52:20', NULL),
(774, '20250053', 3043014, 'Milling Machine-Haas', 2, NULL, NULL, 'unit', 1, '2025-05-09 04:52:20', '2025-05-09 04:52:20', NULL),
(775, '20250054', 3053015, 'Relay Switch-Omron', 11, NULL, NULL, 'pcs', 1, '2025-05-09 04:52:20', '2025-05-09 04:52:20', NULL),
(776, '20250055', 4014016, 'Printer Ink Cartridge-HP', 7, NULL, NULL, 'pcs', 1, '2025-05-09 04:52:20', '2025-05-09 04:52:20', NULL),
(777, '20250056', 4024017, 'Ethernet Cable (Roll)-Generic', 5, NULL, NULL, 'rolls', 1, '2025-05-09 04:52:20', '2025-05-09 04:52:20', NULL),
(778, '20250057', 4034018, 'Brake Fluid (1L)-Dot 4', 8, NULL, NULL, 'liters', 1, '2025-05-09 04:52:20', '2025-05-09 04:52:20', NULL),
(779, '20250058', 4044019, 'Cutting Oil (1L)-Mobil', 12, NULL, NULL, 'ltr', 1, '2025-05-09 04:52:20', '2025-05-09 04:52:20', NULL),
(780, '20250059', 2032008, 'Motor Oil (1L) - Castrol', 5, NULL, NULL, 'pcs', 1, '2025-05-09 06:54:45', '2025-05-09 06:54:45', NULL),
(781, '20250060', 4044019, 'Cutting Oil (1L) - Mobil', 5, NULL, NULL, 'pcs', 1, '2025-05-09 07:09:31', '2025-05-09 07:09:31', NULL),
(782, '20250061', 2032028, 'Hydraulic Jack - Torin', 6, NULL, NULL, 'pcs', 1, '2025-05-09 07:10:08', '2025-05-09 07:10:08', NULL),
(783, '20250062', 4054020, 'Solder Wire (Roll) - Kester', 5, NULL, NULL, 'pcs', 1, '2025-05-09 07:10:33', '2025-05-09 07:10:33', NULL),
(784, '20250063', 4034018, 'Brake Fluid (1L) - Dot 4', 5, NULL, NULL, 'pcs', 1, '2025-05-09 07:10:58', '2025-05-09 07:10:58', NULL),
(785, '20250064', 2052010, 'Electrical Wire (100m) - Southwire', 5, NULL, NULL, 'pcs', 1, '2025-05-09 07:12:15', '2025-05-09 07:12:15', NULL),
(786, '20250065', 1041004, 'Cordless Drill - Bosch', 12, NULL, NULL, 'pcs', 1, '2025-05-09 07:13:06', '2025-05-09 07:13:06', NULL),
(787, '20250066', 3023012, 'Wireless Keyboard - Logitech', 5, NULL, NULL, 'pcs', 1, '2025-05-09 07:13:56', '2025-05-09 07:13:56', NULL),
(788, '20250067', 1011021, 'Office Chair - Steelcase', 12, NULL, NULL, 'pcs', 1, '2025-05-09 07:23:11', '2025-05-09 07:23:11', NULL),
(789, '20250068', 3053015, 'Relay Switch - Omron', 12, NULL, NULL, 'pcs', 1, '2025-05-09 07:23:35', '2025-05-09 07:23:35', NULL),
(790, '20250069', 1051025, 'Proximity Sensor - Keyence', 12, NULL, NULL, 'pcs', 1, '2025-05-09 07:24:06', '2025-05-09 07:24:06', NULL),
(791, '20250070', 1021002, 'Laptop - Dell', 10, NULL, NULL, 'pcs', 1, '2025-05-09 07:25:24', '2025-05-09 07:25:24', NULL),
(792, '20250071', 2052030, 'Electrical Contactor - Siemens', 5, NULL, NULL, 'pcs', 1, '2025-05-09 07:27:21', '2025-05-09 07:27:21', NULL),
(793, '20250072', 2022028, 'Mouse - A4 Tech', 5, NULL, NULL, 'pcs', 1, '2025-05-09 07:28:05', '2025-05-09 07:28:05', NULL),
(794, '20250073', 4044019, 'Cutting Oil (1L) - Mobil', 5, NULL, NULL, 'pcs', 1, '2025-05-09 07:28:29', '2025-05-09 07:28:29', NULL),
(795, '20250074', 4044019, 'Cutting Oil (1L) - Mobil', 6, NULL, NULL, 'pcs', 1, '2025-05-09 07:29:16', '2025-05-09 07:29:16', NULL),
(796, '20250075', 3013011, 'Office Desk - IKEA', 2, NULL, NULL, 'pcs', 1, '2025-05-09 07:30:06', '2025-05-09 07:30:06', NULL),
(797, '20250076', 2022028, 'Mouse - A4 Tech', 5, NULL, NULL, 'pcs', 1, '2025-05-09 07:30:38', '2025-05-09 07:30:38', NULL),
(798, '20250077', 1051025, 'Proximity Sensor - Keyence', 5, NULL, NULL, 'pcs', 1, '2025-05-09 07:31:16', '2025-05-09 07:31:16', NULL),
(799, '20250078', 1041024, 'Welding Machine - Lincoln', 5, NULL, NULL, 'pcs', 1, '2025-05-09 07:31:34', '2025-05-09 07:31:34', NULL),
(800, '20250079', 2052030, 'Electrical Contactor - Siemens', 5, NULL, NULL, 'pcs', 1, '2025-05-09 07:32:51', '2025-05-09 07:32:51', NULL),
(801, '20250080', 1031023, 'Car Battery - ACDelco', 5, NULL, NULL, 'pcs', 1, '2025-05-09 07:33:09', '2025-05-09 07:33:09', NULL),
(802, '20250081', 2052030, 'Electrical Contactor - Siemens', 6, NULL, NULL, 'pcs', 1, '2025-05-09 07:33:45', '2025-05-09 07:33:45', NULL),
(803, '20250082', 4014016, 'Printer Ink Cartridge - HP', 5, NULL, NULL, 'pcs', 1, '2025-05-09 07:34:16', '2025-05-09 07:34:16', NULL),
(804, '20250083', 4024017, 'Ethernet Cable (Roll) - Generic', 6, NULL, NULL, 'pcs', 1, '2025-05-09 07:34:46', '2025-05-09 07:34:46', NULL),
(805, '20250084', 2052030, 'Electrical Contactor - Siemens', 6, NULL, NULL, 'pcs', 1, '2025-05-09 07:35:07', '2025-05-09 07:35:07', NULL),
(806, '20250085', 2042029, 'Hammer - Stanley', 7, NULL, NULL, 'pcs', 1, '2025-05-09 07:35:31', '2025-05-09 07:35:31', NULL),
(807, '20250086', 2022027, 'Laser Printer - Brother', 5, NULL, NULL, 'pcs', 1, '2025-05-09 07:36:09', '2025-05-09 07:36:09', NULL),
(808, '20250087', 1021022, 'Wireless Router - Netgear', 2, NULL, NULL, 'pcs', 1, '2025-05-09 07:36:32', '2025-05-09 07:36:32', NULL),
(809, '20250088', 2022027, 'Laser Printer - Brother', 5, NULL, NULL, 'pcs', 1, '2025-05-09 07:37:54', '2025-05-09 07:37:54', NULL),
(810, '20250089', 2042009, 'Metal Lathe - Grizzly', 7, NULL, NULL, 'pcs', 1, '2025-05-09 07:38:29', '2025-05-09 07:38:29', NULL),
(811, '20250090', 2032028, 'Hydraulic Jack - Torin', 5, NULL, NULL, 'pcs', 1, '2025-05-09 07:38:56', '2025-05-09 07:38:56', NULL),
(812, '20250091', 3043014, 'Milling Machine - Haas', 5, NULL, NULL, 'pcs', 1, '2025-05-09 07:39:34', '2025-05-09 07:39:34', NULL),
(813, '20250092', 2022027, 'Laser Printer - Brother', 5, NULL, NULL, 'pcs', 1, '2025-05-09 07:40:00', '2025-05-09 07:40:00', NULL),
(814, '20250093', 2042029, 'Hammer - Stanley', 5, NULL, NULL, 'pcs', 1, '2025-05-09 07:40:56', '2025-05-09 07:40:56', NULL),
(815, '20250094', 2042029, 'Hammer - Stanley', 2, NULL, NULL, 'pcs', 1, '2025-05-09 07:41:13', '2025-05-09 07:41:13', NULL),
(816, '20250095', 4044019, 'Cutting Oil (1L) - Mobil', 5, NULL, NULL, 'pcs', 1, '2025-05-09 07:41:27', '2025-05-09 07:41:27', NULL),
(817, '20250096', 2042009, 'Metal Lathe - Grizzly', 5, NULL, NULL, 'pcs', 1, '2025-05-09 07:42:09', '2025-05-09 07:42:09', NULL),
(818, '20250097', 4014016, 'Printer Ink Cartridge - HP', 5, NULL, NULL, 'pcs', 1, '2025-05-09 07:43:45', '2025-05-09 07:43:45', NULL),
(819, '20250098', 2012006, 'A4 Paper Ream - HP', 5, NULL, NULL, 'pcs', 1, '2025-05-09 07:44:02', '2025-05-09 07:44:02', NULL),
(820, '20250099', 1051025, 'Proximity Sensor - Keyence', 5, NULL, NULL, 'pcs', 1, '2025-05-09 07:44:25', '2025-05-09 07:44:25', NULL),
(821, '20250100', 4014016, 'Printer Ink Cartridge - HP', 5, NULL, NULL, 'pcs', 1, '2025-05-09 07:44:41', '2025-05-09 07:44:41', NULL),
(822, '20250101', 3043014, 'Milling Machine - Haas', 5, NULL, NULL, 'pcs', 1, '2025-05-09 07:45:12', '2025-05-09 07:45:12', NULL),
(823, '20250102', 2052010, 'Electrical Wire (100m) - Southwire', 5, NULL, NULL, 'pcs', 1, '2025-05-09 07:45:44', '2025-05-09 07:45:44', NULL),
(824, '20250103', 4034018, 'Brake Fluid (1L) - Dot 4', 5, NULL, NULL, 'pcs', 1, '2025-05-09 07:46:18', '2025-05-09 07:46:18', NULL),
(825, '20250104', 2052010, 'Electrical Wire (100m) - Southwire', 5, NULL, NULL, 'pcs', 1, '2025-05-09 07:46:32', '2025-05-09 07:46:32', NULL),
(826, '20250105', 1021022, 'Wireless Router - Netgear', 2, 'PC TECZH', '5000.00', 'pcs', 2, '2025-05-09 09:15:41', '2025-05-10 05:55:22', 1),
(827, '20250106', 1021022, 'Wireless Router - Netgear', 5, 'PC TECZH', '5000.00', 'pcs', 2, '2025-05-09 09:16:43', '2025-05-11 13:57:55', 1),
(830, '20250108', 4014016, 'Printer Ink Cartridge - HP', 5, NULL, NULL, 'pcs', 1, '2025-06-11 07:35:43', '2025-06-11 07:35:43', NULL),
(832, '20250107', 4014016, 'Printer Ink Cartridge - HP', 2, NULL, NULL, 'pcs', 1, '2025-06-11 07:36:05', '2025-06-11 07:36:05', NULL),
(833, '20250109', 1021022, 'Wireless Router - Netgear', 5, 'PC TECZH', '600.00', 'pcs', 2, '2025-06-13 08:04:57', '2025-06-13 08:08:29', 5),
(834, '20250109', 4024017, 'Ethernet Cable (Roll) - Generic', 5, 'PC TECZH', '500.00', 'pcs', 2, '2025-06-13 08:04:57', '2025-06-13 08:08:29', 5);

-- --------------------------------------------------------

--
-- Table structure for table `dbpis_rr`
--

CREATE TABLE `dbpis_rr` (
  `rr_no` int(11) NOT NULL,
  `received_from` int(11) NOT NULL,
  `date_received` date NOT NULL,
  `invoice_no` varchar(50) DEFAULT NULL,
  `invoice_date` date DEFAULT NULL,
  `received_by` varchar(255) NOT NULL,
  `department_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `dbpis_rr`
--

INSERT INTO `dbpis_rr` (`rr_no`, `received_from`, `date_received`, `invoice_no`, `invoice_date`, `received_by`, `department_id`, `created_at`, `updated_at`) VALUES
(128, 19, '2025-04-28', 'INV-2025-248710', '2025-04-28', 'Elena D. Cruz, Sr.', 1, '2025-04-28 04:50:00', '2025-04-28 04:50:00'),
(129, 19, '2025-04-28', 'INV-2025-936231', '2025-04-28', 'Elena D. Cruz, Sr.', 1, '2025-04-28 04:51:01', '2025-04-28 04:51:01'),
(130, 1, '2025-04-28', 'INV-2025-271039', '2025-04-28', 'Elena D. Cruz, Sr.', 1, '2025-04-28 04:51:28', '2025-04-28 04:51:28'),
(131, 2, '2025-04-28', 'INV-2025-127385', '2025-04-28', 'Elena D. Cruz, Sr.', 1, '2025-04-28 04:54:36', '2025-04-28 04:54:36'),
(132, 19, '2025-05-06', 'INV-2025-887946', '2025-05-06', 'Elena D. Cruz, Sr.', 1, '2025-05-06 14:08:17', '2025-05-06 14:08:17'),
(134, 19, '2025-05-06', 'INV-2025-009010', '2025-05-06', 'Elena D. Cruz, Sr.', 1, '2025-05-06 14:45:01', '2025-05-06 14:45:01'),
(136, 19, '2025-05-08', 'INV-2025-051493', '2025-05-08', 'Elena D. Cruz, Sr.', 1, '2025-05-08 16:09:04', '2025-05-08 16:09:04'),
(138, 19, '2025-05-08', 'INV-2025-964565', '2025-05-08', 'Elena D. Cruz, Sr.', 1, '2025-05-08 16:14:54', '2025-05-08 16:14:54'),
(140, 19, '2025-05-10', 'INV-2025-580039', '2025-05-10', 'Elena D. Cruz, Sr.', 1, '2025-05-10 05:52:23', '2025-05-10 05:52:23'),
(141, 19, '2025-05-10', 'INV-2025-321068', '2025-05-10', 'Elena D. Cruz, Sr.', 1, '2025-05-10 05:55:20', '2025-05-10 05:55:20'),
(142, 2, '2025-05-11', 'INV-2025-598572', '2025-05-11', 'Elena D. Cruz, Sr.', 1, '2025-05-11 09:50:11', '2025-05-11 09:50:11'),
(143, 19, '2025-05-11', 'INV-2025-772320', '2025-05-11', 'Elena D. Cruz, Sr.', 1, '2025-05-11 13:57:26', '2025-05-11 13:57:26'),
(144, 19, '2025-05-11', 'INV-2025-915781', '2025-05-11', 'Elena D. Cruz, Sr.', 1, '2025-05-11 13:57:54', '2025-05-11 13:57:54'),
(145, 19, '2025-05-11', 'INV-2025-011819', '2025-05-11', 'Elena D. Cruz, Sr.', 1, '2025-05-11 13:58:10', '2025-05-11 13:58:10'),
(146, 19, '2025-06-13', 'INV-2025-719446', '2025-06-13', 'Elena D. Cruz Sr.', 1, '2025-06-13 08:08:26', '2025-06-13 08:08:26');

-- --------------------------------------------------------

--
-- Table structure for table `dbpis_rr_details`
--

CREATE TABLE `dbpis_rr_details` (
  `rr_detail_id` int(11) NOT NULL,
  `rr_no` int(11) NOT NULL,
  `prs_id` int(11) DEFAULT NULL,
  `prs_date` date DEFAULT NULL,
  `po_no` varchar(50) DEFAULT NULL,
  `po_date` date DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `unit` varchar(50) DEFAULT NULL,
  `particulars` varchar(255) DEFAULT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `total_price` decimal(12,2) NOT NULL,
  `status` varchar(50) DEFAULT 'Received',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `dbpis_rr_details`
--

INSERT INTO `dbpis_rr_details` (`rr_detail_id`, `rr_no`, `prs_id`, `prs_date`, `po_no`, `po_date`, `quantity`, `unit`, `particulars`, `unit_price`, `total_price`, `status`, `created_at`, `updated_at`) VALUES
(29, 128, 354, '2025-04-28', NULL, NULL, 5, 'box', '4024017', '1500.00', '7500.00', 'Deployed', '2025-04-28 04:50:01', '2025-04-28 04:55:42'),
(30, 128, 354, '2025-04-28', NULL, NULL, 2, 'pcs', '1021002', '25000.00', '50000.00', 'Received', '2025-04-28 04:50:01', '2025-06-13 06:58:38'),
(31, 129, 354, '2025-04-28', NULL, NULL, 3, 'pcs', '1021002', '25000.00', '75000.00', 'Deployed', '2025-04-28 04:51:01', '2025-04-28 05:50:27'),
(32, 130, 351, '2025-04-28', NULL, NULL, 5, 'set', '2012026', '150.00', '750.00', 'Deployed', '2025-04-28 04:51:28', '2025-05-07 03:28:49'),
(33, 131, 351, '2025-04-28', NULL, NULL, 5, 'pcs', '1011021', '2500.00', '12500.00', 'Deployed', '2025-04-28 04:54:36', '2025-05-07 03:32:36'),
(34, 131, 351, '2025-04-28', NULL, NULL, 5, 'set', '4014016', '525.00', '2625.00', 'Received', '2025-04-28 04:54:36', '2025-04-28 04:54:36'),
(35, 132, 355, '2025-05-06', NULL, NULL, 2, 'pcs', '4024017', '100.00', '200.00', 'Received', '2025-05-06 14:08:17', '2025-05-06 14:08:17'),
(37, 134, 356, '2025-05-06', NULL, NULL, 5, 'pcs', '4024017', '500.00', '2500.00', 'Received', '2025-05-06 14:45:01', '2025-05-06 14:45:01'),
(39, 136, 356, '2025-05-06', NULL, NULL, 5, 'pcs', '4024017', '500.00', '2500.00', 'Received', '2025-05-08 16:09:04', '2025-05-08 16:09:04'),
(41, 138, 357, '2025-05-07', NULL, NULL, 5, 'pcs', '2022007', '5000.00', '25000.00', 'Received', '2025-05-08 16:14:54', '2025-05-08 16:14:54'),
(44, 140, 456, '2025-05-09', NULL, NULL, 3, 'pcs', '1021022', '5000.00', '15000.00', 'Received', '2025-05-10 05:52:23', '2025-05-10 05:52:23'),
(45, 141, 455, '2025-05-09', NULL, NULL, 2, 'pcs', '1021022', '5000.00', '10000.00', 'Received', '2025-05-10 05:55:20', '2025-05-10 05:55:20'),
(46, 142, 365, '2025-05-09', NULL, NULL, 12, 'reams', '2012006', '250.00', '3000.00', 'Received', '2025-05-11 09:50:11', '2025-05-11 09:50:11'),
(47, 143, 355, '2025-05-06', NULL, NULL, 3, 'pcs', '4024017', '100.00', '300.00', 'Received', '2025-05-11 13:57:26', '2025-05-11 13:57:26'),
(48, 144, 456, '2025-05-09', NULL, NULL, 2, 'pcs', '1021022', '5000.00', '10000.00', 'Received', '2025-05-11 13:57:54', '2025-05-11 13:57:54'),
(49, 145, 361, '2025-05-09', NULL, NULL, 1, 'pcs', '1021002', '7877.00', '7877.00', 'Received', '2025-05-11 13:58:10', '2025-05-11 13:58:10'),
(50, 146, 459, '2025-06-13', NULL, NULL, 5, 'pcs', '1021022', '600.00', '3000.00', 'Deployed', '2025-06-13 08:08:26', '2025-06-13 08:21:57'),
(51, 146, 459, '2025-06-13', NULL, NULL, 5, 'pcs', '4024017', '500.00', '2500.00', 'Deployed', '2025-06-13 08:08:26', '2025-06-13 08:10:10');

-- --------------------------------------------------------

--
-- Table structure for table `dbpis_staff`
--

CREATE TABLE `dbpis_staff` (
  `staff_id` int(11) NOT NULL,
  `surname` varchar(50) NOT NULL,
  `midname` varchar(50) DEFAULT NULL,
  `firstname` varchar(50) NOT NULL,
  `extension` varchar(10) DEFAULT NULL,
  `dept_id` varchar(20) NOT NULL,
  `staff_email` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `dbpis_staff`
--

INSERT INTO `dbpis_staff` (`staff_id`, `surname`, `midname`, `firstname`, `extension`, `dept_id`, `staff_email`) VALUES
(1, 'Garcia', 'A.', 'Miguel', '', '1', '200101@college.edu'),
(2, 'Santos', 'B.', 'Isabel', 'Jr.', '1', '200102@college.edu'),
(3, 'Reyes', 'C.', 'Antonio', NULL, '1', '200201@tech.edu'),
(4, 'Cruz', 'D.', 'Elena', 'Sr.', '1', '200202@tech.edu'),
(5, 'Revilo', 'P.', 'Oliver', NULL, '1', 'oliver.revilo@example.com'),
(6, 'Doe', NULL, 'John', NULL, '1', 'john.doe@example.com'),
(7, 'Smith', '', 'Jane', '', '1', 'jane.smith@example.com'),
(8, 'Johnson', NULL, 'Arisu', NULL, '1', 'arisu.johnson@example.com'),
(9, 'Jones', NULL, 'Max', NULL, '1', 'max.jones@example.com'),
(10, 'Oliver', NULL, 'Jessica', NULL, '1', 'oliver.jessica@example.com');

-- --------------------------------------------------------

--
-- Table structure for table `dbpis_supplier`
--

CREATE TABLE `dbpis_supplier` (
  `supplier_id` int(11) NOT NULL,
  `supplier_name` varchar(255) NOT NULL,
  `contact_name` varchar(255) DEFAULT NULL,
  `contact_email` varchar(255) DEFAULT NULL,
  `contact_phone` varchar(20) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` varchar(20) DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `dbpis_supplier`
--

INSERT INTO `dbpis_supplier` (`supplier_id`, `supplier_name`, `contact_name`, `contact_email`, `contact_phone`, `address`, `created_at`, `updated_at`, `status`) VALUES
(1, 'Tech Supply Co.', 'fred-joe', 'fred.joe@techsupply.com', '555-1234', '123 Tech Street', '2024-10-15 12:57:22', '2024-10-21 11:49:39', 'Active'),
(2, 'Office Essentials', 'janett.smith', 'janett.smith@officeessentials.com', '555-5678', '456 Office Ave', '2024-10-15 12:57:22', '2024-10-21 11:49:18', 'Active'),
(3, 'Warehouse Supplies Ltd.', 'Robert Brown', 'robert.brown@warehousesupplies.com', '555-8765', '789 Warehouse Blvd', '2024-10-15 12:57:22', '2024-10-15 12:57:22', 'Active'),
(4, 'Electro World', 'Alice Green', 'alice.green@electroworld.com', '555-4321', '101 Electro Way', '2024-10-15 12:57:22', '2025-06-13 08:06:49', 'Active'),
(19, 'PC TECZH', 'Kuya Rens', 'PCTECH23@gmail.com', '0921345123', 'blk 1 lot 35 mercury avenue', '2025-02-21 05:06:01', '2025-02-21 05:06:01', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `dbpis_transfer`
--

CREATE TABLE `dbpis_transfer` (
  `trans_id` int(11) NOT NULL,
  `eq_no` int(11) NOT NULL,
  `dept_unit` int(11) NOT NULL,
  `transfer_date` date NOT NULL,
  `received_by` varchar(100) NOT NULL,
  `old_unit` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `dbpis_transfer`
--

INSERT INTO `dbpis_transfer` (`trans_id`, `eq_no`, `dept_unit`, `transfer_date`, `received_by`, `old_unit`, `created_at`, `updated_at`) VALUES
(1001, 5032, 103, '2025-04-28', 'Santos, Isabel B. Jr.', 'Info Tech Lab', '2025-04-28 05:53:38', '2025-05-09 00:53:48'),
(1002, 5032, 102, '2025-04-28', 'Garcia, Miguel A. ', '103', '2025-04-28 05:58:20', '2025-05-09 00:53:51');

-- --------------------------------------------------------

--
-- Table structure for table `dbpis_unit`
--

CREATE TABLE `dbpis_unit` (
  `unit_id` int(11) NOT NULL,
  `unit_name` varchar(255) DEFAULT NULL,
  `dept_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `dbpis_unit`
--

INSERT INTO `dbpis_unit` (`unit_id`, `unit_name`, `dept_id`) VALUES
(101, 'Faculty Office', 1),
(102, 'Info Tech Lab', 1),
(103, 'Automotive Lab', 1),
(104, 'Mechanical Lab', 1),
(105, 'Electromechanical Lab', 1),
(106, 'General Computer Lab', 1);

-- --------------------------------------------------------

--
-- Table structure for table `dbpis_useraccounts`
--

CREATE TABLE `dbpis_useraccounts` (
  `uaid` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(30) DEFAULT NULL,
  `status` varchar(11) DEFAULT NULL,
  `modid` int(11) DEFAULT NULL,
  `position` varchar(255) NOT NULL,
  `last_activity` datetime DEFAULT NULL,
  `failed_attempts` int(11) DEFAULT 0,
  `lock_until` datetime DEFAULT NULL,
  `dept_id` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `dbpis_useraccounts`
--

INSERT INTO `dbpis_useraccounts` (`uaid`, `name`, `username`, `password`, `status`, `modid`, `position`, `last_activity`, `failed_attempts`, `lock_until`, `dept_id`) VALUES
(1, '5', 'admin', '123456', '1', 0, 'System Admin', '2025-08-05 15:30:26', 0, NULL, 1),
(3, '6', 'custodian', '123456', '1', 1, 'PR Custodian', '2025-08-05 17:32:57', 0, NULL, 1),
(4, '7', 'requestor', '123456', '1', 2, 'Requestor', '2025-08-05 15:11:42', 0, NULL, 1),
(5, '8', 'dept_head', '123456', '1', 3, 'Dept. Head', '2025-08-05 15:37:49', 0, NULL, 1),
(6, '9', 'approver', '123456', '1', 4, 'Admin', '2025-08-05 15:37:55', 0, NULL, 1),
(7, '10', 'purchaser', '123456', '1', 5, 'Purchaser', '2025-08-05 17:32:03', 0, NULL, 1),
(8, '4', 'stockroom', '123456', '1', 6, 'Stockroom', '2025-06-14 10:13:56', 0, NULL, 1),
(9, '3', 'requestor2', '123456', '1', 2, 'Requestor', '2025-06-12 09:44:32', 0, NULL, 1);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_df_full`
-- (See below for the actual view)
--
CREATE TABLE `view_df_full` (
`df_no` int(11)
,`staff_id` varchar(50)
,`dept_id` int(11)
,`dept_name` varchar(255)
,`unit_id` int(11)
,`unit_name` varchar(255)
,`df_date` date
,`df_reqstby` varchar(100)
,`rr_no` int(11)
,`updated_at` timestamp
,`it_no` int(11)
,`particular` text
,`category` int(11)
,`df_qty` int(11)
,`df_unit` varchar(50)
,`df_amount` decimal(10,2)
,`eq_no` varchar(50)
,`tagged_to` varchar(50)
,`equipment_location` varchar(255)
,`tagged_date` date
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_prs_full`
-- (See below for the actual view)
--
CREATE TABLE `view_prs_full` (
`prs_id` int(11)
,`prs_code` varchar(50)
,`requested_by` varchar(100)
,`requested_by_name` varchar(50)
,`department` varchar(100)
,`unit_id` int(11)
,`unit_name` varchar(255)
,`date_requested` date
,`date_needed` date
,`dept_head` varchar(255)
,`dept_head_name` varchar(50)
,`approval_status` enum('Pending','Approved','Rejected','Deployed','Received','Purchase')
,`approved_by` varchar(255)
,`approved_by_name` varchar(50)
,`created_at` timestamp
,`updated_at` timestamp
,`prsdetails_id` int(11)
,`item_code` int(11)
,`particular` varchar(255)
,`brand` varchar(255)
,`category` int(11)
,`quantity` int(11)
,`unit_type` varchar(50)
,`unit_price` decimal(10,2)
,`total_price` decimal(10,2)
,`supplier_name` varchar(255)
);

-- --------------------------------------------------------

--
-- Structure for view `dbpis_get_rr`
--
DROP TABLE IF EXISTS `dbpis_get_rr`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `dbpis_get_rr`  AS  select `rr`.`rr_no` AS `rr_no`,`rr`.`date_received` AS `date_received`,`rr`.`invoice_no` AS `invoice_no`,`rr`.`invoice_date` AS `invoice_date`,`rr`.`received_by` AS `received_by`,`su`.`supplier_name` AS `received_from`,`d`.`dept_name` AS `department`,`rd`.`prs_id` AS `prs_id`,`p`.`prs_code` AS `prs_no`,`p`.`date_requested` AS `prs_date`,`rd`.`po_no` AS `po_no`,`rd`.`po_date` AS `po_date`,`i`.`barcode` AS `item_barcode`,`i`.`brand` AS `item_brand`,`i`.`particular` AS `item_particular`,`rd`.`quantity` AS `quantity`,`rd`.`unit` AS `unit`,`rd`.`unit_price` AS `unit_price`,`rd`.`total_price` AS `total_price`,`rd`.`status` AS `status`,`rr`.`created_at` AS `rr_created_at`,`rr`.`updated_at` AS `rr_updated_at`,`rd`.`created_at` AS `detail_created_at`,`rd`.`updated_at` AS `detail_updated_at` from (((((`dbpis_rr` `rr` join `dbpis_supplier` `su` on(`rr`.`received_from` = `su`.`supplier_id`)) left join `dbpis_department` `d` on(`rr`.`department_id` = `d`.`dept_id`)) join `dbpis_rr_details` `rd` on(`rr`.`rr_no` = `rd`.`rr_no`)) left join `dbpis_prs` `p` on(`rd`.`prs_id` = `p`.`prs_id`)) left join `dbpis_items` `i` on(`rd`.`particulars` = `i`.`barcode`)) ;

-- --------------------------------------------------------

--
-- Structure for view `view_df_full`
--
DROP TABLE IF EXISTS `view_df_full`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_df_full`  AS  select `df`.`df_no` AS `df_no`,`df`.`staff_id` AS `staff_id`,`df`.`dept_id` AS `dept_id`,`dept`.`dept_name` AS `dept_name`,`df`.`unit_id` AS `unit_id`,`unit`.`unit_name` AS `unit_name`,`df`.`df_date` AS `df_date`,`df`.`df_reqstby` AS `df_reqstby`,`df`.`rr_no` AS `rr_no`,`df`.`updated_at` AS `updated_at`,`dfd`.`it_no` AS `it_no`,concat(`items`.`particular`,' - ',`items`.`brand`) AS `particular`,`items`.`category` AS `category`,`dfd`.`df_qty` AS `df_qty`,`dfd`.`df_unit` AS `df_unit`,`dfd`.`df_amount` AS `df_amount`,`dfd`.`eq_no` AS `eq_no`,`eq`.`emp_id` AS `tagged_to`,`eq`.`eq_loc` AS `equipment_location`,`eq`.`eq_date` AS `tagged_date` from (((((`dbpis_df` `df` join `dbpis_df_details` `dfd` on(`df`.`df_no` = `dfd`.`df_no`)) left join `dbpis_department` `dept` on(`df`.`dept_id` = `dept`.`dept_id`)) left join `dbpis_unit` `unit` on(`df`.`unit_id` = `unit`.`unit_id`)) left join `dbpis_items` `items` on(`dfd`.`it_no` = `items`.`barcode`)) left join `dbpis_eq_tagging` `eq` on(`dfd`.`eq_no` = `eq`.`eq_no`)) ;

-- --------------------------------------------------------

--
-- Structure for view `view_prs_full`
--
DROP TABLE IF EXISTS `view_prs_full`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_prs_full`  AS  select `p`.`prs_id` AS `prs_id`,`p`.`prs_code` AS `prs_code`,`p`.`requested_by` AS `requested_by`,`ua1`.`name` AS `requested_by_name`,`p`.`department` AS `department`,`p`.`unit_id` AS `unit_id`,`u`.`unit_name` AS `unit_name`,`p`.`date_requested` AS `date_requested`,`p`.`date_needed` AS `date_needed`,`p`.`dept_head` AS `dept_head`,`ua2`.`name` AS `dept_head_name`,`p`.`approval_status` AS `approval_status`,`p`.`approved_by` AS `approved_by`,`ua3`.`name` AS `approved_by_name`,`p`.`created_at` AS `created_at`,`p`.`updated_at` AS `updated_at`,`pd`.`prsdetails_id` AS `prsdetails_id`,`pd`.`item_code` AS `item_code`,`i`.`particular` AS `particular`,`i`.`brand` AS `brand`,`i`.`category` AS `category`,`pd`.`quantity` AS `quantity`,`pd`.`unit_type` AS `unit_type`,`pd`.`unit_price` AS `unit_price`,`pd`.`total_price` AS `total_price`,`s`.`supplier_name` AS `supplier_name` from ((((((((`dbpis_prs` `p` left join `dbpis_prsdetails` `pd` on(`p`.`prs_code` = `pd`.`prs_code`)) left join `dbpis_items` `i` on(`pd`.`item_code` = `i`.`barcode`)) left join `dbpis_supplier` `s` on(`pd`.`supplier` = `s`.`supplier_name`)) left join `dbpis_useraccounts` `ua1` on(`ua1`.`name` = `p`.`requested_by`)) left join `dbpis_useraccounts` `ua2` on(`ua2`.`name` = `p`.`dept_head`)) left join `dbpis_useraccounts` `ua3` on(`ua3`.`name` = `p`.`approved_by`)) left join `dbpis_department` `d` on(`d`.`dept_id` = `p`.`department`)) left join `dbpis_unit` `u` on(`p`.`unit_id` = `u`.`unit_id`)) ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `dbpis_department`
--
ALTER TABLE `dbpis_department`
  ADD PRIMARY KEY (`dept_id`);

--
-- Indexes for table `dbpis_df`
--
ALTER TABLE `dbpis_df`
  ADD PRIMARY KEY (`df_no`);

--
-- Indexes for table `dbpis_df_details`
--
ALTER TABLE `dbpis_df_details`
  ADD PRIMARY KEY (`df_details_id`),
  ADD KEY `df_no` (`df_no`);

--
-- Indexes for table `dbpis_eq_tagging`
--
ALTER TABLE `dbpis_eq_tagging`
  ADD PRIMARY KEY (`eq_no`);

--
-- Indexes for table `dbpis_eq_tag_details`
--
ALTER TABLE `dbpis_eq_tag_details`
  ADD PRIMARY KEY (`eqd_id`),
  ADD KEY `eq_no` (`eq_no`);

--
-- Indexes for table `dbpis_itemcategory_group`
--
ALTER TABLE `dbpis_itemcategory_group`
  ADD PRIMARY KEY (`itemcatgrp_id`);

--
-- Indexes for table `dbpis_items`
--
ALTER TABLE `dbpis_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category` (`category`);

--
-- Indexes for table `dbpis_item_category`
--
ALTER TABLE `dbpis_item_category`
  ADD PRIMARY KEY (`itcat_id`),
  ADD KEY `itemcatgrp_id` (`itemcatgrp_id`);

--
-- Indexes for table `dbpis_item_suppliers`
--
ALTER TABLE `dbpis_item_suppliers`
  ADD PRIMARY KEY (`item_id`,`supplier_id`),
  ADD KEY `supplier_id` (`supplier_id`);

--
-- Indexes for table `dbpis_menusidebar`
--
ALTER TABLE `dbpis_menusidebar`
  ADD PRIMARY KEY (`menu_id`);

--
-- Indexes for table `dbpis_pricelist`
--
ALTER TABLE `dbpis_pricelist`
  ADD PRIMARY KEY (`price_id`);

--
-- Indexes for table `dbpis_prs`
--
ALTER TABLE `dbpis_prs`
  ADD PRIMARY KEY (`prs_id`),
  ADD KEY `fk_unit_id` (`unit_id`);

--
-- Indexes for table `dbpis_prsdetails`
--
ALTER TABLE `dbpis_prsdetails`
  ADD PRIMARY KEY (`prsdetails_id`);

--
-- Indexes for table `dbpis_rr`
--
ALTER TABLE `dbpis_rr`
  ADD PRIMARY KEY (`rr_no`),
  ADD KEY `fk_received_from` (`received_from`),
  ADD KEY `fk_department` (`department_id`);

--
-- Indexes for table `dbpis_rr_details`
--
ALTER TABLE `dbpis_rr_details`
  ADD PRIMARY KEY (`rr_detail_id`),
  ADD KEY `fk_rr` (`rr_no`),
  ADD KEY `fk_prs` (`prs_id`);

--
-- Indexes for table `dbpis_staff`
--
ALTER TABLE `dbpis_staff`
  ADD PRIMARY KEY (`staff_id`),
  ADD UNIQUE KEY `staff_email` (`staff_email`);

--
-- Indexes for table `dbpis_supplier`
--
ALTER TABLE `dbpis_supplier`
  ADD PRIMARY KEY (`supplier_id`);

--
-- Indexes for table `dbpis_transfer`
--
ALTER TABLE `dbpis_transfer`
  ADD PRIMARY KEY (`trans_id`),
  ADD KEY `eq_no` (`eq_no`),
  ADD KEY `dept_unit` (`dept_unit`);

--
-- Indexes for table `dbpis_unit`
--
ALTER TABLE `dbpis_unit`
  ADD PRIMARY KEY (`unit_id`),
  ADD KEY `dept_id` (`dept_id`);

--
-- Indexes for table `dbpis_useraccounts`
--
ALTER TABLE `dbpis_useraccounts`
  ADD PRIMARY KEY (`uaid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `dbpis_df`
--
ALTER TABLE `dbpis_df`
  MODIFY `df_no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10026;

--
-- AUTO_INCREMENT for table `dbpis_df_details`
--
ALTER TABLE `dbpis_df_details`
  MODIFY `df_details_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT for table `dbpis_eq_tagging`
--
ALTER TABLE `dbpis_eq_tagging`
  MODIFY `eq_no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5058;

--
-- AUTO_INCREMENT for table `dbpis_eq_tag_details`
--
ALTER TABLE `dbpis_eq_tag_details`
  MODIFY `eqd_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `dbpis_itemcategory_group`
--
ALTER TABLE `dbpis_itemcategory_group`
  MODIFY `itemcatgrp_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `dbpis_items`
--
ALTER TABLE `dbpis_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=507;

--
-- AUTO_INCREMENT for table `dbpis_item_category`
--
ALTER TABLE `dbpis_item_category`
  MODIFY `itcat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=407;

--
-- AUTO_INCREMENT for table `dbpis_menusidebar`
--
ALTER TABLE `dbpis_menusidebar`
  MODIFY `menu_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `dbpis_pricelist`
--
ALTER TABLE `dbpis_pricelist`
  MODIFY `price_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `dbpis_prs`
--
ALTER TABLE `dbpis_prs`
  MODIFY `prs_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=460;

--
-- AUTO_INCREMENT for table `dbpis_prsdetails`
--
ALTER TABLE `dbpis_prsdetails`
  MODIFY `prsdetails_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=835;

--
-- AUTO_INCREMENT for table `dbpis_rr`
--
ALTER TABLE `dbpis_rr`
  MODIFY `rr_no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=147;

--
-- AUTO_INCREMENT for table `dbpis_rr_details`
--
ALTER TABLE `dbpis_rr_details`
  MODIFY `rr_detail_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `dbpis_staff`
--
ALTER TABLE `dbpis_staff`
  MODIFY `staff_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `dbpis_supplier`
--
ALTER TABLE `dbpis_supplier`
  MODIFY `supplier_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `dbpis_transfer`
--
ALTER TABLE `dbpis_transfer`
  MODIFY `trans_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1004;

--
-- AUTO_INCREMENT for table `dbpis_useraccounts`
--
ALTER TABLE `dbpis_useraccounts`
  MODIFY `uaid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `dbpis_df_details`
--
ALTER TABLE `dbpis_df_details`
  ADD CONSTRAINT `dbpis_df_details_ibfk_1` FOREIGN KEY (`df_no`) REFERENCES `dbpis_df` (`df_no`);

--
-- Constraints for table `dbpis_eq_tag_details`
--
ALTER TABLE `dbpis_eq_tag_details`
  ADD CONSTRAINT `dbpis_eq_tag_details_ibfk_1` FOREIGN KEY (`eq_no`) REFERENCES `dbpis_eq_tagging` (`eq_no`);

--
-- Constraints for table `dbpis_items`
--
ALTER TABLE `dbpis_items`
  ADD CONSTRAINT `dbpis_items_ibfk_1` FOREIGN KEY (`category`) REFERENCES `dbpis_item_category` (`itcat_id`);

--
-- Constraints for table `dbpis_item_category`
--
ALTER TABLE `dbpis_item_category`
  ADD CONSTRAINT `dbpis_item_category_ibfk_1` FOREIGN KEY (`itemcatgrp_id`) REFERENCES `dbpis_itemcategory_group` (`itemcatgrp_id`);

--
-- Constraints for table `dbpis_item_suppliers`
--
ALTER TABLE `dbpis_item_suppliers`
  ADD CONSTRAINT `dbpis_item_suppliers_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `dbpis_items` (`id`),
  ADD CONSTRAINT `dbpis_item_suppliers_ibfk_2` FOREIGN KEY (`supplier_id`) REFERENCES `dbpis_supplier` (`supplier_id`);

--
-- Constraints for table `dbpis_prs`
--
ALTER TABLE `dbpis_prs`
  ADD CONSTRAINT `fk_unit_id` FOREIGN KEY (`unit_id`) REFERENCES `dbpis_unit` (`unit_id`) ON UPDATE CASCADE;

--
-- Constraints for table `dbpis_rr`
--
ALTER TABLE `dbpis_rr`
  ADD CONSTRAINT `fk_department` FOREIGN KEY (`department_id`) REFERENCES `dbpis_department` (`dept_id`),
  ADD CONSTRAINT `fk_received_from` FOREIGN KEY (`received_from`) REFERENCES `dbpis_supplier` (`supplier_id`);

--
-- Constraints for table `dbpis_rr_details`
--
ALTER TABLE `dbpis_rr_details`
  ADD CONSTRAINT `fk_prs` FOREIGN KEY (`prs_id`) REFERENCES `dbpis_prs` (`prs_id`),
  ADD CONSTRAINT `fk_rr` FOREIGN KEY (`rr_no`) REFERENCES `dbpis_rr` (`rr_no`);

--
-- Constraints for table `dbpis_transfer`
--
ALTER TABLE `dbpis_transfer`
  ADD CONSTRAINT `dbpis_transfer_ibfk_1` FOREIGN KEY (`eq_no`) REFERENCES `dbpis_eq_tagging` (`eq_no`),
  ADD CONSTRAINT `dbpis_transfer_ibfk_2` FOREIGN KEY (`dept_unit`) REFERENCES `dbpis_unit` (`unit_id`);

--
-- Constraints for table `dbpis_unit`
--
ALTER TABLE `dbpis_unit`
  ADD CONSTRAINT `dbpis_unit_ibfk_1` FOREIGN KEY (`dept_id`) REFERENCES `dbpis_department` (`dept_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
