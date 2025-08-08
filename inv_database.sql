-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 01, 2025 at 04:58 AM
-- Server version: 10.1.38-MariaDB
-- PHP Version: 5.6.40

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `inv_database`
--

-- --------------------------------------------------------

--
-- Table structure for table `inv_act`
--

CREATE TABLE `inv_act` (
  `id_act` int(11) NOT NULL,
  `id_dvc` int(11) DEFAULT NULL,
  `dvc_sn` varchar(100) DEFAULT NULL,
  `dvc_size` varchar(20) DEFAULT NULL,
  `dvc_col` varchar(50) DEFAULT NULL,
  `dvc_qc` varchar(50) DEFAULT NULL,
  `act_date` datetime DEFAULT NULL,
  `adm_act` int(11) NOT NULL,
  `inv_in` datetime DEFAULT NULL,
  `inv_move` datetime DEFAULT NULL,
  `inv_out` datetime DEFAULT NULL,
  `inv_rls` datetime DEFAULT NULL,
  `adm_in` int(11) DEFAULT NULL,
  `adm_move` int(11) DEFAULT NULL,
  `adm_out` int(11) DEFAULT NULL,
  `adm_rls` int(11) DEFAULT NULL,
  `loc_move` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `inv_dvc`
--

CREATE TABLE `inv_dvc` (
  `id_dvc` int(11) NOT NULL,
  `dvc_tech` varchar(50) DEFAULT NULL,
  `dvc_type` varchar(50) DEFAULT NULL,
  `dvc_code` varchar(50) DEFAULT NULL,
  `dvc_code_sn` varchar(50) DEFAULT NULL,
  `dvc_name` varchar(100) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `dvc_priority` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `inv_dvc`
--

INSERT INTO `inv_dvc` (`id_dvc`, `dvc_tech`, `dvc_type`, `dvc_code`, `dvc_code_sn`, `dvc_name`, `status`, `dvc_priority`) VALUES
(1, 'ecct', 'APP', 'HFH AG', 'T11', 'Helmet Full Head', 0, 13),
(2, 'ecct', 'APP', 'HFH SG', 'T12', 'Helmet Full Head', 0, 14),
(3, 'ecct', 'APP', 'HFM AG', 'T13', 'Helmet Full Mask', 0, 11),
(4, 'ecct', 'APP', 'HFM SG', 'T14', 'Helmet Full Mask', 0, 12),
(5, 'ecct', 'APP', 'HFN AG', 'T15', 'Helmet Full Neck', 0, 9),
(6, 'ecct', 'APP', 'HFN SG', 'T16', 'Helmet Full Neck', 0, 10),
(7, 'ecct', 'APP', 'MFH AG', 'T17', 'Mask Full Head', 0, 15),
(8, 'ecct', 'APP', 'MFH SG', 'T18', 'Mask Full Head', 0, 16),
(9, 'ecct', 'APP', 'VTS AG', 'T21', 'Vest Thorax Standard', 0, 7),
(10, 'ecct', 'APP', 'VTS CG', 'T22', 'Vest Thorax Standard', 0, 8),
(11, 'ecct', 'APP', 'VTA AG', 'T23', 'Vest Thorax Abdomen', 0, 3),
(12, 'ecct', 'APP', 'VTA CG', 'T24', 'Vest Thorax Abdomen', 0, 4),
(13, 'ecct', 'APP', 'VTA L AG', 'T25', 'Vest Thorax Abdomen Long', 0, 5),
(14, 'ecct', 'APP', 'VTA L CG', 'T26', 'Vest Thorax Abdomen Long', 0, 6),
(15, 'ecct', 'APP', 'SS AG', 'T31', 'Short Standart', 0, 19),
(17, 'ecct', 'APP', 'SA AG', 'T33', 'Short Abdomen', 0, 17),
(18, 'ecct', 'APP', 'SA CG', 'T34', 'Short Abdomen', 0, 18),
(19, 'ecct', 'APP', 'SB AG', 'T35', 'Sleeping Bag', 0, 2),
(21, 'ecct', 'APP', 'BFS AG', 'T37', 'Blanket Full Size', 0, 1),
(22, 'ecct', 'APP', 'Kit ADG', 'T41', 'KIT', 0, 20),
(23, 'ecct', 'APP', 'Kit AG', 'T42', 'Kit AG', 1, NULL),
(24, 'ecct', 'APP', 'KNS AG', 'T43', 'KNS AG', 1, NULL),
(25, 'ecct', 'APP', 'KNS CG', 'T44', 'KNS CG', 1, NULL),
(26, 'ecct', 'cus', 'CUS', 'T91', 'CUS', 1, NULL),
(27, 'ecct', 'osc', 'STD', 'T51', 'Power Supply Standard', 0, 1),
(28, 'ecct', 'osc', 'STD', 'T52', 'Power Supply Standard', 0, 1),
(29, 'ecct', 'osc', 'MVHF C1', 'T53', 'ECCT MVHF C1', 0, 2),
(30, 'ecct', 'osc', 'MVHF C2', 'T54', 'ECCT MVHF C2', 1, NULL),
(31, 'ecct', 'osc', 'MVSF', 'T55', 'ECCT MVSF', 0, 3),
(32, 'ecct', 'osc', 'EFD', 'T71', 'EFD', 0, 4),
(33, 'ecct', 'osc', 'EFD', 'T72', 'EFD', 0, 4),
(34, 'ecct', 'osc', 'C-CAGE', 'T81', 'C-CAGE', 1, NULL),
(35, 'ecct', 'cus', 'CUS', 'T93', 'CUS', 1, NULL),
(38, 'ecct', 'APP', 'VNS', 'T27', 'VNS AG', 1, NULL),
(39, 'ecct', 'APP', 'VNS', 'T28', 'VNS CG', 1, NULL),
(40, 'ecct', 'osc', 'CUSTOM OSC ECCT', 'T82', 'ECCT-CUS-021', 1, NULL),
(41, 'ecct', 'osc', 'SC2', 'T75', 'SC2', 0, 5),
(42, 'ecct', 'osc', 'SC3', 'T76', 'SC3', 0, 6),
(43, 'ecct', 'APP', 'HNC OE', 'T45', 'Head Neck Cover Open Eye', 0, 21),
(44, 'ecct', 'APP', 'HNC OM', 'T46', 'Head Neck Cover Open Mouth', 0, 24),
(45, 'ecct', 'APP', 'HNC XE', 'T47', 'Head Neck Cover Xtra Open Eyes', 0, 22),
(46, 'ecct', 'APP', 'HNC XM', 'T48', 'Head Neck Cover Xtra Open Mouth', 0, 25),
(47, 'ecct', 'APP', 'HNC XXOE', 'T49', 'Head Neck Cover 2 Xtra Open Eye', 0, 23),
(48, 'ecct', 'APP', 'HNC XXOM', 'T50', 'Head Neck Cover 2 Xtra Open Mouth', 0, 26),
(50, 'ecbs', 'APP', 'MFN', 'S11', 'Mask Full Neck', 0, NULL),
(51, 'ecbs', 'APP', 'MFF', 'S12', 'Mask Full Face', 0, NULL),
(52, 'ecbs', 'APP', 'V M', 'S21', 'Vest Male', 0, NULL),
(53, 'ecbs', 'APP', 'V F', 'S22', 'Vest Female', 0, NULL),
(54, 'ecbs', 'APP', 'V H', 'S23', 'V H', 1, NULL),
(55, 'ecbs', 'APP', 'V H + HE', 'S24', 'V H + HE', 1, NULL),
(56, 'ecbs', 'APP', 'V S', 'S25', 'Vest Suit', 0, NULL),
(57, 'ecbs', 'APP', 'VOH', 'S26', 'Vest Corset Insert ', 0, 1),
(58, 'ecbs', 'APP', 'VOH', 'S27', 'Vest Corset Insert OUT + HE', 0, 1),
(59, 'ecbs', 'APP', 'INS', 'S28', 'Vest Corset Insert IN', 0, 2),
(60, 'ecbs', 'APP', 'RCR', 'S31', 'CST', 1, NULL),
(61, 'ecbs', 'APP', 'B C', 'S32', 'Back Corset', 0, NULL),
(62, 'ecbs', 'APP', 'SHP', 'S33', 'Short Pants', 0, 5),
(63, 'ecbs', 'APP', 'ACP', 'S34', 'Active Pants', 0, NULL),
(64, 'ecbs', 'APP', 'KNK', 'S41', 'Knee Kit', 0, 6),
(65, 'ecbs', 'APP', 'KNK', 'S42', 'Knee Kit', 0, 6),
(66, 'ecbs', 'cus', 'CUS', 'S92', 'CUSTOM APP ECBS', 0, NULL),
(67, 'ecbs', 'osc', 'A1', 'S61', 'MV-VVLF A1', 0, 1),
(68, 'ecbs', 'osc', 'B1', 'S62', 'MV-VVLF B1', 0, 4),
(69, 'ecbs', 'osc', 'AX', 'S63', 'MV-VVLF AX', 1, NULL),
(70, 'ecbs', 'osc', 'AXT', 'S64', 'MV-VVLF AX TWIST', 0, 3),
(71, 'ecbs', 'osc', 'MVR', 'S65', 'MV-R', 0, 7),
(72, 'ecbs', 'osc', 'TYPE R', 'S66', 'TYPE R', 1, NULL),
(74, 'ecbs', 'osc', 'TS', 'S67', 'TYPE S ECBS', 1, NULL),
(75, 'ecbs', 'APP', 'HFM_S', 'S13', 'HELMET ECBS', 0, 7),
(76, 'ecbs', 'osc', 'Type S', 'S68', 'TYPE S TAKACHI', 1, NULL),
(77, 'ecbs', 'osc', 'MVSF', 'S69', 'MVSF', 1, NULL),
(78, 'ecbs', 'osc', 'TYPE (R)ejuvenate', 'S70', 'TYPE (R)ejuvenate', 1, NULL),
(79, 'ecbs', 'osc', 'SC2', 'S75', 'SPLITTER 2 WAYS\r\n', 0, 11),
(80, 'ecbs', 'osc', 'SC3', 'S76', 'SPLITTER 3 WAYS\r\n', 0, 12),
(81, 'ecbs', 'APP', 'BFS_S', 'S35', 'BFS_S\r\n', 0, NULL),
(82, 'ecbs', 'APP', 'VTS_S', 'S36', 'Vest Thorax Standar AG', 0, 8),
(83, 'ecbs', 'APP', 'SB_S', 'S37', 'SB_S\r\n', 0, 9),
(84, 'ecct', 'osc', 'MVSF', 'T56', 'ECCT MVSF', 0, 3),
(85, 'ecct', 'cus', 'CUS', 'T92', 'CUS', 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `inv_needs`
--

CREATE TABLE `inv_needs` (
  `id_needs` int(11) NOT NULL,
  `id_dvc` int(11) DEFAULT NULL,
  `dvc_size` varchar(20) DEFAULT NULL,
  `dvc_col` varchar(50) DEFAULT NULL,
  `dvc_qc` varchar(50) DEFAULT NULL,
  `needs_qty` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `inv_report`
--

CREATE TABLE `inv_report` (
  `id_pms` int(11) NOT NULL,
  `id_week` int(11) DEFAULT NULL,
  `id_dvc` int(11) DEFAULT NULL,
  `dvc_size` varchar(20) DEFAULT NULL,
  `dvc_col` varchar(50) DEFAULT NULL,
  `dvc_sn` varchar(100) DEFAULT NULL,
  `dvc_qc` varchar(50) DEFAULT NULL,
  `stock` int(11) DEFAULT NULL,
  `on_pms` int(11) DEFAULT NULL,
  `needs` int(11) DEFAULT NULL,
  `order` int(11) DEFAULT NULL,
  `over` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `inv_week`
--

CREATE TABLE `inv_week` (
  `id_week` int(11) NOT NULL,
  `date_start` datetime DEFAULT NULL,
  `date_finish` datetime DEFAULT NULL,
  `period_y` int(11) DEFAULT NULL,
  `period_m` int(11) DEFAULT NULL,
  `period_w` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `inv_act`
--
ALTER TABLE `inv_act`
  ADD PRIMARY KEY (`id_act`),
  ADD KEY `id_dvc` (`id_dvc`);

--
-- Indexes for table `inv_dvc`
--
ALTER TABLE `inv_dvc`
  ADD PRIMARY KEY (`id_dvc`);

--
-- Indexes for table `inv_needs`
--
ALTER TABLE `inv_needs`
  ADD PRIMARY KEY (`id_needs`),
  ADD KEY `id_dvc` (`id_dvc`);

--
-- Indexes for table `inv_report`
--
ALTER TABLE `inv_report`
  ADD PRIMARY KEY (`id_pms`),
  ADD KEY `id_week` (`id_week`),
  ADD KEY `id_dvc` (`id_dvc`);

--
-- Indexes for table `inv_week`
--
ALTER TABLE `inv_week`
  ADD PRIMARY KEY (`id_week`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `inv_act`
--
ALTER TABLE `inv_act`
  MODIFY `id_act` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inv_dvc`
--
ALTER TABLE `inv_dvc`
  MODIFY `id_dvc` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT for table `inv_needs`
--
ALTER TABLE `inv_needs`
  MODIFY `id_needs` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inv_report`
--
ALTER TABLE `inv_report`
  MODIFY `id_pms` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inv_week`
--
ALTER TABLE `inv_week`
  MODIFY `id_week` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `inv_act`
--
ALTER TABLE `inv_act`
  ADD CONSTRAINT `inv_act_ibfk_1` FOREIGN KEY (`id_dvc`) REFERENCES `inv_dvc` (`id_dvc`) ON UPDATE CASCADE;

--
-- Constraints for table `inv_needs`
--
ALTER TABLE `inv_needs`
  ADD CONSTRAINT `inv_needs_ibfk_1` FOREIGN KEY (`id_dvc`) REFERENCES `inv_dvc` (`id_dvc`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `inv_report`
--
ALTER TABLE `inv_report`
  ADD CONSTRAINT `inv_report_ibfk_1` FOREIGN KEY (`id_week`) REFERENCES `inv_week` (`id_week`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `inv_report_ibfk_2` FOREIGN KEY (`id_dvc`) REFERENCES `inv_dvc` (`id_dvc`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
