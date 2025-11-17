-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: mysql-ssis-test.alwaysdata.net
-- Generation Time: Nov 17, 2025 at 09:02 AM
-- Server version: 10.11.14-MariaDB
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ssis-test_database`
--

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `Announcement_Id` int(11) NOT NULL,
  `Title` varchar(255) NOT NULL,
  `Text` text NOT NULL,
  `Image_Path` varchar(500) DEFAULT NULL,
  `Date_Publication` date NOT NULL,
  `Created_At` datetime NOT NULL DEFAULT current_timestamp(),
  `Updated_At` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`Announcement_Id`, `Title`, `Text`, `Image_Path`, `Date_Publication`, `Created_At`, `Updated_At`) VALUES
(1, 'Unannounced Earthquake Drill 2025', 'Isinasagawa ang Pagsasanay sa Lindol upang ihanda ang bawat isa sa tamang paraan ng pagharap kapag may lindol. Layunin nito na matutunan ng mga mag-aaral, guro, at magulang ang mga hakbang sa kaligtasan tulad ng “Duck, Cover, and Hold” upang mabawasan ang panganib at mapanatiling ligtas ang lahat sa oras ng sakuna.', 'ImageUploads/announcements/2025/announcement-1762594218-eb0c122afc.jpg', '2025-11-08', '2025-11-06 14:30:11', '2025-11-10 11:44:47'),
(2, 'Buwan ng Wika', 'Isang makulay, masigla, at punung-puno ng galing ang ipinamalas ng mga mag-aaral sa Baitang Kinder, 1, 2, 3, 4, 5, at 6 sa kanilang pagtatanghal sa paaralan ng Lucena South 2 Elementary School.\r\nLubos na naging makabuluhan ang aktibidad na ito dahil sa pagpapakita ng talento ng mga batang South 2 pagdating sa mga kasuotan, tula, awit, at sayaw na nagpapakita ng kultura ng Pilipinas.\r\nIsang taos-pusong pasasalamat sa mga guro na walang sawang nagturo sa mga bata at naging gabay sa matagumpay na pagsasakatuparan ng gawaing ito. Gayundin sa lahat ng mga magulang na nagpakita ng pagmamahal at pagpapahalaga sa kagalingan ng kanilang mga anak.', 'ImageUploads/announcements/2025/announcement-1762592983-f1db0c2d25.jpg', '2025-11-08', '2025-11-06 14:31:58', '2025-11-08 10:09:45'),
(3, 'Science and Innovation Parade & Experiment Contest', 'Igniting Curiosity, Empowering the Future! Last September 18, our school came active with energy, curiosity, and creativity as pupils and teachers gathered for the much-anticipated STEM Career Parade and Science Experiment Contest, proudly held under the theme: “Harnessing the Unknown: Powering the Future through Science and Innovation.”  September 18', 'ImageUploads/announcements/2025/announcement-1762592196-073a2c4e9b.jpg', '2025-11-11', '2025-11-06 14:34:31', '2025-11-10 12:21:12'),
(4, 'Pagdiriwang ng Buwan ng Nutrisyon', 'Tuwing Hulyo, ipinagdiriwang natin ang Buwan ng Nutrisyon upang paalalahanan ang lahat tungkol sa kahalagahan ng wastong pagkain at malusog na pamumuhay. Layunin nitong itaguyod ang tamang nutrisyon para sa mas malusog na katawan, masiglang isipan, at mas produktibong pamumuhay para sa bawat Pilipino.', 'ImageUploads/announcements/2025/announcement-1762593975-b1d35bb737.jpg', '2025-11-08', '2025-11-08 10:26:17', '2025-11-08 10:26:17'),
(5, '2025-2026 First Quarter PTA Meeting and Honors Assembly Awarding', 'ChatGPT said:\r\n\r\nThe 2025–2026 First Quarter PTA Conference and Card Viewing / Honors’ Assembly Awarding provides parents with a direct academic update on their child’s performance for the opening quarter. It aligns teachers and parents on progress, concerns, and upcoming expectations. The program includes distribution of report cards, brief parent–teacher consultations, and formal recognition of learners who achieved academic and conduct-based honors. It reinforces transparency, parent involvement, and consistent monitoring of student development.', 'ImageUploads/announcements/2025/announcement-1762770673-f315cde929.jpg', '2025-11-10', '2025-11-10 11:31:12', '2025-11-10 12:05:44'),
(6, 'Test Announce', 'Hello world', NULL, '2025-11-20', '2025-11-12 11:41:33', '2025-11-13 17:36:07'),
(7, 'Walang pasok', 'Dahil sa bagyong Uwan, kinansela ng DepEd ang pasok mula sa lahat ng baitang', NULL, '2025-11-13', '2025-11-13 07:08:16', '2025-11-13 07:09:39'),
(9, 'Announcement Test', 'This is only a Test to see if the Announcement works. :)', 'ImageUploads/announcements/2025/announcement-1763053750-62ba65fbb7.jpg', '2025-11-14', '2025-11-13 18:09:10', '2025-11-13 18:09:10'),
(10, 'addsdbsbs', 'sfbsbs', 'ImageUploads/announcements/2025/announcement-1763063207-0706fa1810.jpg', '2025-11-13', '2025-11-13 20:46:19', '2025-11-13 20:47:58');

-- --------------------------------------------------------

--
-- Table structure for table `archive_enrollees`
--

CREATE TABLE `archive_enrollees` (
  `Enrollee_Id` bigint(20) NOT NULL,
  `User_Id` bigint(20) DEFAULT NULL,
  `Student_First_Name` varchar(50) NOT NULL,
  `Student_Middle_Name` varchar(50) DEFAULT NULL,
  `Student_Last_Name` varchar(50) NOT NULL,
  `Student_Extension` varchar(50) DEFAULT NULL,
  `Learner_Reference_Number` bigint(12) DEFAULT NULL,
  `Psa_Number` bigint(13) DEFAULT NULL,
  `Birth_Date` date DEFAULT NULL,
  `Age` int(20) NOT NULL,
  `Sex` varchar(6) DEFAULT NULL,
  `Religion` varchar(50) DEFAULT NULL,
  `Native_Language` varchar(50) DEFAULT NULL,
  `If_Cultural` tinyint(4) DEFAULT NULL,
  `Cultural_Group` varchar(50) DEFAULT NULL,
  `Student_Email` varchar(100) DEFAULT 'N/A',
  `Enrollment_Status` int(5) NOT NULL DEFAULT 3,
  `Enrollee_Address_Id` bigint(20) DEFAULT NULL,
  `Educational_Information_Id` bigint(20) DEFAULT NULL,
  `Educational_Background_Id` bigint(20) DEFAULT NULL,
  `Disabled_Student_Id` bigint(20) DEFAULT NULL,
  `Psa_Image_Id` int(11) DEFAULT NULL,
  `Is_Handled` tinyint(4) NOT NULL,
  `Enrolled_At` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `archive_enrollment_transactions`
--

CREATE TABLE `archive_enrollment_transactions` (
  `Enrollment_Transaction_Id` bigint(20) NOT NULL,
  `Enrollee_Id` bigint(20) DEFAULT NULL,
  `Transaction_Code` varchar(50) DEFAULT NULL,
  `Enrollment_Status` int(11) NOT NULL,
  `Staff_Id` int(11) NOT NULL,
  `Remarks` varchar(255) NOT NULL,
  `Transaction_Status` tinyint(4) NOT NULL,
  `Is_Approved` tinyint(11) NOT NULL,
  `School_Year_Details_Id` int(11) NOT NULL,
  `Created_At` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `archive_section_advisers`
--

CREATE TABLE `archive_section_advisers` (
  `Section_Advisers_Id` int(11) NOT NULL,
  `Section_Id` bigint(20) NOT NULL,
  `Staff_Id` int(11) NOT NULL,
  `School_Year_Details_Id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `archive_section_schedules`
--

