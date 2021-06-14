-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 26, 2020 at 08:50 AM
-- Server version: 10.1.38-MariaDB
-- PHP Version: 7.3.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `990project`
--

-- --------------------------------------------------------

--
-- Table structure for table `agency`
--

CREATE TABLE `agency` (
  `agency_id` varchar(200) NOT NULL,
  `agency_name` varchar(200) NOT NULL,
  `agency_url` varchar(200) NOT NULL,
  `agency_timezone` varchar(200) NOT NULL,
  `agency_lang` varchar(10) NOT NULL,
  `agency_phone` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `calendar`
--

CREATE TABLE `calendar` (
  `service_id` varchar(50) NOT NULL,
  `monday` int(1) NOT NULL,
  `tuesday` int(1) NOT NULL,
  `wednesday` int(1) NOT NULL,
  `thursday` int(1) NOT NULL,
  `friday` int(1) NOT NULL,
  `saturday` int(1) NOT NULL,
  `sunday` int(1) NOT NULL,
  `start_date` varchar(10) NOT NULL,
  `end_date` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `delays`
--

CREATE TABLE `delays` (
  `type` int(2) NOT NULL COMMENT 'type 0 = stop , type 1 = trip , type 3 = route',
  `entity` varchar(200) NOT NULL,
  `header` varchar(500) NOT NULL,
  `description` mediumtext NOT NULL,
  `cause` varchar(500) NOT NULL,
  `effect` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `routes`
--

CREATE TABLE `routes` (
  `route_id` varchar(20) NOT NULL,
  `agency_id` varchar(20) NOT NULL,
  `route_short_name` varchar(10) NOT NULL,
  `route_long_name` varchar(100) NOT NULL,
  `route_desc` varchar(100) NOT NULL,
  `route_type` varchar(5) NOT NULL,
  `route_url` varchar(50) NOT NULL,
  `route_color` varchar(10) NOT NULL,
  `route_text_color` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `shapes`
--

CREATE TABLE `shapes` (
  `shape_id` varchar(10) NOT NULL,
  `shape_pt_lat` varchar(100) NOT NULL,
  `shape_pt_lon` varchar(100) NOT NULL,
  `shape_pt_sequence` int(10) NOT NULL,
  `shape_dist_traveled` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `stops`
--

CREATE TABLE `stops` (
  `stop_id` varchar(20) NOT NULL,
  `stop_code` varchar(20) NOT NULL,
  `stop_name` varchar(100) NOT NULL,
  `stop_desc` varchar(2000) NOT NULL,
  `stop_lat` varchar(100) NOT NULL,
  `stop_lon` varchar(100) NOT NULL,
  `zone_id` varchar(50) NOT NULL,
  `stop_url` varchar(100) NOT NULL,
  `location_type` int(1) NOT NULL,
  `parent_station` varchar(20) NOT NULL,
  `stop_timezone` varchar(20) NOT NULL,
  `wheelchair_boarding` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `stop_times`
--

CREATE TABLE `stop_times` (
  `trip_id` varchar(100) NOT NULL,
  `arrival_time` varchar(20) NOT NULL,
  `departure_time` varchar(20) NOT NULL,
  `stop_id` varchar(20) NOT NULL,
  `stop_sequence` int(10) NOT NULL,
  `stop_headsign` varchar(100) NOT NULL,
  `pickup_type` int(1) NOT NULL,
  `drop_off_type` int(1) NOT NULL,
  `shape_dist_traveled` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `stop_update`
--

CREATE TABLE `stop_update` (
  `trip_id` varchar(200) NOT NULL,
  `stop_id` varchar(200) NOT NULL,
  `arrivalTime` varchar(200) NOT NULL,
  `arrivalDelay` varchar(200) NOT NULL,
  `departureTime` varchar(200) NOT NULL,
  `departureDelay` varchar(200) NOT NULL,
  `stopScheduleRelationship` varchar(200) NOT NULL,
  `trainScheduleRelationship` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `train_position`
--

CREATE TABLE `train_position` (
  `vehicle_id` varchar(200) NOT NULL,
  `trip_id` varchar(200) NOT NULL,
  `position_lat` varchar(100) NOT NULL,
  `position_lon` varchar(100) NOT NULL,
  `stop_id` varchar(500) NOT NULL,
  `schedule_relationship` varchar(500) NOT NULL,
  `congestion_level` varchar(500) NOT NULL,
  `timestamp` varchar(200) NOT NULL,
  `route_id` varchar(500) NOT NULL,
  `train_label` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `trips`
--

CREATE TABLE `trips` (
  `route_id` varchar(20) NOT NULL,
  `service_id` varchar(50) NOT NULL,
  `trip_id` varchar(100) NOT NULL,
  `trip_headsign` varchar(200) NOT NULL,
  `trip_short_name` varchar(100) NOT NULL,
  `direction_id` int(1) NOT NULL,
  `block_id` varchar(10) NOT NULL,
  `shape_id` varchar(10) NOT NULL,
  `wheelchair_accessible` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `calendar`
--
ALTER TABLE `calendar`
  ADD PRIMARY KEY (`service_id`);

--
-- Indexes for table `delays`
--
ALTER TABLE `delays`
  ADD PRIMARY KEY (`entity`);

--
-- Indexes for table `routes`
--
ALTER TABLE `routes`
  ADD PRIMARY KEY (`route_id`);

--
-- Indexes for table `stops`
--
ALTER TABLE `stops`
  ADD PRIMARY KEY (`stop_id`);

--
-- Indexes for table `stop_times`
--
ALTER TABLE `stop_times`
  ADD KEY `tripID` (`trip_id`),
  ADD KEY `stopID` (`stop_id`);

--
-- Indexes for table `stop_update`
--
ALTER TABLE `stop_update`
  ADD PRIMARY KEY (`trip_id`,`stop_id`);

--
-- Indexes for table `train_position`
--
ALTER TABLE `train_position`
  ADD PRIMARY KEY (`vehicle_id`);

--
-- Indexes for table `trips`
--
ALTER TABLE `trips`
  ADD PRIMARY KEY (`trip_id`),
  ADD KEY `routeID` (`route_id`),
  ADD KEY `serviceID` (`service_id`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `stop_times`
--
ALTER TABLE `stop_times`
  ADD CONSTRAINT `stop_times_ibfk_1` FOREIGN KEY (`trip_id`) REFERENCES `trips` (`trip_id`),
  ADD CONSTRAINT `stop_times_ibfk_2` FOREIGN KEY (`stop_id`) REFERENCES `stops` (`stop_id`);

--
-- Constraints for table `trips`
--
ALTER TABLE `trips`
  ADD CONSTRAINT `trips_ibfk_1` FOREIGN KEY (`route_id`) REFERENCES `routes` (`route_id`),
  ADD CONSTRAINT `trips_ibfk_2` FOREIGN KEY (`service_id`) REFERENCES `calendar` (`service_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
