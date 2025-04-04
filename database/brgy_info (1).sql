-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 06, 2024 at 03:32 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `brgy_info`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `accountID` int(20) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`accountID`, `username`, `password`) VALUES
(564, 'admin', 'admin123'),
(55454, 'admin', 'admin\r\n                                                                 ');

-- --------------------------------------------------------

--
-- Table structure for table `blotter`
--

CREATE TABLE `blotter` (
  `File_No` varchar(255) NOT NULL,
  `Barangay` varchar(100) DEFAULT NULL,
  `Purok` varchar(100) DEFAULT NULL,
  `Incident` varchar(255) DEFAULT NULL,
  `Place_of_Incident` varchar(255) DEFAULT NULL,
  `Date` date DEFAULT NULL,
  `Time` time DEFAULT NULL,
  `Complainant` varchar(255) DEFAULT NULL,
  `Witness` varchar(255) DEFAULT NULL,
  `Narrative` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `blotter`
--

INSERT INTO `blotter` (`File_No`, `Barangay`, `Purok`, `Incident`, `Place_of_Incident`, `Date`, `Time`, `Complainant`, `Witness`, `Narrative`) VALUES
('CASE-119302', 'marinig ', '4', 'fight for money', 'marinig purok 4', '2023-03-12', '14:31:00', 'erish', 'leo', 'family problem si mama ksi nanapak'),
('CASE-683104', 'marinig', '1', 'nagnakaw si', 'marinig purok 1', '2024-05-15', '12:24:00', 'erish', 'zai', '123'),
('CASE-696460', 'Parian', '1', 'bangaan', 'bahay ko', '2024-05-15', '04:27:00', 'ako', 'ako', 'nabangga ako'),
('CASE-811581', 'Marinig', '1', 'Car crash', 'Marinig', '2024-05-15', '20:46:00', 'Joacob', 'malfoy', 'apinjedsfghbuasdfaujdh bfasdjkiun fio;sdjka foal;ksd a 12 23 123123 12312 34124 3245 134rwerf sdf sdg sdfg sdfg sdfg sdf gg a gdsf asf asdf sqwa  fasdfdsafsd fasdfasd fsd af sdfa sd fas d ad as asf dsa asd as sdaf sde fas  asd asd asd awerwrfasd gdafdsfadfad fasd fasd fa ds dsad sfioa;jdfijk;lasjd;io/f sodlkifiokl asdnfio sdiok fnd asdiof nasod'),
('CASE-965086', '123', '2', 'thief', '123', '2024-12-31', '21:19:00', 'jessica', 'cardo', 'steal money');

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `RefNo` varchar(14) NOT NULL,
  `Type` varchar(50) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Amount` decimal(10,2) NOT NULL,
  `PaymentDate` date NOT NULL,
  `Purpose` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`RefNo`, `Type`, `Name`, `Amount`, `PaymentDate`, `Purpose`) VALUES
('REQ-664452e5a3', 'Brgy clearance', 'Ali', '20.00', '2024-05-15', 'Loan Purpose'),
('REQ-66460932e2', 'Business Clearance', '123', '20.00', '2024-05-16', 'Loan Purpose'),
('REQ-66474431e7', 'Brgy clearance', 'Jhon Mark', '20.00', '2024-05-17', 'OJT');

-- --------------------------------------------------------

--
-- Table structure for table `request`
--

CREATE TABLE `request` (
  `RefNo` varchar(14) NOT NULL,
  `Type` varchar(50) DEFAULT NULL,
  `Name` varchar(100) DEFAULT NULL,
  `Amount` decimal(10,2) DEFAULT NULL,
  `PaymentDate` date DEFAULT NULL,
  `Purpose` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `request`
--

INSERT INTO `request` (`RefNo`, `Type`, `Name`, `Amount`, `PaymentDate`, `Purpose`) VALUES
('REQ-6647486d21', 'Business Clearance', 'John Raven', '50.00', '2024-05-17', 'assfasfasfas'),
('REQ-6647490a38', 'Building Clearance', 'Ver Sedrick', '40.00', '2024-05-17', 'fasfasfasfasfasghdfs');

-- --------------------------------------------------------

--
-- Table structure for table `resident_list`
--

CREATE TABLE `resident_list` (
  `accountID` int(11) NOT NULL,
  `lastName` varchar(255) DEFAULT NULL,
  `firstName` varchar(255) DEFAULT NULL,
  `middleName` varchar(255) DEFAULT NULL,
  `Alias` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `house_no` varchar(255) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `gender` varchar(255) DEFAULT NULL,
  `civil_status` varchar(255) DEFAULT NULL,
  `birthplace` varchar(255) DEFAULT NULL,
  `religion` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `contact_number` varchar(20) DEFAULT NULL,
  `voter_status` varchar(255) DEFAULT NULL,
  `precinct_no` varchar(255) DEFAULT NULL,
  `education_attainment` varchar(255) DEFAULT NULL,
  `occupation` varchar(255) DEFAULT NULL,
  `disability` varchar(255) DEFAULT NULL,
  `vaccination_status` varchar(255) DEFAULT NULL,
  `vaccine` varchar(255) DEFAULT NULL,
  `vaccination_type` varchar(255) DEFAULT NULL,
  `vaccination_date` date DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `national_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `resident_list`
--

INSERT INTO `resident_list` (`accountID`, `lastName`, `firstName`, `middleName`, `Alias`, `address`, `house_no`, `birthdate`, `age`, `gender`, `civil_status`, `birthplace`, `religion`, `email`, `contact_number`, `voter_status`, `precinct_no`, `education_attainment`, `occupation`, `disability`, `vaccination_status`, `vaccine`, `vaccination_type`, `vaccination_date`, `username`, `password`, `national_id`) VALUES
(6046, 'Suelto', 'Ver Sedrick', 'Medina', 'sed', 'Purok 6', '444B', '2003-02-05', 21, 'Male', 'Single', 'marinig', 'catholic', 'versedrick@gmail.com', '09217895432', 'yes', NULL, 'no', 'student', 'None', 'Yes', 'AstraZeneca', 'Second Dose', '2020-03-15', 'sedrick', 'versedrick', 654),
(7439, 'Zaide', 'John Raven', 'Atendido', 'raven', 'St Joseph 7 Village', '432C', '2003-04-04', 21, 'Male', 'Single', 'marinig', 'catholic', 'raven@gmail.com', '09218762345', 'yes', NULL, 'no', 'student', 'No', 'Yes', 'Sinovac', 'Second Dose', '2020-03-17', 'ravennn', '', 911),
(8448, 'Carta', 'Jhon Mark', 'Manaig', 'jm', 'Purok 3', '227', '2002-05-05', 22, 'Male', 'Single', 'marinig', 'catholic', 'jhonmarkmcarta@gmail.com', '09507300402', 'yes', NULL, 'no', 'student', 'no', 'Yes', 'Moderna', 'Second Dose', '2020-03-17', 'jhonmark', 'jhonmarkmcarta05', 921);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`accountID`);

--
-- Indexes for table `blotter`
--
ALTER TABLE `blotter`
  ADD PRIMARY KEY (`File_No`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`RefNo`);

--
-- Indexes for table `request`
--
ALTER TABLE `request`
  ADD PRIMARY KEY (`RefNo`);

--
-- Indexes for table `resident_list`
--
ALTER TABLE `resident_list`
  ADD PRIMARY KEY (`accountID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `resident_list`
--
ALTER TABLE `resident_list`
  MODIFY `accountID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6626044;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