CREATE TABLE `archive_section_schedules` (
  `Section_Schedules_Id` int(11) NOT NULL,
  `Section_Subjects_Id` int(11) NOT NULL,
  `Schedule_Day` tinyint(4) NOT NULL,
  `Time_Start` time NOT NULL,
  `Time_End` time NOT NULL,
  `Created_At` datetime NOT NULL DEFAULT current_timestamp(),
  `School_Year_Details_Id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `archive_section_subject_teachers`
--

CREATE TABLE `archive_section_subject_teachers` (
  `Section_Subject_Teacher` int(11) NOT NULL,
  `Section_Subjects_Id` int(11) NOT NULL,
  `Staff_Id` int(11) NOT NULL,
  `School_Year_Details_Id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `archive_teachers`
--

CREATE TABLE `archive_teachers` (
  `Staff_Id` int(11) NOT NULL,
  `Staff_First_Name` varchar(255) NOT NULL,
  `Staff_Middle_Name` varchar(255) NOT NULL,
  `Staff_Last_Name` varchar(255) NOT NULL,
  `Staff_Address_Id` int(11) DEFAULT NULL,
  `Staff_Identifier_Id` int(11) DEFAULT NULL,
  `Birth_Date` date DEFAULT NULL,
  `Staff_Email` varchar(255) NOT NULL,
  `Staff_Contact_Number` varchar(255) NOT NULL,
  `Staff_Status` int(11) NOT NULL,
  `Staff_Type` int(11) NOT NULL,
  `Position` varchar(255) DEFAULT NULL,
  `Timestamp` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `disabled_student`
--

CREATE TABLE `disabled_student` (
  `Disabled_Student_Id` bigint(20) NOT NULL,
  `Have_Special_Condition` int(2) DEFAULT NULL,
  `Have_Assistive_Tech` int(2) DEFAULT NULL,
  `Special_Condition` varchar(50) DEFAULT NULL,
  `Assistive_Tech` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `disabled_student`
--

INSERT INTO `disabled_student` (`Disabled_Student_Id`, `Have_Special_Condition`, `Have_Assistive_Tech`, `Special_Condition`, `Assistive_Tech`) VALUES
(12, 0, 0, 'qwqwqwq', 'qwqqwqwqe'),
(16, 0, 0, 'Blind', 'Glasses'),
(59, 1, 1, 'Deaf', 'Hearing aid'),
(60, 0, 0, '', ''),
(63, 0, 0, '', ''),
(64, 1, 1, 'Blind', 'Canes'),
(65, 1, 1, 'Blind', 'Canes'),
(66, 0, 1, '', 'hearing aid '),
(67, 0, 0, '', ''),
(68, 0, 0, '', ''),
(69, 0, 0, '', ''),
(70, 0, 0, '', ''),
(71, 0, 0, '', ''),
(72, 0, 0, '', ''),
(73, 0, 0, '', ''),
(75, 0, 0, '', ''),
(76, 0, 0, '', ''),
(81, 0, 0, '', ''),
(82, 0, 0, '', ''),
(83, 0, 0, '', ''),
(84, 0, 0, '', ''),
(85, 0, 0, '', ''),
(86, 0, 0, '', ''),
(87, 0, 0, '', ''),
(88, 0, 0, '', ''),
(94, 0, 1, NULL, 'Crutches'),
(98, 0, 0, '', ''),
(100, 1, 0, 'Visually Impaired', NULL),
(101, 0, 0, NULL, NULL),
(105, 1, 1, 'Hearing Impairment', 'Hearing Aids'),
(106, 0, 0, NULL, NULL),
(109, 0, 0, '', ''),
(110, 0, 0, NULL, NULL),
(111, 0, 0, NULL, NULL),
(112, 0, 0, NULL, NULL),
(113, 0, 0, NULL, NULL),
(114, 0, 0, NULL, NULL),
(115, 0, 0, NULL, NULL),
(116, 0, 0, NULL, NULL),
(117, 0, 0, NULL, NULL),
(118, 0, 0, NULL, NULL),
(119, 0, 0, '', ''),
(120, 0, 0, NULL, NULL),
(121, 0, 0, NULL, NULL),
(122, 0, 0, NULL, NULL),
(123, 0, 0, NULL, NULL),
(124, 0, 0, NULL, NULL),
(125, 0, 0, NULL, NULL),
(126, 0, 0, NULL, NULL),
(127, 0, 0, NULL, NULL),
(128, 0, 0, NULL, NULL),
(129, 0, 0, NULL, NULL),
(130, 0, 0, NULL, NULL),
(131, 0, 0, NULL, NULL),
(132, 0, 0, NULL, NULL),
(133, 0, 0, NULL, NULL),
(134, 0, 0, NULL, NULL),
(135, 0, 0, NULL, NULL),
(136, 0, 0, NULL, NULL),
(137, 0, 0, NULL, NULL),
(138, 0, 0, NULL, NULL),
(139, 0, 0, NULL, NULL),
(140, 0, 0, NULL, NULL),
(141, 0, 0, NULL, NULL),
(142, 0, 0, NULL, NULL),
(143, 0, 0, NULL, NULL),
(144, 1, 1, 'Visually Impaired', 'Glasses'),
(145, 1, 1, 'Visually Impaired', 'Glasses'),
(146, 0, 0, NULL, NULL),
(147, 0, 0, NULL, NULL),
(150, 0, 0, '', ''),
(151, 0, 0, '', ''),
(152, 0, 0, '', ''),
(153, 0, 0, '', ''),
(154, 0, 0, '', ''),
(155, 0, 0, '', '');

-- --------------------------------------------------------

--
-- Table structure for table `educational_background`
--

CREATE TABLE `educational_background` (
  `Educational_Background_Id` bigint(20) NOT NULL,
  `Last_School_Attended` varchar(50) DEFAULT NULL,
  `School_Id` int(11) DEFAULT NULL,
  `School_Address` varchar(100) DEFAULT NULL,
  `School_Type` varchar(50) DEFAULT NULL,
  `Initial_School_Choice` varchar(50) DEFAULT NULL,
  `Initial_School_Id` int(11) DEFAULT NULL,
  `Initial_School_Address` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `educational_background`
--

INSERT INTO `educational_background` (`Educational_Background_Id`, `Last_School_Attended`, `School_Id`, `School_Address`, `School_Type`, `Initial_School_Choice`, `Initial_School_Id`, `Initial_School_Address`) VALUES
(12, 'Holy Rosary Catholic School', 10101003, 'Cotta, Lucena City', 'private', 'Holy Rosary Catholic School', 10101010, 'Cotta, Lucena City'),
(16, 'South II Elementary School', 134123, '32 Edano Street Lucena City', 'Private', 'South II Elementary School', 134123, '32 Edano Street Lucena City'),
(59, 'South II elementary school', 123456, 'Cotta', 'public', 'South II elementary school', 123456, 'Cotta'),
(60, 'South II elementary school', 123456, 'Cotta', 'public', 'South II elementary school', 123456, 'Cotta'),
(64, 'South II elementary school', 126735, 'Cotta', '', 'South II elementary school', 156431, 'Cotta'),
(65, 'South II elementary school', 126735, 'Cotta', 'public', 'South II elementary school', 156431, 'Cotta'),
(66, 'dalahican elemenmtary school', 321244, 'hsethrthrthrhr', 'Private', '', 456344, 'gdfhdhghrg'),
(67, 'Holy  Rosary Catholic School', 109715, 'WJQG+V6F, Lucena City, 4301 Quezon', 'Public', 'jfhgjgyttjf', 564576, 'ngfhgnffhfghf'),
(68, 'South II Elementary School', 624135, 'South II Elementary School', 'Public', 'South II Elementary School', 624135, 'Cotta, Lucena City'),
(69, 'South II Elementary School', 624135, 'South II Elementary School', 'Public', 'South II Elementary School', 624135, 'Cotta, Lucena City'),
(70, 'South II elementary school', 121212, 'Cotta', 'Public', 'South II elementary school', 121212, 'Cotta'),
(71, 'South II elementary school', 121212, 'Cotta', 'Public', 'South II elementary school', 121212, 'Cotta'),
(72, 'South II elementary school', 123456, 'Cotta', 'Public', 'South II elementary school', 90900, 'Cotta'),
(73, 'South II elementary school', 123456, 'Cotta', 'Public', 'South II elementary school', 90900, 'Cotta'),
(75, 'South II elementary school', 123456, 'Cotta', '', 'South II elementary school', 123452, 'Cotta'),
(76, 'South II elementary school', 123456, 'Cotta', 'public', 'South II elementary school', 123452, 'Cotta'),
(81, 'South II elementary school', 471832, 'Cotta', 'Public', 'South II elementary school', 471832, 'Cotta'),
(82, 'South II elementary school', 611211, 'Cotta', 'public', 'South II elementary school', 611211, 'Cotta'),
(83, 'South II elementary school', 611211, 'Cotta', 'Public', 'South II elementary school', 611211, 'Cotta'),
(84, 'South II elementary school', 616623, 'Cotta', 'Public', 'South II elementary school', 616623, 'Cotta'),
(85, 'Lucena East VIII Elementary School', 109715, 'WJQG+V6F, Lucena City, 4301 Quezon', 'Public', 'South II elementary school', 612612, 'Cotta'),
(86, 'South II Elementary School', 134123, 'Lagos street Lucena City', 'Public', 'South II Elementary School', 134123, 'Lagos street Lucena City'),
(87, 'Ibabang Iyam Lucena City', 562324, 'Ibabang Iyam Lucena City', 'Public', 'Ibabang Iyam Lucena City', 562324, 'Ibabang Iyam Lucena City'),
(88, 'Ibabang Iyam Lucena City', 134123, 'Ibabang Iyam Lucena City', 'Public', 'South II Elementary School', 109732, '32 Edano Street Lucena City'),
(94, 'South II Elementary School', 0, 'Cotta, Lucena City', 'Public', 'South II Elementary School', 109732, 'Teody Street, Capitol Homesite, Brgy. Cotta, Lucena, Philippines'),
(98, 'Maryhill ', 12, 'Cotta, Lucena City', 'Private', 'South II Elementary School', 109732, 'Teody Street, Capitol Homesite, Brgy. Cotta, Lucena, Philippines'),
(100, 'Maryhill ', 20508, 'Cotta, Lucena City', 'Private', 'South II Elementary School', 109732, 'Teody Street, Capitol Homesite, Brgy. Cotta, Lucena, Philippines'),
(101, 'South I Elementary School', 121212, 'Cotta, Lucena City', 'Public', 'South II Elementary School', 109732, 'Teody Street, Capitol Homesite, Brgy. Cotta, Lucena, Philippines'),
(105, 'None', 0, 'none', 'Public', 'South II Elementary School', 109732, 'Teody Street, Capitol Homesite, Brgy. Cotta, Lucena, Philippines'),
(106, 'South I Elementary School', 20202, 'Cotta, Lucena City', 'Public', 'South II Elementary School', 109732, 'Teody Street, Capitol Homesite, Brgy. Cotta, Lucena, Philippines'),
(109, 'South II elementary school', 102371, 'Cotta', 'Public', 'South II elementary school', 102371, 'Cotta'),
(110, 'South II elementary school', 102371, 'Cotta', 'Public', 'South II elementary school', 102371, 'Cotta'),
(111, 'SES', 121212, 'Asdasd', 'Public', 'SEA', 121212, 'Asdasd'),
(112, 'SES', 123121, 'Wqee', 'Public', 'Wqee', 121212, 'Wqee'),
(113, 'Philippine Tong Ho Institute', 134123, 'Muntinlupa st, ', 'Public', 'South II Elementary School', 542341, 'Lagos street Lucena City'),
(114, 'Ibabang Iyam Lucena City', 342523, '32 Edano Street Lucena City', 'Private', 'Southern Luzon Elementary School', 435234, 'Ibabang Iyam Lucena City'),
(115, 'Ibabang Iyam Lucena City', 134123, 'Muntinlupa st, ', 'Private', 'Philippine Tong Ho Institute', 134123, 'Pulong Sta. Cruz, Santa Rosa, Laguna '),
(116, 'South II Elementary School ', 102371, 'Cotta', 'Public', 'South II Elementary School ', 102371, 'Cotta'),
(117, 'South II Elementary School', 109732, 'Cotta, Lucena City ', 'Public', 'South II Elementary School', 109732, 'Cotta, Lucena City '),
(118, 'South II Elementary School', 91287, 'Cotta, Lucena City ', 'Public', 'South II Elementary School', 91287, 'Cotta, Lucena City '),
(119, 'South II Elementary School', 99097, 'Cotta, Lucena City ', 'public', 'South II Elementary School', 92345, 'Cotta, Lucena City '),
(120, 'Southern Luzon Elementary School', 643563, 'Pulong Sta. Cruz, Santa Rosa, Laguna ', 'Public', 'Southern Luzon Elementary School', 643563, 'Pulong Sta. Cruz, Santa Rosa, Laguna '),
(121, 'South II Elementary School', 101868, 'Cotta, Lucena City ', 'Public', 'South II Elementary School', 101868, 'Cotta, Lucena City '),
(122, 'South II Elementary School', 101031, 'Cotta, Lucena City', 'Public', 'South II Elementary School', 101031, 'Cotta, Lucena City'),
(123, 'South II Elementary School', 101031, 'Cotta, Lucena City', 'Public', 'South II Elementary School', 101031, 'Cotta, Lucena City'),
(124, 'South II Elementary School', 101031, 'Cotta, Lucena City', 'Public', 'South II Elementary School', 101031, 'Cotta, Lucena City'),
(125, 'South II Elementary School', 101031, 'Cotta, Lucena City', 'Public', 'South II Elementary School', 101031, 'Cotta, Lucena City'),
(126, 'South II Elementary School', 101031, 'Cotta, Lucena City', 'Public', 'South II Elementary School', 101031, 'Cotta, Lucena City'),
(127, 'South II Elementary School', 101031, 'Cotta, Lucena City', 'Public', 'South II Elementary School', 101031, 'Cotta, Lucena City'),
(128, 'South II Elementary School', 101031, 'Cotta, Lucena City', 'Public', 'South II Elementary School', 101031, 'Cotta, Lucena City'),
(129, 'South II Elementary School', 101031, 'Cotta, Lucena City', 'Public', 'South II Elementary School', 101031, 'Cotta, Lucena City'),
(130, 'South II Elementary School', 101031, 'Cotta, Lucena City', 'Public', 'South II Elementary School', 101031, 'Cotta, Lucena City'),
(131, 'South II Elementary School', 101031, 'Cotta, Lucena City', 'Public', 'South II Elementary School', 101031, 'Cotta, Lucena City'),
(132, 'South II Elementary School', 101031, 'Cotta, Lucena City', 'Public', 'South II Elementary School', 101031, 'Cotta, Lucena City'),
(133, 'South II Elementary School', 101031, 'Cotta, Lucena City', 'Public', 'South II Elementary School', 101031, 'Cotta, Lucena City'),
(134, 'South II Elementary School', 101031, 'Cotta, Lucena City', 'Public', 'South II Elementary School', 101031, 'Cotta, Lucena City'),
(135, 'South II Elementary School', 101031, 'Cotta, Lucena City', 'Public', 'South II Elementary School', 101031, 'Cotta, Lucena City'),
(136, 'South II Elementary School', 101031, 'Cotta, Lucena City', 'Public', 'South II Elementary School', 101031, 'Cotta, Lucena City'),
(137, 'South II Elementary School', 101031, 'Cotta, Lucena City', 'Public', 'South II Elementary School', 101031, 'Cotta, Lucena City'),
(138, 'South II elementary school', 109372, 'Cotta', 'Public', 'South II elementary school', 109372, 'Cotta'),
(139, 'South II Elementary School', 109732, 'Cotta', 'Public', 'South II Elementary School', 109732, 'Cotta'),
(140, 'South II Elementary School ', 109372, 'Cotta', 'Public', 'South II Elementary School ', 109372, 'Cotta'),
(141, 'West I Elementary School', 101031, 'Cotta', 'Public', 'South II elementary school', 101031, 'Cotta'),
(142, 'West I Elementary School', 101031, 'Cotta', 'Public', 'South II elementary school', 101031, 'Cotta'),
(143, 'Ibabang Iyam Lucena City', 134123, 'Pulong Sta. Cruz, Santa Rosa, Laguna ', 'Public', 'Southern Luzon Elementary School', 134123, '32 Edano Street Lucena City'),
(144, 'South II Elementary School', 134123, 'Lagos street Lucena City', 'Public', 'South II Elementary School', 134123, '32 Edano Street Lucena City'),
(145, 'South II Elementary School', 134123, 'Lagos street Lucena City', 'Public', 'South II Elementary School', 134123, '32 Edano Street Lucena City'),
(146, 'Ibabang Iyam Lucena City', 134123, 'Lagos street Lucena City', 'Public', 'Southern Luzon Elementary School', 134123, 'Lagos street Lucena City'),
(147, 'Philippine Tong Ho Institute', 134123, '32 Edano Street Lucena City', 'Public', 'Philippine Tong Ho Institute', 134123, 'Merchant st. Lucena City'),
(150, 'Ibabang Iyam Lucena City', 134123, 'Muntinlupa st, ', 'Public', 'South II Elementary School', 134123, 'Pulong Sta. Cruz, Santa Rosa, Laguna '),
(151, 'South II Elementary School', 134123, 'Ibabang Iyam Lucena City', 'Public', 'Ibabang Iyam Lucena City', 134123, 'Pulong Sta. Cruz, Santa Rosa, Laguna '),
(152, 'Ibabang Iyam Lucena City', 134123, '32 Edano Street Lucena City', 'Public', 'Southern Luzon Elementary School', 134123, 'Delubhasa St.'),
(153, 'South II Elementary School', 134123, 'Muntinlupa st, ', 'Public', 'Philippine Tong Ho Institute', 134123, 'Pulong Sta. Cruz, Santa Rosa, Laguna '),
(154, 'South II Elementary School', 134123, 'Muntinlupa st, ', 'Public', 'Southern Luzon Elementary School', 243523, 'Merchant st. Lucena City'),
(155, 'South II Elementary School', 134123, 'Pulong Sta. Cruz, Santa Rosa, Laguna ', 'Public', 'Ibabang Iyam Lucena City', 134123, 'Ibabang Iyam Lucena City');

-- --------------------------------------------------------

--
-- Table structure for table `educational_information`
--

CREATE TABLE `educational_information` (
  `Educational_Information_Id` bigint(20) NOT NULL,
  `School_Year_Start` year(4) DEFAULT NULL,
  `School_Year_End` year(4) DEFAULT NULL,
  `If_LRN_Returning` varchar(50) DEFAULT NULL,
  `Enrolling_Grade_Level` int(20) DEFAULT NULL,
  `Last_Grade_Level` int(20) DEFAULT NULL,
  `Last_Year_Attended` smallint(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `educational_information`
--

INSERT INTO `educational_information` (`Educational_Information_Id`, `School_Year_Start`, `School_Year_End`, `If_LRN_Returning`, `Enrolling_Grade_Level`, `Last_Grade_Level`, `Last_Year_Attended`) VALUES
(13, '2025', '2026', 'on', 3, 2, 2025),
(17, '2025', '2026', 'no', 6, 5, 2025),
(60, '2025', '2026', '', 2, 1, 2025),
(61, '2025', '2026', '', 2, 1, 2025),
(64, '2025', '2026', '1', 2, 1, 2025),
(65, '2025', '2026', '', 2, 1, 2025),
(66, '2025', '2026', '', 2, 1, 2025),
(67, '2025', '2026', '0', 3, 2, 2025),
(68, '2025', '2026', '', 2, 1, 2024),
(69, '2025', '2026', '', 1, 1, 2024),
(70, '2025', '2026', '', 1, 1, 2024),
(71, '2025', '2026', '', 8, 7, 2025),
(72, '2025', '2026', '', 8, 7, 2025),
(73, '2025', '2026', '0', 5, 4, 2024),
(75, '2025', '2026', '1', 4, 3, 2025),
(76, '2025', '2026', '1', 2, 1, 2025),
(77, '2025', '2026', '1', 2, 1, 2025),
(79, '2025', '2026', '1', 2, 1, 2025),
(80, '2025', '2026', NULL, 2, 1, 2025),
(81, '2025', '2026', '1', 3, 2, 2025),
(82, '2025', '2026', NULL, 4, 3, 2025),
(83, '2025', '2026', NULL, 5, 4, 2025),
(84, '2025', '2026', '1', 7, 6, 2025),
(85, '2025', '2026', NULL, 2, 1, 2025),
(86, '2025', '2026', '1', 2, 1, 2025),
(92, '2025', '2026', '0', 1, NULL, 2025),
(96, '2025', '2026', '0', 3, 2, 2025),
(98, '2025', '2026', '0', 8, 7, 2025),
(99, '2025', '2026', '0', 8, 7, 2025),
(103, '2025', '2026', '0', 1, NULL, 2025),
(104, '2025', '2026', '0', 2, 1, 2025),
(107, '2025', '2026', '1', 2, 1, 2025),
(108, '2025', '2026', '1', 2, 1, 2025),
(109, '2025', '2026', '0', 5, 4, 2024),
(110, '2025', '2026', '1', 5, 4, 2023),
(111, '2025', '2026', '1', 7, 6, 2025),
(112, '2025', '2026', '1', 5, 4, 2025),
(113, '2025', '2026', '1', 7, 6, 2025),
(114, '2025', '2026', '1', 4, 3, 2025),
(115, '2025', '2026', '1', 4, 3, 2025),
(116, '2025', '2026', '1', 4, 3, 2025),
(117, '2025', '2026', '1', 5, 4, 2025),
(118, '2025', '2026', '1', 5, 4, 2025),
(119, '2025', '2026', '1', 5, 4, 2025),
(120, '2025', '2026', '1', 3, 2, 2025),
(121, '2025', '2026', '1', 3, 2, 2025),
(122, '2025', '2026', '1', 3, 2, 2025),
(123, '2025', '2026', '1', 3, 2, 2025),
(124, '2025', '2026', '1', 3, 2, 2025),
(125, '2025', '2026', '1', 3, 2, 2025),
(126, '2025', '2026', '1', 3, 2, 2025),
(127, '2025', '2026', '1', 3, 2, 2025),
(128, '2025', '2026', '1', 3, 2, 2025),
(129, '2025', '2026', '1', 3, 2, 2025),
(130, '2025', '2026', '1', 4, 3, 2025),
(131, '2025', '2026', '1', 4, 3, 2025),
(132, '2025', '2026', '1', 4, 3, 2025),
(133, '2025', '2026', '1', 4, 3, 2025),
(134, '2025', '2026', '1', 4, 3, 2025),
(135, '2025', '2026', '1', 4, 3, 2025),
(136, '2025', '2026', '1', 3, 2, 2025),
(137, '2025', '2026', '1', 5, 4, 2025),
(138, '2025', '2026', '1', 4, 3, 2025),
(139, '2025', '2026', '1', 7, 6, 2025),
(140, '2025', '2026', '1', 7, 6, 2025),
(141, '2025', '2026', '1', 5, 4, 2025),
(142, '2025', '2026', '1', 2, 1, 2024),
(143, '2025', '2026', '0', 2, 1, 2024),
(144, '2025', '2026', '1', 5, 4, 2025),
(145, '2025', '2026', '1', 8, 7, 2025),
(148, '2025', '2026', '1', 5, 4, 2025),
(149, '2025', '2026', '1', 6, 5, 2025),
(150, '2025', '2026', '1', 7, 6, 2025),
(151, '2025', '2026', '1', 5, 4, 2025),
(152, '2025', '2026', '1', 6, 5, 2025),
(153, '2025', '2026', '1', 5, 4, 2025);

-- --------------------------------------------------------

--
-- Table structure for table `enrollee`
--

CREATE TABLE `enrollee` (
  `Enrollee_Id` bigint(20) NOT NULL,
  `User_Id` bigint(20) DEFAULT NULL,
  `Student_First_Name` varchar(50) NOT NULL,
  `Student_Middle_Name` varchar(50) DEFAULT NULL,
  `Student_Last_Name` varchar(50) NOT NULL,
  `Student_Extension` varchar(50) DEFAULT NULL,
  `Learner_Reference_Number` bigint(12) DEFAULT NULL,
  `Birth_Date` date DEFAULT NULL,
  `Age` int(20) NOT NULL,
  `Sex` varchar(6) DEFAULT NULL,
  `Religion` varchar(50) DEFAULT NULL,
  `Native_Language` varchar(50) DEFAULT NULL,
  `If_Cultural` tinyint(4) DEFAULT NULL,
  `Cultural_Group` varchar(50) DEFAULT NULL,
  `Student_Email` varchar(100) DEFAULT 'N/A',
  `Enrollment_Status` int(5) NOT NULL DEFAULT 3,
  `Enrollee_Address_Id` bigint(20) DEFAULT NULL,
  `Educational_Information_Id` bigint(20) DEFAULT NULL,
  `Educational_Background_Id` bigint(20) DEFAULT NULL,
  `Disabled_Student_Id` bigint(20) DEFAULT NULL,
  `Psa_Image_Id` int(11) DEFAULT NULL,
  `Report_Card_Id` int(11) NOT NULL,
  `Is_Handled` tinyint(4) NOT NULL,
  `School_Year_Details_Id` int(11) NOT NULL,
  `Enrolled_At` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `enrollee`
--

INSERT INTO `enrollee` (`Enrollee_Id`, `User_Id`, `Student_First_Name`, `Student_Middle_Name`, `Student_Last_Name`, `Student_Extension`, `Learner_Reference_Number`, `Birth_Date`, `Age`, `Sex`, `Religion`, `Native_Language`, `If_Cultural`, `Cultural_Group`, `Student_Email`, `Enrollment_Status`, `Enrollee_Address_Id`, `Educational_Information_Id`, `Educational_Background_Id`, `Disabled_Student_Id`, `Psa_Image_Id`, `Report_Card_Id`, `Is_Handled`, `School_Year_Details_Id`, `Enrolled_At`) VALUES
(14, 17, 'Lovely Jane', 'Jimenez', 'Dela Cruz', 'N/A', 214748364113, '0000-00-00', 23465, 'Male', 'Catholic', 'Tagalog', 0, 'N/A', 'alojadokeneth@gmail.com', 1, 15, 17, 16, 16, NULL, 0, 1, 1, '2025-05-06 12:54:52'),
(48, 22, 'Jearard', 'Paderes', 'David', 'n/a', 214748364114, '2004-01-01', 21, 'Male', 'Roman Catholic', 'Tagalog', 0, '', 'davidjearard1@gmail.com', 1, 58, 60, 59, 59, 11, 0, 1, 1, '2025-05-19 04:33:59'),
(53, 22, 'Jeremiah', 'Paderes', 'David', '', 214748364119, '2014-01-31', 11, '', 'Roman Catholic', 'Tagalog', 0, '', 'dageh@gmail.com', 1, 63, 65, 64, 64, 16, 0, 1, 1, '2025-05-19 05:49:25'),
(54, 22, 'Jeremiah', 'Paderes', 'David', '', 214748364120, '2014-01-31', 11, 'Female', 'Roman Catholic', 'Tagalog', 0, '', 'dageh@gmail.com', 1, 64, 66, 65, 65, 17, 0, 1, 1, '2025-05-19 05:49:31'),
(55, 69, 'gdfgdgrt', 'dhttttttfgd', 'rewfd', 'gsgdsg', NULL, '2013-02-01', 12, 'Female', 'catholic', 'tagalog', 0, '', 'lovelycruz.1317@gmail.com', 2, 65, 67, 66, 66, 18, 0, 1, 1, '2025-05-22 15:08:52'),
(56, 69, 'Koki', 'Hulio', 'Dela Cruz', '', 214748364121, '2020-02-06', 5, 'Male', 'Buddhist', 'Chinese', 0, '', 'benedict@gmail.com', 4, 66, 68, 67, 67, 19, 0, 1, 1, '2025-05-22 15:52:20'),
(57, 69, 'John Mark', 'Sabile', 'Llorin', 'IV', 214748364122, '2021-05-01', 4, 'Male', 'Catholic', 'Tagalog', 0, '', 'bllorin21@gmail.com', 1, 67, 69, 68, 68, 20, 0, 1, 1, '2025-05-23 02:11:36'),
(58, 69, 'John Mark', 'Sabile', 'Llorin', 'IV', 214748364123, '2021-05-01', 4, 'Male', 'Catholic', 'Tagalog', 0, '', 'bllorin21@gmail.com', 1, 68, 70, 69, 69, 21, 0, 1, 1, '2025-05-23 02:11:43'),
(59, 69, 'Nemesio', 'Solina', 'Llorin', 'Jr.', 214748364124, '2013-02-02', 12, 'Male', 'Roman Catholic', 'Tagalog', 0, '', 'ben@gmail.com', 1, 69, 71, 70, 70, 22, 0, 1, 1, '2025-05-23 05:28:35'),
(60, 69, 'Nemesio', 'Solina', 'Llorin', 'Jr.', 214748364125, '2013-02-02', 12, 'Male', 'Roman Catholic', 'Tagalog', 0, '', 'ben@gmail.com', 2, 70, 72, 71, 71, 23, 0, 1, 1, '2025-05-23 05:28:42'),
(61, 93, 'Jearard', 'Paderes', 'David', 'Jr.', NULL, '2003-02-20', 22, 'Male', 'Roman Catholic', 'Tagalog', 0, '', 'lovelycruz.1317@gmail.com', 1, 71, 73, 72, 72, 24, 0, 1, 1, '2025-05-23 07:41:01'),
(64, 17, 'Anthony', 'Solina', 'David', '', 214748364127, '2019-07-12', 6, 'Male', 'Roman Catholic', 'Tagalog', 1, 'Badjau', 'ben@user', 1, 74, 76, 75, 75, 27, 0, 1, 1, '2025-05-26 16:05:49'),
(65, 17, 'Anthony', 'Solina', 'David', '', 214748364128, '2020-03-19', 5, 'male', 'Roman Catholic', 'Tagalog', 1, 'Badjau', 'ben@user', 1, 75, 77, 76, 76, 28, 0, 1, 1, '2025-05-26 16:09:12'),
(70, 17, 'Reyn Alduz', 'Salico', 'Garcia', '', 214748364130, '2005-01-01', 20, 'Male', 'Roman Catholic', 'Tagalog', 0, '', 'reynalduzg@gmail.com', 1, 80, 79, 81, 81, 33, 0, 1, 1, '2025-06-09 07:38:02'),
(71, 17, 'Ben', '', 'Llorin', 'Jr.', 90000000017, '2018-02-09', 7, 'male', 'Roman Catholic', 'Tagalog', 1, 'Aeta', 'ben@userss', 1, 81, 80, 82, 82, 34, 0, 1, 1, '2025-06-26 17:29:54'),
(72, 22, 'Arjay', 'Jimenez', 'Iglesia', '', 900000000018, '2020-03-19', 0, 'Male', 'Iglesia', 'Tagalog', 0, '', 'arjay123@gmail.com', 1, 82, 81, 83, 83, 35, 0, 1, 1, '2025-06-28 14:46:58'),
(73, 22, 'Jearard', 'Paderes', 'David', '', 900000000019, '2018-01-01', 7, 'Male', 'Roman Catholic', 'Tagalog', 0, '', 'davidjearard1@gmail.com', 1, 83, 82, 84, 84, 36, 0, 1, 1, '2025-07-31 02:22:08'),
(74, 22, 'Ben', 'Salico', 'Garcia', '', 900000000020, '2020-06-19', 5, 'Male', 'Roman Catholic', 'Tagalog', 0, '', 'ben123@gmail.com', 1, 84, 83, 85, 85, 37, 0, 1, 1, '2025-08-01 16:25:09'),
(75, 17, 'Allison', '', 'Villa Berde', '', 23932452355, '2012-11-12', 12, 'Male', 'Catholic', 'Tagalog', 0, '', 'ABerde@gmail.com', 1, 85, 84, 86, 86, 38, 0, 1, 1, '2025-09-11 18:06:33'),
(76, 17, 'Pedro', 'Cainta', 'San Juan', 'Jr.', 759265926404, '2010-10-22', 14, 'Male', 'Catholic', 'Tagalog', 0, '', 'PedroSJ@gmail.com', 1, 86, 85, 87, 87, 39, 0, 1, 1, '2025-09-19 09:10:40'),
(77, 17, 'Kriztian', 'Rodriguez', 'Villa Berde', '', 967452438788, '2013-02-20', 12, 'Male', 'Catholic', 'Tagalog', 0, '', 'Kriztian@gmail.com', 1, 87, 86, 88, 88, 40, 0, 1, 1, '2025-09-19 09:35:54'),
(79, 22, 'Ben', 'S', 'Llorin', 'III', NULL, '2022-10-26', 3, 'Male', 'Roman Catholic', 'Tagalog', 0, NULL, 'bllorin12@gmail.com', 4, 93, 92, 94, 94, 45, 0, 1, 1, '2025-10-27 13:15:35'),
(83, 22, 'Rawkus', 'Sabile', 'Alojarado', '', 901927497320, '2020-02-10', 5, 'Male', 'Roman Catholic', 'Tagalog', 0, NULL, 'emailsample3@gmail.com', 1, 97, 96, 98, 98, 49, 0, 0, 1, '2025-10-29 13:38:48'),
(85, 22, 'Bing', 'Ching', 'Wei', 'Jr.', NULL, '2012-12-12', 12, 'Male', 'Roman Catholic', 'Tagalog', 0, NULL, 'bingchingweiemail@gmail.com', 1, 99, 98, 100, 100, 51, 0, 1, 1, '2025-10-30 17:22:11'),
(86, 22, 'May', 'Habibi', 'Royst', NULL, NULL, '2013-09-10', 12, 'Female', 'Catholic', 'Tagalog', 0, NULL, 'roystmay@gmail.com', 1, 100, 99, 101, 101, 52, 0, 1, 1, '2025-10-31 19:29:38'),
(90, 22, 'Apple', 'Carezo', 'Tan', NULL, NULL, '2022-11-06', 3, 'Female', 'Catholic', 'Tagalog', 0, NULL, 'applect@gmail.com', 1, 104, 103, 105, 105, 56, 0, 1, 1, '2025-11-09 15:48:08'),
(91, 22, 'Ali', 'Gold', 'Santo', NULL, NULL, '2021-06-21', 4, 'Female', 'Catholic', 'Tagalog', 0, NULL, 'santoali@gmail.com', 1, 105, 104, 106, 106, 57, 0, 1, 1, '2025-11-09 16:31:19'),
(93, 17, 'Aaron', 'Balmeo', 'De Vera', '', 900000000523, '2020-06-15', 5, 'Male', 'Born Again', 'Tagalog', 0, NULL, 'aaron@gmail.com', 1, 108, 107, 109, 109, 59, 0, 1, 1, '2025-11-10 12:24:54'),
(94, 22, 'Lander', 'Daniel', 'Ibarra', '', 900000000145, '2019-03-12', 6, 'Female', 'Roman Catholic', 'Tagalog', 0, NULL, 'ibarralander@gmail.com', 1, 109, 108, 110, 110, 60, 0, 1, 1, '2025-11-11 12:10:54'),
(95, 17, 'Asdasd', NULL, 'Asdasd', '', NULL, '2022-11-01', 3, 'Male', 'Catholic', 'Wqeq', 0, NULL, '123@123.com', 4, 110, 109, 111, 111, 61, 0, 1, 1, '2025-11-11 16:50:31'),
(96, 17, 'Wqee', 'Wqee', 'Wqee', '', 900000000146, '2018-01-29', 7, 'Male', 'Wqee', 'Wqee', 0, NULL, 'Wqee@123.com', 2, 111, 110, 112, 112, 62, 0, 1, 1, '2025-11-11 18:05:00'),
(97, 17, 'Lovely Jane', 'Cainta', 'Dela Cruz', '', 234523452345, '2018-07-10', 7, 'Female', 'Catholic', 'Tagalog', 0, NULL, 'agutierrez@gmail.com', 1, 112, 111, 113, 113, 63, 0, 1, 1, '2025-11-11 18:12:01'),
(98, NULL, 'Ezekiel', 'Jimenez', 'Villa Berde', '', 676457457645, '0000-00-00', 0, 'Female', 'Iglesia', 'Tagalog', 0, NULL, 'ezekiel@gmail.com', 1, 113, 112, 114, 114, 64, 0, 0, 1, '2025-11-11 20:29:38'),
(99, NULL, 'Juan', 'Guinivere', 'San Pedro', '', 745245152345, '2013-11-14', 11, 'Female', 'Roman Catholic', 'Bisaya', 0, NULL, 'kjdumpmail@gmail.com', 1, 114, 113, 115, 115, 65, 0, 0, 1, '2025-11-11 20:33:49'),
(100, 17, 'Nicko ', NULL, 'Balmes', '', 900000000192, '2016-11-18', 8, 'Female', 'Catholic', 'Japanese ', 0, NULL, 'balmes@gmail.com', 4, 115, 114, 116, 116, 66, 0, 1, 1, '2025-11-12 00:49:41'),
(101, 22, 'Jerryme', 'Pecho', 'Amortizado', '', 107912230476, '2018-02-17', 7, 'Male', 'Christianity', 'Tagalog', 0, NULL, 'jerryme123@gmail', 1, 116, 115, 117, 117, 67, 0, 1, 1, '2025-11-12 04:55:22'),
(102, 22, 'Jerryme', 'Pecho', 'Amortizado', '', 102323121312, '2018-02-17', 7, 'Male', 'Christianity', 'Tagalog', 0, NULL, 'benedict@gmail.com', 4, 117, 116, 118, 118, 68, 0, 1, 1, '2025-11-12 05:03:51'),
(103, 132, 'Kenjie', 'Alojado', 'Abril', '', 913689231939, '2019-10-26', 6, 'male', 'Christianity', 'Tagalog', 0, '', 'kenjie@gmail.com', 1, 118, 117, 119, 119, 69, 0, 1, 1, '2025-11-12 10:41:51'),
(104, 17, 'Brad', 'Pitt', 'Villa Berde', '', 756210128934, '2018-02-22', 7, 'Male', 'Catholic', 'Tagalog', 0, NULL, 'bradpitt@gmail.com', 2, 119, 118, 120, 120, 70, 0, 1, 1, '2025-11-12 19:19:33'),
(105, 17, 'Mark Sean', 'Ricamata', 'Sena ', '', 109732240025, '2019-01-15', 6, 'Male', 'Christianity', 'Tagalog', 0, NULL, 'ben@gmail.com', 1, 120, 119, 121, 121, 71, 0, 1, 1, '2025-11-12 19:30:22'),
(106, 17, 'Ethan Joseph', 'Ledesma', 'Aldovino', '', 109732240051, '2019-04-20', 6, 'Male', 'Christianity', 'Tagalog ', 0, NULL, 'aejled@gmail.com', 4, 121, 120, 122, 122, 72, 0, 1, 1, '2025-11-13 14:43:00'),
(107, 17, 'Cris Martin', 'Dalugdog', 'Castellano', '', 109732250053, '2019-04-09', 6, 'Male', 'Christianity', 'Tagalog', 0, NULL, 'dvbataller@gmail.com', 2, 122, 121, 123, 123, 73, 0, 1, 1, '2025-11-13 15:05:18'),
(108, 17, 'Wissam Jackson', 'Llona', 'Dela Rosa', '', 109732240054, '2018-11-24', 6, 'Male', 'Christianity', 'Tagalog', 0, NULL, 'delajackson@gmail.com', 3, 123, 122, 124, 124, 74, 0, 0, 1, '2025-11-13 15:08:42'),
(109, 17, 'Arlhan Antonio', 'Quinto', 'Ferrer', '', 109732240055, '2019-03-14', 6, 'Male', 'Christianity', 'Tagalog', 0, NULL, 'faa@gmail.com', 3, 124, 123, 125, 125, 75, 0, 1, 1, '2025-11-13 15:11:55'),
(110, 17, 'Gizmhyr Cyronne', 'Levelo', 'Hernandez', '', 109732240056, '2018-12-17', 6, 'Male', 'Christianity', 'Tagalog', 0, NULL, 'gizcyr@gmail.com', 3, 125, 124, 126, 126, 76, 0, 0, 1, '2025-11-13 15:20:29'),
(111, 17, 'Kyla', 'Pelaez', 'Aldovino', '', 109732240063, '2018-12-27', 6, 'Female', 'Christianity', 'Tagalog', 0, NULL, 'kylapela@gmail.com', 3, 126, 125, 127, 127, 77, 0, 0, 1, '2025-11-13 15:23:30'),
(112, 17, 'Erica', 'Magnaye', 'Catamio', '', 109732240062, '2018-08-23', 7, 'Female', 'Christianity', 'Tagalog', 0, NULL, 'magnayoerica@gmail.com', 3, 127, 126, 128, 128, 78, 0, 0, 1, '2025-11-13 15:26:00'),
(113, 17, 'Maria Cristina', 'Cortez', 'Ilagan', '', 109732240065, '2019-04-15', 6, 'Female', 'Christianity', 'Tagalog', 0, NULL, 'dvguelas@gmail.com', 3, 128, 127, 129, 129, 79, 0, 0, 1, '2025-11-13 15:33:32'),
(114, 17, 'Sheonel James', 'De Vega', 'Bataller', '', 109732240052, '2018-11-16', 6, 'Male', 'Christianity', 'Tagalog', 0, NULL, 'dvbataller@gmail.com', 3, 129, 128, 130, 130, 80, 0, 0, 1, '2025-11-13 15:47:56'),
(115, 17, 'Isabella Louise', 'De Vega', 'Guelas', '', 109732240064, '2018-08-23', 7, 'Female', 'Christianity', 'Tagalog', 0, NULL, 'dvguelas@gmail.com', 3, 130, 129, 131, 131, 81, 0, 0, 1, '2025-11-13 15:54:34'),
(116, 17, 'Lhucas Angelo', 'De Los Reyes', 'Aductante', '', 109732240002, '2019-04-13', 6, 'Male', 'Christianity', 'Tagalog', 0, NULL, 'lhucas@gmail.com', 3, 131, 130, 132, 132, 82, 0, 0, 1, '2025-11-13 16:02:16'),
(117, 17, 'Steven Clark', 'Jaqueca', 'Fornea', '', 109732240032, '2018-11-12', 7, 'Male', 'Christianity', 'Tagalog', 0, NULL, 'clarkjaq@gmail.com', 3, 132, 131, 133, 133, 83, 0, 0, 1, '2025-11-13 16:11:57'),
(118, 17, 'Zach', 'Amantillo', 'Marquez', '', 109732240006, '2019-08-03', 6, 'Male', 'Christianity', 'Tagalog', 0, NULL, 'zach@gmail.com', 3, 133, 132, 134, 134, 84, 0, 0, 1, '2025-11-13 16:14:26'),
(119, 17, 'Zion Matthew', 'Amante', 'Ormacido', '', 109732240058, '2019-04-12', 6, 'Male', 'Christianity', 'Tagalog', 0, NULL, 'zionmatt@gmail.com', 3, 134, 133, 135, 135, 85, 0, 0, 1, '2025-11-13 16:25:15'),
(120, 17, 'Rod Sigfred', 'Perlas', 'Ortiz', '', 425075240008, '2019-10-19', 6, 'Male', 'Christianity', 'Tagalog', 0, NULL, 'sigrod@gmail.com', 3, 135, 134, 136, 136, 86, 0, 0, 1, '2025-11-13 16:29:02'),
(121, 17, 'Ayesha Rose', 'Pantoja', 'Amante', '', 109732240011, '2019-03-31', 6, 'Female', 'Christianity', 'Tagalog', 0, NULL, 'roseamante@gmail.com', 3, 136, 135, 137, 137, 87, 0, 0, 1, '2025-11-13 16:32:37'),
(122, NULL, 'Kyla', 'Apit', 'Llona', '', 109732230082, '2018-02-05', 7, 'Female', 'Christianity', 'Tagalog', 0, NULL, '', 1, 137, 136, 138, 138, 88, 0, 0, 1, '2025-11-13 17:44:29'),
(123, NULL, 'Aleon Shin', 'Dela Cruz', 'Palermo', '', 109732220042, '2017-07-05', 8, 'Male', 'Christianity', 'Tagalog', 0, NULL, '', 1, 138, 137, 139, 139, 89, 0, 1, 1, '2025-11-13 19:34:21'),
(124, 17, 'Maria', 'Duldulao', 'Lingo-Lingo', '', 900000000425, '2018-09-13', 7, 'Female', 'Christianity', 'Tagalog', 0, NULL, 'maria@gmail.com', 3, 139, 138, 140, 140, 90, 0, 0, 1, '2025-11-13 22:19:36'),
(125, 143, 'Onoy', 'Sabile', 'Llorin', '', 458910263103, '2016-10-05', 9, 'Male', 'Roman Catholic', 'Tagalog', 0, NULL, 'ben@samplegmail.com', 3, 140, 139, 141, 141, 91, 0, 0, 1, '2025-11-14 05:12:56'),
(126, 143, 'Onoy', 'Sabile', 'Llorin', '', 954871031266, '2016-10-05', 9, 'Male', 'Roman Catholic', 'Tagalog', 0, NULL, 'ben@samplegmail.com', 1, 141, 140, 142, 142, 92, 0, 1, 1, '2025-11-14 05:15:16'),
(127, 17, 'John Mark', 'Gutierrez', 'Villa Berde', '', 812841743123, '2018-02-04', 7, 'Male', 'Iglesia Ni Cristo', 'Tagalog', 0, NULL, 'agutierrez@gmail.com', 3, 142, 141, 143, 143, 93, 0, 0, 1, '2025-11-15 12:07:25'),
(128, 17, 'Rose', 'Rodriguez', 'San Juan', 'N/A', 986769696776, '2002-09-06', 23, 'Female', 'Catholic', 'Tagalog', 1, 'N/A', 'alojadokeneth@gmail.com', 3, 143, 142, 144, 144, 94, 0, 0, 1, '2025-11-15 12:56:27'),
(129, 17, 'Rose', 'Rodriguez', 'San Juan', 'N/A', NULL, '2002-09-06', 23, 'Female', 'Catholic', 'Tagalog', 1, 'N/A', 'alojadokeneth@gmail.com', 3, 144, 143, 145, 145, 95, 0, 0, 1, '2025-11-15 12:57:40'),
(130, 17, 'Anatoly', 'Karangalan', 'Cumrade', '', 187264365781, '2017-05-10', 8, 'Male', 'Roman Catholic', 'Tagalog', 0, NULL, '', 3, 145, 144, 146, 146, 96, 0, 0, 1, '2025-11-15 13:31:00'),
(131, 17, 'Kenneth Jeffrey', 'Jimenez', 'Alojado', '', 345643563456, '2014-11-15', 11, 'Female', 'Roman Catholic', 'Tagalog', 0, NULL, 'kjdumpmail@gmail.com', 3, 146, 145, 147, 147, 97, 0, 0, 1, '2025-11-15 14:35:38'),
(132, 17, 'Maya', 'Dein', 'River', '', 283475892345, '2019-09-23', 6, 'Female', 'Buddhist', 'Tagalog', 0, NULL, 'kjdumpmail@gmail.com', 3, 149, 148, 150, 150, NULL, 0, 0, 1, '2025-11-16 19:45:59'),
(133, 17, 'Vincent', 'Von', 'Van Gough', '', 891789162737, '2016-07-06', 9, 'Female', 'Buddhist', 'Tagalog', 0, NULL, 'agutierrez@gmail.com', 3, 150, 149, 151, 151, NULL, 0, 0, 1, '2025-11-17 07:28:13'),
(134, 17, 'Joseph', 'Di Ko Alam', 'Racelis', '', 92173490871, '2017-05-17', 8, 'Male', 'Catholic', 'Tagalog', 0, NULL, 'lovelycruz.13@gmail.com', 3, 151, 150, 152, 152, NULL, 0, 0, 1, '2025-11-17 07:33:14'),
(135, 17, 'Tony', 'Abigail', 'Stark', '', 162348971629, '2019-03-15', 6, 'Male', 'Roman Catholic', 'Tagalog', 0, NULL, 'lovelycruz.13@gmail.com', 3, 152, 151, 153, 153, NULL, 0, 0, 1, '2025-11-17 07:35:19'),
(136, 17, 'Knight', 'Gay', 'Mega', '', 827459823478, '2018-02-08', 7, 'Female', 'Buddhist', 'Tagalog', 0, NULL, 'kjdumpmail@gmail.com', 3, 153, 152, 154, 154, NULL, 0, 0, 1, '2025-11-17 07:56:42'),
(137, 17, 'John Mark', 'Jimenez', 'Villa Berde', '', 238947658792, '2018-09-14', 7, 'Female', 'Roman Catholic', 'Tagalog', 0, NULL, 'alojadokeneth@gmail.com', 3, 154, 153, 155, 155, NULL, 0, 0, 1, '2025-11-17 08:18:46');

-- --------------------------------------------------------

--
-- Table structure for table `enrollee_address`
--

CREATE TABLE `enrollee_address` (
  `Enrollee_Address_Id` bigint(20) NOT NULL,
  `House_Number` bigint(20) DEFAULT NULL,
  `Subd_Name` varchar(50) DEFAULT NULL,
  `Brgy_Name` varchar(50) DEFAULT NULL,
  `Brgy_Code` varchar(11) NOT NULL,
  `Municipality_Name` varchar(50) DEFAULT NULL,
  `Municipality_Code` varchar(11) NOT NULL,
  `Province_Name` varchar(50) DEFAULT NULL,
  `Province_Code` varchar(11) NOT NULL,
  `Region` varchar(50) DEFAULT NULL,
  `Region_Code` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `enrollee_address`
--

INSERT INTO `enrollee_address` (`Enrollee_Address_Id`, `House_Number`, `Subd_Name`, `Brgy_Name`, `Brgy_Code`, `Municipality_Name`, `Municipality_Code`, `Province_Name`, `Province_Code`, `Region`, `Region_Code`) VALUES
(11, 0, 'Purok Pinagisa', 'Cotta', '045624016', 'City of Lucena', '045624000', 'Quezon', '045600000', 'CALABARZON', '040000000'),
(15, 32, 'Talipan', 'Barangay 4', '045624007', 'City of Lucena', '045624000', 'Quezon', '045600000', 'CALABARZON', '040000000'),
(58, 322, 'Balugbug', 'Bataan', '045639008', 'Sampaloc', '045639000', 'Quezon', '045600000', 'CALABARZON', '040000000'),
(59, 3234, 'Southville', 'Poblacion', '025001007', 'Ambaguio', '025001000', 'Nueva Vizcaya', '025000000', 'Cagayan Valley', '020000000'),
(63, 6513, 'Norte', 'Lusacan', '045648017', 'Tiaong', '045648000', 'Quezon', '045600000', 'CALABARZON', '040000000'),
(64, 6513, 'Norte', 'Masin Sur', '045608017', 'Candelaria', '045608000', 'Quezon', '045600000', 'CALABARZON', '040000000'),
(65, 0, 'talipan', 'Cabaruan', '012802003', 'Bacarra', '012802000', 'Ilocos Norte', '012800000', 'Ilocos Region', '010000000'),
(66, 4363, '64thtrf', 'Santa Cruz', '097312020', 'Labangan', '097312000', 'Zamboanga Del Sur', '097300000', 'Zamboanga Peninsula', '090000000'),
(67, 327, 'Purok Pinagisa', 'Cotta', '045624016', 'City of Lucena', '045624000', 'Quezon', '045600000', 'CALABARZON', '040000000'),
(68, 327, 'Purok Pinagisa', 'Cotta', '045624016', 'City of Lucena', '045624000', 'Quezon', '045600000', 'CALABARZON', '040000000'),
(69, 1034, 'Southville', 'Cotta', '045624016', 'City of Lucena', '045624000', 'Quezon', '045600000', 'CALABARZON', '040000000'),
(70, 1034, 'Southville', 'Cotta', '045624016', 'City of Lucena', '045624000', 'Quezon', '045600000', 'CALABARZON', '040000000'),
(71, 334, 'Southville', 'Carasuchi', '042110010', 'Indang', '042110000', 'Cavite', '042100000', 'CALABARZON', '040000000'),
(72, 334, 'Southville', 'Carasuchi', '042110010', 'Indang', '042110000', 'Cavite', '042100000', 'CALABARZON', '040000000'),
(74, 1034, 'West', 'Dalahican', '045624018', 'City of Lucena', '045624000', 'Quezon', '045600000', 'CALABARZON', '040000000'),
(75, 1034, 'West', '', '', '', '', 'Quezon', '045600000', 'CALABARZON', '040000000'),
(80, 32, 'Peninsula', 'Talipan', '045630026', 'Pagbilao', '045630000', 'Quezon', '045600000', 'CALABARZON', '040000000'),
(81, 300, 'Greenville', 'Kakawit', '045623016', 'Lucban', '045623000', 'Quezon', '045600000', 'CALABARZON', '040000000'),
(82, 641, 'Zaballero', 'Gulang-gulang', '045624017', 'City of Lucena', '045624000', 'Quezon', '045600000', 'CALABARZON', '040000000'),
(83, 322, 'Greenville', 'Kakawit', '045623016', 'Lucban', '045623000', 'Quezon', '045600000', 'CALABARZON', '040000000'),
(84, 300, 'Norte', 'Dapdap', '045647017', 'City of Tayabas', '045647000', 'Quezon', '045600000', 'CALABARZON', '040000000'),
(85, 12, 'Luningning', 'Toclong', '042111011', 'Kawit', '042111000', 'Cavite', '042100000', 'CALABARZON', '040000000'),
(86, 9, 'Luningning', 'Santa Brigida', '175207016', 'Mansalay', '175207000', 'Oriental Mindoro', '175200000', 'MIMAROPA Region', '170000000'),
(87, 12, 'N/A', 'Barangay 4 (Pob.)', '045624007', 'City of Lucena', '045624000', 'Quezon', '045600000', 'CALABARZON', '040000000'),
(93, 321, 'Purok Pinagisa', 'Cotta', '45624016', 'City of Lucena', '45624000', 'Quezon', '45600000', 'CALABARZON', '40000000'),
(97, 203, 'Puting Buhangin', 'Cotta', '45624016', 'City of Lucena', '45624000', 'Quezon', '45600000', 'CALABARZON', '40000000'),
(99, 427, 'Iyam', 'Ibabang Iyam', '45624021', 'City of Lucena', '45624000', 'Quezon', '45600000', 'CALABARZON', '40000000'),
(100, 98, 'Domoit', 'Domoit', '45624019', 'City of Lucena', '45624000', 'Quezon', '45600000', 'CALABARZON', '40000000'),
(104, 396, 'Teacher\'s Village', 'Cotta', '45624016', 'City of Lucena', '45624000', 'Quezon', '45600000', 'CALABARZON', '40000000'),
(105, 756, 'Purok Pinagisa', 'Cotta', '45624016', 'City of Lucena', '45624000', 'Quezon', '45600000', 'CALABARZON', '40000000'),
(108, 32, 'Greenville', 'Tiawe', '45623031', 'Lucban', '45623000', 'Quezon', '45600000', 'CALABARZON', '40000000'),
(109, 223, 'Ibaba', 'Luya-luya', '45627014', 'Mauban', '45627000', 'Quezon', '45600000', 'CALABARZON', '40000000'),
(110, 32, 'Talipan', 'Likas Ng Silangan (Pob.)', '175104013', 'Lubang', '175104000', 'Occidental Mindoro', '175100000', 'MIMAROPA Region', '170000000'),
(111, 32, 'Talipan', 'Southern Paligue', '112411014', 'Padada', '112411000', 'Davao Del Sur', '112400000', 'Davao Region', '110000000'),
(112, 76, 'Luningning', 'Eddet', '141107010', 'Kabayan', '141107000', 'Benguet', '141100000', 'CAR', '140000000'),
(113, 3412, 'Relevati', 'Batong Dalig', '42111013', 'Kawit', '42111000', 'Cavite', '42100000', 'CALABARZON', '40000000'),
(114, 2345234, 'dsfgsdfg', 'Toclong', '42111011', 'Kawit', '42111000', 'Cavite', '42100000', 'CALABARZON', '40000000'),
(115, 334, 'Talo', 'Lual (Pob.)', '45627017', 'Mauban', '45627000', 'Quezon', '45600000', 'CALABARZON', '40000000'),
(116, 32, 'Purok Pinag-isa', 'Cotta', '45624016', 'City of Lucena', '45624000', 'Quezon', '45600000', 'CALABARZON', '40000000'),
(117, 32, 'Purok Pinag-isa', 'Cotta', '45624016', 'City of Lucena', '45624000', 'Quezon', '45600000', 'CALABARZON', '40000000'),
(118, 476, 'Purok Pinag-isa', 'Cotta', '', 'Select City/Municipality', '', 'Select Province', '', 'CALABARZON', '40000000'),
(119, 35235, 'West', 'Maracta (Pob.)', '43413007', 'Lumban', '43413000', 'Laguna', '43400000', 'CALABARZON', '40000000'),
(120, 32, 'Purok Pinag-isa', 'Cotta', '45624016', 'City of Lucena', '45624000', 'Quezon', '45600000', 'CALABARZON', '40000000'),
(121, 1, 'Purok Pinagisa', 'Cotta', '45624016', 'City of Lucena', '45624000', 'Quezon', '45600000', 'CALABARZON', '40000000'),
(122, 2, 'Purok Pinagisa', 'Cotta', '45624016', 'City of Lucena', '45624000', 'Quezon', '45600000', 'CALABARZON', '40000000'),
(123, 3, 'Purok Pinagisa', 'Cotta', '45624016', 'City of Lucena', '45624000', 'Quezon', '45600000', 'CALABARZON', '40000000'),
(124, 4, 'Purok Pinagisa', 'Cotta', '45624016', 'City of Lucena', '45624000', 'Quezon', '45600000', 'CALABARZON', '40000000'),
(125, 5, 'Purok Pinagisa', 'Cotta', '45624016', 'City of Lucena', '45624000', 'Quezon', '45600000', 'CALABARZON', '40000000'),
(126, 6, 'Purok Pinagisa', 'Cotta', '45624016', 'City of Lucena', '45624000', 'Quezon', '45600000', 'CALABARZON', '40000000'),
(127, 7, 'Purok Pinagisa', 'Cotta', '45624016', 'City of Lucena', '45624000', 'Quezon', '45600000', 'CALABARZON', '40000000'),
(128, 8, 'Purok Pinagisa', 'Cotta', '45624016', 'City of Lucena', '45624000', 'Quezon', '45600000', 'CALABARZON', '40000000'),
(129, 9, 'Purok Pinagisa', 'Cotta', '45624016', 'City of Lucena', '45624000', 'Quezon', '45600000', 'CALABARZON', '40000000'),
(130, 10, 'Purok Pinagisa', 'Cotta', '45624016', 'City of Lucena', '45624000', 'Quezon', '45600000', 'CALABARZON', '40000000'),
(131, 101, 'Purok Pinagisa', 'Cotta', '45624016', 'City of Lucena', '45624000', 'Quezon', '45600000', 'CALABARZON', '40000000'),
(132, 102, 'Purok Pinagisa', 'Cotta', '45624016', 'City of Lucena', '45624000', 'Quezon', '45600000', 'CALABARZON', '40000000'),
(133, 103, 'Purok Pinagisa', 'Cotta', '45624016', 'City of Lucena', '45624000', 'Quezon', '45600000', 'CALABARZON', '40000000'),
(134, 104, 'Purok Pinagisa', 'Cotta', '45624016', 'City of Lucena', '45624000', 'Quezon', '45600000', 'CALABARZON', '40000000'),
(135, 105, 'Purok Pinagisa', 'Cotta', '45624016', 'City of Lucena', '45624000', 'Quezon', '45600000', 'CALABARZON', '40000000'),
(136, 106, 'Purok Pinagisa', 'Cotta', '45624016', 'City of Lucena', '45624000', 'Quezon', '45600000', 'CALABARZON', '40000000'),
(137, 123, 'Teachers village', 'Cotta', '45624016', 'City of Lucena', '45624000', 'Quezon', '45600000', 'CALABARZON', '40000000'),
(138, 32, 'Purokkk', 'Cotta', '45624016', 'City of Lucena', '45624000', 'Quezon', '45600000', 'CALABARZON', '40000000'),
(139, 329, 'Teacher\'s village', 'Cotta', '45624016', 'City of Lucena', '45624000', 'Quezon', '45600000', 'CALABARZON', '40000000'),
(140, 527, 'Purok Pinagisa', 'Cotta', '45624016', 'City of Lucena', '45624000', 'Quezon', '45600000', 'CALABARZON', '40000000'),
(141, 527, 'Purok Pinagisa', 'Cotta', '45624016', 'City of Lucena', '45624000', 'Quezon', '45600000', 'CALABARZON', '40000000'),
(142, 12, 'St. Luna', 'Barangay 9 (Pob.)', '45624012', 'City of Lucena', '45624000', 'Quezon', '45600000', 'CALABARZON', '40000000'),
(143, 32, 'West', 'Buyon', '12802002', 'Bacarra', '12802000', 'Ilocos Norte', '12800000', 'Ilocos Region', '10000000'),
(144, 32, 'West', 'Basca', '13302002', 'Aringay', '13302000', 'La Union', '13300000', 'Ilocos Region', '10000000'),
(145, 9, 'West', 'Barangay 8 (Pob.)', '45624011', 'City of Lucena', '45624000', 'Quezon', '45600000', 'CALABARZON', '40000000'),
(146, 12, 'West', 'Lumingon', '45648016', 'Tiaong', '45648000', 'Quezon', '45600000', 'CALABARZON', '40000000'),
(149, 12, 'Talipan', 'Lucong', '043414003', 'Mabitac', '043414000', 'Laguna', '043400000', 'CALABARZON', '040000000'),
(150, 12, 'West', 'Santa Cruz', '043402012', 'Bay', '043402000', 'Laguna', '043400000', 'CALABARZON', '040000000'),
(151, 9, 'West', 'Barangay XI (Pob.)', '042102013', 'Amadeo', '042102000', 'Cavite', '042100000', 'CALABARZON', '040000000'),
(152, 12, 'St. Luna', 'Mampalasan', '043403014', 'City of Biñan', '043403000', 'Laguna', '043400000', 'CALABARZON', '040000000'),
(153, 9, 'St. Luna', 'Tablu', '126314007', 'Tampakan', '126314000', 'South Cotabato', '126300000', 'SOCCSKSARGEN', '120000000'),
(154, 12, 'St. Luna', 'Cupang', '041006013', 'Bauan', '041006000', 'Batangas', '041000000', 'CALABARZON', '040000000');

-- --------------------------------------------------------

--
-- Table structure for table `enrollee_parents`
--

CREATE TABLE `enrollee_parents` (
  `Enrollee_Id` bigint(20) NOT NULL,
  `Parent_Id` bigint(20) NOT NULL,
  `Relationship` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `enrollee_parents`
--

INSERT INTO `enrollee_parents` (`Enrollee_Id`, `Parent_Id`, `Relationship`) VALUES
(14, 43, 'Father'),
(14, 44, 'Mother'),
(14, 45, 'Guardian'),
(48, 172, 'Father'),
(48, 173, 'Mother'),
(48, 174, 'Guardian'),
(53, 187, 'Father'),
(53, 188, 'Mother'),
(53, 189, 'Guardian'),
(54, 190, 'Father'),
(54, 191, 'Mother'),
(54, 192, 'Guardian'),
(55, 193, 'Father'),
(55, 194, 'Mother'),
(55, 195, 'Guardian'),
(56, 196, 'Father'),
(56, 197, 'Mother'),
(56, 198, 'Guardian'),
(57, 199, 'Father'),
(57, 200, 'Mother'),
(57, 201, 'Guardian'),
(58, 202, 'Father'),
(58, 203, 'Mother'),
(58, 204, 'Guardian'),
(59, 205, 'Father'),
(59, 206, 'Mother'),
(59, 207, 'Guardian'),
(60, 208, 'Father'),
(60, 209, 'Mother'),
(60, 210, 'Guardian'),
(61, 211, 'Father'),
(61, 212, 'Mother'),
(61, 213, 'Guardian'),
(64, 220, 'Father'),
(64, 221, 'Mother'),
(64, 222, 'Guardian'),
(65, 223, 'Father'),
(65, 224, 'Mother'),
(65, 225, 'Guardian'),
(70, 238, 'Father'),
(70, 239, 'Mother'),
(70, 240, 'Guardian'),
(71, 241, 'Father'),
(71, 242, 'Mother'),
(71, 243, 'Guardian'),
(72, 244, 'Father'),
(72, 245, 'Mother'),
(72, 246, 'Guardian'),
(73, 247, 'Father'),
(73, 248, 'Mother'),
(73, 249, 'Guardian'),
(74, 250, 'Father'),
(74, 251, 'Mother'),
(74, 252, 'Guardian'),
(75, 253, 'Father'),
(75, 254, 'Mother'),
(75, 255, 'Guardian'),
(76, 256, 'Father'),
(76, 257, 'Mother'),
(76, 258, 'Guardian'),
(77, 259, 'Father'),
(77, 260, 'Mother'),
(77, 261, 'Guardian'),
(79, 277, 'Father'),
(79, 278, 'Mother'),
(79, 279, 'Guardian'),
(83, 289, 'Father'),
(83, 290, 'Mother'),
(83, 291, 'Guardian'),
(85, 295, 'Father'),
(85, 296, 'Mother'),
(85, 297, 'Guardian'),
(86, 298, 'Father'),
(86, 299, 'Mother'),
(86, 300, 'Guardian'),
(90, 310, 'Father'),
(90, 311, 'Mother'),
(90, 312, 'Guardian'),
(91, 313, 'Father'),
(91, 314, 'Mother'),
(91, 315, 'Guardian'),
(93, 322, 'Father'),
(93, 323, 'Mother'),
(93, 324, 'Guardian'),
(94, 325, 'Father'),
(94, 326, 'Mother'),
(94, 327, 'Guardian'),
(95, 328, 'Father'),
(95, 329, 'Mother'),
(95, 330, 'Guardian'),
(96, 331, 'Father'),
(96, 332, 'Mother'),
(96, 333, 'Guardian'),
(97, 334, 'Father'),
(97, 335, 'Mother'),
(97, 336, 'Guardian'),
(98, 337, 'Father'),
(98, 338, 'Mother'),
(98, 339, 'Guardian'),
(99, 340, 'Father'),
(99, 341, 'Mother'),
(99, 342, 'Guardian'),
(100, 343, 'Father'),
(100, 344, 'Mother'),
(100, 345, 'Guardian'),
(101, 346, 'Father'),
(101, 347, 'Mother'),
(101, 348, 'Guardian'),
(102, 349, 'Father'),
(102, 350, 'Mother'),
(102, 351, 'Guardian'),
(103, 352, 'Father'),
(103, 353, 'Mother'),
(103, 354, 'Guardian'),
(104, 355, 'Father'),
(104, 356, 'Mother'),
(104, 357, 'Guardian'),
(105, 358, 'Father'),
(105, 359, 'Mother'),
(105, 360, 'Guardian'),
(106, 361, 'Father'),
(106, 362, 'Mother'),
(106, 363, 'Guardian'),
(107, 364, 'Father'),
(107, 365, 'Mother'),
(107, 366, 'Guardian'),
(108, 367, 'Father'),
(108, 368, 'Mother'),
(108, 369, 'Guardian'),
(109, 370, 'Father'),
(109, 371, 'Mother'),
(109, 372, 'Guardian'),
(110, 373, 'Father'),
(110, 374, 'Mother'),
(110, 375, 'Guardian'),
(111, 376, 'Father'),
(111, 377, 'Mother'),
(111, 378, 'Guardian'),
(112, 379, 'Father'),
(112, 380, 'Mother'),
(112, 381, 'Guardian'),
(113, 382, 'Father'),
(113, 383, 'Mother'),
(113, 384, 'Guardian'),
(114, 385, 'Father'),
(114, 386, 'Mother'),
(114, 387, 'Guardian'),
(115, 388, 'Father'),
(115, 389, 'Mother'),
(115, 390, 'Guardian'),
(116, 391, 'Father'),
(116, 392, 'Mother'),
(116, 393, 'Guardian'),
(117, 394, 'Father'),
(117, 395, 'Mother'),
(117, 396, 'Guardian'),
(118, 397, 'Father'),
(118, 398, 'Mother'),
(118, 399, 'Guardian'),
(119, 400, 'Father'),
(119, 401, 'Mother'),
(119, 402, 'Guardian'),
(120, 403, 'Father'),
(120, 404, 'Mother'),
(120, 405, 'Guardian'),
(121, 406, 'Father'),
(121, 407, 'Mother'),
(121, 408, 'Guardian'),
(122, 409, 'Father'),
(122, 410, 'Mother'),
(122, 411, 'Guardian'),
(123, 412, 'Father'),
(123, 413, 'Mother'),
(123, 414, 'Guardian'),
(124, 415, 'Father'),
(124, 416, 'Mother'),
(124, 417, 'Guardian'),
(125, 418, 'Father'),
(125, 419, 'Mother'),
(125, 420, 'Guardian'),
(126, 421, 'Father'),
(126, 422, 'Mother'),
(126, 423, 'Guardian'),
(127, 424, 'Father'),
(127, 425, 'Mother'),
(127, 426, 'Guardian'),
(128, 427, 'Father'),
(128, 428, 'Mother'),
(128, 429, 'Guardian'),
(129, 430, 'Father'),
(129, 431, 'Mother'),
(129, 432, 'Guardian'),
(130, 433, 'Father'),
(130, 434, 'Mother'),
(130, 435, 'Guardian'),
(131, 436, 'Father'),
(131, 437, 'Mother'),
(131, 438, 'Guardian'),
(132, 441, 'Guardian'),
(133, 442, 'Guardian'),
(134, 443, 'Guardian'),
(135, 444, 'Guardian'),
(136, 445, 'Guardian'),
(137, 446, 'Guardian');

-- --------------------------------------------------------

--
-- Table structure for table `enrollment_transactions`
--

CREATE TABLE `enrollment_transactions` (
  `Enrollment_Transaction_Id` bigint(20) NOT NULL,
  `Enrollee_Id` bigint(20) DEFAULT NULL,
  `Transaction_Code` varchar(50) DEFAULT NULL,
  `Enrollment_Status` int(11) NOT NULL,
  `Staff_Id` int(11) NOT NULL,
  `Remarks` varchar(255) NOT NULL,
  `Transaction_Status` tinyint(4) NOT NULL,
  `Is_Approved` tinyint(11) NOT NULL,
  `School_Year_Details_Id` int(11) NOT NULL,
  `Created_At` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `enrollment_transactions`
--

INSERT INTO `enrollment_transactions` (`Enrollment_Transaction_Id`, `Enrollee_Id`, `Transaction_Code`, `Enrollment_Status`, `Staff_Id`, `Remarks`, `Transaction_Status`, `Is_Approved`, `School_Year_Details_Id`, `Created_At`) VALUES
(8, 14, 'D-20250522-1747921493', 4, 18, 'mali ang edad at walang nakalagay na birthdate ', 0, 1, 1, '2025-05-22 13:44:53'),
(10, 55, 'D-20250522-1747921538', 2, 18, 'magsend ng mas malinaw na picture ng birthcert', 0, 1, 1, '2025-05-22 13:45:38'),
(14, 56, 'F-20250522-1747934469', 4, 18, 'rerher', 0, 1, 1, '2025-05-22 17:21:07'),
(15, 57, 'F-20250523-1747970431', 4, 18, 'No image', 0, 1, 1, '2025-05-23 03:20:32'),
(16, 60, 'D-20250523-1747972418', 2, 18, 'false inputs', 0, 1, 1, '2025-05-23 03:53:39'),
(17, 48, 'D-20250523-1747979563', 4, 18, 'The image you submmitted is not a birth certificate', 0, 1, 1, '2025-05-23 05:52:44'),
(18, 53, 'F-20250602-1748842809', 4, 18, 'Fields for Age is wrong, image sent is not PSA', 0, 1, 1, '2025-06-02 05:40:11'),
(20, 65, 'D-20250609-1749429956', 4, 18, 'Repetitive submissions', 0, 1, 1, '2025-06-09 00:45:57'),
(21, 64, 'F-20250609-1749430004', 4, 18, 'The input for  PSA number is the same as LRN', 0, 1, 1, '2025-06-09 00:46:45'),
(22, 71, 'F-20250628-1751088485', 4, 18, 'No Image', 0, 1, 1, '2025-06-28 05:28:06'),
(23, 72, 'F-20250731-1753967465', 4, 28, 'age input wrong', 0, 1, 1, '2025-07-31 13:11:06'),
(27, 73, 'E-20250801-1754034471', 1, 28, 'None', 0, 1, 1, '2025-08-01 07:47:53'),
(29, 74, 'E-20250801-1754058917', 1, 18, 'None', 0, 1, 1, '2025-08-01 14:35:19'),
(30, 61, 'F-20250911-1757606178', 4, 18, 'NO LRN', 0, 1, 1, '2025-09-11 15:56:18'),
(31, 75, 'E-20250911-1757606853', 1, 18, 'Outstanding', 0, 1, 1, '2025-09-11 16:07:34'),
(32, 75, 'E-20250911-1757606878', 1, 18, '', 0, 1, 1, '2025-09-11 16:07:58'),
(34, 70, 'E-20250911-1757607106', 1, 18, 'ok', 0, 1, 1, '2025-09-11 16:11:46'),
(35, 75, 'E-20250911-1757607125', 1, 18, 'ok', 0, 1, 1, '2025-09-11 16:12:05'),
(36, 70, 'E-20250912-1757628593', 1, 18, '', 0, 1, 1, '2025-09-11 22:09:53'),
(37, 59, 'E-20250913-1757729507', 1, 18, 'None', 0, 1, 1, '2025-09-13 02:11:49'),
(38, 58, 'F-20250913-1757729587', 4, 18, 'No PSA image', 0, 1, 1, '2025-09-13 02:13:09'),
(39, 54, 'E-20250913-1757729947', 1, 18, 'None', 0, 1, 1, '2025-09-13 02:19:09'),
(40, 77, 'E-20250919-1758267564', 1, 18, 'Good', 0, 1, 1, '2025-09-19 07:39:28'),
(41, 83, 'E-20251029-1761774142', 1, 28, 'None', 0, 1, 1, '2025-10-29 21:42:25'),
(46, 79, 'F-01504663-1762075922', 4, 28, 'Incomplete middle name', 2, 0, 1, '2025-11-02 09:32:03'),
(47, 76, 'F-61814474-1762184352', 4, 18, 'Hello', 0, 1, 1, '2025-11-03 15:39:13'),
(48, 85, 'F-99359039-1762235379', 4, 28, 'No LRN', 0, 1, 1, '2025-11-04 05:49:40'),
(49, 86, 'D-66507729-1762235434', 4, 28, 'NO PSA', 0, 1, 1, '2025-11-04 05:50:35'),
(50, 93, 'F-57646333-1762779393', 4, 18, 'None', 0, 1, 1, '2025-11-10 12:56:34'),
(51, 93, 'E-41114150-1762780506', 1, 18, 'None', 0, 1, 1, '2025-11-10 13:15:07'),
(52, 90, 'F-29335783-1762780595', 4, 18, 'Wrong input', 0, 1, 1, '2025-11-10 13:16:36'),
(53, 91, 'F-51223385-1762847310', 4, 18, 'Special Needs', 0, 1, 1, '2025-11-11 07:48:30'),
(54, 94, 'E-57412806-1762902351', 4, 28, 'Complete info', 0, 1, 1, '2025-11-11 23:05:52'),
(55, 103, 'F-30038833-1762941119', 4, 28, 'Wrong PSA image', 0, 1, 1, '2025-11-12 09:52:00'),
(56, 95, 'D-07747441-1762941219', 4, 28, 'Name does not exist', 1, 0, 1, '2025-11-12 09:53:40'),
(57, 96, 'D-36206722-1762941353', 2, 28, 'Hi', 0, 1, 1, '2025-11-12 09:55:54'),
(58, 102, 'E-34784609-1762941421', 4, 28, 'accept', 0, 0, 1, '2025-11-12 09:57:02'),
(59, 97, 'E-28173465-1762955892', 1, 61, 'NONE', 0, 1, 1, '2025-11-12 13:58:13'),
(60, 100, 'F-78721007-1762956093', 4, 28, '', 0, 0, 1, '2025-11-12 14:01:33'),
(61, 101, 'D-52962035-1762956291', 4, 61, 'Denied', 0, 1, 1, '2025-11-12 14:04:51'),
(62, 104, 'E-89769473-1763049329', 1, 28, 'Good ', 0, 1, 1, '2025-11-13 15:55:01'),
(63, 105, 'E-64192367-1763049442', 1, 28, 'Good', 0, 1, 1, '2025-11-13 15:56:55'),
(64, 106, 'F-20873255-1763049498', 4, 28, 'Wrong Image', 0, 0, 1, '2025-11-13 15:57:50'),
(65, 107, 'D-15107813-1763049916', 2, 28, 'Bad Image', 0, 1, 1, '2025-11-13 16:04:48'),
(66, 109, 'D-76149342-1763079482', 2, 28, 'Wrong Image Submitted', 0, 0, 1, '2025-11-14 00:18:01'),
(67, 126, 'E-08374478-1763094370', 1, 28, '', 0, 1, 1, '2025-11-14 04:26:11');

-- --------------------------------------------------------

--
-- Table structure for table `grade_level`
--

CREATE TABLE `grade_level` (
  `Grade_Level_Id` int(20) NOT NULL,
  `Grade_Level` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `grade_level`
--

INSERT INTO `grade_level` (`Grade_Level_Id`, `Grade_Level`) VALUES
(1, 'Kinder I'),
(2, 'Kinder II'),
(3, 'Grade 1'),
(4, 'Grade 2'),
(5, 'Grade 3'),
(6, 'Grade 4'),
(7, 'Grade 5'),
(8, 'Grade 6');

-- --------------------------------------------------------

--
-- Table structure for table `grade_level_subjects`
--

CREATE TABLE `grade_level_subjects` (
  `Grade_Level_Subject_Id` int(20) NOT NULL,
  `Grade_Level_Id` int(20) NOT NULL,
  `Subject_Id` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `grade_level_subjects`
--

INSERT INTO `grade_level_subjects` (`Grade_Level_Subject_Id`, `Grade_Level_Id`, `Subject_Id`) VALUES
(1, 1, 1),
(19, 1, 22),
(38, 5, 40),
(59, 1, 47),
(60, 5, 48),
(61, 6, 48),
(68, 2, 49),
(65, 3, 49),
(66, 4, 49),
(67, 5, 49),
(62, 6, 49),
(63, 7, 49),
(64, 8, 49),
(69, 1, 52),
(70, 2, 52),
(71, 3, 52),
(72, 4, 52),
(73, 5, 52),
(74, 6, 52),
(75, 7, 52),
(76, 8, 52),
(77, 1, 53),
(78, 2, 53),
(79, 3, 53),
(80, 4, 53),
(81, 5, 53),
(82, 6, 53),
(83, 7, 53),
(84, 8, 53),
(88, 1, 57),
(89, 2, 57),
(90, 3, 57),
(91, 4, 57),
(92, 5, 57),
(93, 6, 57),
(94, 7, 57),
(95, 8, 57);

-- --------------------------------------------------------

--
-- Table structure for table `locker_files`
--

CREATE TABLE `locker_files` (
  `Locker_File_Id` int(11) NOT NULL,
  `Staff_Id` int(11) NOT NULL,
  `File_Name` varchar(255) NOT NULL,
  `Original_File_Name` varchar(255) NOT NULL,
  `File_Path` varchar(500) NOT NULL,
  `File_Type` varchar(50) NOT NULL,
  `File_Size` int(11) NOT NULL,
  `Description` text DEFAULT NULL,
  `Uploaded_At` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `locker_files`
--

INSERT INTO `locker_files` (`Locker_File_Id`, `Staff_Id`, `File_Name`, `Original_File_Name`, `File_Path`, `File_Type`, `File_Size`, `Description`, `Uploaded_At`) VALUES
(3, 18, '18-1762089159-8e83743903.docx', 'Ethics.docx', '/var/www/html/BackEnd/admin/controller/../../../LockerFiles/2025/18-1762089159-8e83743903.docx', 'Document', 22554, NULL, '2025-11-02 14:12:38'),
(4, 18, '18-1762184092-a02d21e648.pdf', 'Arellano_05LabExer_12.pdf', '/var/www/html/BackEnd/admin/controller/../../../LockerFiles/2025/18-1762184092-a02d21e648.pdf', 'Document', 71896, NULL, '2025-11-03 16:34:52'),
(5, 18, '18-1762441435-4d93469c14.pdf', 'Adormeo_Rhesty_H_IAS_05_TP_1.pdf', '/var/www/html/BackEnd/admin/controllers/../../../LockerFiles/2025/18-1762441435-4d93469c14.pdf', 'Document', 180851, NULL, '2025-11-06 16:03:55'),
(7, 28, '28-1762528975-7e84d37549.png', 'Screenshot 2025-09-16 235611.png', '/var/www/html/BackEnd/teacher/controller/../../../LockerFiles/2025/28-1762528975-7e84d37549.png', 'Image', 233890, NULL, '2025-11-07 16:22:55'),
(8, 28, '28-1762783225-60a0eb159d.jpg', 'science-month.jpg', '/var/www/html/BackEnd/teacher/controllers/../../../LockerFiles/2025/28-1762783225-60a0eb159d.jpg', 'Image', 125116, NULL, '2025-11-10 15:00:26'),
(9, 18, '18-1762943850-463f382c41.jpg', 'Page`1.jpg', '/var/www/html/BackEnd/admin/controllers/../../../LockerFiles/2025/18-1762943850-463f382c41.jpg', 'Image', 190063, NULL, '2025-11-12 11:37:30');

-- --------------------------------------------------------

--
-- Table structure for table `otp_verification`
--

CREATE TABLE `otp_verification` (
  `OTP_ID` int(11) NOT NULL,
  `User_Id` bigint(20) NOT NULL,
  `OTP_Code` varchar(6) NOT NULL,
  `Token` varchar(64) NOT NULL,
  `Expiry_Time` datetime NOT NULL,
  `Is_Used` tinyint(1) DEFAULT 0,
  `Created_At` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `otp_verification`
--

INSERT INTO `otp_verification` (`OTP_ID`, `User_Id`, `OTP_Code`, `Token`, `Expiry_Time`, `Is_Used`, `Created_At`) VALUES
(26, 101, '809612', 'aa2c41b9de8e5fb0ee6035d88ca73cad2e35a7be893c48c14dcbdbc9838310d1', '2025-10-23 03:34:30', 1, '2025-10-23 03:29:29'),
(31, 43, '302134', 'a2395954db52dc56e87a5ce274d0bb3ba4d1f5478a95bd58adbde9b4dfc7ba7b', '2025-11-12 16:40:26', 0, '2025-11-12 17:35:26'),
(45, 37, '129996', '514a396cba43773ec18e0b2e01fb06e63944e8ace56508f465ad251866db32a2', '2025-11-13 17:47:54', 1, '2025-11-13 18:42:26'),
(46, 27, '277080', 'e319d2f61bbf2b5e9c77e41656974ecb5667f5f3b29cf02df5dd7fa9278c237c', '2025-11-13 23:29:33', 1, '2025-11-14 00:24:34');

-- --------------------------------------------------------

--
-- Table structure for table `parent_information`
--

CREATE TABLE `parent_information` (
  `Parent_Id` bigint(20) NOT NULL,
  `First_Name` varchar(50) DEFAULT NULL,
  `Last_Name` varchar(50) DEFAULT NULL,
  `Middle_Name` varchar(50) DEFAULT NULL,
  `Parent_Type` varchar(50) DEFAULT NULL,
  `Educational_Attainment` varchar(100) DEFAULT NULL,
  `Contact_Number` varchar(11) DEFAULT NULL,
  `If_4Ps` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `parent_information`
--

INSERT INTO `parent_information` (`Parent_Id`, `First_Name`, `Last_Name`, `Middle_Name`, `Parent_Type`, `Educational_Attainment`, `Contact_Number`, `If_4Ps`) VALUES
(43, 'Rey', 'Dela Cruz', 'De Vera ', 'Father', 'College', '1345235345', 0),
(44, 'Jeannette', 'Alojado', 'Jimenez', 'Mother', 'College', 'N/A', 0),
(45, 'Aldrin', 'Catubay', 'sdfhsdfg', 'Guardian', 'College', '11111111111', 0),
(172, 'Gerardo', 'David', 'Cayton', 'Father', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal/Bokasyonal', '09231853823', 0),
(173, 'Jeanilyn', 'David', 'Paderes', 'Mother', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal/Bokasyonal', '09231853823', 0),
(174, 'Jeanilyn', 'David', 'Paderes', 'Guardian', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal/Bokasyonal', '09231853823', 0),
(187, 'John', 'David', 'Endura', 'Father', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal/Bokasyonal', '09231853823', 0),
(188, 'Wilma', 'David', 'Paderes', 'Mother', 'Nakapagtapos ng Sekundarya', '09236721783', 0),
(189, 'Paul', 'Lavarez', 'Castillejo', 'Guardian', 'Nakatuntong ng Sekundarya', '09231856457', 0),
(190, 'John', 'David', 'Endura', 'Father', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal/Bokasyonal', '09231853823', 0),
(191, 'Wilma', 'David', 'Paderes', 'Mother', 'Nakapagtapos ng Sekundarya', '09236721783', 0),
(192, 'Paul', 'Lavarez', 'Castillejo', 'Guardian', 'Nakatuntong ng Sekundarya', '09231856457', 0),
(193, 'rey', 'dela cruz', 'de vera', 'Father', 'Hindi Nakapag-aral pero marunong magbasa at magsul', '09354876649', 0),
(194, 'maria', 'dela cruz', 'jumenez', 'Mother', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal/Bokasyonal', '09354876649', 0),
(195, '', '', '', 'Guardian', 'Hindi Nakapag-aral', '', 0),
(196, 'egrtgerg', 'dfsfsc', 'gfrsfd', 'Father', 'Hindi Nakapag-aral', '', 0),
(197, 'rtuyhrthru', 'hjruyhhj', 'hrthrt', 'Mother', 'Hindi Nakapag-aral', '09453876649', 0),
(198, '', '', '', 'Guardian', 'Hindi Nakapag-aral', '', 0),
(199, 'Nemesio', 'Llorin', 'Sabile', 'Father', 'Nakapagtapos ng Sekundarya', '09123456789', 0),
(200, 'Adelaida', 'Llorin', 'Sabile', 'Mother', 'Nakapagtapos ng Sekundarya', '09123456789', 0),
(201, 'Jane', 'Alojado', 'Dela Cruz', 'Guardian', 'Hindi Nakapag-aral', '09123456789', 0),
(202, 'Nemesio', 'Llorin', 'Sabile', 'Father', 'Nakapagtapos ng Sekundarya', '09123456789', 0),
(203, 'Adelaida', 'Llorin', 'Sabile', 'Mother', 'Nakapagtapos ng Sekundarya', '09123456789', 0),
(204, 'Jane', 'Alojado', 'Dela Cruz', 'Guardian', 'Hindi Nakapag-aral', '09123456789', 0),
(205, 'Andrei', 'Llorin', 'De Vera', 'Father', 'Nakapagtapos ng Sekundarya', '09231853823', 0),
(206, ' Maria', 'Llorin', 'De Vera', 'Mother', 'Nakapagtapos ng Sekundarya', '09236721783', 0),
(207, 'Paul', 'Lavarez', 'Jimenez', 'Guardian', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal/Bokasyonal', '09123456789', 0),
(208, 'Andrei', 'Llorin', 'De Vera', 'Father', 'Nakapagtapos ng Sekundarya', '09231853823', 0),
(209, ' Maria', 'Llorin', 'De Vera', 'Mother', 'Nakapagtapos ng Sekundarya', '09236721783', 0),
(210, 'Paul', 'Lavarez', 'Jimenez', 'Guardian', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal/Bokasyonal', '09123456789', 0),
(211, 'Gerardo', 'David', 'Cayton', 'Father', 'Nakatuntong ng Sekundarya', '09354876649', 0),
(212, 'Wilma', 'David', 'Paderes', 'Mother', 'Hindi Nakapag-aral pero marunong magbasa at magsul', '09354876649', 0),
(213, 'Jeanilyn', 'Lavarez', 'Castillejo', 'Guardian', 'Hindi Nakapag-aral', '09354876649', 0),
(220, 'Gerardo', 'David', 'Cayton', 'Father', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal/Bokasyonal', '09354876649', 0),
(221, 'Wilma', 'David', 'Paderes', 'Mother', 'Nakapagtapos ng Sekundarya', '09236721783', 0),
(222, 'Leonard Venci', 'Yap', 'Oficiar', 'Guardian', 'Nakapagtapos ng Sekundarya', '09123456789', 0),
(223, 'Gerardo', 'David', 'Cayton', 'Father', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal/Bokasyonal', '09354876649', 0),
(224, 'Wilma', 'David', 'Paderes', 'Mother', 'Nakapagtapos ng Sekundarya', '09236721783', 0),
(225, 'Leonard Venci', 'Yap', 'Oficiar', 'Guardian', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal/Bokasyonal', '09123456789', 0),
(238, 'Andrei', 'Garcia', 'Endura', 'Father', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal /Bokasyonal', '09354876649', 0),
(239, 'Wilma', 'Garcia', 'Salico', 'Mother', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal /Bokasyonal', '09212183045', 0),
(240, 'Wilma', 'Garcia', 'Salico', 'Guardian', 'Nakapagtapos ng Sekundarya', '09231856457', 0),
(241, 'Andrei', 'Llorin', 'Endura', 'Father', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal/Bokasyonal', '09354876649', 0),
(242, 'Wilma', 'Llorin', 'Paderes', 'Mother', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal/Bokasyonal', '09231853823', 0),
(243, 'Wilma', 'Llorin', 'Paderes', 'Guardian', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal/Bokasyonal', '09231853823', 0),
(244, 'Rey', 'Iglesia', 'De Vera', 'Father', 'Nakapagtapos ng Sekundarya', '09615644621', 0),
(245, 'Anna', 'Iglesia', 'Jimenez', 'Mother', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal /Bokasyonal', '09354876649', 0),
(246, 'Anna', 'Iglesia', 'Jimenez', 'Guardian', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal /Bokasyonal', '09354876649', 0),
(247, 'Gerardo', 'David', 'Cayton', 'Father', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal /Bokasyonal', '09231853823', 0),
(248, 'Jeanilyn', 'David', 'Paderes', 'Mother', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal /Bokasyonal', '09231853823', 0),
(249, 'Jeanilyn', 'David', 'Paderes', 'Guardian', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal /Bokasyonal', '09231853823', 0),
(250, 'Rey', 'Garcia', 'De Vera', 'Father', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal /Bokasyonal', '09615644621', 0),
(251, 'Wilma', 'Garcia', 'Endura', 'Mother', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal /Bokasyonal', '09212183045', 0),
(252, 'Rey', 'Garcia', 'De Vera', 'Guardian', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal /Bokasyonal', '09615644621', 0),
(253, 'Ramon', 'Villa Berde', 'Rizal', 'Father', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal /Bokasyonal', '09427642975', 0),
(254, 'Maris', 'Villa Berde', 'Caloocan', 'Mother', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal /Bokasyonal', '09817482648', 0),
(255, 'Ramon', 'Villa Berde', 'Rizal', 'Guardian', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal /Bokasyonal', '09427642975', 0),
(256, 'Benjamin', 'San Juan', 'Cainta', 'Father', 'Nakapagtapos ng Sekundarya', '09123419481', 0),
(257, 'Rose', 'San Juan', 'Capiz', 'Mother', 'Nakapagtapos ng Elementarya', '09123123423', 0),
(258, 'Benjamin', 'San Juan', 'Cainta', 'Guardian', 'Nakapagtapos ng Sekundarya', '09123419481', 0),
(259, 'Jefferson', 'Rodriguez', 'De Vera', 'Father', 'Nakapagtapos ng Sekundarya', '09918726473', 0),
(260, 'Jeannette', 'Rodriguez', 'Co', 'Mother', 'Nakatuntong ng Sekundarya', '09346312562', 0),
(261, 'Jefferson', 'Rodriguez', 'De Vera', 'Guardian', 'Nakapagtapos ng Sekundarya', '09918726473', 0),
(277, 'None', 'None', 'None', 'Father', 'Hindi Nakapag-aral', '09000000000', 0),
(278, 'Ai', 'Llorin', 'Solina', 'Mother', 'Nakapagtapos ng Sekundarya', '09123456789', 0),
(279, 'Adelaida', 'Llorin', 'Solina', 'Guardian', 'Nakapagtapos ng Sekundarya', '09123456789', 0),
(289, 'Danneth', 'Alojarado', 'Sabile', 'Father', 'Nakapagtapos ng Elementarya', '09000000012', 1),
(290, 'Mina', 'Alojarado', 'Sabile', 'Mother', 'Nakatuntong ng Sekundarya', '09222222212', 1),
(291, 'Danuel', 'Alojarado', 'Sabile', 'Guardian', 'Nakapagtapos ng Sekundarya', '09111234567', 1),
(295, 'Bing', 'Wei', 'Ching', 'Father', 'Nakapagtapos ng Sekundarya', '09111155555', 0),
(296, 'Ning', 'Wei', 'Ching', 'Mother', 'Nakapagtapos ng Sekundarya', '09121212121', 0),
(297, 'Xing', 'Wei', 'Ching', 'Guardian', 'Nakatuntong ng Sekundarya', '09555667899', 0),
(298, 'Moe', 'Royst', 'Habibi', 'Father', 'Nakapagtapos ng Sekundarya', '09231298765', 0),
(299, 'Jhoanna', 'Royst', 'Habibi', 'Mother', 'Nakapagtapos ng Sekundarya', '09223322334', 0),
(300, 'Miguel', 'Royst', 'Habibi', 'Guardian', 'Nakatuntong ng Sekundarya', '09987655321', 0),
(310, 'Roberto', 'Tan', 'Carezo', 'Father', 'Nakapagtapos ng Sekundarya', '09111145678', 0),
(311, 'Mary Rose', 'Tan', 'Carezo', 'Mother', 'Nakapagtapos ng Sekundarya', '09092223456', 0),
(312, 'Loise', 'Tan', 'Erezo', 'Guardian', 'Nakapagtapos ng Sekundarya', '09988765432', 0),
(313, 'Danuel', 'Santo', 'Gold', 'Father', 'Nakapagtapos ng Sekundarya', '09098765432', 0),
(314, 'Joy', 'Santo', 'Gold', 'Mother', 'Nakapagtapos ng Sekundarya', '09543219876', 0),
(315, 'Rick', 'Gold', 'Grimes', 'Guardian', 'Nakapagtapos ng Sekundarya', '09231231897', 0),
(322, 'Rey', 'De Vera', 'Balmeo', 'Father', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal /Bokasyonal', '09231853823', 0),
(323, 'Maria', 'De Vera', 'Balmeo', 'Mother', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal /Bokasyonal', '09212183045', 0),
(324, 'Maria', 'De Vera', 'Balmeo', 'Guardian', 'Hindi Nakapag-aral', '09212183045', 0),
(325, 'Marco', 'Ibarra', 'Daniel', 'Father', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal /Bokasyonal', '09354876649', 0),
(326, 'Wilma', 'Ibarra', 'Daniel', 'Mother', 'Hindi Nakapag-aral', '09236721783', 0),
(327, 'Marco', 'Ibarra', 'Daniel', 'Guardian', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal /Bokasyonal', '09354876649', 0),
(328, 'Qwe', 'Qwe', 'Qwe', 'Father', 'Hindi Nakapag-aral', '09121212121', 0),
(329, 'Qwe', 'Qwe', 'Qwe', 'Mother', 'Hindi Nakapag-aral', '09121212121', 0),
(330, 'Qwe', 'Qwe', 'Qwe', 'Guardian', 'Hindi Nakapag-aral', '09121212121', 0),
(331, 'Wqee', 'Wqee', 'Wqee', 'Father', 'Hindi Nakapag-aral', '09121212121', 0),
(332, 'Wqee', 'Wqee', 'Wqee', 'Mother', 'Hindi Nakapag-aral', '09121212121', 0),
(333, 'Wqee', 'Wqee', 'Wqee', 'Guardian', 'Hindi Nakapag-aral', '09121212121', 0),
(334, 'John', 'Dela Cruz', 'Rizal', 'Father', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal /Bokasyonal', '09243523452', 0),
(335, 'Rose', 'San Juan', 'Capiz', 'Mother', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal /Bokasyonal', '09345623463', 0),
(336, 'John', 'Dela Cruz', 'Rizal', 'Guardian', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal /Bokasyonal', '09243523452', 0),
(337, 'Marcus', 'Rodriguez', 'Escanilla', 'Father', 'Nakapagtapos ng Sekundarya', '09621476112', 0),
(338, 'Jean', 'Rodriguez', 'Capistrano', 'Mother', 'Nakatuntong ng Sekundarya', '09735467363', 0),
(339, 'Marcus', 'Rodriguez', 'Escanilla', 'Guardian', 'Nakatuntong ng Sekundarya', '09621476112', 0),
(340, 'Kenneth', 'Dela Cruz', 'Jimenez', 'Father', 'Nakapagtapos ng Elementarya', '09356345634', 0),
(341, 'Rose', 'Villa Berde', 'Capiz', 'Mother', 'Nakapagtapos ng Sekundarya', '09345634563', 0),
(342, 'Kenneth', 'Dela Cruz', 'Jimenez', 'Guardian', 'Nakatuntong ng Elementarya', '09356345634', 0),
(343, 'Donny', 'Balmes', NULL, 'Father', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal /Bokasyonal', '09125467892', 0),
(344, 'Jeanne', 'Balmes', NULL, 'Mother', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal /Bokasyonal', '09468792173', 0),
(345, 'Jeanne', 'Balmes', NULL, 'Guardian', 'Hindi Nakapag-aral', '09468792173', 0),
(346, 'Domingo', 'Amortizado', 'Canillo', 'Father', 'Nakapagtapos ng Sekundarya', '09128769283', 0),
(347, 'Vernadeth', 'Pecho', 'Razza', 'Mother', 'Nakapagtapos ng Sekundarya', '09128769283', 0),
(348, 'Domingo', 'Amortizado', 'Canillo', 'Guardian', 'Nakatuntong ng Sekundarya', '09128769283', 0),
(349, 'Domingo', 'Amortizado', 'Canillo', 'Father', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal /Bokasyonal', '09128769283', 0),
(350, 'Vernadeth', 'Pecho', 'Razza', 'Mother', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal /Bokasyonal', '09128769283', 0),
(351, 'Irene ', 'Yap', 'Porte', 'Guardian', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal /Bokasyonal', '09128769283', 0),
(352, 'JP', 'Abril', 'Alojado', 'Father', 'Nakapagtapos ng Sekundarya', '09876543211', 0),
(353, 'Vernadeth', 'Alojado', 'Razza', 'Mother', 'Nakapagtapos ng Sekundarya', '09987654321', 0),
(354, 'Jp', 'Abril', 'Alojado', 'Guardian', 'Nakapagtapos ng Sekundarya', '09876543211', 0),
(355, 'Matibag', 'Villa Berde', 'Anthony', 'Father', 'Nakapagtapos ng Sekundarya', '09435634634', 0),
(356, 'Dichoso', 'Villa Berde', 'Kendra', 'Mother', 'Nakapagtapos ng Sekundarya', '09519237821', 0),
(357, 'Matibag', 'Villa Berde', 'Anthony', 'Guardian', 'Nakapagtapos ng Sekundarya', '09435634634', 0),
(358, 'Senna', 'Teody', 'Manuba', 'Father', 'Nakapagtapos ng Sekundarya', '09128769283', 0),
(359, 'Ricamata', 'Magielyn', 'Luisaga', 'Mother', 'Nakapagtapos ng Sekundarya', '09128769283', 0),
(360, 'Ricamata', 'Magielyn', 'Luisaga', 'Guardian', 'Nakapagtapos ng Sekundarya', '09128769283', 0),
(361, 'Michael', 'Aldovino', NULL, 'Father', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal /Bokasyonal', '09123455789', 0),
(362, 'Catherine Anne', 'Ledesma', 'Montemayor', 'Mother', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal /Bokasyonal', '09123456789', 0),
(363, 'Michael', 'Aldovino', NULL, 'Guardian', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal /Bokasyonal', '09123456789', 0),
(364, 'Mark Anthony', 'Castellano', 'Candelaria', 'Father', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal /Bokasyonal', '09123456789', 0),
(365, 'Daisy', 'Dalugdog', 'Zubia', 'Mother', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal /Bokasyonal', '09123456789', 0),
(366, 'Daisy', 'Dalugdog', 'Zubia', 'Guardian', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal /Bokasyonal', '09123456789', 0),
(367, 'Erick', 'Dela Rosa', 'Panogalen', 'Father', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal /Bokasyonal', '09123456789', 0),
(368, 'Angelica', 'Llona', 'Merle', 'Mother', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal /Bokasyonal', '09123456789', 0),
(369, 'Angelica', 'Llona', 'Merle', 'Guardian', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal /Bokasyonal', '09123456789', 0),
(370, 'Antonio', 'Ferrer', 'Estanislao', 'Father', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal /Bokasyonal', '09123456789', 0),
(371, 'Prelly', 'Quinto', 'Lavarez', 'Mother', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal /Bokasyonal', '09123456789', 0),
(372, 'Antonio', 'Ferrer', 'Estanislao', 'Guardian', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal /Bokasyonal', '09123456789', 0),
(373, 'Gerald', 'Hernandez', 'De Gala', 'Father', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal /Bokasyonal', '09123456789', 0),
(374, 'Ma Cristina', 'Levelo', 'Barcelona', 'Mother', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal /Bokasyonal', '09123456789', 0),
(375, 'Gerald', 'Hernandez', 'De Gala', 'Guardian', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal /Bokasyonal', '09123456789', 0),
(376, 'Kevin', 'Aldovino', 'Palermo', 'Father', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal /Bokasyonal', '09123456789', 0),
(377, 'Ella Rae', 'Pelaez', 'Olveda', 'Mother', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal /Bokasyonal', '09123456789', 0),
(378, 'Ella Rae', 'Pelaez', 'Olveda', 'Guardian', 'Hindi Nakapag-aral', '09123456789', 0),
(379, 'Enrico', 'Catamio', 'Recorhemoso', 'Father', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal /Bokasyonal', '09123456789', 0),
(380, 'Mary Grace', 'Magnaye', 'Basilio', 'Mother', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal /Bokasyonal', '09123456789', 0),
(381, 'Mary Grace', 'Magnaye', 'Basilio', 'Guardian', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal /Bokasyonal', '09123456789', 0),
(382, 'Christopher', 'Ilagan', 'Contreras', 'Father', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal /Bokasyonal', '09123456789', 0),
(383, 'Maricon', 'Cortez', 'Rivadenera', 'Mother', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal /Bokasyonal', '09123456789', 0),
(384, 'Maricon', 'Cortez', 'Rivadenera', 'Guardian', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal /Bokasyonal', '09123456789', 0),
(385, 'Jonel', 'Bataller', 'Azaña', 'Father', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal /Bokasyonal', '09123456789', 0),
(386, 'Sherry May', 'De Vega', 'Herico', 'Mother', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal /Bokasyonal', '09123456789', 0),
(387, 'Jonel', 'Bataller', 'Azaña', 'Guardian', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal /Bokasyonal', '09123456789', 0),
(388, 'Abel', 'Guelas', 'Jalimao Jr.', 'Father', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal /Bokasyonal', '09123456789', 0),
(389, 'Anna Lorisse', 'De Vega', 'Herico', 'Mother', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal /Bokasyonal', '09123456789', 0),
(390, 'Abel', 'Guelas', 'Jalimao Jr.', 'Guardian', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal /Bokasyonal', '09123456789', 0),
(391, 'Lloyd', 'Aductante', 'Ignacio', 'Father', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal /Bokasyonal', '09123456789', 0),
(392, 'Babylyn', 'De Los Reyes', 'Juliano', 'Mother', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal /Bokasyonal', '09123456789', 0),
(393, 'Lloyd', 'Aductante', 'Ignacio', 'Guardian', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal /Bokasyonal', '09123456789', 0),
(394, 'Jerbi', 'Fornea', 'Rotarla', 'Father', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal /Bokasyonal', '09123456789', 0),
(395, 'Jessa', 'Jaqueca', NULL, 'Mother', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal /Bokasyonal', '09123456789', 0),
(396, 'Jessa', 'Jaqueca', NULL, 'Guardian', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal /Bokasyonal', '09123456789', 0),
(397, 'Roeil', 'Marquez', 'Duka', 'Father', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal /Bokasyonal', '09123456789', 0),
(398, 'Mary Joy', 'Amantillo', 'Rondina', 'Mother', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal /Bokasyonal', '09123456789', 0),
(399, 'Roeil', 'Marquez', 'Duka', 'Guardian', 'Hindi Nakapag-aral', '09123456789', 0),
(400, 'McFlorence', 'Ormacido', 'Asia', 'Father', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal /Bokasyonal', '09123456789', 0),
(401, 'Lessa', 'Amante', 'Aguirre', 'Mother', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal /Bokasyonal', '09123456789', 0),
(402, 'Lessa', 'Amante', 'Aguirre', 'Guardian', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal /Bokasyonal', '09123456789', 0),
(403, 'Helmer', 'Ortiz', 'Roqueza', 'Father', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal /Bokasyonal', '09123456789', 0),
(404, 'Ginalyn', 'Perlas', 'Manuba', 'Mother', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal /Bokasyonal', '09123456789', 0),
(405, 'Helmer', 'Ortiz', 'Roqueza', 'Guardian', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal /Bokasyonal', '09123456789', 0),
(406, 'Zaimon', 'Amante', 'Golde', 'Father', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal /Bokasyonal', '09123456789', 0),
(407, 'Rose', 'Pantoja', 'Lincallo', 'Mother', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal /Bokasyonal', '09123456789', 0),
(408, 'Rose', 'Pantoja', 'Lincallo', 'Guardian', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal /Bokasyonal', '09123456789', 0),
(409, 'Lynsons', 'Llona', 'Merle', 'Father', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal /Bokasyonal', '09615644621', 0),
(410, 'Etheliana', 'Apit', 'Campopos', 'Mother', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal /Bokasyonal', '09236721783', 0),
(411, 'Etheliana', 'Apit', 'Campopos', 'Guardian', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal /Bokasyonal', '09231856457', 0),
(412, 'Cabuya', 'Palermo', 'Levin', 'Father', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal /Bokasyonal', '09054634563', 0),
(413, 'Palma', 'Dela Cruz', 'Sharon', 'Mother', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal /Bokasyonal', '09868723423', 0),
(414, 'Cabuya', 'Palermo', 'Levin', 'Guardian', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal /Bokasyonal', '09054634563', 0),
(415, 'Kulawañ', 'Lingo-Lingo', 'Duldulao', 'Father', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal /Bokasyonal', '09125467892', 0),
(416, 'Wilma', 'Lingo-Lingo', 'Duldulao', 'Mother', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal /Bokasyonal', '09468792173', 0),
(417, 'Wilma', 'Lingo-Lingo', 'Duldulao ', 'Guardian', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal /Bokasyonal', '09468792173', 0),
(418, 'NA', 'Na', 'Na', 'Father', 'Hindi Nakapag-aral', '09000000000', 0),
(419, 'Na', 'Na', 'Na', 'Mother', 'Hindi Nakapag-aral', '09000000000', 0),
(420, 'Nemesio', 'Llorin', 'Sabile', 'Guardian', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal /Bokasyonal', '09123456789', 0),
(421, 'NA', 'Na', 'Na', 'Father', 'Hindi Nakapag-aral', '09000000000', 0),
(422, 'Na', 'Na', 'Na', 'Mother', 'Hindi Nakapag-aral', '09000000000', 0),
(423, 'Nemesio', 'Llorin', 'Sabile', 'Guardian', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal /Bokasyonal', '09123456789', 0),
(424, 'John', 'San Juan', 'Rumueldo', 'Father', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal /Bokasyonal', '09435643563', 0),
(425, 'Rose', 'Amarillio', 'Jimenez', 'Mother', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal /Bokasyonal', '09345324523', 0),
(426, 'John', 'San Juan', 'Rumueldo', 'Guardian', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal /Bokasyonal', '09435643563', 0),
(427, 'Jason', 'Dela Cruz', 'Riano', 'Father', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal /Bokasyonal', '09435643563', 1),
(428, 'Alexis', 'Dela Cruz', 'Capistrano', 'Mother', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal /Bokasyonal', '09923423452', 1),
(429, 'John Andrew', 'Dela Cruz', 'Jimenez ', 'Guardian', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal /Bokasyonal', '09123419481', 1),
(430, 'Jason', 'Dela Cruz', 'Riano', 'Father', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal /Bokasyonal', '09435643563', 1),
(431, 'Alexis', 'Dela Cruz', 'Capistrano', 'Mother', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal /Bokasyonal', '09923423452', 1),
(432, 'John Andrew', 'Dela Cruz', 'Jimenez ', 'Guardian', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal /Bokasyonal', '09123419481', 1),
(433, 'Kenneth', 'Villa Berde', 'Rumueldo', 'Father', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal /Bokasyonal', '09123419481', 0),
(434, 'Jeannette', 'Villa Berde', 'Jimenez', 'Mother', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal /Bokasyonal', '09345634563', 0),
(435, 'John', 'Dela Cruz', 'Jimenez', 'Guardian', 'Nakatuntong ng Sekundarya', '09346312562', 0),
(436, 'Benjamin', 'Alojado', 'De Vera ', 'Father', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal /Bokasyonal', '09435643563', 0),
(437, 'Maris', 'Alojado', 'Jimenez', 'Mother', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal /Bokasyonal', '09345634563', 0),
(438, 'John', 'Alojado', 'De Vera ', 'Guardian', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal /Bokasyonal', '09234523452', 0),
(441, 'John', 'San Victores', 'Jimenez', 'Guardian', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal /Bokasyonal', '09123419481', 0),
(442, 'Jamella', 'San Victores', 'Escanilla', 'Guardian', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal /Bokasyonal', '09123419481', 0),
(443, 'John Andrew', 'San Victores', 'Jimenez', 'Guardian', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal /Bokasyonal', '09123419481', 0),
(444, 'Kenneth', 'Dela Cruz', 'Jimenez ', 'Guardian', 'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal /Bokasyonal', '09123419481', 0),
(445, 'John Andrew', 'San Victores', 'Jimenez', 'Guardian', 'Hindi Nakapag-aral', '09918726473', 0),
(446, 'John Andrew', 'Dela Cruz', 'Jimenez ', 'Guardian', 'Hindi Nakapag-aral', '09918726473', 0);

-- --------------------------------------------------------

--
-- Table structure for table `profile_directory`
--

CREATE TABLE `profile_directory` (
  `Profile_Picture_Id` int(11) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `directory` varchar(255) NOT NULL,
  `Timestamp` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `profile_directory`
--

INSERT INTO `profile_directory` (`Profile_Picture_Id`, `file_name`, `directory`, `Timestamp`) VALUES
(1, 'staff_18_1762542097.jpg', '/ImageUploads/profile_pictures/', '2025-10-29'),
(2, 'staff_28_1761750189.png', '/ImageUploads/profile_pictures/', '2025-10-29');

-- --------------------------------------------------------

--
-- Table structure for table `Psa_directory`
--

CREATE TABLE `Psa_directory` (
  `Psa_Image_Id` int(11) NOT NULL,
  `filename` varchar(255) DEFAULT NULL,
  `directory` varchar(255) DEFAULT NULL,
  `Uploaded_At` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Psa_directory`
--

INSERT INTO `Psa_directory` (`Psa_Image_Id`, `filename`, `directory`, `Uploaded_At`) VALUES
(1, '09c37da0-aca9-418a-85b4-aa01ed64a14d.jpg', '../../../ImageUploads/2025/09c37da0-aca9-418a-85b4-aa01ed64a14d.jpg', '2025-04-25 17:45:57'),
(2, '09c37da0-aca9-418a-85b4-aa01ed64a14d.jpg', '../../../ImageUploads/2025/09c37da0-aca9-418a-85b4-aa01ed64a14d.jpg', '2025-05-02 07:29:02'),
(3, '17-1746197167-16a1c2d9d6.jpg', '../../../ImageUploads/2025/17-1746197167-16a1c2d9d6.jpg', '2025-05-02 14:46:10'),
(4, '17-1746201828-088681d139.png', '../../../ImageUploads/2025/17-1746201828-088681d139.png', '2025-05-02 16:03:50'),
(5, '17-1746202677-6ad92694b5.png', '../../../ImageUploads/2025/17-1746202677-6ad92694b5.png', '2025-05-02 16:17:59'),
(6, '17-1746203825-bc4b30e44a.png', '../../../ImageUploads/2025/17-1746203825-bc4b30e44a.png', '2025-05-02 16:37:07'),
(8, '-1747570623-ebceb47633.png', '../../../ImageUploads/2025/-1747570623-ebceb47633.png', '2025-05-18 12:17:06'),
(9, '-1747576035-be0946a013.png', '../../../ImageUploads/2025/-1747576035-be0946a013.png', '2025-05-18 13:47:18'),
(11, '22-1747622036-56cb8ec3c7.png', '../../../ImageUploads/2025/22-1747622036-56cb8ec3c7.png', '2025-05-19 02:33:59'),
(12, '22-1747624407-7e031fb46b.png', '../../../ImageUploads/2025/22-1747624407-7e031fb46b.png', '2025-05-19 03:13:30'),
(15, '22-1747625433-5daec6e147.png', '../../../ImageUploads/2025/22-1747625433-5daec6e147.png', '2025-05-19 03:30:36'),
(16, '22-1747626563-c5dadbc386.png', '../../../ImageUploads/2025/22-1747626563-c5dadbc386.png', '2025-05-19 03:49:25'),
(17, '22-1747626568-1d5993361e.png', '../../../ImageUploads/2025/22-1747626568-1d5993361e.png', '2025-05-19 03:49:31'),
(18, '69-1747919330-128f62cd3d.png', '../../../ImageUploads/2025/69-1747919330-128f62cd3d.png', '2025-05-22 13:08:52'),
(19, '69-1747921937-00c3ea070e.png', '../../../ImageUploads/2025/69-1747921937-00c3ea070e.png', '2025-05-22 13:52:19'),
(20, '69-1747959092-ce62309b91.png', '../../../ImageUploads/2025/69-1747959092-ce62309b91.png', '2025-05-23 00:11:36'),
(21, '69-1747959099-4462c0ba07.png', '../../../ImageUploads/2025/69-1747959099-4462c0ba07.png', '2025-05-23 00:11:43'),
(22, '69-1747970911-ca852c953b.png', '../../../ImageUploads/2025/69-1747970911-ca852c953b.png', '2025-05-23 03:28:34'),
(23, '69-1747970918-76679a7a10.png', '../../../ImageUploads/2025/69-1747970918-76679a7a10.png', '2025-05-23 03:28:42'),
(24, '93-1747978858-7d5c9c9388.png', '../../../ImageUploads/2025/93-1747978858-7d5c9c9388.png', '2025-05-23 05:41:01'),
(25, '93-1747978863-1e140647ef.png', '../../../ImageUploads/2025/93-1747978863-1e140647ef.png', '2025-05-23 05:41:05'),
(27, '17-1748268344-4c215d8e31.png', '../../../ImageUploads/2025/17-1748268344-4c215d8e31.png', '2025-05-26 14:05:48'),
(28, '17-1762705647-c5afcbc1aa.jpg', '../../../ImageUploads/2025/17-1762705647-c5afcbc1aa.jpg', '2025-05-26 14:09:12'),
(33, '17-1749447478-003539429d.jpg', '../../../ImageUploads/2025/17-1749447478-003539429d.jpg', '2025-06-09 05:38:02'),
(34, '17-1760951791-34acef0c4e.png', '../../../ImageUploads/2025/17-1760951791-34acef0c4e.png', '2025-06-26 15:29:54'),
(35, '22-1751114815-07563476f9.png', '../../../ImageUploads/2025/22-1751114815-07563476f9.png', '2025-06-28 12:46:58'),
(36, '22-1753899725-ac1f27ebb8.png', '../../../ImageUploads/2025/22-1753899725-ac1f27ebb8.png', '2025-07-30 18:22:07'),
(37, '22-1754058305-8f7b874ca5.png', '../../../ImageUploads/2025/22-1754058305-8f7b874ca5.png', '2025-08-01 14:25:08'),
(38, '17-1757606790-35d003ba83.png', '../../../ImageUploads/2025/17-1757606790-35d003ba83.png', '2025-09-11 16:06:33'),
(39, '17-1758265834-b47513e38d.png', '../../../ImageUploads/2025/17-1758265834-b47513e38d.png', '2025-09-19 07:10:40'),
(40, '17-1758267348-d4259f7026.png', '../../../ImageUploads/2025/17-1758267348-d4259f7026.png', '2025-09-19 07:35:54'),
(45, '22-1761567332-b7ca827925.png', '../../../ImageUploads/2025/22-1761567332-b7ca827925.png', '2025-10-27 12:15:35'),
(49, '22-1761741526-0fcfe0a44f.png', '../../../ImageUploads/2025/22-1761741526-0fcfe0a44f.png', '2025-10-29 12:38:48'),
(51, '22-1761841329-fa0569e9c4.png', '../../../ImageUploads/2025/22-1761841329-fa0569e9c4.png', '2025-10-30 16:22:11'),
(52, '22-1761935376-7602612bfe.png', '../../../ImageUploads/2025/22-1761935376-7602612bfe.png', '2025-10-31 18:29:38'),
(56, '22-1762699686-769ce9c38b.jpg', 'C:\\xampp\\htdocs\\SSISv2.1\\BackEnd\\api\\user/../../../ImageUploads/2025/22-1762699686-769ce9c38b.jpg', '2025-11-09 14:48:08'),
(57, '22-1762702277-3c4480fffd.png', 'C:\\xampp\\htdocs\\SSISv2.1\\BackEnd\\api\\user/../../../ImageUploads/2025/22-1762702277-3c4480fffd.png', '2025-11-09 15:31:19'),
(59, '17-1762773891-3f20b4ea79.png', '../../../ImageUploads/2025/17-1762773891-3f20b4ea79.png', '2025-11-10 11:24:54'),
(60, '22-1762859451-d0e70bda93.jpg', '../../../ImageUploads/2025/22-1762859451-d0e70bda93.jpg', '2025-11-11 11:10:54'),
(61, '17-1762876228-f4b6448210.png', '../../../ImageUploads/2025/17-1762876228-f4b6448210.png', '2025-11-11 15:50:31'),
(62, '17-1762880696-61aab45cb1.png', '../../../ImageUploads/2025/17-1762880696-61aab45cb1.png', '2025-11-11 17:05:00'),
(63, '17-1762881118-6c9dbc7f87.jpg', '../../../ImageUploads/2025/17-1762881118-6c9dbc7f87.jpg', '2025-11-11 17:12:00'),
(64, '0-1762889375-98ad772573.jpg', '../../../ImageUploads/2025/0-1762889375-98ad772573.jpg', '2025-11-11 19:29:38'),
(65, '0-1762889627-e3cb2e2080.png', '../../../ImageUploads/2025/0-1762889627-e3cb2e2080.png', '2025-11-11 19:33:49'),
(66, '17-1762904978-1773915922.jpeg', '../../../ImageUploads/2025/17-1762904978-1773915922.jpeg', '2025-11-11 23:49:41'),
(67, '22-1762919717-239a795fd4.jpg', '../../../ImageUploads/2025/22-1762919717-239a795fd4.jpg', '2025-11-12 03:55:22'),
(68, '22-1762920227-7163f7090c.jpg', '../../../ImageUploads/2025/22-1762920227-7163f7090c.jpg', '2025-11-12 04:03:51'),
(69, '132-1762942206-040ffbeae6.jpg', '../../../ImageUploads/2025/132-1762942206-040ffbeae6.jpg', '2025-11-12 09:41:51'),
(70, '17-1762971571-a804a2048b.jpg', '../../../ImageUploads/2025/17-1762971571-a804a2048b.jpg', '2025-11-12 18:19:33'),
(71, '17-1762972219-2f7e09c74e.jpg', '../../../ImageUploads/2025/17-1762972219-2f7e09c74e.jpg', '2025-11-12 18:30:21'),
(72, '17-1763041406-d567aa1054.jpeg', '../../../ImageUploads/2025/17-1763041406-d567aa1054.jpeg', '2025-11-13 13:43:00'),
(73, '17-1763042744-ac55836593.png', '../../../ImageUploads/2025/17-1763042744-ac55836593.png', '2025-11-13 14:05:18'),
(74, '17-1763042948-075c4e266f.png', '../../../ImageUploads/2025/17-1763042948-075c4e266f.png', '2025-11-13 14:08:42'),
(75, '17-1763043141-7f1d8afa11.png', '../../../ImageUploads/2025/17-1763043141-7f1d8afa11.png', '2025-11-13 14:11:55'),
(76, '17-1763043654-6271107698.png', '../../../ImageUploads/2025/17-1763043654-6271107698.png', '2025-11-13 14:20:28'),
(77, '17-1763043836-fab4f3f10b.png', '../../../ImageUploads/2025/17-1763043836-fab4f3f10b.png', '2025-11-13 14:23:30'),
(78, '17-1763043986-2ae07caece.png', '../../../ImageUploads/2025/17-1763043986-2ae07caece.png', '2025-11-13 14:25:59'),
(79, '17-1763044438-38b367a485.png', '../../../ImageUploads/2025/17-1763044438-38b367a485.png', '2025-11-13 14:33:31'),
(80, '17-1763045301-6e4390a883.png', '../../../ImageUploads/2025/17-1763045301-6e4390a883.png', '2025-11-13 14:47:55'),
(81, '17-1763045700-c286721f35.png', '../../../ImageUploads/2025/17-1763045700-c286721f35.png', '2025-11-13 14:54:34'),
(82, '17-1763046162-525ffcc6d0.png', '../../../ImageUploads/2025/17-1763046162-525ffcc6d0.png', '2025-11-13 15:02:16'),
(83, '17-1763046743-d53dbb56b0.png', '../../../ImageUploads/2025/17-1763046743-d53dbb56b0.png', '2025-11-13 15:11:57'),
(84, '17-1763046892-bef2f765b7.png', '../../../ImageUploads/2025/17-1763046892-bef2f765b7.png', '2025-11-13 15:14:26'),
(85, '17-1763047541-d8ef8372ba.png', '../../../ImageUploads/2025/17-1763047541-d8ef8372ba.png', '2025-11-13 15:25:15'),
(86, '17-1763047768-0eac127515.png', '../../../ImageUploads/2025/17-1763047768-0eac127515.png', '2025-11-13 15:29:02'),
(87, '17-1763047983-688fa550c5.png', '../../../ImageUploads/2025/17-1763047983-688fa550c5.png', '2025-11-13 15:32:37'),
(88, '0-1763052263-b125f18393.jpg', '../../../ImageUploads/2025/0-1763052263-b125f18393.jpg', '2025-11-13 16:44:29'),
(89, '0-1763058887-eb8240aa4e.jpg', '../../../ImageUploads/2025/0-1763058887-eb8240aa4e.jpg', '2025-11-13 18:34:21'),
(90, '17-1763068802-7ed2fc30bb.jpeg', '../../../ImageUploads/2025/17-1763068802-7ed2fc30bb.jpeg', '2025-11-13 21:19:36'),
(91, '143-1763093573-69ed145d64.png', '../../../ImageUploads/2025/143-1763093573-69ed145d64.png', '2025-11-14 04:12:56'),
(92, '143-1763093713-fece766eba.png', '../../../ImageUploads/2025/143-1763093713-fece766eba.png', '2025-11-14 04:15:15'),
(93, '17-1763204841-e636594547.png', '../../../ImageUploads/2025/17-1763204841-e636594547.png', '2025-11-15 11:07:24'),
(94, '17-1763207766-5010db640e.png', '../../../ImageUploads/2025/17-1763207766-5010db640e.png', '2025-11-15 11:56:26'),
(95, '17-1763207856-3fb73fee01.png', '../../../ImageUploads/2025/17-1763207856-3fb73fee01.png', '2025-11-15 11:57:40'),
(96, 'placeholder-1763209856.jpg', '../../../ImageUploads/2025/placeholder-1763209856.jpg', '2025-11-15 12:31:00'),
(97, 'placeholder-1763213734.jpg', '../../../ImageUploads/2025/placeholder-1763213734.jpg', '2025-11-15 13:35:38');

-- --------------------------------------------------------

--
-- Table structure for table `registrations`
--

CREATE TABLE `registrations` (
  `Registration_Id` bigint(20) NOT NULL,
  `First_Name` varchar(50) DEFAULT NULL,
  `Last_Name` varchar(50) DEFAULT NULL,
  `Middle_Name` varchar(50) DEFAULT NULL,
  `Contact_Number` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `registrations`
--

INSERT INTO `registrations` (`Registration_Id`, `First_Name`, `Last_Name`, `Middle_Name`, `Contact_Number`) VALUES
(87, 'Lovely Jane', 'Dela Cruz', 'Musa', '09946956168'),
(90, 'Arjay', 'Iglesia', 'Di ko alam', '09682298263'),
(117, 'Kenneth', 'Alojado', 'Jimenez ', '09706573096'),
(122, 'Nemesio benedict', 'llorin', 'alojado', '09206926714'),
(164, 'Jeanilyn', 'David', 'Paderes', '09150558725'),
(165, 'SUMMER', 'GLORIOSO', 'GONZALES', '09686564397'),
(170, 'Jeannette', 'Alojado', 'Jimenez ', '09120291840'),
(172, 'aldrin123!#', 'Catubay!@#123', 'Portes123!#', '09266636871'),
(181, 'Jamella', 'Alojado', 'Jimenez', '09219430890'),
(206, 'Vaughn Joebert', 'Alojado', 'Jimenez', '09683118116'),
(216, 'Jan Paul', 'Abril', 'Tolentino', '09307004590'),
(218, 'Rey', 'Matibag', 'Guinto', '09745239845'),
(220, 'Rey', 'Matibag', 'Guinto', '09463456345'),
(223, 'Gsefyhsd', 'Matibag', 'Guinto', '09435634757'),
(225, 'Gsefyhsd', 'Matibag', 'Guinto', '09783242326'),
(226, 'Gsefyhsd', 'Matibag', 'Guinto', '09435634564'),
(228, 'Daniel Jose', 'Llarena', 'Mukhang Burat', '09564586568'),
(229, 'Daniel Jose', 'Llarena', 'Mukhang Burat', '09476027602'),
(230, 'Rey', 'Matibag', 'Guinto', '09645634564'),
(231, 'Nemesio', 'Llorin Iii', 'Solina', '09776706639');

-- --------------------------------------------------------

--
-- Table structure for table `report_card_submissions`
--

CREATE TABLE `report_card_submissions` (
  `Report_Card_Id` int(11) NOT NULL,
  `student_name` varchar(255) NOT NULL,
  `student_lrn` varchar(12) NOT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `report_card_front_path` varchar(500) NOT NULL,
  `report_card_back_path` varchar(500) DEFAULT NULL,
  `ocr_json` text DEFAULT NULL,
  `form_data_json` text DEFAULT NULL,
  `status` enum('approved','flagged_for_review','pending_review','reupload_needed','rejected') DEFAULT 'pending_review',
  `flag_reason` text DEFAULT NULL,
  `enrollee_id` int(11) DEFAULT NULL,
  `validation_only` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `report_card_submissions`
--

INSERT INTO `report_card_submissions` (`Report_Card_Id`, `student_name`, `student_lrn`, `user_id`, `session_id`, `report_card_front_path`, `report_card_back_path`, `ocr_json`, `form_data_json`, `status`, `flag_reason`, `enrollee_id`, `validation_only`, `created_at`, `updated_at`) VALUES
(1, 'Anatoly Karangalan Cumrade', '187264365781', NULL, NULL, 'ImageUploads/report_cards/2025/17-1763209861-4de2b5ec66.jpg', 'ImageUploads/report_cards/2025/17-1763209861-0270382deb.jpg', '{\"error\":\"OCR failed on one or both images\"}', NULL, 'flagged_for_review', NULL, 130, 0, '2025-11-15 12:31:02', '2025-11-15 12:31:02'),
(2, 'Kenneth Jeffrey Jimenez Alojado', '345643563456', NULL, NULL, 'ImageUploads/report_cards/2025/17-1763213741-4965374f95.jpg', 'ImageUploads/report_cards/2025/17-1763213741-32604cdb64.jpg', '{\"error\":\"OCR failed on one or both images\"}', NULL, 'flagged_for_review', NULL, 131, 0, '2025-11-15 13:35:41', '2025-11-15 13:35:41'),
(3, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763215351-50c46f1d41.jpg', 'ImageUploads/report_cards/2025/9999-1763215351-26ad836eba.jpg', '{\"error\":\"OCR failed on one or both images\"}', NULL, 'flagged_for_review', 'OCR processing failed: OCR failed on one or both images', NULL, 0, '2025-11-15 14:02:31', '2025-11-15 14:02:31'),
(4, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763215811-a672158506.jpg', 'ImageUploads/report_cards/2025/9999-1763215811-aa5d1e68cd.jpg', '{\"error\":\"Front image: Invalid JSON from OCR script: sh: python3: not found\\n; Back image: Invalid JSON from OCR script: sh: python3: not found\\n\",\"front_error\":\"Invalid JSON from OCR script: sh: python3: not found\\n\",\"back_error\":\"Invalid JSON from OCR script: sh: python3: not found\\n\",\"front_ocr\":null,\"back_ocr\":null}', NULL, 'flagged_for_review', 'Front image error: Invalid JSON from OCR script: sh: python3: not found\n; Back image error: Invalid JSON from OCR script: sh: python3: not found\n', NULL, 0, '2025-11-15 14:10:11', '2025-11-15 14:10:11'),
(5, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763217072-1588a0482a.jpg', 'ImageUploads/report_cards/2025/9999-1763217073-e71a5cffe8.jpg', '{\"lrn\":null,\"grades_found\":0,\"word_count\":212,\"flags\":[\"no_grades\"],\"front_ocr\":{\"lrn\":null,\"grades_found\":0,\"word_count\":129,\"flags\":[\"no_grades\"]},\"back_ocr\":{\"lrn\":null,\"grades_found\":0,\"word_count\":83,\"flags\":[\"no_grades\"]},\"lrn_source\":null,\"grades_primary_source\":null}', NULL, 'flagged_for_review', 'Insufficient grades detected (found: 0, required: 5+)', NULL, 0, '2025-11-15 14:31:20', '2025-11-15 14:31:20'),
(6, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763217317-504c755a77.jpg', 'ImageUploads/report_cards/2025/9999-1763217317-23744e933e.jpg', '{\"lrn\":null,\"grades_found\":3,\"word_count\":218,\"flags\":[\"no_grades\",\"grades_not_on_back\"],\"front_ocr\":{\"lrn\":null,\"grades_found\":3,\"word_count\":131,\"flags\":[\"no_grades\"]},\"back_ocr\":{\"lrn\":null,\"grades_found\":0,\"word_count\":87,\"flags\":[\"no_grades\"]},\"lrn_source\":null,\"grades_primary_source\":\"front\"}', NULL, 'flagged_for_review', 'Insufficient grades detected (found: 3, required: 5+); Grades found on front instead of back (grades side)', NULL, 0, '2025-11-15 14:35:23', '2025-11-15 14:35:23'),
(7, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763217462-4fa2ec48f6.jpg', 'ImageUploads/report_cards/2025/9999-1763217462-ea20f3d9a9.jpg', '{\"lrn\":null,\"grades_found\":3,\"word_count\":218,\"flags\":[\"no_grades\",\"grades_not_on_back\"],\"front_ocr\":{\"lrn\":null,\"grades_found\":3,\"word_count\":131,\"flags\":[\"no_grades\"]},\"back_ocr\":{\"lrn\":null,\"grades_found\":0,\"word_count\":87,\"flags\":[\"no_grades\"]},\"lrn_source\":null,\"grades_primary_source\":\"front\"}', NULL, 'flagged_for_review', 'Insufficient grades detected (found: 3, required: 5+); Grades found on front instead of back (grades side)', NULL, 0, '2025-11-15 14:37:49', '2025-11-15 14:37:49'),
(8, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763217560-d10f2a9dea.jpg', 'ImageUploads/report_cards/2025/9999-1763217560-1502f1519d.jpg', '{\"lrn\":null,\"grades_found\":6,\"word_count\":262,\"flags\":[\"no_grades\"],\"front_ocr\":{\"lrn\":null,\"grades_found\":3,\"word_count\":131,\"flags\":[\"no_grades\"]},\"back_ocr\":{\"lrn\":null,\"grades_found\":3,\"word_count\":131,\"flags\":[\"no_grades\"]},\"lrn_source\":null,\"grades_primary_source\":\"back\"}', NULL, 'flagged_for_review', 'Insufficient grades detected (found: 6, required: 5+)', NULL, 0, '2025-11-15 14:39:26', '2025-11-15 14:39:26'),
(9, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763217680-f612929170.jpg', 'ImageUploads/report_cards/2025/9999-1763217680-1d3221c455.jpg', '{\"lrn\":null,\"grades_found\":3,\"word_count\":218,\"flags\":[\"no_grades\",\"grades_not_on_back\"],\"front_ocr\":{\"lrn\":null,\"grades_found\":3,\"word_count\":131,\"flags\":[\"no_grades\"]},\"back_ocr\":{\"lrn\":null,\"grades_found\":0,\"word_count\":87,\"flags\":[\"no_grades\"]},\"lrn_source\":null,\"grades_primary_source\":\"front\"}', NULL, 'flagged_for_review', 'Insufficient grades detected (found: 3, required: 5+); Grades found on front instead of back (grades side)', NULL, 0, '2025-11-15 14:41:26', '2025-11-15 14:41:26'),
(10, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763217705-e4697f3f15.jpg', 'ImageUploads/report_cards/2025/9999-1763217705-44299e45ce.jpg', '{\"lrn\":null,\"grades_found\":3,\"word_count\":218,\"flags\":[\"no_grades\"],\"front_ocr\":{\"lrn\":null,\"grades_found\":0,\"word_count\":87,\"flags\":[\"no_grades\"]},\"back_ocr\":{\"lrn\":null,\"grades_found\":3,\"word_count\":131,\"flags\":[\"no_grades\"]},\"lrn_source\":null,\"grades_primary_source\":\"back\"}', NULL, 'flagged_for_review', 'Insufficient grades detected (found: 3, required: 5+)', NULL, 0, '2025-11-15 14:41:51', '2025-11-15 14:41:51'),
(11, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763217841-1bad7bac69.jpg', 'ImageUploads/report_cards/2025/9999-1763217841-5fc426758c.jpg', '{\"lrn\":null,\"grades_found\":3,\"word_count\":218,\"flags\":[\"no_grades\"],\"front_ocr\":{\"grades_found\":0,\"word_count\":87,\"flags\":[\"no_grades\"]},\"back_ocr\":{\"grades_found\":3,\"word_count\":131,\"flags\":[\"no_grades\"]},\"lrn_source\":null,\"grades_primary_source\":\"back\"}', NULL, 'flagged_for_review', 'Insufficient grades detected (found: 3, required: 5+)', NULL, 0, '2025-11-15 14:44:08', '2025-11-15 14:44:08'),
(12, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763217871-d6b475e90c.jpg', 'ImageUploads/report_cards/2025/9999-1763217871-ee9d5a4f2a.jpg', '{\"lrn\":null,\"grades_found\":3,\"word_count\":218,\"flags\":[\"no_grades\"],\"front_ocr\":{\"grades_found\":0,\"word_count\":87,\"flags\":[\"no_grades\"]},\"back_ocr\":{\"grades_found\":3,\"word_count\":131,\"flags\":[\"no_grades\"]},\"lrn_source\":null,\"grades_primary_source\":\"back\"}', NULL, 'flagged_for_review', 'Insufficient grades detected (found: 3, required: 5+)', NULL, 0, '2025-11-15 14:44:37', '2025-11-15 14:44:37'),
(13, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763217954-105ec39c65.jpg', 'ImageUploads/report_cards/2025/9999-1763217954-7f01ddcaed.jpg', '{\"lrn\":null,\"grades_found\":1,\"word_count\":175,\"flags\":[\"no_grades\"],\"front_ocr\":{\"grades_found\":0,\"word_count\":87,\"flags\":[\"no_grades\"]},\"back_ocr\":{\"grades_found\":1,\"word_count\":88,\"flags\":[\"no_grades\"]},\"lrn_source\":null,\"grades_primary_source\":\"back\"}', NULL, 'flagged_for_review', 'Insufficient grades detected (found: 1, required: 5+)', NULL, 0, '2025-11-15 14:46:00', '2025-11-15 14:46:00'),
(14, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763218012-53e079bb7c.jpg', 'ImageUploads/report_cards/2025/9999-1763218012-95fd4f2e30.jpg', '{\"lrn\":null,\"grades_found\":1,\"word_count\":175,\"flags\":[\"no_grades\"],\"front_ocr\":{\"grades_found\":0,\"word_count\":87,\"flags\":[\"no_grades\"]},\"back_ocr\":{\"grades_found\":1,\"word_count\":88,\"flags\":[\"no_grades\"]},\"lrn_source\":null,\"grades_primary_source\":\"back\"}', NULL, 'flagged_for_review', 'Insufficient grades detected (found: 1, required: 5+)', NULL, 0, '2025-11-15 14:46:57', '2025-11-15 14:46:57'),
(15, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763218036-241c933a5e.jpg', 'ImageUploads/report_cards/2025/9999-1763218036-bf51e8fcb0.jpg', '{\"lrn\":null,\"grades_found\":0,\"word_count\":117,\"flags\":[\"no_grades\",\"low_text\",\"no_keywords\"],\"front_ocr\":{\"grades_found\":0,\"word_count\":87,\"flags\":[\"no_grades\"]},\"back_ocr\":{\"grades_found\":0,\"word_count\":30,\"flags\":[\"no_grades\",\"low_text\",\"no_keywords\"]},\"lrn_source\":null,\"grades_primary_source\":null}', NULL, 'flagged_for_review', 'Back image: Low/no text content (words: 30); Insufficient grades detected (found: 0, required: 5+); Low text content (total word count: 117, required: 50+); Missing expected report card keywords (quarter, grade, subject, etc.)', NULL, 0, '2025-11-15 14:47:20', '2025-11-15 14:47:20'),
(16, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763218109-ec0b162943.jpg', 'ImageUploads/report_cards/2025/9999-1763218109-fe6aa2b318.jpg', '{\"lrn\":null,\"grades_found\":0,\"word_count\":213,\"flags\":[\"no_grades\"],\"front_ocr\":{\"grades_found\":0,\"word_count\":87,\"flags\":[\"no_grades\"]},\"back_ocr\":{\"grades_found\":0,\"word_count\":126,\"flags\":[\"no_grades\"]},\"lrn_source\":null,\"grades_primary_source\":null}', NULL, 'flagged_for_review', 'Insufficient grades detected (found: 0, required: 5+)', NULL, 0, '2025-11-15 14:48:34', '2025-11-15 14:48:34'),
(17, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763218173-7d0450006c.jpg', 'ImageUploads/report_cards/2025/9999-1763218173-d6afc79325.jpg', '{\"lrn\":null,\"grades_found\":0,\"word_count\":87,\"flags\":[\"no_grades\",\"no_text\",\"low_text\"],\"front_ocr\":{\"grades_found\":0,\"word_count\":87,\"flags\":[\"no_grades\"]},\"back_ocr\":{\"lrn\":null,\"grades_found\":0,\"word_count\":0,\"flags\":[\"no_text\",\"low_text\"]},\"lrn_source\":null,\"grades_primary_source\":null}', NULL, 'flagged_for_review', 'No readable text detected in image; Back image: Low/no text content (words: 0); Insufficient grades detected (found: 0, required: 5+); Low text content (total word count: 87, required: 50+)', NULL, 0, '2025-11-15 14:49:37', '2025-11-15 14:49:37'),
(18, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763218365-c02a4a0cba.jpg', 'ImageUploads/report_cards/2025/9999-1763218365-f1c3ce1b70.jpg', '{\"lrn\":null,\"grades_found\":0,\"word_count\":87,\"flags\":[\"no_grades\",\"no_text\",\"low_text\"],\"front_ocr\":{\"grades_found\":0,\"word_count\":87,\"flags\":[\"no_grades\"]},\"back_ocr\":{\"lrn\":null,\"grades_found\":0,\"word_count\":0,\"flags\":[\"no_text\",\"low_text\"]},\"lrn_source\":null,\"grades_primary_source\":null}', NULL, 'flagged_for_review', 'No readable text detected in image; Back image: Low/no text content (words: 0); Insufficient grades detected (found: 0, required: 5+); Low text content (total word count: 87, required: 50+)', NULL, 0, '2025-11-15 14:52:49', '2025-11-15 14:52:49'),
(19, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763218380-95fbda0e51.jpg', 'ImageUploads/report_cards/2025/9999-1763218380-5718d45957.jpg', '{\"lrn\":null,\"grades_found\":0,\"word_count\":96,\"flags\":[\"no_grades\",\"low_text\"],\"front_ocr\":{\"grades_found\":0,\"word_count\":48,\"flags\":[\"no_grades\",\"low_text\"]},\"back_ocr\":{\"grades_found\":0,\"word_count\":48,\"flags\":[\"no_grades\",\"low_text\"]},\"lrn_source\":null,\"grades_primary_source\":null}', NULL, 'flagged_for_review', 'Front image: Low/no text content (words: 48); Back image: Low/no text content (words: 48); Insufficient grades detected (found: 0, required: 5+); Low text content (total word count: 96, required: 50+)', NULL, 0, '2025-11-15 14:53:04', '2025-11-15 14:53:04'),
(20, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763219004-c2bad6e08d.jpg', 'ImageUploads/report_cards/2025/9999-1763219004-3c45071382.jpg', '{\"lrn\":null,\"grades_found\":0,\"word_count\":78,\"flags\":[\"no_keywords\",\"no_grades\"],\"front_ocr\":{\"lrn\":null,\"grades_found\":0,\"word_count\":48,\"flags\":[]},\"back_ocr\":{\"lrn\":null,\"grades_found\":0,\"word_count\":30,\"flags\":[\"no_keywords\"]},\"lrn_source\":null,\"grades_primary_source\":null}', NULL, 'flagged_for_review', 'Insufficient grades detected (Total: 0 [Front: 0, Back: 0], Required: 5+); Missing expected report card keywords (quarter, grade, subject, etc.)', NULL, 0, '2025-11-15 15:03:28', '2025-11-15 15:03:28'),
(21, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763219126-62a35eb2cd.jpg', 'ImageUploads/report_cards/2025/9999-1763219126-86ea50cec0.jpg', '{\"lrn\":\"109717230118\",\"grades_found\":10,\"word_count\":241,\"flags\":[\"no_grade_level\"],\"front_ocr\":{\"lrn\":\"109717230118\",\"grades_found\":1,\"word_count\":150,\"flags\":[\"no_grade_level\"]},\"back_ocr\":{\"lrn\":null,\"grades_found\":9,\"word_count\":91,\"flags\":[\"no_grade_level\"]},\"lrn_source\":\"front\",\"grades_primary_source\":\"back\"}', NULL, 'approved', NULL, NULL, 0, '2025-11-15 15:05:31', '2025-11-15 15:05:31'),
(22, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763219200-af30277510.jpg', 'ImageUploads/report_cards/2025/9999-1763219200-a86fc577cf.jpg', '{\"lrn\":\"109717230118\",\"grades_found\":10,\"word_count\":241,\"flags\":[\"no_grade_level\"],\"front_ocr\":{\"lrn\":\"109717230118\",\"grades_found\":1,\"word_count\":150,\"flags\":[\"no_grade_level\"]},\"back_ocr\":{\"lrn\":null,\"grades_found\":9,\"word_count\":91,\"flags\":[\"no_grade_level\"]},\"lrn_source\":\"front\",\"grades_primary_source\":\"back\"}', NULL, 'approved', NULL, NULL, 0, '2025-11-15 15:06:45', '2025-11-15 15:06:45'),
(23, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763219230-96298527ed.jpg', 'ImageUploads/report_cards/2025/9999-1763219231-5568cd055d.jpg', '{\"lrn\":null,\"grades_found\":9,\"word_count\":139,\"flags\":[\"no_grade_level\"],\"front_ocr\":{\"lrn\":null,\"grades_found\":0,\"word_count\":48,\"flags\":[\"no_grade_level\"]},\"back_ocr\":{\"lrn\":null,\"grades_found\":9,\"word_count\":91,\"flags\":[\"no_grade_level\"]},\"lrn_source\":null,\"grades_primary_source\":\"back\"}', NULL, 'approved', NULL, NULL, 0, '2025-11-15 15:07:15', '2025-11-15 15:07:15'),
(24, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763219251-5a8b55b94e.jpg', 'ImageUploads/report_cards/2025/9999-1763219251-3fcce646fb.jpg', '{\"lrn\":null,\"grades_found\":9,\"word_count\":217,\"flags\":[\"no_grade_level\"],\"front_ocr\":{\"lrn\":null,\"grades_found\":0,\"word_count\":126,\"flags\":[\"no_grade_level\"]},\"back_ocr\":{\"lrn\":null,\"grades_found\":9,\"word_count\":91,\"flags\":[\"no_grade_level\"]},\"lrn_source\":null,\"grades_primary_source\":\"back\"}', NULL, 'approved', NULL, NULL, 0, '2025-11-15 15:07:36', '2025-11-15 15:07:36'),
(25, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763219387-8deb7db34f.jpg', 'ImageUploads/report_cards/2025/9999-1763219387-08af6a1c3f.jpg', '{\"lrn\":null,\"grades_found\":2,\"word_count\":322,\"flags\":[\"no_grade_level\",\"no_grades\"],\"front_ocr\":{\"lrn\":null,\"grades_found\":1,\"word_count\":161,\"flags\":[\"no_grade_level\"]},\"back_ocr\":{\"lrn\":null,\"grades_found\":1,\"word_count\":161,\"flags\":[\"no_grade_level\"]},\"lrn_source\":null,\"grades_primary_source\":\"front\"}', NULL, 'flagged_for_review', 'Insufficient grades detected (Total: 2 [Front: 1, Back: 1], Required: 5+)', NULL, 0, '2025-11-15 15:09:55', '2025-11-15 15:09:55'),
(26, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763219509-33535115d8.jpg', 'ImageUploads/report_cards/2025/9999-1763219509-ce6b71214c.jpg', '{\"lrn\":\"109717230118\",\"grades_found\":1,\"word_count\":179,\"flags\":[\"no_grade_level\",\"no_grades\"],\"front_ocr\":{\"lrn\":\"109717230118\",\"grades_found\":1,\"word_count\":150,\"flags\":[\"no_grade_level\"]},\"back_ocr\":{\"lrn\":null,\"grades_found\":0,\"word_count\":29,\"flags\":[\"no_grade_level\"]},\"lrn_source\":\"front\",\"grades_primary_source\":\"front\"}', NULL, 'flagged_for_review', 'Insufficient grades detected (Total: 1 [Front: 1, Back: 0], Required: 5+)', NULL, 0, '2025-11-15 15:11:52', '2025-11-15 15:11:52'),
(27, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763219533-6d14fdf7a3.jpg', 'ImageUploads/report_cards/2025/9999-1763219533-06c2490233.jpg', '{\"lrn\":\"109717230118\",\"grades_found\":10,\"word_count\":241,\"flags\":[\"no_grade_level\"],\"front_ocr\":{\"lrn\":\"109717230118\",\"grades_found\":1,\"word_count\":150,\"flags\":[\"no_grade_level\"]},\"back_ocr\":{\"lrn\":null,\"grades_found\":9,\"word_count\":91,\"flags\":[\"no_grade_level\"]},\"lrn_source\":\"front\",\"grades_primary_source\":\"back\"}', NULL, 'approved', NULL, NULL, 0, '2025-11-15 15:12:17', '2025-11-15 15:12:17'),
(28, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763219897-b2d2966e32.jpg', 'ImageUploads/report_cards/2025/9999-1763219897-33974c2ed8.jpg', '{\"lrn\":\"109717230118\",\"grades_found\":10,\"word_count\":241,\"flags\":[\"no_grade_level\"],\"front_ocr\":{\"lrn\":\"109717230118\",\"grades_found\":1,\"word_count\":150,\"flags\":[\"no_grade_level\"]},\"back_ocr\":{\"lrn\":null,\"grades_found\":9,\"word_count\":91,\"flags\":[\"no_grade_level\"]},\"lrn_source\":\"front\",\"grades_primary_source\":\"back\"}', NULL, 'approved', NULL, NULL, 0, '2025-11-15 15:18:22', '2025-11-15 15:18:22'),
(29, 'Kenneth Alojado', '145134523452', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763219927-8ec6d826ea.jpg', 'ImageUploads/report_cards/2025/9999-1763219927-c6801a2b29.jpg', '{\"lrn\":null,\"grades_found\":0,\"word_count\":0,\"flags\":[\"no_text\",\"low_text\",\"no_grades\"],\"front_ocr\":{\"lrn\":null,\"grades_found\":0,\"word_count\":0,\"flags\":[\"no_text\",\"low_text\"]},\"back_ocr\":{\"lrn\":null,\"grades_found\":0,\"word_count\":0,\"flags\":[\"no_text\",\"low_text\"]},\"lrn_source\":null,\"grades_primary_source\":null}', NULL, 'flagged_for_review', 'No readable text detected in image; Front image: No readable text detected; Back image: No readable text detected; Insufficient grades detected (Total: 0 [Front: 0, Back: 0], Required: 5+); Low text content (Total: 0 [Front: 0, Back: 0] words, Required: 50+)', NULL, 0, '2025-11-15 15:18:49', '2025-11-15 15:18:49'),
(30, 'Kenneth Alojado', '145134523452', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763220010-515e94faf6.jpg', 'ImageUploads/report_cards/2025/9999-1763220010-4387a0f4fe.jpg', '{\"lrn\":null,\"grades_found\":0,\"word_count\":35,\"flags\":[\"no_grade_level\",\"no_text\",\"low_text\",\"no_grades\"],\"front_ocr\":{\"lrn\":null,\"grades_found\":0,\"word_count\":35,\"flags\":[\"no_grade_level\"]},\"back_ocr\":{\"lrn\":null,\"grades_found\":0,\"word_count\":0,\"flags\":[\"no_text\",\"low_text\"]},\"lrn_source\":null,\"grades_primary_source\":null}', NULL, 'flagged_for_review', 'No readable text detected in image; Back image: No readable text detected; Insufficient grades detected (Total: 0 [Front: 0, Back: 0], Required: 5+); Low text content (Total: 35 [Front: 35, Back: 0] words, Required: 50+)', NULL, 0, '2025-11-15 15:20:12', '2025-11-15 15:20:12'),
(31, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763220157-db574fce22.png', 'ImageUploads/report_cards/2025/9999-1763220157-00e6e35c29.jpg', '{\"lrn\":null,\"grades_found\":0,\"word_count\":35,\"flags\":[\"no_keywords\",\"no_grade_level\",\"no_text\",\"low_text\",\"no_grades\"],\"front_ocr\":{\"lrn\":null,\"grades_found\":0,\"word_count\":35,\"flags\":[\"no_keywords\",\"no_grade_level\"]},\"back_ocr\":{\"lrn\":null,\"grades_found\":0,\"word_count\":0,\"flags\":[\"no_text\",\"low_text\"]},\"lrn_source\":null,\"grades_primary_source\":null}', NULL, 'flagged_for_review', 'No readable text detected in image; Back image: No readable text detected; Insufficient grades detected (Total: 0 [Front: 0, Back: 0], Required: 5+); Low text content (Total: 35 [Front: 35, Back: 0] words, Required: 50+); Missing expected report card keywords (quarter, grade, subject, etc.)', NULL, 0, '2025-11-15 15:22:39', '2025-11-15 15:22:39'),
(32, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763220757-ace5414e69.jpg', 'ImageUploads/report_cards/2025/9999-1763220757-b75d912133.jpg', '{\"lrn\":null,\"grades_found\":0,\"word_count\":0,\"flags\":[\"no_text\",\"low_text\",\"no_grades\"],\"front_ocr\":{\"lrn\":null,\"grades_found\":0,\"word_count\":0,\"flags\":[\"no_text\",\"low_text\"]},\"back_ocr\":{\"lrn\":null,\"grades_found\":0,\"word_count\":0,\"flags\":[\"no_text\",\"low_text\"]},\"lrn_source\":null,\"grades_primary_source\":null}', NULL, 'flagged_for_review', 'No readable text detected in image; Front image: No readable text detected; Back image: No readable text detected; Insufficient grades detected (Total: 0 [Front: 0, Back: 0], Required: 5+); Low text content (Total: 0 [Front: 0, Back: 0] words, Required: 50+)', NULL, 0, '2025-11-15 15:32:42', '2025-11-15 15:32:42'),
(33, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763221134-b2297e4d6e.jpg', 'ImageUploads/report_cards/2025/9999-1763221134-cea2eea61f.jpg', '{\"lrn\":null,\"grades_found\":0,\"word_count\":35,\"flags\":[\"no_grade_level\",\"no_text\",\"low_text\",\"no_grades\"],\"front_ocr\":{\"lrn\":null,\"grades_found\":0,\"word_count\":35,\"flags\":[\"no_grade_level\"]},\"back_ocr\":{\"lrn\":null,\"grades_found\":0,\"word_count\":0,\"flags\":[\"no_text\",\"low_text\"]},\"lrn_source\":null,\"grades_primary_source\":null}', NULL, 'rejected', 'Please submit a report card', NULL, 0, '2025-11-15 15:38:56', '2025-11-15 15:38:56'),
(34, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763221175-601e5a6c9d.jpg', 'ImageUploads/report_cards/2025/9999-1763221175-248344057c.jpg', '{\"lrn\":\"109717230118\",\"grades_found\":1,\"word_count\":150,\"flags\":[\"no_grade_level\",\"no_text\",\"low_text\",\"no_grades\"],\"front_ocr\":{\"lrn\":\"109717230118\",\"grades_found\":1,\"word_count\":150,\"flags\":[\"no_grade_level\"]},\"back_ocr\":{\"lrn\":null,\"grades_found\":0,\"word_count\":0,\"flags\":[\"no_text\",\"low_text\"]},\"lrn_source\":\"front\",\"grades_primary_source\":\"front\"}', NULL, 'rejected', 'Please submit a report card', NULL, 0, '2025-11-15 15:39:38', '2025-11-15 15:39:38'),
(35, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763221196-c79a6560fd.jpg', 'ImageUploads/report_cards/2025/9999-1763221196-aec33b64ba.jpg', '{\"lrn\":null,\"grades_found\":9,\"word_count\":91,\"flags\":[\"no_grade_level\",\"no_text\",\"low_text\"],\"front_ocr\":{\"lrn\":null,\"grades_found\":9,\"word_count\":91,\"flags\":[\"no_grade_level\"]},\"back_ocr\":{\"lrn\":null,\"grades_found\":0,\"word_count\":0,\"flags\":[\"no_text\",\"low_text\"]},\"lrn_source\":null,\"grades_primary_source\":\"front\"}', NULL, 'rejected', 'Please submit a report card', NULL, 0, '2025-11-15 15:39:59', '2025-11-15 15:39:59'),
(36, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763221558-8b539bb76b.jpg', 'ImageUploads/report_cards/2025/9999-1763221558-135b49cdb7.jpg', '{\"lrn\":null,\"grades_found\":0,\"word_count\":64,\"flags\":[\"no_grade_level\",\"no_grades\"],\"front_ocr\":{\"lrn\":null,\"grades_found\":0,\"word_count\":29,\"flags\":[\"no_grade_level\"]},\"back_ocr\":{\"lrn\":null,\"grades_found\":0,\"word_count\":35,\"flags\":[\"no_grade_level\"]},\"lrn_source\":null,\"grades_primary_source\":null}', NULL, 'flagged_for_review', 'Insufficient grades detected (Total: 0 [Front: 0, Back: 0], Required: 5+)', NULL, 0, '2025-11-15 15:46:00', '2025-11-15 15:46:00'),
(37, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763221583-77e4e814f4.png', 'ImageUploads/report_cards/2025/9999-1763221583-010e707a12.jpg', '{\"lrn\":null,\"grades_found\":0,\"word_count\":0,\"flags\":[\"no_text\",\"low_text\",\"no_grades\",\"both_no_text\",\"both_low_quality\"],\"front_ocr\":{\"lrn\":null,\"grades_found\":0,\"word_count\":0,\"flags\":[\"no_text\",\"low_text\"]},\"back_ocr\":{\"lrn\":null,\"grades_found\":0,\"word_count\":0,\"flags\":[\"no_text\",\"low_text\"]},\"lrn_source\":null,\"grades_primary_source\":null}', NULL, 'rejected', 'Please submit a report card', NULL, 0, '2025-11-15 15:46:25', '2025-11-15 15:46:25'),
(38, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763221618-fb90a33162.jpg', 'ImageUploads/report_cards/2025/9999-1763221618-ddcd65ab04.jpg', '{\"lrn\":null,\"grades_found\":0,\"word_count\":77,\"flags\":[\"no_grade_level\",\"no_grades\"],\"front_ocr\":{\"lrn\":null,\"grades_found\":0,\"word_count\":48,\"flags\":[\"no_grade_level\"]},\"back_ocr\":{\"lrn\":null,\"grades_found\":0,\"word_count\":29,\"flags\":[\"no_grade_level\"]},\"lrn_source\":null,\"grades_primary_source\":null}', NULL, 'flagged_for_review', 'Insufficient grades detected (Total: 0 [Front: 0, Back: 0], Required: 5+)', NULL, 0, '2025-11-15 15:47:01', '2025-11-15 15:47:01'),
(39, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763221645-473fba0e15.jpg', 'ImageUploads/report_cards/2025/9999-1763221645-b88ffa9f41.jpg', '{\"lrn\":null,\"grades_found\":0,\"word_count\":213,\"flags\":[\"no_grade_level\",\"no_grades\"],\"front_ocr\":{\"lrn\":null,\"grades_found\":0,\"word_count\":87,\"flags\":[\"no_grade_level\"]},\"back_ocr\":{\"lrn\":null,\"grades_found\":0,\"word_count\":126,\"flags\":[\"no_grade_level\"]},\"lrn_source\":null,\"grades_primary_source\":null}', NULL, 'flagged_for_review', 'Insufficient grades detected (Total: 0 [Front: 0, Back: 0], Required: 5+)', NULL, 0, '2025-11-15 15:47:31', '2025-11-15 15:47:31'),
(40, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763221668-042dc96408.jpg', 'ImageUploads/report_cards/2025/9999-1763221668-679558c454.jpg', '{\"lrn\":null,\"grades_found\":0,\"word_count\":39,\"flags\":[\"no_keywords\",\"no_grade_level\",\"no_text\",\"low_text\",\"no_grades\"],\"front_ocr\":{\"lrn\":null,\"grades_found\":0,\"word_count\":39,\"flags\":[\"no_keywords\",\"no_grade_level\"]},\"back_ocr\":{\"lrn\":null,\"grades_found\":0,\"word_count\":0,\"flags\":[\"no_text\",\"low_text\"]},\"lrn_source\":null,\"grades_primary_source\":null}', NULL, 'flagged_for_review', 'No readable text detected in image; Back image: No readable text detected; Insufficient grades detected (Total: 0 [Front: 0, Back: 0], Required: 5+); Low text content (Total: 39 [Front: 39, Back: 0] words, Required: 50+); Missing expected report card keywords (quarter, grade, subject, etc.)', NULL, 0, '2025-11-15 15:47:51', '2025-11-15 15:47:51'),
(41, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763222138-52a2d44e77.jpg', 'ImageUploads/report_cards/2025/9999-1763222138-41c1656244.jpg', '{\"lrn\":null,\"grades_found\":1,\"word_count\":190,\"flags\":[\"no_grade_level\",\"no_grades\"],\"front_ocr\":{\"lrn\":null,\"grades_found\":1,\"word_count\":161,\"flags\":[\"no_grade_level\"]},\"back_ocr\":{\"lrn\":null,\"grades_found\":0,\"word_count\":29,\"flags\":[\"no_grade_level\"]},\"lrn_source\":null,\"grades_primary_source\":\"front\"}', NULL, 'flagged_for_review', 'Insufficient grades detected (Total: 1 [Front: 1, Back: 0], Required: 5+)', NULL, 0, '2025-11-15 15:55:43', '2025-11-15 15:55:43'),
(42, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763222162-9fbcfb3558.jpg', 'ImageUploads/report_cards/2025/9999-1763222162-d40e4d6314.jpg', '{\"lrn\":null,\"grades_found\":0,\"word_count\":58,\"flags\":[\"no_grade_level\",\"no_grades\",\"both_no_grades\"],\"front_ocr\":{\"lrn\":null,\"grades_found\":0,\"word_count\":29,\"flags\":[\"no_grade_level\"]},\"back_ocr\":{\"lrn\":null,\"grades_found\":0,\"word_count\":29,\"flags\":[\"no_grade_level\"]},\"lrn_source\":null,\"grades_primary_source\":null}', NULL, 'rejected', 'Please submit a report card', NULL, 0, '2025-11-15 15:56:05', '2025-11-15 15:56:05'),
(43, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763222215-30298dab8e.jpg', 'ImageUploads/report_cards/2025/9999-1763222215-d552ddfa4b.jpg', '{\"lrn\":\"109717230118\",\"grades_found\":1,\"word_count\":179,\"flags\":[\"no_grade_level\",\"no_grades\"],\"front_ocr\":{\"lrn\":\"109717230118\",\"grades_found\":1,\"word_count\":150,\"flags\":[\"no_grade_level\"]},\"back_ocr\":{\"lrn\":null,\"grades_found\":0,\"word_count\":29,\"flags\":[\"no_grade_level\"]},\"lrn_source\":\"front\",\"grades_primary_source\":\"front\"}', NULL, 'flagged_for_review', 'Insufficient grades detected (Total: 1 [Front: 1, Back: 0], Required: 5+)', NULL, 0, '2025-11-15 15:56:58', '2025-11-15 15:56:58'),
(44, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763222270-0064a0b9b7.jpg', 'ImageUploads/report_cards/2025/9999-1763222270-58c6611724.jpg', '{\"lrn\":\"109717230118\",\"grades_found\":10,\"word_count\":241,\"flags\":[\"no_grade_level\"],\"front_ocr\":{\"lrn\":\"109717230118\",\"grades_found\":1,\"word_count\":150,\"flags\":[\"no_grade_level\"]},\"back_ocr\":{\"lrn\":null,\"grades_found\":9,\"word_count\":91,\"flags\":[\"no_grade_level\"]},\"lrn_source\":\"front\",\"grades_primary_source\":\"back\"}', NULL, 'approved', NULL, NULL, 0, '2025-11-15 15:57:56', '2025-11-15 15:57:56'),
(45, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763222293-c3cb91e542.jpg', 'ImageUploads/report_cards/2025/9999-1763222293-c42e91a6e8.png', '{\"lrn\":\"109717230118\",\"grades_found\":1,\"word_count\":150,\"flags\":[\"no_grade_level\",\"no_text\",\"low_text\",\"no_grades\"],\"front_ocr\":{\"lrn\":\"109717230118\",\"grades_found\":1,\"word_count\":150,\"flags\":[\"no_grade_level\"]},\"back_ocr\":{\"lrn\":null,\"grades_found\":0,\"word_count\":0,\"flags\":[\"no_text\",\"low_text\"]},\"lrn_source\":\"front\",\"grades_primary_source\":\"front\"}', NULL, 'rejected', 'Please submit a report card or send a higher quality image', NULL, 0, '2025-11-15 15:58:16', '2025-11-15 15:58:16'),
(46, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763222421-c54666cc53.jpg', 'ImageUploads/report_cards/2025/9999-1763222421-cdd0b14308.jpg', '{\"lrn\":null,\"grades_found\":0,\"word_count\":39,\"flags\":[\"no_text\",\"low_text\",\"no_keywords\",\"no_grade_level\",\"no_grades\",\"both_no_grades\"],\"front_ocr\":{\"lrn\":null,\"grades_found\":0,\"word_count\":0,\"flags\":[\"no_text\",\"low_text\"]},\"back_ocr\":{\"lrn\":null,\"grades_found\":0,\"word_count\":39,\"flags\":[\"no_keywords\",\"no_grade_level\"]},\"lrn_source\":null,\"grades_primary_source\":null}', NULL, 'rejected', 'Please submit a report card', NULL, 0, '2025-11-15 16:00:23', '2025-11-15 16:00:23'),
(47, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763222468-3b2b6347e2.jpg', 'ImageUploads/report_cards/2025/9999-1763222468-88aff1ac6b.jpg', '{\"lrn\":null,\"grades_found\":0,\"word_count\":39,\"flags\":[\"no_keywords\",\"no_grade_level\",\"no_text\",\"low_text\",\"no_grades\",\"both_no_grades\"],\"front_ocr\":{\"lrn\":null,\"grades_found\":0,\"word_count\":39,\"flags\":[\"no_keywords\",\"no_grade_level\"]},\"back_ocr\":{\"lrn\":null,\"grades_found\":0,\"word_count\":0,\"flags\":[\"no_text\",\"low_text\"]},\"lrn_source\":null,\"grades_primary_source\":null}', NULL, 'rejected', 'Please submit a report card', NULL, 0, '2025-11-15 16:01:10', '2025-11-15 16:01:10'),
(48, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763222488-9482825282.jpg', 'ImageUploads/report_cards/2025/9999-1763222488-0233a37525.jpg', '{\"lrn\":\"109717230118\",\"grades_found\":10,\"word_count\":241,\"flags\":[\"no_grade_level\"],\"front_ocr\":{\"lrn\":null,\"grades_found\":9,\"word_count\":91,\"flags\":[\"no_grade_level\"]},\"back_ocr\":{\"lrn\":\"109717230118\",\"grades_found\":1,\"word_count\":150,\"flags\":[\"no_grade_level\"]},\"lrn_source\":\"back\",\"grades_primary_source\":\"front\"}', NULL, 'approved', NULL, NULL, 0, '2025-11-15 16:01:33', '2025-11-15 16:01:33'),
(49, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763222529-e7dd11bb9f.jpg', 'ImageUploads/report_cards/2025/9999-1763222529-623c44c853.jpg', '{\"lrn\":\"109717230118\",\"grades_found\":1,\"word_count\":150,\"flags\":[\"no_text\",\"low_text\",\"no_grade_level\",\"no_grades\"],\"front_ocr\":{\"lrn\":null,\"grades_found\":0,\"word_count\":0,\"flags\":[\"no_text\",\"low_text\"]},\"back_ocr\":{\"lrn\":\"109717230118\",\"grades_found\":1,\"word_count\":150,\"flags\":[\"no_grade_level\"]},\"lrn_source\":\"back\",\"grades_primary_source\":\"back\"}', NULL, 'rejected', 'Please submit a report card or send a higher quality image', NULL, 0, '2025-11-15 16:02:13', '2025-11-15 16:02:13'),
(50, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763222551-c499034a24.jpg', 'ImageUploads/report_cards/2025/9999-1763222551-9afbb774e1.jpg', '{\"lrn\":\"109717230118\",\"grades_found\":1,\"word_count\":276,\"flags\":[\"no_grade_level\",\"no_grades\"],\"front_ocr\":{\"lrn\":null,\"grades_found\":0,\"word_count\":126,\"flags\":[\"no_grade_level\"]},\"back_ocr\":{\"lrn\":\"109717230118\",\"grades_found\":1,\"word_count\":150,\"flags\":[\"no_grade_level\"]},\"lrn_source\":\"back\",\"grades_primary_source\":\"back\"}', NULL, 'flagged_for_review', 'Insufficient grades detected (Total: 1 [Front: 0, Back: 1], Required: 5+)', NULL, 0, '2025-11-15 16:02:35', '2025-11-15 16:02:35'),
(51, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763222653-6d7f7c69d8.jpg', 'ImageUploads/report_cards/2025/9999-1763222653-0885b31d08.png', '{\"lrn\":null,\"grades_found\":0,\"word_count\":0,\"flags\":[\"no_text\",\"low_text\",\"no_grades\",\"both_no_text\",\"both_no_grades\",\"both_low_quality\"],\"front_ocr\":{\"lrn\":null,\"grades_found\":0,\"word_count\":0,\"flags\":[\"no_text\",\"low_text\"]},\"back_ocr\":{\"lrn\":null,\"grades_found\":0,\"word_count\":0,\"flags\":[\"no_text\",\"low_text\"]},\"lrn_source\":null,\"grades_primary_source\":null}', NULL, 'rejected', 'Please submit a report card', NULL, 0, '2025-11-15 16:04:15', '2025-11-15 16:04:15'),
(52, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763223934-a64682f905.jpg', 'ImageUploads/report_cards/2025/9999-1763223934-34d67549ac.jpg', '{\"lrn\":\"109717230118\",\"grades_found\":2,\"word_count\":300,\"flags\":[\"no_grade_level\",\"no_grades\"],\"front_ocr\":{\"lrn\":\"109717230118\",\"grades_found\":1,\"word_count\":150,\"flags\":[\"no_grade_level\"]},\"back_ocr\":{\"lrn\":\"109717230118\",\"grades_found\":1,\"word_count\":150,\"flags\":[\"no_grade_level\"]},\"lrn_source\":\"front\",\"grades_primary_source\":\"front\"}', NULL, 'flagged_for_review', 'Insufficient grades detected (Total: 2 [Front: 1, Back: 1], Required: 5+)', NULL, 0, '2025-11-15 16:25:40', '2025-11-15 16:25:40'),
(53, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763223985-2468899d12.jpg', 'ImageUploads/report_cards/2025/9999-1763223986-feeeac18a5.jpg', '{\"lrn\":\"109717230118\",\"grades_found\":1,\"word_count\":276,\"flags\":[\"no_grade_level\",\"no_grades\"],\"front_ocr\":{\"lrn\":null,\"grades_found\":0,\"word_count\":126,\"flags\":[\"no_grade_level\"]},\"back_ocr\":{\"lrn\":\"109717230118\",\"grades_found\":1,\"word_count\":150,\"flags\":[\"no_grade_level\"]},\"lrn_source\":\"back\",\"grades_primary_source\":\"back\"}', NULL, 'flagged_for_review', 'Insufficient grades detected (Total: 1 [Front: 0, Back: 1], Required: 5+)', NULL, 0, '2025-11-15 16:26:32', '2025-11-15 16:26:32'),
(54, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763224014-23573f3a1e.jpg', 'ImageUploads/report_cards/2025/9999-1763224014-58c411216d.jpg', '{\"lrn\":null,\"grades_found\":0,\"word_count\":68,\"flags\":[\"no_grade_level\",\"no_keywords\",\"no_grades\",\"both_no_grades\"],\"front_ocr\":{\"lrn\":null,\"grades_found\":0,\"word_count\":29,\"flags\":[\"no_grade_level\"]},\"back_ocr\":{\"lrn\":null,\"grades_found\":0,\"word_count\":39,\"flags\":[\"no_keywords\",\"no_grade_level\"]},\"lrn_source\":null,\"grades_primary_source\":null}', NULL, 'rejected', 'Please submit a report card', NULL, 0, '2025-11-15 16:26:56', '2025-11-15 16:26:56'),
(55, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763224131-f9106643a3.jpg', 'ImageUploads/report_cards/2025/9999-1763224131-3d56bd1888.jpg', '{\"lrn\":\"109717230118\",\"grades_found\":10,\"word_count\":241,\"flags\":[\"no_grade_level\"],\"front_ocr\":{\"lrn\":null,\"grades_found\":9,\"word_count\":91,\"flags\":[\"no_grade_level\"]},\"back_ocr\":{\"lrn\":\"109717230118\",\"grades_found\":1,\"word_count\":150,\"flags\":[\"no_grade_level\"]},\"lrn_source\":\"back\",\"grades_primary_source\":\"front\"}', NULL, 'approved', NULL, NULL, 0, '2025-11-15 16:28:57', '2025-11-15 16:28:57'),
(56, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763284843-3bd50193f3.jpg', 'ImageUploads/report_cards/2025/9999-1763284843-ac2276c7a6.jpg', '{\"lrn\":\"109717230118\",\"grades_found\":10,\"word_count\":241,\"flags\":[\"no_grade_level\"],\"front_ocr\":{\"lrn\":\"109717230118\",\"grades_found\":1,\"word_count\":150,\"flags\":[\"no_grade_level\"]},\"back_ocr\":{\"lrn\":null,\"grades_found\":9,\"word_count\":91,\"flags\":[\"no_grade_level\"]},\"lrn_source\":\"front\",\"grades_primary_source\":\"back\"}', NULL, 'flagged_for_review', 'Manual verification required for report card authenticity', NULL, 0, '2025-11-16 09:20:48', '2025-11-16 09:20:48'),
(57, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763285040-f1b9f1d8a3.png', 'ImageUploads/report_cards/2025/9999-1763285040-9e08197949.png', '{\"lrn\":null,\"grades_found\":0,\"word_count\":120,\"flags\":[\"no_keywords\",\"no_grade_level\",\"no_grades\",\"both_no_keywords\",\"both_no_grades\"],\"front_ocr\":{\"lrn\":null,\"grades_found\":0,\"word_count\":2,\"flags\":[\"no_keywords\",\"no_grade_level\"]},\"back_ocr\":{\"lrn\":null,\"grades_found\":0,\"word_count\":118,\"flags\":[\"no_keywords\",\"no_grade_level\"]},\"lrn_source\":null,\"grades_primary_source\":null}', NULL, 'rejected', 'Please submit a report card', NULL, 0, '2025-11-16 09:24:02', '2025-11-16 09:24:02'),
(58, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763290630-342cb05bc7.png', 'ImageUploads/report_cards/2025/9999-1763290630-8200dbafb6.jpg', '{\"lrn\":\"109717230118\",\"grades_found\":1,\"word_count\":152,\"flags\":[\"no_keywords\",\"no_grade_level\",\"no_grades\"],\"front_ocr\":{\"lrn\":null,\"grades_found\":0,\"word_count\":2,\"flags\":[\"no_keywords\",\"no_grade_level\"]},\"back_ocr\":{\"lrn\":\"109717230118\",\"grades_found\":1,\"word_count\":150,\"flags\":[\"no_grade_level\"]},\"lrn_source\":\"back\",\"grades_primary_source\":\"back\"}', NULL, 'flagged_for_review', 'Manual verification required for report card authenticity; Low grade count detected (Total: 1 [Front: 0, Back: 1]); Missing expected report card keywords (quarter, grade, subject, etc.)', NULL, 0, '2025-11-16 10:57:12', '2025-11-16 10:57:12'),
(59, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763291352-a506d2e5b9.png', 'ImageUploads/report_cards/2025/9999-1763291352-af51926948.jpg', '{\"lrn\":\"109717230118\",\"grades_found\":3,\"word_count\":320,\"flags\":[\"no_keywords\",\"no_grade_level\",\"no_grades\"],\"front_ocr\":{\"lrn\":null,\"grades_found\":2,\"word_count\":170,\"flags\":[\"no_keywords\",\"no_grade_level\"]},\"back_ocr\":{\"lrn\":\"109717230118\",\"grades_found\":1,\"word_count\":150,\"flags\":[\"no_grade_level\"]},\"lrn_source\":\"back\",\"grades_primary_source\":\"front\"}', NULL, 'flagged_for_review', 'Manual verification required for report card authenticity; Low grade count detected (Total: 3 [Front: 2, Back: 1]); Missing expected report card keywords (quarter, grade, subject, etc.)', NULL, 0, '2025-11-16 11:09:15', '2025-11-16 11:09:15'),
(60, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763291441-43714dd4ab.png', 'ImageUploads/report_cards/2025/9999-1763291441-ee11693c01.jpg', '{\"lrn\":null,\"grades_found\":9,\"word_count\":93,\"flags\":[\"no_keywords\",\"no_grade_level\"],\"front_ocr\":{\"lrn\":null,\"grades_found\":0,\"word_count\":2,\"flags\":[\"no_keywords\",\"no_grade_level\"]},\"back_ocr\":{\"lrn\":null,\"grades_found\":9,\"word_count\":91,\"flags\":[\"no_grade_level\"]},\"lrn_source\":null,\"grades_primary_source\":\"back\"}', NULL, 'flagged_for_review', 'Manual verification required for report card authenticity; Missing expected report card keywords (quarter, grade, subject, etc.)', NULL, 0, '2025-11-16 11:10:43', '2025-11-16 11:10:43'),
(61, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763291871-61842da246.jpg', 'ImageUploads/report_cards/2025/9999-1763291871-d01707989a.jpg', '{\"lrn\":\"109717230118\",\"grades_found\":10,\"word_count\":241,\"flags\":[\"no_grade_level\"],\"front_ocr\":{\"lrn\":\"109717230118\",\"grades_found\":1,\"word_count\":150,\"flags\":[\"no_grade_level\"]},\"back_ocr\":{\"lrn\":null,\"grades_found\":9,\"word_count\":91,\"flags\":[\"no_grade_level\"]},\"lrn_source\":\"front\",\"grades_primary_source\":\"back\"}', NULL, 'flagged_for_review', 'Manual verification required for report card authenticity', NULL, 0, '2025-11-16 11:17:56', '2025-11-16 11:17:56'),
(62, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763291892-a92b700664.png', 'ImageUploads/report_cards/2025/9999-1763291892-494a858462.jpg', '{\"lrn\":null,\"grades_found\":9,\"word_count\":93,\"flags\":[\"no_keywords\",\"no_grade_level\"],\"front_ocr\":{\"lrn\":null,\"grades_found\":0,\"word_count\":2,\"flags\":[\"no_keywords\",\"no_grade_level\"]},\"back_ocr\":{\"lrn\":null,\"grades_found\":9,\"word_count\":91,\"flags\":[\"no_grade_level\"]},\"lrn_source\":null,\"grades_primary_source\":\"back\"}', NULL, 'flagged_for_review', 'Manual verification required for report card authenticity; Missing expected report card keywords (quarter, grade, subject, etc.)', NULL, 0, '2025-11-16 11:18:13', '2025-11-16 11:18:13'),
(63, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763292503-2cd0c689d0.jpg', 'ImageUploads/report_cards/2025/9999-1763292503-2d06738b54.jpg', '{\"lrn\":null,\"grades_found\":1,\"word_count\":248,\"flags\":[\"no_grade_level\",\"no_grades\"],\"front_ocr\":{\"lrn\":null,\"grades_found\":0,\"word_count\":87,\"flags\":[\"no_grade_level\"]},\"back_ocr\":{\"lrn\":null,\"grades_found\":1,\"word_count\":161,\"flags\":[\"no_grade_level\"]},\"front_flags\":[\"no_grade_level\"],\"back_flags\":[\"no_grade_level\"],\"lrn_source\":null,\"grades_primary_source\":\"back\"}', NULL, 'flagged_for_review', 'Manual verification required for report card authenticity', NULL, 0, '2025-11-16 11:28:28', '2025-11-16 11:28:28'),
(64, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763292541-2cdd472840.jpg', 'ImageUploads/report_cards/2025/9999-1763292541-0061d8f4d6.jpg', '{\"lrn\":\"109717230118\",\"grades_found\":10,\"word_count\":241,\"flags\":[\"no_grade_level\"],\"front_ocr\":{\"lrn\":\"109717230118\",\"grades_found\":1,\"word_count\":150,\"flags\":[\"no_grade_level\"]},\"back_ocr\":{\"lrn\":null,\"grades_found\":9,\"word_count\":91,\"flags\":[\"no_grade_level\"]},\"front_flags\":[\"no_grade_level\"],\"back_flags\":[\"no_grade_level\"],\"lrn_source\":\"front\",\"grades_primary_source\":\"back\"}', NULL, 'flagged_for_review', 'Manual verification required for report card authenticity', NULL, 0, '2025-11-16 11:29:04', '2025-11-16 11:29:04'),
(65, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763292566-74f4564999.jpg', 'ImageUploads/report_cards/2025/9999-1763292566-f3eb75291d.png', '{\"lrn\":\"109717230118\",\"grades_found\":1,\"word_count\":152,\"flags\":[\"no_grade_level\",\"no_keywords\",\"no_grades\"],\"front_ocr\":{\"lrn\":\"109717230118\",\"grades_found\":1,\"word_count\":150,\"flags\":[\"no_grade_level\"]},\"back_ocr\":{\"lrn\":null,\"grades_found\":0,\"word_count\":2,\"flags\":[\"no_keywords\",\"no_grade_level\"]},\"front_flags\":[\"no_grade_level\"],\"back_flags\":[\"no_keywords\",\"no_grade_level\"],\"lrn_source\":\"front\",\"grades_primary_source\":\"front\"}', NULL, 'flagged_for_review', 'Manual verification required for report card authenticity', NULL, 0, '2025-11-16 11:29:29', '2025-11-16 11:29:29'),
(66, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763293055-e242db3dca.jpg', 'ImageUploads/report_cards/2025/9999-1763293055-f73c452d20.jpg', '{\"lrn\":\"109717230118\",\"grades_found\":10,\"word_count\":241,\"flags\":[\"no_grade_level\"],\"front_ocr\":{\"lrn\":\"109717230118\",\"grades_found\":1,\"word_count\":150,\"flags\":[\"no_grade_level\"]},\"back_ocr\":{\"lrn\":null,\"grades_found\":9,\"word_count\":91,\"flags\":[\"no_grade_level\"]},\"lrn_source\":\"front\",\"grades_primary_source\":\"back\"}', NULL, 'flagged_for_review', 'Manual verification required for report card authenticity', NULL, 0, '2025-11-16 11:37:39', '2025-11-16 11:37:39'),
(67, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763293076-f455cb57eb.jpg', 'ImageUploads/report_cards/2025/9999-1763293076-dcec0beaca.jpg', '{\"lrn\":null,\"grades_found\":18,\"word_count\":182,\"flags\":[\"no_grade_level\"],\"front_ocr\":{\"lrn\":null,\"grades_found\":9,\"word_count\":91,\"flags\":[\"no_grade_level\"]},\"back_ocr\":{\"lrn\":null,\"grades_found\":9,\"word_count\":91,\"flags\":[\"no_grade_level\"]},\"lrn_source\":null,\"grades_primary_source\":\"front\"}', NULL, 'flagged_for_review', 'Manual verification required for report card authenticity', NULL, 0, '2025-11-16 11:38:00', '2025-11-16 11:38:00'),
(68, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763293118-cc4f3077c0.jpg', 'ImageUploads/report_cards/2025/9999-1763293118-0bfb4849c4.jpg', '{\"lrn\":null,\"grades_found\":3,\"word_count\":218,\"flags\":[\"no_grade_level\",\"no_grades\"],\"front_ocr\":{\"lrn\":null,\"grades_found\":3,\"word_count\":131,\"flags\":[]},\"back_ocr\":{\"lrn\":null,\"grades_found\":0,\"word_count\":87,\"flags\":[\"no_grade_level\"]},\"lrn_source\":null,\"grades_primary_source\":\"front\"}', NULL, 'flagged_for_review', 'Manual verification required for report card authenticity; Low grade count detected (Total: 3 [Front: 3, Back: 0])', NULL, 0, '2025-11-16 11:38:44', '2025-11-16 11:38:44'),
(69, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763293146-844a9d6cf2.jpg', 'ImageUploads/report_cards/2025/9999-1763293146-9e36780c4d.png', '{\"lrn\":null,\"grades_found\":3,\"word_count\":133,\"flags\":[\"no_keywords\",\"no_grade_level\",\"no_grades\"],\"front_ocr\":{\"lrn\":null,\"grades_found\":3,\"word_count\":131,\"flags\":[]},\"back_ocr\":{\"lrn\":null,\"grades_found\":0,\"word_count\":2,\"flags\":[\"no_keywords\",\"no_grade_level\"]},\"lrn_source\":null,\"grades_primary_source\":\"front\"}', NULL, 'rejected', 'Please submit a report card or send a higher quality image', NULL, 0, '2025-11-16 11:39:08', '2025-11-16 11:39:08'),
(70, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763293157-53b37cf79c.jpg', 'ImageUploads/report_cards/2025/9999-1763293157-05cf24960f.png', '{\"lrn\":null,\"grades_found\":3,\"word_count\":249,\"flags\":[\"no_keywords\",\"no_grade_level\",\"no_grades\"],\"front_ocr\":{\"lrn\":null,\"grades_found\":3,\"word_count\":131,\"flags\":[]},\"back_ocr\":{\"lrn\":null,\"grades_found\":0,\"word_count\":118,\"flags\":[\"no_keywords\",\"no_grade_level\"]},\"lrn_source\":null,\"grades_primary_source\":\"front\"}', NULL, 'flagged_for_review', 'Manual verification required for report card authenticity; Low grade count detected (Total: 3 [Front: 3, Back: 0]); Missing expected report card keywords (quarter, grade, subject, etc.)', NULL, 0, '2025-11-16 11:39:20', '2025-11-16 11:39:20'),
(71, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763293573-3d94d4554c.jpg', 'ImageUploads/report_cards/2025/9999-1763293573-5e359f3ef1.jpg', '{\"lrn\":\"109717230118\",\"grades_found\":10,\"word_count\":241,\"flags\":[\"no_grade_level\"],\"front_ocr\":{\"lrn\":null,\"grades_found\":9,\"word_count\":91,\"flags\":[\"no_grade_level\"]},\"back_ocr\":{\"lrn\":\"109717230118\",\"grades_found\":1,\"word_count\":150,\"flags\":[\"no_grade_level\"]},\"lrn_source\":\"back\",\"grades_primary_source\":\"front\"}', NULL, 'flagged_for_review', 'Manual verification required for report card authenticity', NULL, 0, '2025-11-16 11:46:16', '2025-11-16 11:46:16');
INSERT INTO `report_card_submissions` (`Report_Card_Id`, `student_name`, `student_lrn`, `user_id`, `session_id`, `report_card_front_path`, `report_card_back_path`, `ocr_json`, `form_data_json`, `status`, `flag_reason`, `enrollee_id`, `validation_only`, `created_at`, `updated_at`) VALUES
(72, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763293589-a0fcd4bd3c.jpg', 'ImageUploads/report_cards/2025/9999-1763293589-3853f9889b.jpg', '{\"lrn\":\"109717230118\",\"grades_found\":2,\"word_count\":300,\"flags\":[\"no_grade_level\",\"no_grades\"],\"front_ocr\":{\"lrn\":\"109717230118\",\"grades_found\":1,\"word_count\":150,\"flags\":[\"no_grade_level\"]},\"back_ocr\":{\"lrn\":\"109717230118\",\"grades_found\":1,\"word_count\":150,\"flags\":[\"no_grade_level\"]},\"lrn_source\":\"front\",\"grades_primary_source\":\"front\"}', NULL, 'flagged_for_review', 'Manual verification required for report card authenticity; Low grade count detected (Total: 2 [Front: 1, Back: 1])', NULL, 0, '2025-11-16 11:46:34', '2025-11-16 11:46:34'),
(73, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763293620-6584e3c7e9.png', 'ImageUploads/report_cards/2025/9999-1763293620-2180755a7c.jpg', '{\"lrn\":\"109717230118\",\"grades_found\":1,\"word_count\":152,\"flags\":[\"no_keywords\",\"no_grade_level\",\"no_grades\"],\"front_ocr\":{\"lrn\":null,\"grades_found\":0,\"word_count\":2,\"flags\":[\"no_keywords\",\"no_grade_level\"]},\"back_ocr\":{\"lrn\":\"109717230118\",\"grades_found\":1,\"word_count\":150,\"flags\":[\"no_grade_level\"]},\"lrn_source\":\"back\",\"grades_primary_source\":\"back\"}', NULL, 'rejected', 'Please submit a report card', NULL, 0, '2025-11-16 11:47:02', '2025-11-16 11:47:02'),
(74, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763293636-6e89508c69.png', 'ImageUploads/report_cards/2025/9999-1763293636-b8d6a95794.jpg', '{\"lrn\":\"109717230118\",\"grades_found\":1,\"word_count\":268,\"flags\":[\"no_keywords\",\"no_grade_level\",\"no_grades\"],\"front_ocr\":{\"lrn\":null,\"grades_found\":0,\"word_count\":118,\"flags\":[\"no_keywords\",\"no_grade_level\"]},\"back_ocr\":{\"lrn\":\"109717230118\",\"grades_found\":1,\"word_count\":150,\"flags\":[\"no_grade_level\"]},\"lrn_source\":\"back\",\"grades_primary_source\":\"back\"}', NULL, 'rejected', 'Please submit a report card', NULL, 0, '2025-11-16 11:47:18', '2025-11-16 11:47:18'),
(75, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763293662-51084008c8.png', 'ImageUploads/report_cards/2025/9999-1763293662-6932102ad2.jpg', '{\"lrn\":\"109717230118\",\"grades_found\":2,\"word_count\":178,\"flags\":[\"no_grade_level\",\"no_grades\"],\"front_ocr\":{\"lrn\":null,\"grades_found\":1,\"word_count\":28,\"flags\":[\"no_grade_level\"]},\"back_ocr\":{\"lrn\":\"109717230118\",\"grades_found\":1,\"word_count\":150,\"flags\":[\"no_grade_level\"]},\"lrn_source\":\"back\",\"grades_primary_source\":\"front\"}', NULL, 'flagged_for_review', 'Manual verification required for report card authenticity; Low grade count detected (Total: 2 [Front: 1, Back: 1])', NULL, 0, '2025-11-16 11:47:44', '2025-11-16 11:47:44'),
(76, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763293862-7e12ab336e.png', 'ImageUploads/report_cards/2025/9999-1763293862-df7eab8c4c.jpg', '{\"lrn\":\"109717230118\",\"grades_found\":1,\"word_count\":268,\"flags\":[\"no_keywords\",\"no_grade_level\",\"no_grades\"],\"front_ocr\":{\"lrn\":null,\"grades_found\":0,\"word_count\":118,\"flags\":[\"no_keywords\",\"no_grade_level\"]},\"back_ocr\":{\"lrn\":\"109717230118\",\"grades_found\":1,\"word_count\":150,\"flags\":[\"no_grade_level\"]},\"lrn_source\":\"back\",\"grades_primary_source\":\"back\"}', NULL, 'rejected', 'Please submit a report card', NULL, 0, '2025-11-16 11:51:04', '2025-11-16 11:51:04'),
(77, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763293890-1eb8c57100.png', 'ImageUploads/report_cards/2025/9999-1763293890-3c9f2ca06a.png', '{\"lrn\":null,\"grades_found\":0,\"word_count\":120,\"flags\":[\"no_keywords\",\"no_grade_level\",\"no_grades\",\"both_no_keywords\",\"both_no_grades\"],\"front_ocr\":{\"lrn\":null,\"grades_found\":0,\"word_count\":118,\"flags\":[\"no_keywords\",\"no_grade_level\"]},\"back_ocr\":{\"lrn\":null,\"grades_found\":0,\"word_count\":2,\"flags\":[\"no_keywords\",\"no_grade_level\"]},\"lrn_source\":null,\"grades_primary_source\":null}', NULL, 'rejected', 'Please submit a report card', NULL, 0, '2025-11-16 11:51:30', '2025-11-16 11:51:30'),
(78, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763293912-1ab703bc12.png', 'ImageUploads/report_cards/2025/9999-1763293912-3c5d582dd4.jpg', '{\"lrn\":null,\"grades_found\":9,\"word_count\":209,\"flags\":[\"no_keywords\",\"no_grade_level\"],\"front_ocr\":{\"lrn\":null,\"grades_found\":0,\"word_count\":118,\"flags\":[\"no_keywords\",\"no_grade_level\"]},\"back_ocr\":{\"lrn\":null,\"grades_found\":9,\"word_count\":91,\"flags\":[\"no_grade_level\"]},\"lrn_source\":null,\"grades_primary_source\":\"back\"}', NULL, 'rejected', 'Please submit a report card', NULL, 0, '2025-11-16 11:51:55', '2025-11-16 11:51:55'),
(79, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763293942-6abd835cd2.jpg', 'ImageUploads/report_cards/2025/9999-1763293942-fcc2164c03.jpg', '{\"lrn\":null,\"grades_found\":10,\"word_count\":252,\"flags\":[\"no_grade_level\"],\"front_ocr\":{\"lrn\":null,\"grades_found\":1,\"word_count\":161,\"flags\":[\"no_grade_level\"]},\"back_ocr\":{\"lrn\":null,\"grades_found\":9,\"word_count\":91,\"flags\":[\"no_grade_level\"]},\"lrn_source\":null,\"grades_primary_source\":\"back\"}', NULL, 'flagged_for_review', 'Manual verification required for report card authenticity', NULL, 0, '2025-11-16 11:52:28', '2025-11-16 11:52:28'),
(80, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763293970-904accd815.jpg', 'ImageUploads/report_cards/2025/9999-1763293970-c43fa09c31.jpg', '{\"lrn\":null,\"grades_found\":9,\"word_count\":178,\"flags\":[\"no_grade_level\"],\"front_ocr\":{\"lrn\":null,\"grades_found\":0,\"word_count\":87,\"flags\":[\"no_grade_level\"]},\"back_ocr\":{\"lrn\":null,\"grades_found\":9,\"word_count\":91,\"flags\":[\"no_grade_level\"]},\"lrn_source\":null,\"grades_primary_source\":\"back\"}', NULL, 'rejected', 'Please submit a report card', NULL, 0, '2025-11-16 11:52:55', '2025-11-16 11:52:55'),
(81, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763294174-8da2e07e21.jpg', 'ImageUploads/report_cards/2025/9999-1763294174-996e5a0e17.jpg', '{\"lrn\":\"109717230118\",\"grades_found\":10,\"word_count\":241,\"flags\":[\"no_grade_level\"],\"front_ocr\":{\"lrn\":null,\"grades_found\":9,\"word_count\":91,\"flags\":[\"no_grade_level\"]},\"back_ocr\":{\"lrn\":\"109717230118\",\"grades_found\":1,\"word_count\":150,\"flags\":[\"no_grade_level\"]},\"lrn_source\":\"back\",\"grades_primary_source\":\"front\"}', NULL, 'flagged_for_review', 'Manual verification required for report card authenticity', NULL, 0, '2025-11-16 11:56:17', '2025-11-16 11:56:17'),
(82, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763294213-4d9a7bf0c2.jpg', 'ImageUploads/report_cards/2025/9999-1763294213-ee509433d3.jpg', '{\"lrn\":\"109717230118\",\"grades_found\":2,\"word_count\":300,\"flags\":[\"no_grade_level\",\"no_grades\"],\"front_ocr\":{\"lrn\":\"109717230118\",\"grades_found\":1,\"word_count\":150,\"flags\":[\"no_grade_level\"]},\"back_ocr\":{\"lrn\":\"109717230118\",\"grades_found\":1,\"word_count\":150,\"flags\":[\"no_grade_level\"]},\"lrn_source\":\"front\",\"grades_primary_source\":\"front\"}', NULL, 'flagged_for_review', 'Manual verification required for report card authenticity; Low grade count detected (Total: 2 [Front: 1, Back: 1])', NULL, 0, '2025-11-16 11:56:56', '2025-11-16 11:56:56'),
(83, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763294227-494a2e5247.jpg', 'ImageUploads/report_cards/2025/9999-1763294227-8b546a0608.jpg', '{\"lrn\":\"109717230118\",\"grades_found\":1,\"word_count\":237,\"flags\":[\"no_grade_level\",\"no_grades\"],\"front_ocr\":{\"lrn\":null,\"grades_found\":0,\"word_count\":87,\"flags\":[\"no_grade_level\"]},\"back_ocr\":{\"lrn\":\"109717230118\",\"grades_found\":1,\"word_count\":150,\"flags\":[\"no_grade_level\"]},\"lrn_source\":\"back\",\"grades_primary_source\":\"back\"}', NULL, 'flagged_for_review', 'Manual verification required for report card authenticity; Low grade count detected (Total: 1 [Front: 0, Back: 1])', NULL, 0, '2025-11-16 11:57:11', '2025-11-16 11:57:11'),
(84, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763294249-409c3e117e.jpg', 'ImageUploads/report_cards/2025/9999-1763294249-f1e6b1e342.jpg', '{\"lrn\":null,\"grades_found\":9,\"word_count\":178,\"flags\":[\"no_grade_level\"],\"front_ocr\":{\"lrn\":null,\"grades_found\":0,\"word_count\":87,\"flags\":[\"no_grade_level\"]},\"back_ocr\":{\"lrn\":null,\"grades_found\":9,\"word_count\":91,\"flags\":[\"no_grade_level\"]},\"lrn_source\":null,\"grades_primary_source\":\"back\"}', NULL, 'flagged_for_review', 'Manual verification required for report card authenticity', NULL, 0, '2025-11-16 11:57:33', '2025-11-16 11:57:33'),
(85, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763294263-530c0879f7.jpg', 'ImageUploads/report_cards/2025/9999-1763294263-376b2e039b.jpg', '{\"lrn\":null,\"grades_found\":12,\"word_count\":222,\"flags\":[\"no_grade_level\"],\"front_ocr\":{\"lrn\":null,\"grades_found\":3,\"word_count\":131,\"flags\":[]},\"back_ocr\":{\"lrn\":null,\"grades_found\":9,\"word_count\":91,\"flags\":[\"no_grade_level\"]},\"lrn_source\":null,\"grades_primary_source\":\"back\"}', NULL, 'flagged_for_review', 'Manual verification required for report card authenticity', NULL, 0, '2025-11-16 11:57:47', '2025-11-16 11:57:47'),
(86, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763294303-9fe64db9cd.png', 'ImageUploads/report_cards/2025/9999-1763294303-39e4a86af4.jpg', '{\"lrn\":null,\"grades_found\":10,\"word_count\":119,\"flags\":[\"no_grade_level\"],\"front_ocr\":{\"lrn\":null,\"grades_found\":1,\"word_count\":28,\"flags\":[\"no_grade_level\"]},\"back_ocr\":{\"lrn\":null,\"grades_found\":9,\"word_count\":91,\"flags\":[\"no_grade_level\"]},\"lrn_source\":null,\"grades_primary_source\":\"back\"}', NULL, 'flagged_for_review', 'Manual verification required for report card authenticity', NULL, 0, '2025-11-16 11:58:25', '2025-11-16 11:58:25'),
(87, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763294316-e700c807f2.png', 'ImageUploads/report_cards/2025/9999-1763294316-67d0150461.jpg', '{\"lrn\":null,\"grades_found\":9,\"word_count\":209,\"flags\":[\"no_keywords\",\"no_grade_level\"],\"front_ocr\":{\"lrn\":null,\"grades_found\":0,\"word_count\":118,\"flags\":[\"no_keywords\",\"no_grade_level\"]},\"back_ocr\":{\"lrn\":null,\"grades_found\":9,\"word_count\":91,\"flags\":[\"no_grade_level\"]},\"lrn_source\":null,\"grades_primary_source\":\"back\"}', NULL, 'rejected', 'Please submit a report card', NULL, 0, '2025-11-16 11:58:38', '2025-11-16 11:58:38'),
(88, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763294329-f697166075.png', 'ImageUploads/report_cards/2025/9999-1763294329-1360dab58b.jpg', '{\"lrn\":null,\"grades_found\":10,\"word_count\":270,\"flags\":[\"no_grade_level\"],\"front_ocr\":{\"lrn\":null,\"grades_found\":1,\"word_count\":179,\"flags\":[\"no_grade_level\"]},\"back_ocr\":{\"lrn\":null,\"grades_found\":9,\"word_count\":91,\"flags\":[\"no_grade_level\"]},\"lrn_source\":null,\"grades_primary_source\":\"back\"}', NULL, 'flagged_for_review', 'Manual verification required for report card authenticity', NULL, 0, '2025-11-16 11:58:53', '2025-11-16 11:58:53'),
(89, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763294372-ccce4a705a.png', 'ImageUploads/report_cards/2025/9999-1763294372-402f9a162f.png', '{\"lrn\":null,\"grades_found\":0,\"word_count\":44,\"flags\":[\"no_keywords\",\"no_grade_level\",\"no_grades\",\"low_text\",\"both_no_grades\"],\"front_ocr\":{\"lrn\":null,\"grades_found\":0,\"word_count\":2,\"flags\":[\"no_keywords\",\"no_grade_level\"]},\"back_ocr\":{\"lrn\":null,\"grades_found\":0,\"word_count\":42,\"flags\":[\"no_grade_level\"]},\"lrn_source\":null,\"grades_primary_source\":null}', NULL, 'rejected', 'Please submit a report card', NULL, 0, '2025-11-16 11:59:33', '2025-11-16 11:59:33'),
(90, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763294414-0d85fef9d1.png', 'ImageUploads/report_cards/2025/9999-1763294414-bb76439546.png', '{\"lrn\":null,\"grades_found\":0,\"word_count\":15,\"flags\":[\"no_keywords\",\"no_grade_level\",\"no_grades\",\"low_text\",\"both_no_keywords\",\"both_no_grades\",\"both_low_quality\"],\"front_ocr\":{\"lrn\":null,\"grades_found\":0,\"word_count\":2,\"flags\":[\"no_keywords\",\"no_grade_level\"]},\"back_ocr\":{\"lrn\":null,\"grades_found\":0,\"word_count\":13,\"flags\":[\"no_keywords\",\"no_grade_level\"]},\"lrn_source\":null,\"grades_primary_source\":null}', NULL, 'rejected', 'Please submit a report card', NULL, 0, '2025-11-16 12:00:15', '2025-11-16 12:00:15'),
(91, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763294430-18d32ba8c2.png', 'ImageUploads/report_cards/2025/9999-1763294430-4715e3993c.png', '{\"lrn\":null,\"grades_found\":1,\"word_count\":45,\"flags\":[\"no_grade_level\",\"no_keywords\",\"no_grades\",\"low_text\"],\"front_ocr\":{\"lrn\":null,\"grades_found\":1,\"word_count\":32,\"flags\":[\"no_grade_level\"]},\"back_ocr\":{\"lrn\":null,\"grades_found\":0,\"word_count\":13,\"flags\":[\"no_keywords\",\"no_grade_level\"]},\"lrn_source\":null,\"grades_primary_source\":\"front\"}', NULL, 'rejected', 'Please submit a report card', NULL, 0, '2025-11-16 12:00:32', '2025-11-16 12:00:32'),
(92, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763294464-6b2ed2fdef.jpg', 'ImageUploads/report_cards/2025/9999-1763294464-0a84a65e6f.png', '{\"lrn\":null,\"grades_found\":9,\"word_count\":104,\"flags\":[\"no_grade_level\",\"no_keywords\"],\"front_ocr\":{\"lrn\":null,\"grades_found\":9,\"word_count\":91,\"flags\":[\"no_grade_level\"]},\"back_ocr\":{\"lrn\":null,\"grades_found\":0,\"word_count\":13,\"flags\":[\"no_keywords\",\"no_grade_level\"]},\"lrn_source\":null,\"grades_primary_source\":\"front\"}', NULL, 'rejected', 'Please submit a report card', NULL, 0, '2025-11-16 12:01:07', '2025-11-16 12:01:07'),
(93, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763294485-b36e4fa51d.jpg', 'ImageUploads/report_cards/2025/9999-1763294485-d720c19c5c.png', '{\"lrn\":null,\"grades_found\":11,\"word_count\":261,\"flags\":[\"no_grade_level\",\"no_keywords\"],\"front_ocr\":{\"lrn\":null,\"grades_found\":9,\"word_count\":91,\"flags\":[\"no_grade_level\"]},\"back_ocr\":{\"lrn\":null,\"grades_found\":2,\"word_count\":170,\"flags\":[\"no_keywords\",\"no_grade_level\"]},\"lrn_source\":null,\"grades_primary_source\":\"front\"}', NULL, 'flagged_for_review', 'Manual verification required for report card authenticity; Missing expected report card keywords (quarter, grade, subject, etc.)', NULL, 0, '2025-11-16 12:01:29', '2025-11-16 12:01:29'),
(94, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763295108-0614655944.png', 'ImageUploads/report_cards/2025/9999-1763295108-ac864682dc.png', '{\"lrn\":null,\"grades_found\":1,\"word_count\":175,\"flags\":[\"no_grade_level\",\"no_grades\"],\"front_ocr\":{\"lrn\":null,\"grades_found\":0,\"word_count\":147,\"flags\":[\"no_grade_level\"]},\"back_ocr\":{\"lrn\":null,\"grades_found\":1,\"word_count\":28,\"flags\":[\"no_grade_level\"]},\"lrn_source\":null,\"grades_primary_source\":\"back\"}', NULL, 'flagged_for_review', 'Manual verification required for report card authenticity; Low grade count detected (Total: 1 [Front: 0, Back: 1])', NULL, 0, '2025-11-16 12:11:50', '2025-11-16 12:11:50'),
(95, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763295149-4db2448e72.png', 'ImageUploads/report_cards/2025/9999-1763295149-fa3c39b8e8.png', '{\"lrn\":null,\"grades_found\":0,\"word_count\":149,\"flags\":[\"no_grade_level\",\"no_keywords\",\"no_grades\",\"both_no_grades\"],\"front_ocr\":{\"lrn\":null,\"grades_found\":0,\"word_count\":147,\"flags\":[\"no_grade_level\"]},\"back_ocr\":{\"lrn\":null,\"grades_found\":0,\"word_count\":2,\"flags\":[\"no_keywords\",\"no_grade_level\"]},\"lrn_source\":null,\"grades_primary_source\":null}', NULL, 'rejected', 'Please submit a report card', NULL, 0, '2025-11-16 12:12:30', '2025-11-16 12:12:30'),
(96, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763295169-c29a1623e6.png', 'ImageUploads/report_cards/2025/9999-1763295169-8169e517c8.png', '{\"lrn\":null,\"grades_found\":0,\"word_count\":160,\"flags\":[\"no_grade_level\",\"no_keywords\",\"no_grades\",\"both_no_grades\"],\"front_ocr\":{\"lrn\":null,\"grades_found\":0,\"word_count\":147,\"flags\":[\"no_grade_level\"]},\"back_ocr\":{\"lrn\":null,\"grades_found\":0,\"word_count\":13,\"flags\":[\"no_keywords\",\"no_grade_level\"]},\"lrn_source\":null,\"grades_primary_source\":null}', NULL, 'rejected', 'Please submit a report card', NULL, 0, '2025-11-16 12:12:52', '2025-11-16 12:12:52'),
(97, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763295188-8b680df4f2.png', 'ImageUploads/report_cards/2025/9999-1763295188-b9acc64ed1.png', '{\"lrn\":null,\"grades_found\":1,\"word_count\":30,\"flags\":[\"no_grade_level\",\"no_keywords\",\"no_grades\",\"low_text\"],\"front_ocr\":{\"lrn\":null,\"grades_found\":1,\"word_count\":28,\"flags\":[\"no_grade_level\"]},\"back_ocr\":{\"lrn\":null,\"grades_found\":0,\"word_count\":2,\"flags\":[\"no_keywords\",\"no_grade_level\"]},\"lrn_source\":null,\"grades_primary_source\":\"front\"}', NULL, 'rejected', 'Please submit a report card', NULL, 0, '2025-11-16 12:13:09', '2025-11-16 12:13:09'),
(98, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763295202-61cb10b1a2.png', 'ImageUploads/report_cards/2025/9999-1763295202-8eea542f73.png', '{\"lrn\":null,\"grades_found\":1,\"word_count\":188,\"flags\":[\"no_grade_level\",\"no_keywords\",\"no_grades\"],\"front_ocr\":{\"lrn\":null,\"grades_found\":1,\"word_count\":28,\"flags\":[\"no_grade_level\"]},\"back_ocr\":{\"lrn\":null,\"grades_found\":0,\"word_count\":160,\"flags\":[\"no_keywords\",\"no_grade_level\"]},\"lrn_source\":null,\"grades_primary_source\":\"front\"}', NULL, 'rejected', 'Please submit a report card', NULL, 0, '2025-11-16 12:13:25', '2025-11-16 12:13:25'),
(99, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763295218-a864331486.png', 'ImageUploads/report_cards/2025/9999-1763295218-557ea28bfd.jpg', '{\"lrn\":\"109717230118\",\"grades_found\":2,\"word_count\":178,\"flags\":[\"no_grade_level\",\"no_grades\"],\"front_ocr\":{\"lrn\":null,\"grades_found\":1,\"word_count\":28,\"flags\":[\"no_grade_level\"]},\"back_ocr\":{\"lrn\":\"109717230118\",\"grades_found\":1,\"word_count\":150,\"flags\":[\"no_grade_level\"]},\"lrn_source\":\"back\",\"grades_primary_source\":\"front\"}', NULL, 'flagged_for_review', 'Manual verification required for report card authenticity; Low grade count detected (Total: 2 [Front: 1, Back: 1])', NULL, 0, '2025-11-16 12:13:41', '2025-11-16 12:13:41'),
(100, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763295250-b4982c139e.png', 'ImageUploads/report_cards/2025/9999-1763295250-a356e35394.jpg', '{\"lrn\":\"109717230118\",\"grades_found\":1,\"word_count\":152,\"flags\":[\"no_keywords\",\"no_grade_level\",\"no_grades\"],\"front_ocr\":{\"lrn\":null,\"grades_found\":0,\"word_count\":2,\"flags\":[\"no_keywords\",\"no_grade_level\"]},\"back_ocr\":{\"lrn\":\"109717230118\",\"grades_found\":1,\"word_count\":150,\"flags\":[\"no_grade_level\"]},\"lrn_source\":\"back\",\"grades_primary_source\":\"back\"}', NULL, 'rejected', 'Please submit a report card', NULL, 0, '2025-11-16 12:14:13', '2025-11-16 12:14:13'),
(101, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763295271-2e4389c129.png', 'ImageUploads/report_cards/2025/9999-1763295271-06df550e4f.jpg', '{\"lrn\":\"109717230118\",\"grades_found\":3,\"word_count\":320,\"flags\":[\"no_keywords\",\"no_grade_level\",\"no_grades\"],\"front_ocr\":{\"lrn\":null,\"grades_found\":2,\"word_count\":170,\"flags\":[\"no_keywords\",\"no_grade_level\"]},\"back_ocr\":{\"lrn\":\"109717230118\",\"grades_found\":1,\"word_count\":150,\"flags\":[\"no_grade_level\"]},\"lrn_source\":\"back\",\"grades_primary_source\":\"front\"}', NULL, 'flagged_for_review', 'Manual verification required for report card authenticity; Low grade count detected (Total: 3 [Front: 2, Back: 1]); Missing expected report card keywords (quarter, grade, subject, etc.)', NULL, 0, '2025-11-16 12:14:35', '2025-11-16 12:14:35'),
(102, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763295333-050f7a26c4.png', 'ImageUploads/report_cards/2025/9999-1763295333-9a8b2fcde5.jpg', '{\"lrn\":\"109717230118\",\"grades_found\":2,\"word_count\":189,\"flags\":[\"no_keywords\",\"no_grade_level\",\"no_grades\"],\"front_ocr\":{\"lrn\":null,\"grades_found\":1,\"word_count\":39,\"flags\":[\"no_keywords\",\"no_grade_level\"]},\"back_ocr\":{\"lrn\":\"109717230118\",\"grades_found\":1,\"word_count\":150,\"flags\":[\"no_grade_level\"]},\"lrn_source\":\"back\",\"grades_primary_source\":\"front\"}', NULL, 'flagged_for_review', 'Manual verification required for report card authenticity; Low grade count detected (Total: 2 [Front: 1, Back: 1]); Missing expected report card keywords (quarter, grade, subject, etc.)', NULL, 0, '2025-11-16 12:15:35', '2025-11-16 12:15:35'),
(103, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763295692-13cb37db48.png', 'ImageUploads/report_cards/2025/9999-1763295692-0c74e07e69.png', '{\"lrn\":null,\"grades_found\":1,\"word_count\":30,\"flags\":[\"no_keywords\",\"no_grade_level\",\"no_grades\",\"low_text\"],\"front_ocr\":{\"lrn\":null,\"grades_found\":0,\"word_count\":2,\"flags\":[\"no_keywords\",\"no_grade_level\"]},\"back_ocr\":{\"lrn\":null,\"grades_found\":1,\"word_count\":28,\"flags\":[\"no_grade_level\"]},\"lrn_source\":null,\"grades_primary_source\":\"back\"}', NULL, 'rejected', 'Please submit a report card', NULL, 0, '2025-11-16 12:21:33', '2025-11-16 12:21:33'),
(104, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763295701-3fc0ff4978.png', 'ImageUploads/report_cards/2025/9999-1763295701-10ee4931ed.png', '{\"lrn\":null,\"grades_found\":3,\"word_count\":198,\"flags\":[\"no_keywords\",\"no_grade_level\",\"no_grades\"],\"front_ocr\":{\"lrn\":null,\"grades_found\":2,\"word_count\":170,\"flags\":[\"no_keywords\",\"no_grade_level\"]},\"back_ocr\":{\"lrn\":null,\"grades_found\":1,\"word_count\":28,\"flags\":[\"no_grade_level\"]},\"lrn_source\":null,\"grades_primary_source\":\"front\"}', NULL, 'rejected', 'Please submit a report card', NULL, 0, '2025-11-16 12:21:43', '2025-11-16 12:21:43'),
(105, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763295715-4add2e522c.png', 'ImageUploads/report_cards/2025/9999-1763295715-af7c449619.png', '{\"lrn\":null,\"grades_found\":1,\"word_count\":146,\"flags\":[\"no_keywords\",\"no_grade_level\",\"no_grades\"],\"front_ocr\":{\"lrn\":null,\"grades_found\":0,\"word_count\":118,\"flags\":[\"no_keywords\",\"no_grade_level\"]},\"back_ocr\":{\"lrn\":null,\"grades_found\":1,\"word_count\":28,\"flags\":[\"no_grade_level\"]},\"lrn_source\":null,\"grades_primary_source\":\"back\"}', NULL, 'rejected', 'Please submit a report card', NULL, 0, '2025-11-16 12:21:56', '2025-11-16 12:21:56'),
(106, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763295727-3d97d2a89b.jpg', 'ImageUploads/report_cards/2025/9999-1763295727-343e78bc22.png', '{\"lrn\":\"109717230118\",\"grades_found\":2,\"word_count\":178,\"flags\":[\"no_grade_level\",\"no_grades\"],\"front_ocr\":{\"lrn\":\"109717230118\",\"grades_found\":1,\"word_count\":150,\"flags\":[\"no_grade_level\"]},\"back_ocr\":{\"lrn\":null,\"grades_found\":1,\"word_count\":28,\"flags\":[\"no_grade_level\"]},\"lrn_source\":\"front\",\"grades_primary_source\":\"front\"}', NULL, 'rejected', 'Please submit a report card', NULL, 0, '2025-11-16 12:22:09', '2025-11-16 12:22:09'),
(107, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763295740-aba4fc5f97.jpg', 'ImageUploads/report_cards/2025/9999-1763295740-41cd347e3e.png', '{\"lrn\":null,\"grades_found\":10,\"word_count\":119,\"flags\":[\"no_grade_level\"],\"front_ocr\":{\"lrn\":null,\"grades_found\":9,\"word_count\":91,\"flags\":[\"no_grade_level\"]},\"back_ocr\":{\"lrn\":null,\"grades_found\":1,\"word_count\":28,\"flags\":[\"no_grade_level\"]},\"lrn_source\":null,\"grades_primary_source\":\"front\"}', NULL, 'flagged_for_review', 'Manual verification required for report card authenticity', NULL, 0, '2025-11-16 12:22:22', '2025-11-16 12:22:22'),
(108, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763295754-f5af875866.jpg', 'ImageUploads/report_cards/2025/9999-1763295755-1685000fc5.jpg', '{\"lrn\":\"109717230118\",\"grades_found\":10,\"word_count\":241,\"flags\":[\"no_grade_level\"],\"front_ocr\":{\"lrn\":null,\"grades_found\":9,\"word_count\":91,\"flags\":[\"no_grade_level\"]},\"back_ocr\":{\"lrn\":\"109717230118\",\"grades_found\":1,\"word_count\":150,\"flags\":[\"no_grade_level\"]},\"lrn_source\":\"back\",\"grades_primary_source\":\"front\"}', NULL, 'flagged_for_review', 'Manual verification required for report card authenticity', NULL, 0, '2025-11-16 12:22:38', '2025-11-16 12:22:38'),
(109, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763295767-644e994390.jpg', 'ImageUploads/report_cards/2025/9999-1763295767-6224277187.jpg', '{\"lrn\":\"109717230118\",\"grades_found\":2,\"word_count\":311,\"flags\":[\"no_grade_level\",\"no_grades\"],\"front_ocr\":{\"lrn\":null,\"grades_found\":1,\"word_count\":161,\"flags\":[\"no_grade_level\"]},\"back_ocr\":{\"lrn\":\"109717230118\",\"grades_found\":1,\"word_count\":150,\"flags\":[\"no_grade_level\"]},\"lrn_source\":\"back\",\"grades_primary_source\":\"front\"}', NULL, 'rejected', 'Please submit a report card', NULL, 0, '2025-11-16 12:22:54', '2025-11-16 12:22:54'),
(110, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763295801-07290f3c7b.png', 'ImageUploads/report_cards/2025/9999-1763295801-3f789b9eef.jpg', '{\"lrn\":\"109717230118\",\"grades_found\":2,\"word_count\":178,\"flags\":[\"no_grade_level\",\"no_grades\"],\"front_ocr\":{\"lrn\":null,\"grades_found\":1,\"word_count\":28,\"flags\":[\"no_grade_level\"]},\"back_ocr\":{\"lrn\":\"109717230118\",\"grades_found\":1,\"word_count\":150,\"flags\":[\"no_grade_level\"]},\"lrn_source\":\"back\",\"grades_primary_source\":\"front\"}', NULL, 'rejected', 'Please submit a report card', NULL, 0, '2025-11-16 12:23:23', '2025-11-16 12:23:23'),
(111, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763295827-031ad20d38.jpg', 'ImageUploads/report_cards/2025/9999-1763295827-60494bcebb.jpg', '{\"lrn\":\"109717230118\",\"grades_found\":4,\"word_count\":281,\"flags\":[\"no_grade_level\",\"no_grades\"],\"front_ocr\":{\"lrn\":null,\"grades_found\":3,\"word_count\":131,\"flags\":[]},\"back_ocr\":{\"lrn\":\"109717230118\",\"grades_found\":1,\"word_count\":150,\"flags\":[\"no_grade_level\"]},\"lrn_source\":\"back\",\"grades_primary_source\":\"front\"}', NULL, 'flagged_for_review', 'Manual verification required for report card authenticity; Low grade count detected (Total: 4 [Front: 3, Back: 1])', NULL, 0, '2025-11-16 12:23:52', '2025-11-16 12:23:52'),
(112, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763295849-a526eaafe9.jpg', 'ImageUploads/report_cards/2025/9999-1763295849-e49e7c3f0b.jpg', '{\"lrn\":\"109717230118\",\"grades_found\":2,\"word_count\":300,\"flags\":[\"no_grade_level\",\"no_grades\"],\"front_ocr\":{\"lrn\":\"109717230118\",\"grades_found\":1,\"word_count\":150,\"flags\":[\"no_grade_level\"]},\"back_ocr\":{\"lrn\":\"109717230118\",\"grades_found\":1,\"word_count\":150,\"flags\":[\"no_grade_level\"]},\"lrn_source\":\"front\",\"grades_primary_source\":\"front\"}', NULL, 'rejected', 'Please submit a report card', NULL, 0, '2025-11-16 12:24:13', '2025-11-16 12:24:13'),
(113, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763296035-da3613d453.jpg', 'ImageUploads/report_cards/2025/9999-1763296035-f041106fda.jpg', '{\"error\":\"Duplicate images detected\",\"front_ocr\":null,\"back_ocr\":null}', NULL, 'rejected', 'Front and back images are identical. Please submit different images for front and back of report card.', NULL, 0, '2025-11-16 12:27:13', '2025-11-16 12:27:13'),
(114, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763296233-7372714f71.jpg', 'ImageUploads/report_cards/2025/9999-1763296233-306b325052.jpg', '{\"lrn\":\"109717230118\",\"grades_found\":10,\"word_count\":241,\"flags\":[\"no_grade_level\"],\"front_ocr\":{\"lrn\":null,\"grades_found\":9,\"word_count\":91,\"flags\":[\"no_grade_level\"]},\"back_ocr\":{\"lrn\":\"109717230118\",\"grades_found\":1,\"word_count\":150,\"flags\":[\"no_grade_level\"]},\"lrn_source\":\"back\",\"grades_primary_source\":\"front\"}', NULL, 'flagged_for_review', 'Manual verification required for report card authenticity', NULL, 0, '2025-11-16 12:30:37', '2025-11-16 12:30:37'),
(115, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763296253-aa730cf53c.png', 'ImageUploads/report_cards/2025/9999-1763296253-e475e18c74.jpg', '{\"lrn\":\"109717230118\",\"grades_found\":1,\"word_count\":152,\"flags\":[\"no_keywords\",\"no_grade_level\",\"no_grades\"],\"front_ocr\":{\"lrn\":null,\"grades_found\":0,\"word_count\":2,\"flags\":[\"no_keywords\",\"no_grade_level\"]},\"back_ocr\":{\"lrn\":\"109717230118\",\"grades_found\":1,\"word_count\":150,\"flags\":[\"no_grade_level\"]},\"lrn_source\":\"back\",\"grades_primary_source\":\"back\"}', NULL, 'rejected', 'Please submit a report card', NULL, 0, '2025-11-16 12:30:55', '2025-11-16 12:30:55'),
(116, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763296269-355ea7af2f.png', 'ImageUploads/report_cards/2025/9999-1763296269-81e736ce92.jpg', '{\"lrn\":\"109717230118\",\"grades_found\":2,\"word_count\":178,\"flags\":[\"no_grade_level\",\"no_grades\"],\"front_ocr\":{\"lrn\":null,\"grades_found\":1,\"word_count\":28,\"flags\":[\"no_grade_level\"]},\"back_ocr\":{\"lrn\":\"109717230118\",\"grades_found\":1,\"word_count\":150,\"flags\":[\"no_grade_level\"]},\"lrn_source\":\"back\",\"grades_primary_source\":\"front\"}', NULL, 'rejected', 'Please submit a report card', NULL, 0, '2025-11-16 12:31:12', '2025-11-16 12:31:12'),
(117, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763296302-14de1e9b01.jpg', 'ImageUploads/report_cards/2025/9999-1763296302-7d83d880cf.jpg', '{\"lrn\":\"109717230118\",\"grades_found\":2,\"word_count\":311,\"flags\":[\"no_grade_level\",\"no_grades\"],\"front_ocr\":{\"lrn\":null,\"grades_found\":1,\"word_count\":161,\"flags\":[\"no_grade_level\"]},\"back_ocr\":{\"lrn\":\"109717230118\",\"grades_found\":1,\"word_count\":150,\"flags\":[\"no_grade_level\"]},\"lrn_source\":\"back\",\"grades_primary_source\":\"front\"}', NULL, 'rejected', 'Please submit a report card', NULL, 0, '2025-11-16 12:31:48', '2025-11-16 12:31:48'),
(118, 'Kenneth Alojado', '234523452345', NULL, NULL, 'ImageUploads/report_cards/2025/9999-1763296319-94d3e4b64e.jpg', 'ImageUploads/report_cards/2025/9999-1763296319-fb60925e2f.jpg', '{\"lrn\":null,\"grades_found\":10,\"word_count\":252,\"flags\":[\"no_grade_level\"],\"front_ocr\":{\"lrn\":null,\"grades_found\":1,\"word_count\":161,\"flags\":[\"no_grade_level\"]},\"back_ocr\":{\"lrn\":null,\"grades_found\":9,\"word_count\":91,\"flags\":[\"no_grade_level\"]},\"lrn_source\":null,\"grades_primary_source\":\"back\"}', NULL, 'flagged_for_review', 'Manual verification required for report card authenticity', NULL, 0, '2025-11-16 12:32:04', '2025-11-16 12:32:04'),
(134, 'Maya Dein River', '283475892345', 17, '9397efb04e465aaefaa20af9a0ae8892', 'ImageUploads/report_cards/2025/17-1763318763-0a8d0e68e7.jpg', 'ImageUploads/report_cards/2025/17-1763318763-7c0cdd50d1.jpg', '{\"lrn\":\"109717230118\",\"grades_found\":10,\"word_count\":241,\"flags\":[\"no_grade_level\"],\"front_ocr\":{\"lrn\":\"109717230118\",\"grades_found\":1,\"word_count\":150,\"flags\":[\"no_grade_level\"]},\"back_ocr\":{\"lrn\":null,\"grades_found\":9,\"word_count\":91,\"flags\":[\"no_grade_level\"]},\"lrn_source\":\"front\",\"grades_primary_source\":\"back\"}', NULL, 'flagged_for_review', 'Manual verification required for report card authenticity', 132, 0, '2025-11-16 18:46:09', '2025-11-16 18:46:09'),
(135, 'Maya Dein River', '283475892345', 17, '9397efb04e465aaefaa20af9a0ae8892', 'ImageUploads/report_cards/2025/17-1763318778-c68c0ad347.jpg', 'ImageUploads/report_cards/2025/17-1763318778-976b6d2b66.jpg', '{\"lrn\":\"109717230118\",\"grades_found\":10,\"word_count\":241,\"flags\":[\"no_grade_level\"],\"front_ocr\":{\"lrn\":\"109717230118\",\"grades_found\":1,\"word_count\":150,\"flags\":[\"no_grade_level\"]},\"back_ocr\":{\"lrn\":null,\"grades_found\":9,\"word_count\":91,\"flags\":[\"no_grade_level\"]},\"lrn_source\":\"front\",\"grades_primary_source\":\"back\"}', NULL, 'flagged_for_review', 'Manual verification required for report card authenticity', NULL, 1, '2025-11-16 18:46:23', '2025-11-16 18:46:23'),
(139, 'Vincent Von Van Gough', '891789162737', 17, 'd9e970ccce9e1a77ae54cd9cb742a742', 'ImageUploads/report_cards/2025/17-1763360896-b9db232052.jpg', 'ImageUploads/report_cards/2025/17-1763360897-a35eaf2431.jpg', '{\"lrn\":\"109717230118\",\"grades_found\":10,\"word_count\":241,\"flags\":[\"no_grade_level\"],\"front_ocr\":{\"lrn\":\"109717230118\",\"grades_found\":1,\"word_count\":150,\"flags\":[\"no_grade_level\"]},\"back_ocr\":{\"lrn\":null,\"grades_found\":9,\"word_count\":91,\"flags\":[\"no_grade_level\"]},\"lrn_source\":\"front\",\"grades_primary_source\":\"back\"}', NULL, 'flagged_for_review', 'Manual verification required for report card authenticity', 133, 0, '2025-11-17 06:28:21', '2025-11-17 06:28:21'),
(141, 'Joseph Di Ko Alam Racelis', '092173490871', 17, 'd9e970ccce9e1a77ae54cd9cb742a742', 'ImageUploads/report_cards/2025/17-1763361197-2985db1b31.jpg', 'ImageUploads/report_cards/2025/17-1763361197-06869bc64c.jpg', '{\"lrn\":\"109717230118\",\"grades_found\":10,\"word_count\":241,\"flags\":[\"no_grade_level\"],\"front_ocr\":{\"lrn\":\"109717230118\",\"grades_found\":1,\"word_count\":150,\"flags\":[\"no_grade_level\"]},\"back_ocr\":{\"lrn\":null,\"grades_found\":9,\"word_count\":91,\"flags\":[\"no_grade_level\"]},\"lrn_source\":\"front\",\"grades_primary_source\":\"back\"}', NULL, 'flagged_for_review', 'Manual verification required for report card authenticity', 134, 0, '2025-11-17 06:33:22', '2025-11-17 06:33:22'),
(143, 'Tony Abigail Stark', '162348971629', 17, 'd9e970ccce9e1a77ae54cd9cb742a742', 'ImageUploads/report_cards/2025/17-1763361323-a0cfc4ff1c.jpg', 'ImageUploads/report_cards/2025/17-1763361323-07dd454d9f.jpg', '{\"lrn\":\"109717230118\",\"grades_found\":10,\"word_count\":241,\"flags\":[\"no_grade_level\"],\"front_ocr\":{\"lrn\":\"109717230118\",\"grades_found\":1,\"word_count\":150,\"flags\":[\"no_grade_level\"]},\"back_ocr\":{\"lrn\":null,\"grades_found\":9,\"word_count\":91,\"flags\":[\"no_grade_level\"]},\"lrn_source\":\"front\",\"grades_primary_source\":\"back\"}', NULL, 'flagged_for_review', 'Manual verification required for report card authenticity', 135, 0, '2025-11-17 06:35:27', '2025-11-17 06:35:27'),
(145, 'Knight Gay Mega', '827459823478', 17, 'd9e970ccce9e1a77ae54cd9cb742a742', 'ImageUploads/report_cards/2025/17-1763362605-ab9272e5cd.jpg', 'ImageUploads/report_cards/2025/17-1763362605-98037e706a.png', '{\"lrn\":null,\"grades_found\":4,\"word_count\":159,\"flags\":[\"no_grade_level\",\"no_grades\"],\"front_ocr\":{\"lrn\":null,\"grades_found\":3,\"word_count\":131,\"flags\":[]},\"back_ocr\":{\"lrn\":null,\"grades_found\":1,\"word_count\":28,\"flags\":[\"no_grade_level\"]},\"lrn_source\":null,\"grades_primary_source\":\"front\"}', NULL, 'flagged_for_review', 'Manual verification required for report card authenticity; Low grade count detected (Total: 4 [Front: 3, Back: 1])', 136, 0, '2025-11-17 06:56:49', '2025-11-17 06:56:49'),
(157, 'John Mark Jimenez Villa Berde', '238947658792', 17, 'd9e970ccce9e1a77ae54cd9cb742a742', 'ImageUploads/report_cards/2025/17-1763363929-ab3ff0d0bd.jpg', 'ImageUploads/report_cards/2025/17-1763363929-c332e6b7dd.jpg', '{\"lrn\":\"109717230118\",\"grades_found\":10,\"word_count\":241,\"flags\":[\"no_grade_level\"],\"front_ocr\":{\"lrn\":\"109717230118\",\"grades_found\":1,\"word_count\":150,\"flags\":[\"no_grade_level\"]},\"back_ocr\":{\"lrn\":null,\"grades_found\":9,\"word_count\":91,\"flags\":[\"no_grade_level\"]},\"lrn_source\":\"front\",\"grades_primary_source\":\"back\"}', NULL, 'flagged_for_review', 'Manual verification required for report card authenticity', 137, 0, '2025-11-17 07:18:53', '2025-11-17 07:18:53');

-- --------------------------------------------------------

--
-- Table structure for table `school_year_details`
--

CREATE TABLE `school_year_details` (
  `School_Year_Details_Id` int(11) NOT NULL,
  `Starting_Date` date NOT NULL,
  `Ending_Date` date NOT NULL,
  `Created_At` timestamp NOT NULL DEFAULT current_timestamp(),
  `start_year` smallint(6) GENERATED ALWAYS AS (year(`Starting_Date`)) STORED,
  `end_year` smallint(6) GENERATED ALWAYS AS (year(`Ending_Date`)) STORED,
  `Is_Expired` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `school_year_details`
--

INSERT INTO `school_year_details` (`School_Year_Details_Id`, `Starting_Date`, `Ending_Date`, `Created_At`, `Is_Expired`) VALUES
(1, '2025-11-12', '2026-11-10', '2025-11-06 08:45:33', 0);

-- --------------------------------------------------------

--
-- Table structure for table `sections`
--

CREATE TABLE `sections` (
  `Section_Id` bigint(20) NOT NULL,
  `Section_Name` varchar(50) NOT NULL,
  `Grade_Level_Id` int(20) NOT NULL,
  `Is_Archived` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sections`
--

INSERT INTO `sections` (`Section_Id`, `Section_Name`, `Grade_Level_Id`, `Is_Archived`) VALUES
(1, 'Sampaguita', 1, 0),
(2, 'Gumamela', 1, 0),
(3, 'Redwood', 3, 0),
(4, 'Narra', 7, 0),
(5, 'Maria', 2, 0),
(6, 'Rizal', 4, 0),
(7, 'Bonifacio', 5, 0),
(8, 'Dagohoy', 6, 0),
(9, 'Del Pilar', 8, 0),
(10, 'Mabini', 5, 0),
(13, 'Aguinaldo', 5, 0),
(14, 'Quezon', 5, 0),
(15, 'De Luna', 5, 0),
(16, 'Prosperity', 7, 0),
(17, ' Bougainvillea', 1, 0),
(18, 'Courteous', 3, 0),
(19, 'Righteous', 1, 0),
(20, 'Elegance', 7, 0),
(21, 'Ilang-Ilang', 1, 0),
(22, 'Honesty', 2, 0),
(24, 'Clara ', 2, 0),
(25, 'Ibarra', 2, 0),
(26, 'Diamond', 5, 0),
(27, 'Lotus', 6, 0),
(28, 'Joy', 1, 0),
(29, 'Calmness', 5, 0);

-- --------------------------------------------------------

--
-- Table structure for table `section_advisers`
--

CREATE TABLE `section_advisers` (
  `Section_Advisers_Id` int(11) NOT NULL,
  `Section_Id` bigint(20) NOT NULL,
  `Staff_Id` int(11) NOT NULL,
  `School_Year_Details_Id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `section_advisers`
--

INSERT INTO `section_advisers` (`Section_Advisers_Id`, `Section_Id`, `Staff_Id`, `School_Year_Details_Id`) VALUES
(56, 25, 38, 1),
(59, 5, 28, 1),
(66, 2, 61, 1),
(67, 7, 63, 1),
(68, 18, 62, 1),
(71, 17, 64, 1),
(76, 20, 66, 1);

-- --------------------------------------------------------

--
-- Table structure for table `section_schedules`
--

CREATE TABLE `section_schedules` (
  `Section_Schedules_Id` int(11) NOT NULL,
  `Section_Subjects_Id` int(11) NOT NULL,
  `Schedule_Day` tinyint(4) NOT NULL,
  `Time_Start` time NOT NULL,
  `Time_End` time NOT NULL,
  `Created_At` datetime NOT NULL DEFAULT current_timestamp(),
  `School_Year_Details_Id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `section_schedules`
--

INSERT INTO `section_schedules` (`Section_Schedules_Id`, `Section_Subjects_Id`, `Schedule_Day`, `Time_Start`, `Time_End`, `Created_At`, `School_Year_Details_Id`) VALUES
(2, 32, 2, '11:00:00', '00:00:00', '2025-10-09 01:24:46', 1),
(8, 104, 3, '09:30:00', '10:30:00', '2025-11-04 22:47:07', 1),
(11, 93, 1, '11:00:00', '12:00:00', '2025-11-11 15:48:56', 1),
(23, 93, 5, '11:00:00', '12:00:00', '2025-11-11 16:22:35', 1),
(25, 93, 3, '13:00:00', '14:30:00', '2025-11-11 16:27:16', 1),
(29, 119, 2, '11:00:00', '00:00:00', '2025-11-12 03:45:05', 1),
(30, 119, 4, '10:00:00', '11:30:00', '2025-11-12 03:45:05', 1),
(31, 28, 1, '14:00:00', '16:00:00', '2025-11-12 11:36:11', 1),
(32, 28, 3, '07:30:00', '10:00:00', '2025-11-12 11:36:11', 1),
(33, 28, 2, '12:30:00', '13:30:00', '2025-11-13 17:29:42', 1),
(34, 28, 4, '07:00:00', '08:30:00', '2025-11-13 17:29:43', 1),
(35, 28, 5, '12:00:00', '13:30:00', '2025-11-13 17:29:43', 1),
(36, 28, 6, '14:30:00', '15:30:00', '2025-11-13 17:29:44', 1),
(37, 28, 7, '09:00:00', '10:30:00', '2025-11-13 17:29:44', 1);

-- --------------------------------------------------------

--
-- Table structure for table `section_subjects`
--

CREATE TABLE `section_subjects` (
  `Section_Subjects_Id` int(11) NOT NULL,
  `Subject_Id` int(11) NOT NULL,
  `Section_Id` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `section_subjects`
--

INSERT INTO `section_subjects` (`Section_Subjects_Id`, `Subject_Id`, `Section_Id`) VALUES
(117, 1, 1),
(10, 1, 2),
(28, 1, 17),
(32, 1, 19),
(100, 1, 21),
(239, 1, 28),
(12, 22, 1),
(13, 22, 2),
(118, 22, 17),
(119, 22, 19),
(101, 22, 21),
(240, 22, 28),
(110, 40, 7),
(111, 40, 10),
(112, 40, 13),
(113, 40, 14),
(114, 40, 15),
(172, 40, 26),
(246, 40, 29),
(77, 47, 1),
(78, 47, 2),
(79, 47, 17),
(80, 47, 19),
(102, 47, 21),
(241, 47, 28),
(81, 48, 7),
(86, 48, 8),
(82, 48, 10),
(83, 48, 13),
(84, 48, 14),
(85, 48, 15),
(173, 48, 26),
(179, 48, 27),
(247, 48, 29),
(92, 49, 3),
(88, 49, 4),
(103, 49, 5),
(94, 49, 6),
(95, 49, 7),
(87, 49, 8),
(91, 49, 9),
(96, 49, 10),
(97, 49, 13),
(98, 49, 14),
(99, 49, 15),
(89, 49, 16),
(93, 49, 18),
(90, 49, 20),
(104, 49, 22),
(108, 49, 24),
(109, 49, 25),
(174, 49, 26),
(180, 49, 27),
(248, 49, 29),
(120, 52, 1),
(121, 52, 2),
(129, 52, 3),
(138, 52, 4),
(125, 52, 5),
(131, 52, 6),
(132, 52, 7),
(137, 52, 8),
(141, 52, 9),
(133, 52, 10),
(134, 52, 13),
(135, 52, 14),
(136, 52, 15),
(139, 52, 16),
(122, 52, 17),
(130, 52, 18),
(123, 52, 19),
(140, 52, 20),
(124, 52, 21),
(126, 52, 22),
(127, 52, 24),
(128, 52, 25),
(175, 52, 26),
(181, 52, 27),
(242, 52, 28),
(249, 52, 29),
(142, 53, 1),
(143, 53, 2),
(156, 53, 3),
(168, 53, 4),
(149, 53, 5),
(159, 53, 6),
(160, 53, 7),
(167, 53, 8),
(171, 53, 9),
(161, 53, 10),
(162, 53, 13),
(163, 53, 14),
(164, 53, 15),
(169, 53, 16),
(144, 53, 17),
(157, 53, 18),
(145, 53, 19),
(170, 53, 20),
(146, 53, 21),
(150, 53, 22),
(151, 53, 24),
(152, 53, 25),
(176, 53, 26),
(182, 53, 27),
(243, 53, 28),
(250, 53, 29),
(207, 57, 1),
(208, 57, 2),
(221, 57, 3),
(235, 57, 4),
(214, 57, 5),
(224, 57, 6),
(225, 57, 7),
(232, 57, 8),
(238, 57, 9),
(226, 57, 10),
(227, 57, 13),
(228, 57, 14),
(229, 57, 15),
(236, 57, 16),
(209, 57, 17),
(222, 57, 18),
(210, 57, 19),
(237, 57, 20),
(211, 57, 21),
(215, 57, 22),
(216, 57, 24),
(217, 57, 25),
(230, 57, 26),
(233, 57, 27),
(244, 57, 28),
(251, 57, 29);

-- --------------------------------------------------------

--
-- Table structure for table `section_subject_teachers`
--

CREATE TABLE `section_subject_teachers` (
  `Section_Subject_Teacher` int(11) NOT NULL,
  `Section_Subjects_Id` int(11) NOT NULL,
  `Staff_Id` int(11) NOT NULL,
  `School_Year_Details_Id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `section_subject_teachers`
--

INSERT INTO `section_subject_teachers` (`Section_Subject_Teacher`, `Section_Subjects_Id`, `Staff_Id`, `School_Year_Details_Id`) VALUES
(13, 100, 30, 1),
(16, 28, 30, 1),
(17, 32, 30, 1),
(19, 108, 28, 1),
(20, 104, 28, 1),
(21, 109, 28, 1),
(22, 103, 28, 1),
(23, 93, 59, 1),
(24, 112, 66, 1),
(25, 110, 66, 1),
(26, 114, 67, 1),
(27, 172, 67, 1),
(28, 111, 62, 1),
(29, 113, 62, 1),
(30, 83, 63, 1),
(31, 81, 63, 1),
(32, 85, 63, 1),
(33, 10, 28, 1),
(34, 117, 28, 1);

-- --------------------------------------------------------

--
-- Table structure for table `staffs`
--

CREATE TABLE `staffs` (
  `Staff_Id` int(11) NOT NULL,
  `Staff_First_Name` varchar(255) NOT NULL,
  `Staff_Middle_Name` varchar(255) NOT NULL,
  `Staff_Last_Name` varchar(255) NOT NULL,
  `Staff_Address_Id` int(11) DEFAULT NULL,
  `Staff_Identifier_Id` int(11) DEFAULT NULL,
  `Birth_Date` date DEFAULT NULL,
  `Staff_Email` varchar(255) NOT NULL,
  `Staff_Contact_Number` varchar(255) NOT NULL,
  `Staff_Status` int(11) NOT NULL DEFAULT 1,
  `Staff_Type` int(11) NOT NULL DEFAULT 2,
  `Position` varchar(255) DEFAULT NULL,
  `Timestamp` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staffs`
--

INSERT INTO `staffs` (`Staff_Id`, `Staff_First_Name`, `Staff_Middle_Name`, `Staff_Last_Name`, `Staff_Address_Id`, `Staff_Identifier_Id`, `Birth_Date`, `Staff_Email`, `Staff_Contact_Number`, `Staff_Status`, `Staff_Type`, `Position`, `Timestamp`) VALUES
(18, 'Jefferson', 'Riano', 'Alojado', 1, 6, NULL, 'jeffersonalojado@gmail.com', '09706573096', 2, 1, 'Head Teacher', '2025-05-05 12:10:06'),
(28, 'Lovely Jane', 'Musa', 'Dela Cruz', NULL, 7, NULL, 'lovelycruz.1317@gmail.com', '09354876649', 1, 2, 'Teacher 3', '2025-05-14 19:05:55'),
(30, 'Benedict', '', 'Llorin', NULL, NULL, NULL, 'Bllorin21@gmail.com', '09206926714', 3, 2, 'Teacher 4', '2025-05-16 11:39:57'),
(38, 'Kenneth Jeffrey', 'Jimenez', 'Alojado', NULL, NULL, NULL, 'alojadokeneth@gmail.com', '09946956168', 1, 2, NULL, '2025-05-16 12:53:11'),
(59, 'Jearard', 'Paderes', 'David', NULL, NULL, NULL, 'Jeararddavid@gmail.com', '09217687673', 1, 2, 'Teacher 1', '2025-11-10 16:40:42'),
(60, 'JEFFERSON', 'RIANO', 'ALOJADO', NULL, NULL, NULL, 'jefferson.alojado@deped.gov.ph', '09483349301', 1, 2, NULL, '2025-11-12 05:03:40'),
(61, 'ELOCEL', 'DELOS SANTOS', 'REYES', NULL, NULL, NULL, 'elocel.reyes@deped.gov.ph', '09685112022', 1, 2, NULL, '2025-11-12 05:07:41'),
(62, 'LENIE', 'SUAREZ', 'GUINTO', NULL, NULL, NULL, 'lenie.guinto@deped.gov.ph', '09331256544', 1, 2, NULL, '2025-11-12 05:08:56'),
(63, 'CHERRY', 'TOLOSA', 'MIRAS', NULL, NULL, NULL, 'cherry.tolosa001@deped.gov.ph', '09338524101', 1, 2, NULL, '2025-11-12 05:10:15'),
(64, 'MA ESPERANZA ', 'BACAYAN', 'RIVADULLA', NULL, NULL, NULL, 'maeperanza.rivadulla003@deped.gov.ph', '09338269148', 1, 2, NULL, '2025-11-12 05:11:34'),
(65, 'YOLANDA', 'DURANTE', 'BALDOVINO', NULL, NULL, NULL, 'yolanda.baldovino001@deped.gov.ph', '09621242573', 1, 2, NULL, '2025-11-12 05:13:18'),
(66, 'MARIETTA ', 'GERONGA', 'LANDICHO', NULL, NULL, NULL, 'marietta.landicho001@deped.gov.ph', '09920169139', 1, 2, NULL, '2025-11-12 05:17:01'),
(67, 'Galilea', 'Palmero', 'Cuarto', NULL, NULL, NULL, 'galilea.cuarto@deped.gov.ph', '09569775903', 1, 2, NULL, '2025-11-12 05:18:20'),
(68, 'Aldrin', 'Portes', 'Pogi', NULL, NULL, NULL, 'aldrincatubau07@gmail.com', '09266636871', 1, 2, NULL, '2025-11-12 11:43:35');

-- --------------------------------------------------------

--
-- Table structure for table `staff_address`
--

CREATE TABLE `staff_address` (
  `Staff_Address_Id` int(11) NOT NULL,
  `House_Number` varchar(255) NOT NULL,
  `Subd_Name` varchar(255) NOT NULL,
  `Brgy_Name` varchar(255) NOT NULL,
  `Municipality_Name` varchar(255) NOT NULL,
  `Province_Name` varchar(255) NOT NULL,
  `Region` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff_address`
--

INSERT INTO `staff_address` (`Staff_Address_Id`, `House_Number`, `Subd_Name`, `Brgy_Name`, `Municipality_Name`, `Province_Name`, `Region`) VALUES
(1, 'Lot 28', 'N/A', 'Barangay 4', 'Lucena City', 'Quezon Province 342---', 'Iv-a Calabarzon');

-- --------------------------------------------------------

--
-- Table structure for table `staff_Identifiers`
--

CREATE TABLE `staff_Identifiers` (
  `Staff_Identifier_Id` int(11) NOT NULL,
  `Employee_Number` varchar(200) DEFAULT NULL,
  `Philhealth_Number` varchar(300) DEFAULT NULL,
  `TIN` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff_Identifiers`
--

INSERT INTO `staff_Identifiers` (`Staff_Identifier_Id`, `Employee_Number`, `Philhealth_Number`, `TIN`) VALUES
(6, '6L2Ht8ZEECqYt1zyoZctFWVmWlR4VVR0NmM5bjNMVFlvLzg1V0lmakZQaWdXUWZleHE4N256a3p3S1U9', 'VNZE9Y3KQAJW7FziiFJBUkx4TEYwUjZjTCtMU0dIWlVIUEozV1E9PQ==', 'vfmly+i1h/G2gqvYVWBz5nZCSkExL1B5eTY4MDBoM0JlSktoNkE9PQ=='),
(7, 'tD8ZrATugUo16NC3b5Q5qSszSG5MWEhXOGpxd0ppZEtENDA3eWc9PQ==', 'hb4rVAlcolFlZTFL7efpQHAyTFl6Z0lnV0I0djc5V0xpem5NQ1E9PQ==', 'T1YjvMvH19xQuHqlNXYYgXFkSHd3VXFBdkMycGRMdEhob0RVN2c9PQ==');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `Student_Id` bigint(20) NOT NULL,
  `Enrollee_Id` bigint(20) NOT NULL,
  `First_Name` varchar(50) NOT NULL,
  `Last_Name` varchar(50) NOT NULL,
  `Middle_Name` varchar(50) DEFAULT NULL,
  `Suffix` varchar(20) DEFAULT NULL,
  `Birthday` date NOT NULL,
  `Age` int(20) NOT NULL,
  `Sex` varchar(6) NOT NULL,
  `LRN` bigint(12) DEFAULT NULL,
  `Grade_Level_Id` int(20) NOT NULL,
  `Section_Id` bigint(20) DEFAULT NULL,
  `Student_Status` int(2) NOT NULL,
  `Is_Archived` tinyint(1) NOT NULL,
  `Added_At` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`Student_Id`, `Enrollee_Id`, `First_Name`, `Last_Name`, `Middle_Name`, `Suffix`, `Birthday`, `Age`, `Sex`, `LRN`, `Grade_Level_Id`, `Section_Id`, `Student_Status`, `Is_Archived`, `Added_At`) VALUES
(9, 56, 'Koki', 'Dela Cruz', 'Hulio', '', '2020-02-06', 5, 'Male', 214748364121, 2, 5, 2, 1, '2025-05-22 15:52:52'),
(15, 53, 'Jeremiah', 'David', 'Paderes', '', '2014-01-31', 11, '', 214748364119, 2, 5, 3, 1, '2025-06-09 02:33:36'),
(16, 74, 'Ben', 'Garcia', 'Salico', '', '2020-06-19', 5, 'Male', 900000000020, 5, 13, 1, 0, '2025-08-01 16:35:20'),
(17, 83, 'Rawkus', 'Alojarado', 'Sabile', '', '2020-02-10', 5, 'Male', 901927497320, 3, 18, 1, 0, '2025-11-01 18:30:25'),
(18, 64, 'Anthony', 'David', 'Solina', '', '2019-07-12', 6, 'Male', 214748364127, 3, 5, 1, 0, '2025-11-01 18:41:18'),
(20, 14, 'Lovely Jane', 'Dela Cruz', 'Jimenez', '', '0000-00-00', 23465, 'Male', 214748364113, 6, NULL, 1, 0, '2025-11-01 22:17:07'),
(21, 93, 'Aaron', 'De Vera', 'Balmeo', '', '2020-06-15', 5, 'Male', 900000000523, 2, 5, 1, 0, '2025-11-10 14:15:08'),
(22, 73, 'Jearard', 'David', 'Paderes', '', '2018-01-01', 7, 'Male', 900000000019, 4, NULL, 1, 0, '2025-11-10 14:36:31'),
(23, 90, 'Apple', 'Tan', 'Carezo', NULL, '2022-11-06', 3, 'Female', NULL, 1, 17, 1, 0, '2025-11-10 14:39:42'),
(24, 85, 'Bing', 'Wei', 'Ching', 'Jr.', '2012-12-12', 12, 'Male', NULL, 8, NULL, 1, 0, '2025-11-10 14:41:03'),
(25, 65, 'Anthony', 'David', 'Solina', '', '2020-03-19', 5, 'male', 214748364128, 2, 5, 1, 0, '2025-11-10 14:45:08'),
(26, 71, 'Ben', 'Llorin', '', 'Jr.', '2018-02-09', 7, 'male', 90000000017, 2, 5, 1, 0, '2025-11-11 03:05:03'),
(27, 58, 'John Mark', 'Llorin', 'Sabile', 'IV', '2021-05-01', 4, 'Male', 214748364123, 1, NULL, 1, 0, '2025-11-11 14:41:47'),
(28, 72, 'Arjay', 'Iglesia', 'Jimenez', '', '2020-03-19', 0, 'Male', 900000000018, 3, NULL, 1, 0, '2025-11-11 14:43:05'),
(29, 48, 'Jearard', 'David', 'Paderes', 'n/a', '2004-01-01', 21, 'Male', 214748364114, 2, 5, 1, 0, '2025-11-11 14:43:42'),
(31, 94, 'Lander', 'Ibarra', 'Daniel', '', '2019-03-12', 6, 'Female', 900000000145, 2, 5, 1, 0, '2025-11-12 17:15:39'),
(32, 101, 'Jerryme', 'Amortizado', 'Pecho', '', '2018-02-17', 7, 'Male', 107912230476, 4, NULL, 1, 0, '2025-11-13 18:47:36'),
(33, 105, 'Mark Sean', 'Sena ', 'Ricamata', '', '2019-01-15', 6, 'Male', 109732240025, 5, NULL, 1, 0, '2025-11-13 19:10:16'),
(34, 57, 'John Mark', 'Llorin', 'Sabile', 'IV', '2021-05-01', 4, 'Male', 214748364122, 1, NULL, 1, 0, '2025-11-13 19:22:50'),
(35, 103, 'Kenjie', 'Abril', 'Alojado', '', '2019-10-26', 6, 'male', 913689231939, 5, NULL, 1, 0, '2025-11-13 19:23:11'),
(36, 123, 'Aleon Shin', 'Palermo', 'Dela Cruz', '', '2017-07-05', 8, 'Male', 109732220042, 5, NULL, 1, 0, '2025-11-13 19:34:22'),
(37, 97, 'Lovely Jane', 'Dela Cruz', 'Cainta', '', '2018-07-10', 7, 'Female', 234523452345, 7, NULL, 1, 0, '2025-11-13 19:45:43'),
(38, 91, 'Ali', 'Santo', 'Gold', NULL, '2021-06-21', 4, 'Female', NULL, 2, NULL, 1, 0, '2025-11-13 19:48:59'),
(39, 76, 'Pedro', 'San Juan', 'Cainta', 'Jr.', '2010-10-22', 14, 'Male', 759265926404, 2, NULL, 1, 0, '2025-11-13 22:31:31'),
(40, 61, 'Jearard', 'David', 'Paderes', 'Jr.', '2003-02-20', 22, 'Male', NULL, 5, NULL, 1, 0, '2025-11-13 22:36:18'),
(41, 126, 'Onoy', 'Llorin', 'Sabile', '', '2016-10-05', 9, 'Male', 954871031266, 7, 20, 1, 0, '2025-11-14 05:44:00');

-- --------------------------------------------------------

--
-- Table structure for table `student_grades`
--

CREATE TABLE `student_grades` (
  `Student_Grades_Id` int(11) NOT NULL,
  `Section_Subjects_Id` int(11) NOT NULL,
  `Student_Id` bigint(20) NOT NULL,
  `Quarter` tinyint(1) NOT NULL,
  `Academic_Year` int(11) NOT NULL,
  `Grade_Value` decimal(4,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_grades`
--

INSERT INTO `student_grades` (`Student_Grades_Id`, `Section_Subjects_Id`, `Student_Id`, `Quarter`, `Academic_Year`, `Grade_Value`) VALUES
(74, 103, 9, 1, 0, 90.00),
(78, 103, 15, 1, 0, 90.00),
(79, 103, 18, 1, 0, 90.00),
(80, 103, 18, 2, 0, 90.00),
(81, 103, 15, 2, 0, 95.00),
(82, 103, 21, 1, 0, 75.00),
(83, 103, 21, 2, 0, 84.00),
(84, 103, 21, 3, 0, 86.00),
(85, 103, 9, 2, 0, 68.00),
(86, 103, 9, 3, 0, 69.00),
(87, 103, 9, 4, 0, 99.99),
(88, 103, 18, 3, 0, 90.00),
(89, 103, 25, 1, 0, 95.00),
(90, 103, 25, 2, 0, 80.00),
(91, 103, 25, 3, 0, 75.00),
(92, 103, 15, 3, 0, 87.00),
(93, 103, 15, 4, 0, 87.00),
(94, 103, 18, 4, 0, 87.00),
(95, 103, 21, 4, 0, 67.00),
(96, 103, 25, 4, 0, 98.00),
(97, 103, 26, 1, 0, 89.00),
(98, 103, 26, 2, 0, 96.00),
(99, 103, 26, 3, 0, 57.00),
(100, 103, 26, 4, 0, 97.00),
(101, 103, 29, 1, 0, 97.00),
(102, 103, 29, 2, 0, 97.00),
(103, 103, 29, 3, 0, 76.00),
(104, 103, 29, 4, 0, 97.00),
(105, 103, 31, 2, 0, 67.00),
(106, 103, 31, 3, 0, 67.00),
(107, 103, 31, 1, 0, 86.00),
(108, 103, 31, 4, 0, 5.00);

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `Subject_Id` int(20) NOT NULL,
  `Subject_Name` varchar(50) NOT NULL,
  `Is_Archived` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`Subject_Id`, `Subject_Name`, `Is_Archived`) VALUES
(1, 'ESP', 0),
(22, 'PHYSICAL EDUCATION', 0),
(40, 'ARALING PANLIPUNAN', 0),
(47, 'MATHEMATICS', 0),
(48, 'FILIPINO', 0),
(49, 'SIYENSA', 0),
(52, 'GOOD MANNERS AND RIGHT CONDUCT', 0),
(53, 'TECHNOLOGY AND LIVELIHOOD EDUCATION', 0),
(57, 'MAPEH', 0);

-- --------------------------------------------------------

--
-- Table structure for table `teacher_logs`
--

CREATE TABLE `teacher_logs` (
  `User_Log_Id` bigint(20) NOT NULL,
  `User_Id` bigint(20) NOT NULL,
  `Logged_At` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teacher_logs`
--

INSERT INTO `teacher_logs` (`User_Log_Id`, `User_Id`, `Logged_At`) VALUES
(1, 35, '2025-11-05 18:33:16'),
(2, 35, '2025-11-06 23:07:40'),
(3, 35, '2025-11-07 02:23:36'),
(4, 35, '2025-11-07 19:29:38'),
(5, 35, '2025-11-08 00:33:40'),
(6, 35, '2025-11-08 03:03:03'),
(7, 35, '2025-11-08 03:07:12'),
(8, 35, '2025-11-08 11:51:56'),
(9, 35, '2025-11-08 22:58:01'),
(10, 35, '2025-11-10 14:50:12'),
(11, 35, '2025-11-10 18:28:25'),
(12, 35, '2025-11-10 18:30:07'),
(13, 35, '2025-11-10 21:32:34'),
(14, 35, '2025-11-10 21:33:50'),
(15, 35, '2025-11-10 21:42:29'),
(16, 35, '2025-11-10 21:58:15'),
(17, 35, '2025-11-10 22:06:58'),
(18, 35, '2025-11-10 22:16:03'),
(19, 122, '2025-11-10 23:47:46'),
(20, 122, '2025-11-11 00:13:47'),
(21, 35, '2025-11-11 01:14:36'),
(22, 35, '2025-11-11 01:47:33'),
(23, 35, '2025-11-11 13:33:25'),
(24, 122, '2025-11-11 14:59:47'),
(25, 35, '2025-11-11 15:58:14'),
(26, 35, '2025-11-12 01:14:55'),
(27, 35, '2025-11-12 07:05:03'),
(28, 35, '2025-11-12 17:43:49'),
(29, 35, '2025-11-12 18:46:23'),
(30, 125, '2025-11-12 21:55:20'),
(31, 125, '2025-11-12 21:57:20'),
(32, 35, '2025-11-12 22:00:01'),
(33, 35, '2025-11-13 00:48:46'),
(34, 35, '2025-11-13 02:21:13'),
(35, 35, '2025-11-13 02:31:12'),
(36, 35, '2025-11-13 13:25:27'),
(37, 35, '2025-11-13 14:26:04'),
(38, 35, '2025-11-13 14:29:30'),
(39, 125, '2025-11-13 17:39:57'),
(40, 35, '2025-11-13 23:00:08'),
(41, 35, '2025-11-13 23:49:16'),
(42, 35, '2025-11-14 00:25:35'),
(43, 35, '2025-11-14 02:18:28'),
(44, 35, '2025-11-14 02:52:30'),
(45, 35, '2025-11-14 03:27:12'),
(46, 35, '2025-11-14 03:34:25'),
(47, 35, '2025-11-14 04:09:23'),
(48, 35, '2025-11-14 04:43:23'),
(49, 35, '2025-11-14 05:33:50'),
(50, 35, '2025-11-14 05:50:15'),
(51, 35, '2025-11-14 06:01:33'),
(52, 35, '2025-11-14 06:10:37'),
(53, 35, '2025-11-14 07:05:07'),
(54, 35, '2025-11-14 07:36:58'),
(55, 35, '2025-11-14 11:55:22'),
(56, 35, '2025-11-15 13:23:59'),
(57, 35, '2025-11-15 19:08:28'),
(58, 35, '2025-11-16 20:33:08'),
(59, 35, '2025-11-17 02:48:29'),
(60, 35, '2025-11-17 14:24:46'),
(61, 35, '2025-11-17 14:28:44'),
(62, 35, '2025-11-17 14:36:09'),
(63, 35, '2025-11-17 15:30:32'),
(64, 35, '2025-11-17 15:46:32');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `User_Id` bigint(20) NOT NULL,
  `Registration_Id` bigint(20) DEFAULT NULL,
  `Password` varchar(255) DEFAULT NULL,
  `User_Type` int(2) NOT NULL,
  `Staff_Id` int(11) DEFAULT NULL,
  `Time_created` datetime NOT NULL DEFAULT current_timestamp(),
  `Must_Change_Password` tinyint(1) DEFAULT 1,
  `Profile_Picture_Id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`User_Id`, `Registration_Id`, `Password`, `User_Type`, `Staff_Id`, `Time_created`, `Must_Change_Password`, `Profile_Picture_Id`) VALUES
(17, 87, '$2y$10$iZjCIpfxq2gsZ.8YQoTebeQOgkeF.ACg5FQfTMOCQ0gpBLXjAn/N2', 3, NULL, '2025-05-05 10:59:37', 1, NULL),
(22, 90, '$2y$10$veTCDREbRixg0XdNop16OukxFMg21txEbaTQ8vfx4hkKmlKuoRRJe', 3, NULL, '2025-05-05 10:59:37', 0, NULL),
(27, NULL, '$2y$10$oLTKRndxx.aJK1Qb/TfpXOUFKKQ7sjnUYTQf/zwk.TWqaRB9u5Ehq', 1, 18, '2025-05-05 12:10:06', 1, 1),
(35, NULL, '$2y$10$02tcW1La2xkYWjM619bB6eZdrU5xIWLeGM4abyr2TcS4CV9HHCFeK', 2, 28, '2025-05-14 19:05:55', 0, 2),
(37, NULL, '$2y$10$5Py8XZDwCjtE1X1zMWHKvOq92VH94hOlAxn9wbsbu7KfM.Q0dopeO', 2, 30, '2025-05-16 11:39:57', 1, NULL),
(43, NULL, '$2y$10$ODvOf1hBE/Yv73pKdtgcseZMuT94oFHv3urkF/hW90FgFjrNAr3si', 2, 38, '2025-05-16 12:53:12', 1, NULL),
(64, 117, '$2y$10$otDoXVG5cnU2dQRsttxTRePetwbPL9GjYvutYI2gdrxPci7pJZQ86', 3, NULL, '2025-05-20 16:41:02', 1, NULL),
(69, 122, '$2y$10$A951TLxwZp1SrSvF4QRuWe01F/QSLIj4AEOOhVOzXmPk6HHfjPY1O', 3, NULL, '2025-05-22 14:40:56', 1, NULL),
(93, 164, '$2y$10$ikdyLRyDzcSEcXH6FkPpce8vN7vXR.UiscNWsmPBRUNsCYu92v.5a', 3, NULL, '2025-05-23 07:27:35', 1, NULL),
(94, 165, '$2y$10$hPFCf.HmhWDEDAr7H/VRpuwcNh0EgNbaSNYMUN5DQWKL4Dty84lDG', 3, NULL, '2025-05-23 08:02:15', 1, NULL),
(99, 170, '$2y$10$DjPls72O9QB7Z.hlBjG85OheIF0N1eGF1EplJ.k6rmacCQvtO/4pW', 3, NULL, '2025-10-18 12:05:01', 1, NULL),
(101, 172, '$2y$10$lkLX/5R.NT04eB3eH2HAWO6Rce9DayMdBctr.KzE4ZxCzs.LVfuf.', 3, NULL, '2025-10-23 03:21:52', 1, NULL),
(107, 181, '$2y$10$o30/jnZjgQz.2BJHjhvycezdZXgY5owm6k5t0e6Rg9RCOFqthLx1.', 3, NULL, '2025-11-02 05:58:21', 1, NULL),
(121, 206, '$2y$10$1k8W4jDwvpHLcSCmgTX8/./Z9995ipjQZyU7Q6at15z1O5nHjXxSC', 3, NULL, '2025-11-08 05:36:30', 0, NULL),
(122, NULL, '$2y$10$wE1uuBX.ZELVr4zAjoUZf.lAUonRGWtE49tdadInJqcSsrv.oiK6m', 2, 59, '2025-11-10 16:40:42', 0, NULL),
(124, NULL, '$2y$10$OAQrnyL.94mr6DivSQBi2.26Vz.uUF9Y774mJsU4i8XjvkIQtm9u6', 2, 60, '2025-11-12 05:03:40', 1, NULL),
(125, NULL, '$2y$10$3mubOt9Rs5Iqy75DWTV.A.HaijSx196iwaUzCIwhIsPR22wLzs6xm', 2, 61, '2025-11-12 05:07:41', 1, NULL),
(126, NULL, '$2y$10$iC0RryyT4QzIqjUDXJwbZOuPZpudyUiUvO8YKnYt9Czn6M7Y4Izde', 2, 62, '2025-11-12 05:08:56', 1, NULL),
(127, NULL, '$2y$10$k9pzeTmOTEjpymYARZ/0rO4mhkfOJo1R3gVvvtuzJ4Qista8k9kBa', 2, 63, '2025-11-12 05:10:15', 1, NULL),
(128, NULL, '$2y$10$nc//SbXcBiqapz5BlqZFa.rM83JVwm1aAnCGWI9A0Tu1JjZolktoO', 2, 64, '2025-11-12 05:11:35', 1, NULL),
(129, NULL, '$2y$10$HsWRnqje.TiOHFTWMKxffeTWjxyrtnP.VPiKu/j.a3L3.LzaKp2qu', 2, 65, '2025-11-12 05:13:19', 1, NULL),
(130, NULL, '$2y$10$GyQqf.rlwFazMmYSSMn/J.n2XA3YMGYv4En8D3.tTYG8ApIHc.Rty', 2, 66, '2025-11-12 05:17:01', 1, NULL),
(131, NULL, '$2y$10$rzC716D7C8d2PPg6Exu/0.pMvFUJiv5mhx0jjsfSSWhih6OzWohkS', 2, 67, '2025-11-12 05:18:20', 1, NULL),
(132, 216, '$2y$10$cDEQZoiR2Sem6atmaHlCBuchIUMk3aYQLiUfMbEZ5bCtWMRPAf/hC', 3, NULL, '2025-11-12 10:26:11', 0, NULL),
(133, NULL, '$2y$10$LXxKuRRxTVAtzaN3ZU0YI.lhA1rs1aeQOBaCdUq/Fc66hNoS2AfHW', 2, 68, '2025-11-12 11:43:35', 1, NULL),
(134, 218, '$2y$10$ugxWL2IuJoe775gVcx7Kp.hLImDvI0QGBq5B/E0aX/v.GGw58TSZK', 3, NULL, '2025-11-13 14:52:51', 1, NULL),
(135, 220, '$2y$10$xPRsiC.4Im02f1pDEyXBT.E3HeziUXMCrL.VlHn6tBaKL5bW8zEnu', 3, NULL, '2025-11-13 14:55:56', 1, NULL),
(136, 223, '$2y$10$qkZmfLCVl0.anu1GC8joYOjMviKGcRF5mXvxdP9hwnjhEqyWiYusu', 3, NULL, '2025-11-13 15:19:44', 1, NULL),
(137, 225, '$2y$10$eBd3cyLe8hQryiq1oqbOAejR1xBnyS5hk3H7Sk10h8wSIf0sxnOd6', 3, NULL, '2025-11-13 15:21:04', 1, NULL),
(138, 226, '$2y$10$ZwA/n3LQ394tRnCg7bhGvesdOlK3tb/20ES76wDkKCies4EtekeWi', 3, NULL, '2025-11-13 15:21:22', 1, NULL),
(141, 229, '$2y$10$zfhwmgEtwzA9WAPf19hZT.ea6Ay.vMq6nQR7mZVQAymmrYQsYrLjS', 3, NULL, '2025-11-13 15:42:42', 1, NULL),
(142, 230, '$2y$10$zCtK7.VDnIlqPhTdBAdq7ebznEjOyvAAkeFq5E2axPcvGTrAyzBZG', 3, NULL, '2025-11-13 15:56:15', 1, NULL),
(143, 231, '$2y$10$Tvx2QzRwWfbd73p.q/ej9.LrFfjtRvxXQDqQByIkR9keqYVqw535K', 3, NULL, '2025-11-14 04:54:48', 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_logs`
--

CREATE TABLE `user_logs` (
  `User_Log_Id` bigint(20) NOT NULL,
  `User_Id` bigint(20) NOT NULL,
  `Logged_At` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_logs`
--

INSERT INTO `user_logs` (`User_Log_Id`, `User_Id`, `Logged_At`) VALUES
(1, 17, '2025-11-05 18:30:58'),
(2, 17, '2025-11-06 23:07:20'),
(3, 17, '2025-11-07 01:08:17'),
(4, 17, '2025-11-07 01:34:50'),
(5, 22, '2025-11-07 02:39:14'),
(6, 22, '2025-11-07 02:48:02'),
(7, 17, '2025-11-07 03:42:44'),
(8, 17, '2025-11-07 03:42:54'),
(9, 17, '2025-11-07 07:50:11'),
(10, 17, '2025-11-07 07:59:46'),
(11, 17, '2025-11-07 09:29:49'),
(12, 17, '2025-11-07 19:37:51'),
(13, 17, '2025-11-08 02:19:33'),
(14, 17, '2025-11-08 02:50:37'),
(15, 17, '2025-11-08 11:26:22'),
(16, 17, '2025-11-08 11:28:08'),
(17, 17, '2025-11-08 12:01:10'),
(18, 121, '2025-11-08 12:39:45'),
(19, 121, '2025-11-08 22:58:44'),
(20, 17, '2025-11-08 23:00:06'),
(21, 17, '2025-11-09 01:55:01'),
(22, 17, '2025-11-09 19:57:59'),
(23, 17, '2025-11-09 20:28:38'),
(24, 17, '2025-11-09 20:46:14'),
(25, 17, '2025-11-09 21:00:03'),
(26, 17, '2025-11-09 22:20:42'),
(27, 17, '2025-11-09 22:48:08'),
(28, 17, '2025-11-10 00:08:22'),
(29, 17, '2025-11-10 00:10:16'),
(30, 17, '2025-11-10 00:12:40'),
(31, 17, '2025-11-10 00:34:33'),
(32, 17, '2025-11-10 00:34:57'),
(33, 17, '2025-11-10 03:29:13'),
(34, 22, '2025-11-10 12:27:36'),
(35, 17, '2025-11-10 12:41:57'),
(36, 17, '2025-11-10 14:41:13'),
(37, 17, '2025-11-10 14:44:02'),
(38, 22, '2025-11-10 14:46:59'),
(39, 17, '2025-11-10 17:57:15'),
(40, 17, '2025-11-10 18:20:56'),
(41, 17, '2025-11-10 18:21:48'),
(42, 17, '2025-11-10 18:33:47'),
(43, 17, '2025-11-10 18:44:58'),
(44, 17, '2025-11-10 19:03:28'),
(45, 17, '2025-11-10 19:06:22'),
(46, 17, '2025-11-10 19:18:51'),
(47, 17, '2025-11-10 19:20:12'),
(48, 17, '2025-11-10 19:21:24'),
(49, 17, '2025-11-10 19:43:13'),
(50, 17, '2025-11-10 19:56:37'),
(51, 17, '2025-11-10 20:08:32'),
(52, 17, '2025-11-10 21:37:38'),
(53, 17, '2025-11-10 21:37:57'),
(54, 22, '2025-11-10 22:44:25'),
(55, 17, '2025-11-11 09:59:06'),
(56, 17, '2025-11-11 11:25:10'),
(57, 17, '2025-11-11 13:33:40'),
(58, 17, '2025-11-11 13:34:12'),
(59, 17, '2025-11-11 13:36:40'),
(60, 17, '2025-11-11 13:37:07'),
(61, 17, '2025-11-11 13:39:27'),
(62, 17, '2025-11-11 13:39:27'),
(63, 17, '2025-11-11 13:40:52'),
(64, 17, '2025-11-11 13:55:11'),
(65, 17, '2025-11-11 14:03:53'),
(66, 17, '2025-11-11 14:04:33'),
(67, 17, '2025-11-11 14:04:51'),
(68, 17, '2025-11-11 14:13:39'),
(69, 22, '2025-11-11 14:21:34'),
(70, 17, '2025-11-11 14:23:07'),
(71, 17, '2025-11-11 14:23:14'),
(72, 17, '2025-11-11 14:26:14'),
(73, 17, '2025-11-11 14:32:56'),
(74, 17, '2025-11-11 14:35:29'),
(75, 17, '2025-11-11 14:53:08'),
(77, 17, '2025-11-11 15:20:06'),
(78, 17, '2025-11-11 15:33:34'),
(79, 22, '2025-11-11 15:54:55'),
(80, 17, '2025-11-11 15:56:11'),
(81, 17, '2025-11-11 18:50:20'),
(82, 17, '2025-11-11 18:55:07'),
(83, 17, '2025-11-11 18:55:53'),
(84, 17, '2025-11-11 19:05:08'),
(85, 22, '2025-11-11 19:07:03'),
(86, 17, '2025-11-11 19:47:01'),
(89, 17, '2025-11-11 21:18:20'),
(90, 17, '2025-11-11 21:42:04'),
(91, 17, '2025-11-11 21:42:51'),
(92, 17, '2025-11-11 21:44:58'),
(93, 17, '2025-11-11 21:46:16'),
(94, 17, '2025-11-11 21:50:12'),
(95, 17, '2025-11-11 21:52:52'),
(96, 17, '2025-11-11 21:53:39'),
(97, 17, '2025-11-11 21:56:16'),
(98, 17, '2025-11-11 21:57:09'),
(99, 17, '2025-11-11 22:04:19'),
(100, 17, '2025-11-11 22:09:51'),
(101, 17, '2025-11-11 22:17:49'),
(102, 17, '2025-11-11 22:18:46'),
(103, 17, '2025-11-11 22:20:13'),
(104, 17, '2025-11-11 22:31:58'),
(105, 17, '2025-11-11 22:43:37'),
(106, 17, '2025-11-11 22:59:29'),
(107, 17, '2025-11-11 23:22:26'),
(108, 17, '2025-11-11 23:48:11'),
(109, 17, '2025-11-12 00:29:12'),
(110, 17, '2025-11-12 00:35:58'),
(111, 17, '2025-11-12 01:02:17'),
(112, 17, '2025-11-12 05:22:06'),
(114, 17, '2025-11-12 06:05:59'),
(115, 17, '2025-11-12 07:43:15'),
(116, 17, '2025-11-12 07:44:40'),
(119, 17, '2025-11-12 10:23:52'),
(120, 17, '2025-11-12 11:19:34'),
(121, 22, '2025-11-12 11:37:51'),
(122, 132, '2025-11-12 17:35:17'),
(123, 132, '2025-11-12 18:08:40'),
(124, 132, '2025-11-12 18:42:44'),
(125, 17, '2025-11-12 18:44:29'),
(126, 17, '2025-11-13 00:36:37'),
(127, 17, '2025-11-13 00:47:59'),
(128, 17, '2025-11-13 02:03:38'),
(129, 17, '2025-11-13 02:17:06'),
(130, 17, '2025-11-13 03:02:51'),
(131, 17, '2025-11-13 03:12:59'),
(132, 17, '2025-11-13 05:14:03'),
(133, 17, '2025-11-13 09:53:57'),
(134, 17, '2025-11-13 13:15:43'),
(135, 17, '2025-11-13 13:37:38'),
(136, 17, '2025-11-13 13:38:33'),
(137, 17, '2025-11-13 13:53:45'),
(138, 17, '2025-11-13 18:21:22'),
(139, 17, '2025-11-13 19:54:12'),
(140, 17, '2025-11-13 21:09:55'),
(141, 17, '2025-11-13 21:10:35'),
(142, 17, '2025-11-13 21:16:35'),
(143, 17, '2025-11-13 21:17:24'),
(144, 17, '2025-11-13 21:21:49'),
(145, 17, '2025-11-13 21:23:42'),
(146, 17, '2025-11-13 21:44:53'),
(147, 17, '2025-11-13 22:45:20'),
(148, 17, '2025-11-13 23:28:11'),
(149, 17, '2025-11-14 00:35:02'),
(150, 22, '2025-11-14 01:28:33'),
(151, 22, '2025-11-14 01:32:49'),
(152, 17, '2025-11-14 01:44:10'),
(153, 17, '2025-11-14 03:47:09'),
(154, 17, '2025-11-14 05:10:15'),
(155, 17, '2025-11-14 05:47:36'),
(156, 17, '2025-11-14 07:26:10'),
(157, 17, '2025-11-14 07:45:13'),
(158, 143, '2025-11-14 11:57:00'),
(159, 17, '2025-11-15 13:23:00'),
(160, 17, '2025-11-15 13:28:48'),
(161, 17, '2025-11-15 13:30:13'),
(162, 17, '2025-11-15 13:44:09'),
(163, 17, '2025-11-15 14:46:16'),
(164, 17, '2025-11-15 19:00:19'),
(165, 17, '2025-11-15 19:27:56'),
(166, 17, '2025-11-16 00:31:21'),
(167, 17, '2025-11-16 17:40:19'),
(168, 17, '2025-11-16 20:50:25'),
(169, 17, '2025-11-17 14:25:43'),
(170, 17, '2025-11-17 14:30:39'),
(171, 17, '2025-11-17 14:43:13');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`Announcement_Id`),
  ADD KEY `idx_date_publication` (`Date_Publication`),
  ADD KEY `idx_created_at` (`Created_At`);

--
-- Indexes for table `archive_enrollees`
--
ALTER TABLE `archive_enrollees`
  ADD PRIMARY KEY (`Enrollee_Id`),
  ADD UNIQUE KEY `uniq_lrn` (`Learner_Reference_Number`),
  ADD UNIQUE KEY `uniq_psa` (`Psa_Number`),
  ADD KEY `Enrollee_Address_Id` (`Enrollee_Address_Id`),
  ADD KEY `fk_educatuinal_background` (`Educational_Background_Id`),
  ADD KEY `fk_educatuinal_information` (`Educational_Information_Id`),
  ADD KEY `fk_disabled_student` (`Disabled_Student_Id`),
  ADD KEY `Psa_Image_Id` (`Psa_Image_Id`);

--
-- Indexes for table `archive_enrollment_transactions`
--
ALTER TABLE `archive_enrollment_transactions`
  ADD PRIMARY KEY (`Enrollment_Transaction_Id`),
  ADD KEY `Enrollee_Id` (`Enrollee_Id`),
  ADD KEY `Staff_Id` (`Staff_Id`);

--
-- Indexes for table `archive_section_advisers`
--
ALTER TABLE `archive_section_advisers`
  ADD PRIMARY KEY (`Section_Advisers_Id`),
  ADD UNIQUE KEY `unique_adviser_per_year` (`School_Year_Details_Id`,`Staff_Id`);

--
-- Indexes for table `archive_section_schedules`
--
ALTER TABLE `archive_section_schedules`
  ADD PRIMARY KEY (`Section_Schedules_Id`),
  ADD UNIQUE KEY `unique_sched_per_subject_and_year` (`Section_Subjects_Id`,`Schedule_Day`,`School_Year_Details_Id`),
  ADD KEY `section_schedules_to_school_year` (`School_Year_Details_Id`);

--
-- Indexes for table `archive_section_subject_teachers`
--
ALTER TABLE `archive_section_subject_teachers`
  ADD PRIMARY KEY (`Section_Subject_Teacher`),
  ADD UNIQUE KEY `uniq_subj_teacher_per_year` (`Section_Subjects_Id`,`School_Year_Details_Id`) USING BTREE,
  ADD KEY `section_subj_teacher_to_staff` (`Staff_Id`),
  ADD KEY `section_subj_teacher_to_school_year` (`School_Year_Details_Id`);

--
-- Indexes for table `archive_teachers`
--
ALTER TABLE `archive_teachers`
  ADD PRIMARY KEY (`Staff_Id`),
  ADD UNIQUE KEY `Staff_Contact_Number` (`Staff_Contact_Number`),
  ADD KEY `fk_staff_address` (`Staff_Address_Id`),
  ADD KEY `fk_staff_identifier` (`Staff_Identifier_Id`);

--
-- Indexes for table `disabled_student`
--
ALTER TABLE `disabled_student`
  ADD PRIMARY KEY (`Disabled_Student_Id`);

--
-- Indexes for table `educational_background`
--
ALTER TABLE `educational_background`
  ADD PRIMARY KEY (`Educational_Background_Id`);

--
-- Indexes for table `educational_information`
--
ALTER TABLE `educational_information`
  ADD PRIMARY KEY (`Educational_Information_Id`),
  ADD KEY `Enrolling_Grade_Level` (`Enrolling_Grade_Level`),
  ADD KEY `Last_Grade_Level` (`Last_Grade_Level`);

--
-- Indexes for table `enrollee`
--
ALTER TABLE `enrollee`
  ADD PRIMARY KEY (`Enrollee_Id`),
  ADD UNIQUE KEY `uniq_lrn` (`Learner_Reference_Number`),
  ADD KEY `Enrollee_Address_Id` (`Enrollee_Address_Id`),
  ADD KEY `fk_educatuinal_background` (`Educational_Background_Id`),
  ADD KEY `fk_educatuinal_information` (`Educational_Information_Id`),
  ADD KEY `fk_disabled_student` (`Disabled_Student_Id`),
  ADD KEY `Psa_Image_Id` (`Psa_Image_Id`);

--
-- Indexes for table `enrollee_address`
--
ALTER TABLE `enrollee_address`
  ADD PRIMARY KEY (`Enrollee_Address_Id`);

--
-- Indexes for table `enrollee_parents`
--
ALTER TABLE `enrollee_parents`
  ADD KEY `enrollee_parents_enrollee_id_fk` (`Enrollee_Id`),
  ADD KEY `enrolle_parents_parent_id_fk` (`Parent_Id`);

--
-- Indexes for table `enrollment_transactions`
--
ALTER TABLE `enrollment_transactions`
  ADD PRIMARY KEY (`Enrollment_Transaction_Id`),
  ADD KEY `Enrollee_Id` (`Enrollee_Id`),
  ADD KEY `Staff_Id` (`Staff_Id`);

--
-- Indexes for table `grade_level`
--
ALTER TABLE `grade_level`
  ADD PRIMARY KEY (`Grade_Level_Id`);

--
-- Indexes for table `grade_level_subjects`
--
ALTER TABLE `grade_level_subjects`
  ADD PRIMARY KEY (`Grade_Level_Subject_Id`),
  ADD UNIQUE KEY `uniq_subject_grade` (`Subject_Id`,`Grade_Level_Id`),
  ADD KEY `Grade_Level_Id` (`Grade_Level_Id`);

--
-- Indexes for table `locker_files`
--
ALTER TABLE `locker_files`
  ADD PRIMARY KEY (`Locker_File_Id`),
  ADD KEY `idx_staff_id` (`Staff_Id`),
  ADD KEY `idx_uploaded_at` (`Uploaded_At`);

--
-- Indexes for table `otp_verification`
--
ALTER TABLE `otp_verification`
  ADD PRIMARY KEY (`OTP_ID`),
  ADD KEY `idx_token` (`Token`),
  ADD KEY `idx_user_id` (`User_Id`),
  ADD KEY `idx_expiry` (`Expiry_Time`);

--
-- Indexes for table `parent_information`
--
ALTER TABLE `parent_information`
  ADD PRIMARY KEY (`Parent_Id`);

--
-- Indexes for table `profile_directory`
--
ALTER TABLE `profile_directory`
  ADD PRIMARY KEY (`Profile_Picture_Id`);

--
-- Indexes for table `Psa_directory`
--
ALTER TABLE `Psa_directory`
  ADD PRIMARY KEY (`Psa_Image_Id`);

--
-- Indexes for table `registrations`
--
ALTER TABLE `registrations`
  ADD PRIMARY KEY (`Registration_Id`),
  ADD UNIQUE KEY `unique_phone_number` (`Contact_Number`);

--
-- Indexes for table `report_card_submissions`
--
ALTER TABLE `report_card_submissions`
  ADD PRIMARY KEY (`Report_Card_Id`),
  ADD KEY `idx_lrn` (`student_lrn`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_enrollee` (`enrollee_id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_validation_only` (`validation_only`),
  ADD KEY `idx_session_id` (`session_id`);

--
-- Indexes for table `school_year_details`
--
ALTER TABLE `school_year_details`
  ADD PRIMARY KEY (`School_Year_Details_Id`),
  ADD UNIQUE KEY `uniq_academic_year` (`start_year`,`end_year`);

--
-- Indexes for table `sections`
--
ALTER TABLE `sections`
  ADD PRIMARY KEY (`Section_Id`),
  ADD KEY `Grade_Level_Id` (`Grade_Level_Id`);

--
-- Indexes for table `section_advisers`
--
ALTER TABLE `section_advisers`
  ADD PRIMARY KEY (`Section_Advisers_Id`),
  ADD UNIQUE KEY `unique_adviser_per_year` (`School_Year_Details_Id`,`Section_Id`) USING BTREE;

--
-- Indexes for table `section_schedules`
--
ALTER TABLE `section_schedules`
  ADD PRIMARY KEY (`Section_Schedules_Id`),
  ADD UNIQUE KEY `unique_sched_per_subject_and_year` (`Section_Subjects_Id`,`Schedule_Day`,`School_Year_Details_Id`),
  ADD KEY `section_schedules_to_school_year` (`School_Year_Details_Id`);

--
-- Indexes for table `section_subjects`
--
ALTER TABLE `section_subjects`
  ADD PRIMARY KEY (`Section_Subjects_Id`),
  ADD UNIQUE KEY `uq_subject_section` (`Subject_Id`,`Section_Id`),
  ADD KEY `Subject_Id` (`Subject_Id`),
  ADD KEY `section_subjects_to_sections` (`Section_Id`);

--
-- Indexes for table `section_subject_teachers`
--
ALTER TABLE `section_subject_teachers`
  ADD PRIMARY KEY (`Section_Subject_Teacher`),
  ADD UNIQUE KEY `uniq_subj_teacher_per_year` (`Section_Subjects_Id`,`School_Year_Details_Id`) USING BTREE,
  ADD KEY `section_subj_teacher_to_staff` (`Staff_Id`),
  ADD KEY `section_subj_teacher_to_school_year` (`School_Year_Details_Id`);

--
-- Indexes for table `staffs`
--
ALTER TABLE `staffs`
  ADD PRIMARY KEY (`Staff_Id`),
  ADD UNIQUE KEY `Staff_Contact_Number` (`Staff_Contact_Number`),
  ADD KEY `fk_staff_address` (`Staff_Address_Id`),
  ADD KEY `fk_staff_identifier` (`Staff_Identifier_Id`);

--
-- Indexes for table `staff_address`
--
ALTER TABLE `staff_address`
  ADD PRIMARY KEY (`Staff_Address_Id`);

--
-- Indexes for table `staff_Identifiers`
--
ALTER TABLE `staff_Identifiers`
  ADD PRIMARY KEY (`Staff_Identifier_Id`),
  ADD UNIQUE KEY `Employee_Number` (`Employee_Number`),
  ADD UNIQUE KEY `TIN` (`TIN`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`Student_Id`),
  ADD UNIQUE KEY `idx_enrollee` (`Enrollee_Id`),
  ADD UNIQUE KEY `LRN` (`LRN`),
  ADD KEY `Section_Id` (`Section_Id`),
  ADD KEY `Grade_Level_Id` (`Grade_Level_Id`);

--
-- Indexes for table `student_grades`
--
ALTER TABLE `student_grades`
  ADD PRIMARY KEY (`Student_Grades_Id`),
  ADD UNIQUE KEY `uniq_grade` (`Section_Subjects_Id`,`Student_Id`,`Quarter`),
  ADD KEY `grades_to_students` (`Student_Id`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`Subject_Id`),
  ADD UNIQUE KEY `uniq_subj_name` (`Subject_Name`);

--
-- Indexes for table `teacher_logs`
--
ALTER TABLE `teacher_logs`
  ADD PRIMARY KEY (`User_Log_Id`),
  ADD KEY `user_log_to_user` (`User_Id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`User_Id`),
  ADD KEY `Registration_Id` (`Registration_Id`),
  ADD KEY `fk_staff_id` (`Staff_Id`),
  ADD KEY `fk_profile_picture` (`Profile_Picture_Id`);

--
-- Indexes for table `user_logs`
--
ALTER TABLE `user_logs`
  ADD PRIMARY KEY (`User_Log_Id`),
  ADD KEY `user_log_to_user` (`User_Id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `Announcement_Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `archive_enrollees`
--
ALTER TABLE `archive_enrollees`
  MODIFY `Enrollee_Id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `archive_enrollment_transactions`
--
ALTER TABLE `archive_enrollment_transactions`
  MODIFY `Enrollment_Transaction_Id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `archive_section_advisers`
--
ALTER TABLE `archive_section_advisers`
  MODIFY `Section_Advisers_Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `archive_section_schedules`
--
ALTER TABLE `archive_section_schedules`
  MODIFY `Section_Schedules_Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `archive_section_subject_teachers`
--
ALTER TABLE `archive_section_subject_teachers`
  MODIFY `Section_Subject_Teacher` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `archive_teachers`
--
ALTER TABLE `archive_teachers`
  MODIFY `Staff_Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `disabled_student`
--
ALTER TABLE `disabled_student`
  MODIFY `Disabled_Student_Id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=156;

--
-- AUTO_INCREMENT for table `educational_background`
--
ALTER TABLE `educational_background`
  MODIFY `Educational_Background_Id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=156;

--
-- AUTO_INCREMENT for table `educational_information`
--
ALTER TABLE `educational_information`
  MODIFY `Educational_Information_Id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=154;

--
-- AUTO_INCREMENT for table `enrollee`
--
ALTER TABLE `enrollee`
  MODIFY `Enrollee_Id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=138;

--
-- AUTO_INCREMENT for table `enrollee_address`
--
ALTER TABLE `enrollee_address`
  MODIFY `Enrollee_Address_Id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=155;

--
-- AUTO_INCREMENT for table `enrollment_transactions`
--
ALTER TABLE `enrollment_transactions`
  MODIFY `Enrollment_Transaction_Id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `grade_level`
--
ALTER TABLE `grade_level`
  MODIFY `Grade_Level_Id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `grade_level_subjects`
--
ALTER TABLE `grade_level_subjects`
  MODIFY `Grade_Level_Subject_Id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=96;

--
-- AUTO_INCREMENT for table `locker_files`
--
ALTER TABLE `locker_files`
  MODIFY `Locker_File_Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `otp_verification`
--
ALTER TABLE `otp_verification`
  MODIFY `OTP_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `parent_information`
--
ALTER TABLE `parent_information`
  MODIFY `Parent_Id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=447;

--
-- AUTO_INCREMENT for table `profile_directory`
--
ALTER TABLE `profile_directory`
  MODIFY `Profile_Picture_Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `Psa_directory`
--
ALTER TABLE `Psa_directory`
  MODIFY `Psa_Image_Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=99;

--
-- AUTO_INCREMENT for table `registrations`
--
ALTER TABLE `registrations`
  MODIFY `Registration_Id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=232;

--
-- AUTO_INCREMENT for table `report_card_submissions`
--
ALTER TABLE `report_card_submissions`
  MODIFY `Report_Card_Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=158;

--
-- AUTO_INCREMENT for table `school_year_details`
--
ALTER TABLE `school_year_details`
  MODIFY `School_Year_Details_Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `sections`
--
ALTER TABLE `sections`
  MODIFY `Section_Id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `section_advisers`
--
ALTER TABLE `section_advisers`
  MODIFY `Section_Advisers_Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT for table `section_schedules`
--
ALTER TABLE `section_schedules`
  MODIFY `Section_Schedules_Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `section_subjects`
--
ALTER TABLE `section_subjects`
  MODIFY `Section_Subjects_Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=252;

--
-- AUTO_INCREMENT for table `section_subject_teachers`
--
ALTER TABLE `section_subject_teachers`
  MODIFY `Section_Subject_Teacher` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `staffs`
--
ALTER TABLE `staffs`
  MODIFY `Staff_Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT for table `staff_address`
--
ALTER TABLE `staff_address`
  MODIFY `Staff_Address_Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `staff_Identifiers`
--
ALTER TABLE `staff_Identifiers`
  MODIFY `Staff_Identifier_Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `Student_Id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `student_grades`
--
ALTER TABLE `student_grades`
  MODIFY `Student_Grades_Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=109;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `Subject_Id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `teacher_logs`
--
ALTER TABLE `teacher_logs`
  MODIFY `User_Log_Id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `User_Id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=144;

--
-- AUTO_INCREMENT for table `user_logs`
--
ALTER TABLE `user_logs`
  MODIFY `User_Log_Id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=172;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `educational_information`
--
ALTER TABLE `educational_information`
  ADD CONSTRAINT `educational_information_ibfk_1` FOREIGN KEY (`Enrolling_Grade_Level`) REFERENCES `grade_level` (`Grade_Level_Id`),
  ADD CONSTRAINT `educational_information_ibfk_2` FOREIGN KEY (`Last_Grade_Level`) REFERENCES `grade_level` (`Grade_Level_Id`);

--
-- Constraints for table `enrollee`
--
ALTER TABLE `enrollee`
  ADD CONSTRAINT `enrollee_ibfk_1` FOREIGN KEY (`Enrollee_Address_Id`) REFERENCES `enrollee_address` (`Enrollee_Address_Id`),
  ADD CONSTRAINT `enrollee_ibfk_2` FOREIGN KEY (`Psa_Image_Id`) REFERENCES `Psa_directory` (`Psa_Image_Id`),
  ADD CONSTRAINT `fk_disabled_student` FOREIGN KEY (`Disabled_Student_Id`) REFERENCES `disabled_student` (`Disabled_Student_Id`),
  ADD CONSTRAINT `fk_educatuinal_background` FOREIGN KEY (`Educational_Background_Id`) REFERENCES `educational_background` (`Educational_Background_Id`),
  ADD CONSTRAINT `fk_educatuinal_information` FOREIGN KEY (`Educational_Information_Id`) REFERENCES `educational_information` (`Educational_Information_Id`);

--
-- Constraints for table `enrollee_parents`
--
ALTER TABLE `enrollee_parents`
  ADD CONSTRAINT `enrolle_parents_parent_id_fk` FOREIGN KEY (`Parent_Id`) REFERENCES `parent_information` (`Parent_Id`),
  ADD CONSTRAINT `enrollee_parents_enrollee_id_fk` FOREIGN KEY (`Enrollee_Id`) REFERENCES `enrollee` (`Enrollee_Id`);

--
-- Constraints for table `enrollment_transactions`
--
ALTER TABLE `enrollment_transactions`
  ADD CONSTRAINT `enrollment_transactions_ibfk_1` FOREIGN KEY (`Enrollee_Id`) REFERENCES `enrollee` (`Enrollee_Id`),
  ADD CONSTRAINT `enrollment_transactions_ibfk_2` FOREIGN KEY (`Staff_Id`) REFERENCES `staffs` (`Staff_Id`);

--
-- Constraints for table `grade_level_subjects`
--
ALTER TABLE `grade_level_subjects`
  ADD CONSTRAINT `grade_level_subjects_ibfk_1` FOREIGN KEY (`Grade_Level_Id`) REFERENCES `grade_level` (`Grade_Level_Id`),
  ADD CONSTRAINT `grade_level_subjects_ibfk_2` FOREIGN KEY (`Subject_Id`) REFERENCES `subjects` (`Subject_Id`) ON DELETE CASCADE;

--
-- Constraints for table `locker_files`
--
ALTER TABLE `locker_files`
  ADD CONSTRAINT `fk_locker_files_staff` FOREIGN KEY (`Staff_Id`) REFERENCES `staffs` (`Staff_Id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `otp_verification`
--
ALTER TABLE `otp_verification`
  ADD CONSTRAINT `otp_verification_ibfk_1` FOREIGN KEY (`User_Id`) REFERENCES `users` (`User_Id`) ON DELETE CASCADE;

--
-- Constraints for table `sections`
--
ALTER TABLE `sections`
  ADD CONSTRAINT `sections_ibfk_1` FOREIGN KEY (`Grade_Level_Id`) REFERENCES `grade_level` (`Grade_Level_Id`);

--
-- Constraints for table `section_advisers`
--
ALTER TABLE `section_advisers`
  ADD CONSTRAINT `section_advisers_to_school_year` FOREIGN KEY (`School_Year_Details_Id`) REFERENCES `school_year_details` (`School_Year_Details_Id`);

--
-- Constraints for table `section_schedules`
--
ALTER TABLE `section_schedules`
  ADD CONSTRAINT `section_schedules_ibfk_1` FOREIGN KEY (`Section_Subjects_Id`) REFERENCES `section_subjects` (`Section_Subjects_Id`),
  ADD CONSTRAINT `section_schedules_to_school_year` FOREIGN KEY (`School_Year_Details_Id`) REFERENCES `school_year_details` (`School_Year_Details_Id`);

--
-- Constraints for table `section_subjects`
--
ALTER TABLE `section_subjects`
  ADD CONSTRAINT `section_subjects_to_sections` FOREIGN KEY (`Section_Id`) REFERENCES `sections` (`Section_Id`),
  ADD CONSTRAINT `section_subjects_to_subejcts` FOREIGN KEY (`Subject_Id`) REFERENCES `subjects` (`Subject_Id`);

--
-- Constraints for table `section_subject_teachers`
--
ALTER TABLE `section_subject_teachers`
  ADD CONSTRAINT `section_subj_teacher_to_school_year` FOREIGN KEY (`School_Year_Details_Id`) REFERENCES `school_year_details` (`School_Year_Details_Id`),
  ADD CONSTRAINT `section_subj_teacher_to_section_subj` FOREIGN KEY (`Section_Subjects_Id`) REFERENCES `section_subjects` (`Section_Subjects_Id`),
  ADD CONSTRAINT `section_subj_teacher_to_staff` FOREIGN KEY (`Staff_Id`) REFERENCES `staffs` (`Staff_Id`);

--
-- Constraints for table `staffs`
--
ALTER TABLE `staffs`
  ADD CONSTRAINT `fk_staff_address` FOREIGN KEY (`Staff_Address_Id`) REFERENCES `staff_address` (`Staff_Address_Id`),
  ADD CONSTRAINT `fk_staff_identifier` FOREIGN KEY (`Staff_Identifier_Id`) REFERENCES `staff_Identifiers` (`Staff_Identifier_Id`);

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_ibfk_1` FOREIGN KEY (`Section_Id`) REFERENCES `sections` (`Section_Id`),
  ADD CONSTRAINT `students_ibfk_2` FOREIGN KEY (`Enrollee_Id`) REFERENCES `enrollee` (`Enrollee_Id`),
  ADD CONSTRAINT `students_ibfk_3` FOREIGN KEY (`Grade_Level_Id`) REFERENCES `grade_level` (`Grade_Level_Id`);

--
-- Constraints for table `student_grades`
--
ALTER TABLE `student_grades`
  ADD CONSTRAINT `grades_to_section_subjects` FOREIGN KEY (`Section_Subjects_Id`) REFERENCES `section_subjects` (`Section_Subjects_Id`),
  ADD CONSTRAINT `grades_to_students` FOREIGN KEY (`Student_Id`) REFERENCES `students` (`Student_Id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_profile_picture` FOREIGN KEY (`Profile_Picture_Id`) REFERENCES `profile_directory` (`Profile_Picture_Id`),
  ADD CONSTRAINT `fk_staff_id` FOREIGN KEY (`Staff_Id`) REFERENCES `staffs` (`Staff_Id`),
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`Registration_Id`) REFERENCES `registrations` (`Registration_Id`);

--
-- Constraints for table `user_logs`
--
ALTER TABLE `user_logs`
  ADD CONSTRAINT `user_log_to_user` FOREIGN KEY (`User_Id`) REFERENCES `users` (`User_Id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
