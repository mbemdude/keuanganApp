-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 19, 2025 at 03:14 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `keuangan_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `barang`
--

CREATE TABLE `barang` (
  `id` int(11) NOT NULL,
  `kode_barang` varchar(255) NOT NULL,
  `nama_barang` varchar(255) NOT NULL,
  `kategori` varchar(255) NOT NULL,
  `konversi_satuan` int(11) NOT NULL,
  `harga` float DEFAULT 0,
  `stock` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `barang_masuk`
--

CREATE TABLE `barang_masuk` (
  `id` int(11) NOT NULL,
  `harga_beli` float NOT NULL,
  `jumlah` int(11) NOT NULL,
  `tanggal_transaksi` date NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `barang_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jenis_pembayaran`
--

CREATE TABLE `jenis_pembayaran` (
  `id` int(11) NOT NULL,
  `jenis_pembayaran` varchar(50) NOT NULL,
  `prioritas` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jenis_pembayaran`
--

INSERT INTO `jenis_pembayaran` (`id`, `jenis_pembayaran`, `prioritas`) VALUES
(1, 'Uang Pangkal', 1),
(2, 'Daftar Ulang', 2),
(3, 'SPP', 3);

-- --------------------------------------------------------

--
-- Table structure for table `jenjang`
--

CREATE TABLE `jenjang` (
  `id` int(11) NOT NULL,
  `jenjang` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jenjang`
--

INSERT INTO `jenjang` (`id`, `jenjang`) VALUES
(1, 'TKITQ'),
(2, 'SDITQ'),
(3, 'MTs'),
(4, 'IL/MA');

-- --------------------------------------------------------

--
-- Table structure for table `kelas`
--

CREATE TABLE `kelas` (
  `id` int(11) NOT NULL,
  `kelas` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kelas`
--

INSERT INTO `kelas` (`id`, `kelas`) VALUES
(1, '1'),
(2, '2'),
(3, '3'),
(4, '4'),
(5, '5'),
(6, '6'),
(7, '7'),
(8, '8'),
(9, '9'),
(10, '10'),
(11, '11'),
(12, '12'),
(13, 'IL');

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `id` int(11) NOT NULL,
  `role` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`id`, `role`) VALUES
(1, 'Admin'),
(3, 'Kasir'),
(5, 'Keuangan'),
(6, 'Operator'),
(7, 'Siswa');

-- --------------------------------------------------------

--
-- Table structure for table `siswa`
--

CREATE TABLE `siswa` (
  `id` int(20) NOT NULL,
  `kode` varchar(50) NOT NULL,
  `nis` int(11) NOT NULL,
  `nama` varchar(60) NOT NULL,
  `alamat` text DEFAULT NULL,
  `jenis_kelamin` enum('L','P') NOT NULL,
  `jenjang_id` int(11) NOT NULL,
  `kelas_id` int(11) NOT NULL,
  `status_id` int(11) NOT NULL,
  `foto` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `siswa`
--

INSERT INTO `siswa` (`id`, `kode`, `nis`, `nama`, `alamat`, `jenis_kelamin`, `jenjang_id`, `kelas_id`, `status_id`, `foto`) VALUES
(1, 'A240585a', 240585, 'Afiqah Hasya Hanania', 'Jl. Sa\'adah 3', 'P', 3, 7, 1, NULL),
(2, 'A240638a', 240638, 'Aisya Az Zahra', 'Jl. Sa\'adah 4', 'P', 3, 7, 1, NULL),
(3, 'A240508h', 240508, 'Aisya Hafthah', 'Jl. Sa\'adah 5', 'P', 3, 7, 1, NULL),
(4, 'A240509a', 240509, 'Aisyah Aqyla Ulazheema', 'Jl. Sa\'adah 6', 'P', 3, 7, 1, NULL),
(5, 'A240622r', 240622, 'Alifa Ismid Misfir', 'Jl. Sa\'adah 7', 'P', 3, 7, 1, NULL),
(6, 'A240586n', 240586, 'Alikha Azalea Djaman', 'Jl. Sa\'adah 8', 'P', 3, 7, 1, NULL),
(7, 'A240510i', 240510, 'Alinta Zahra Alkindi', 'Jl. Sa\'adah 9', 'P', 3, 7, 1, NULL),
(8, 'A240623a', 240623, 'Amandla Wulan Callysta', 'Jl. Sa\'adah 10', 'P', 3, 7, 1, NULL),
(9, 'A240587a', 240587, 'Anatasya Bunga Oktaviana', 'Jl. Sa\'adah 11', 'P', 3, 7, 1, NULL),
(10, 'A240624n', 240624, 'Anindita Khaznah Putri Listyawan', 'Jl. Sa\'adah 12', 'P', 3, 7, 1, NULL),
(11, 'A240589a', 240589, 'Aqila Nurmufida Priathma', 'Jl. Sa\'adah 13', 'P', 3, 7, 1, NULL),
(12, 'A240590a', 240590, 'Arina Sabila Qanita', 'Jl. Sa\'adah 14', 'P', 3, 7, 1, NULL),
(13, 'A240512h', 240512, 'Ayu Novia Nurhalimah', 'Jl. Sa\'adah 15', 'P', 3, 7, 1, NULL),
(14, 'C240626a', 240626, 'Chyztha Tavia Nafeeza', 'Jl. Sa\'adah 16', 'P', 3, 7, 1, NULL),
(15, 'D240518h', 240518, 'Darra Pridaningsih', 'Jl. Sa\'adah 17', 'P', 3, 7, 1, NULL),
(16, 'D240593a', 240593, 'Diandra Alizya Kamiliya', 'Jl. Sa\'adah 18', 'P', 3, 7, 1, NULL),
(17, 'D240519a', 240519, 'Dzakiyyah Afifatunnisa', 'Jl. Sa\'adah 19', 'P', 3, 7, 1, NULL),
(18, 'F240521h', 240521, 'Farah Isyqi Islamiyyah', 'Jl. Sa\'adah 20', 'P', 3, 7, 1, NULL),
(19, 'F240523a', 240523, 'Farissa Naira Pradhita', 'Jl. Sa\'adah 21', 'P', 3, 7, 1, NULL),
(20, 'F240627a', 240627, 'Fatimah Izzah Nabila', 'Jl. Sa\'adah 22', 'P', 3, 7, 1, NULL),
(21, 'F240525a', 240525, 'Fitriya', 'Jl. Sa\'adah 23', 'P', 3, 7, 1, NULL),
(22, 'G240595i', 240595, 'Ghaiza Ikhtiarani', 'Jl. Sa\'adah 24', 'P', 3, 7, 1, NULL),
(23, 'G240596i', 240596, 'Ghaziyah Adika Zafarani', 'Jl. Sa\'adah 25', 'P', 3, 7, 1, NULL),
(24, 'H240527i', 240527, 'Hafidzahtul Zahra Ramadhani', 'Jl. Sa\'adah 26', 'P', 3, 7, 1, NULL),
(25, 'H240528h', 240528, 'Hafsah', 'Jl. Sa\'adah 27', 'P', 3, 7, 1, NULL),
(26, 'H240529a', 240529, 'Haira Nazwa', 'Jl. Sa\'adah 28', 'P', 3, 7, 1, NULL),
(27, 'H240530a', 240530, 'Hanania', 'Jl. Sa\'adah 29', 'P', 3, 7, 1, NULL),
(28, 'H240532n', 240532, 'Hanin', 'Jl. Sa\'adah 30', 'P', 3, 7, 1, NULL),
(29, 'H240533a', 240533, 'Haura Zivara Salsabila', 'Jl. Sa\'adah 31', 'P', 3, 7, 1, NULL),
(30, 'H240534a', 240534, 'Hilyah Hanna', 'Jl. Sa\'adah 32', 'P', 3, 7, 1, NULL),
(31, 'J240600a', 240600, 'Jihan Azka Talitha', 'Jl. Sa\'adah 33', 'P', 3, 7, 1, NULL),
(32, 'J240536a', 240536, 'Jinan Shafira', 'Jl. Sa\'adah 34', 'P', 3, 7, 1, NULL),
(33, 'L240602a', 240602, 'Lilien Wafa Nizwa', 'Jl. Sa\'adah 35', 'P', 3, 7, 1, NULL),
(34, 'M240564h', 240564, 'Mupidah', 'Jl. Sa\'adah 36', 'P', 3, 7, 1, NULL),
(35, 'M240565a', 240565, 'Mutia Zulfa', 'Jl. Sa\'adah 37', 'P', 3, 7, 1, NULL),
(36, 'N240628a', 240628, 'Nabila', 'Jl. Sa\'adah 38', 'P', 3, 7, 1, NULL),
(37, 'N240611i', 240611, 'Nabila Azzahra Ramadhani', 'Jl. Sa\'adah 39', 'P', 3, 7, 1, NULL),
(38, 'N240629a', 240629, 'Nadhira Azkia Salsabila', 'Jl. Sa\'adah 40', 'P', 3, 7, 1, NULL),
(39, 'N240566i', 240566, 'Nadia Safitri', 'Jl. Sa\'adah 41', 'P', 3, 7, 1, NULL),
(40, 'N240567h', 240567, 'Nafisah', 'Jl. Sa\'adah 42', 'P', 3, 7, 1, NULL),
(41, 'N240568a', 240568, 'Novendra Thera Adiva', 'Jl. Sa\'adah 43', 'P', 3, 7, 1, NULL),
(42, 'N240569i', 240569, 'Novi Fitriyani', 'Jl. Sa\'adah 44', 'P', 3, 7, 1, NULL),
(43, 'N240570h', 240570, 'Nuriva Assyahirah', 'Jl. Sa\'adah 45', 'P', 3, 7, 1, NULL),
(44, 'N240630h', 240630, 'Nurlaila Khasanah', 'Jl. Sa\'adah 46', 'P', 3, 7, 1, NULL),
(45, 'N240571a', 240571, 'Nuzzila Asysyifa', 'Jl. Sa\'adah 47', 'P', 3, 7, 1, NULL),
(46, 'Q240631n', 240631, 'Qanita Haura Shabria Irwan', 'Jl. Sa\'adah 48', 'P', 3, 7, 1, NULL),
(47, 'Q240572a', 240572, 'Qonita Syasya Zhafira', 'Jl. Sa\'adah 49', 'P', 3, 7, 1, NULL),
(48, 'Q240612a', 240612, 'Qotrunnada Salsabila', 'Jl. Sa\'adah 50', 'P', 3, 7, 1, NULL),
(49, 'R240632a', 240632, 'Raihana Azka Humaira', 'Jl. Sa\'adah 51', 'P', 3, 7, 1, NULL),
(50, 'R240633a', 240633, 'Raisa Aqila', 'Jl. Sa\'adah 52', 'P', 3, 7, 2, NULL),
(51, 'R240639a', 240639, 'Raisya Az Zahira', 'Jl. Sa\'adah 53', 'P', 3, 7, 1, NULL),
(52, 'R240575l', 240575, 'Razita Aaylah Fachrial', 'Jl. Sa\'adah 54', 'P', 3, 7, 1, NULL),
(53, 'R240576i', 240576, 'Rifda Nabila Putri', 'Jl. Sa\'adah 55', 'P', 3, 7, 1, NULL),
(54, 'S240615i', 240615, 'Sabrina Alexa Pramesti', 'Jl. Sa\'adah 56', 'P', 3, 7, 1, NULL),
(55, 'S240578a', 240578, 'Sahla', 'Jl. Sa\'adah 57', 'P', 3, 7, 1, NULL),
(56, 'S240617a', 240617, 'Sarah Qonita', 'Jl. Sa\'adah 58', 'P', 3, 7, 1, NULL),
(57, 'S240618h', 240618, 'Shafira Putri Hafidzah', 'Jl. Sa\'adah 59', 'P', 3, 7, 1, NULL),
(58, 'S240579h', 240579, 'Shofiyah', 'Jl. Sa\'adah 60', 'P', 3, 7, 1, NULL),
(59, 'S240580i', 240580, 'Shofiyya Firdaus Khudhori', 'Jl. Sa\'adah 61', 'P', 3, 7, 1, NULL),
(60, 'Z240634a', 240634, 'Zivanka Letisha', 'Jl. Sa\'adah 62', 'P', 3, 7, 1, NULL),
(61, 'H2230493A', 2230493, 'HANA AZKIYA KHAIRUNNISA', 'Jl. Sa\'adah 63', 'P', 3, 7, 1, NULL),
(62, 'M2230498A', 2230498, 'MIFA SYAKIRAH ROYYA', 'Jl. Sa\'adah 64', 'P', 3, 7, 2, NULL),
(63, 'A2230422o', 2230422, 'Aila Assya Nur Afa Riyanto', 'Jl. Sa\'adah 65', 'P', 3, 8, 1, NULL),
(64, 'A2230423a', 2230423, 'Aisyah Assyifa', 'Jl. Sa\'adah 66', 'P', 3, 8, 1, NULL),
(65, 'A2230424h', 2230424, 'Aisyah Nur Azizah', 'Jl. Sa\'adah 67', 'P', 3, 8, 1, NULL),
(66, 'A2230425h', 2230425, 'Aisyah Syifaaur Rahmah', 'Jl. Sa\'adah 68', 'P', 3, 8, 1, NULL),
(67, 'A2230489S', 2230489, 'AL FIRSILIA BILQIS', 'Jl. Sa\'adah 69', 'P', 3, 8, 1, NULL),
(68, 'A2230426a', 2230426, 'Alicia Zakinah Almyndra', 'Jl. Sa\'adah 70', 'P', 3, 8, 1, NULL),
(69, 'A2230427n', 2230427, 'Alifa Mukhbita Meysun', 'Jl. Sa\'adah 71', 'P', 3, 8, 1, NULL),
(70, 'A2230490H', 2230490, 'AMIRAH ASMI MAHIRAH', 'Jl. Sa\'adah 72', 'P', 3, 8, 1, NULL),
(71, 'A2230428r', 2230428, 'Anindya Luthfi Fairuz Imandar', 'Jl. Sa\'adah 73', 'P', 3, 8, 1, NULL),
(72, 'A2230429a', 2230429, 'Aqila Ghassania', 'Jl. Sa\'adah 74', 'P', 3, 8, 1, NULL),
(73, 'A2230430a', 2230430, 'Aqilla Azzahra Suta', 'Jl. Sa\'adah 75', 'P', 3, 8, 1, NULL),
(74, 'A2230491W', 2230491, 'ARJU AGHNINA ZAIDA W', 'Jl. Sa\'adah 76', 'P', 3, 8, 1, NULL),
(75, 'A2230431i', 2230431, 'Athaya Nisrina Fikri', 'Jl. Sa\'adah 77', 'P', 3, 8, 1, NULL),
(76, 'A2230432a', 2230432, 'Athira Wardatunnisa', 'Jl. Sa\'adah 78', 'P', 3, 8, 1, NULL),
(77, 'A2230503a', 2230503, 'Azka Ilmi Aisya', 'Jl. Sa\'adah 79', 'P', 3, 8, 1, NULL),
(78, 'C2230433i', 2230433, 'Chilla Fidela An Nakhrowi', 'Jl. Sa\'adah 80', 'P', 3, 8, 1, NULL),
(79, 'D2230434i', 2230434, 'Dania Hanifa Irhasni', 'Jl. Sa\'adah 81', 'P', 3, 8, 1, NULL),
(80, 'D2230435a', 2230435, 'Dhia Salma Salsabila', 'Jl. Sa\'adah 82', 'P', 3, 8, 1, NULL),
(81, 'D2230436i', 2230436, 'Dzakiyya Akma Aftani', 'Jl. Sa\'adah 83', 'P', 3, 8, 1, NULL),
(82, 'F2230492A', 2230492, 'FADIA KEISYA ASHSHABIRA', 'Jl. Sa\'adah 84', 'P', 3, 8, 1, NULL),
(83, 'H2230438a', 2230438, 'Hilyatul Azkiya', 'Jl. Sa\'adah 85', 'P', 3, 8, 1, NULL),
(84, 'H2230494A', 2230494, 'HUMAIRA FILZA', 'Jl. Sa\'adah 86', 'P', 3, 8, 1, NULL),
(85, 'H2230439a', 2230439, 'Husna Amalia', 'Jl. Sa\'adah 87', 'P', 3, 8, 1, NULL),
(86, 'I2230440h', 2230440, 'Ishmah Az Zahidah', 'Jl. Sa\'adah 88', 'P', 3, 8, 1, NULL),
(87, 'J2230441a', 2230441, 'Janeeta Najla Alima', 'Jl. Sa\'adah 89', 'P', 3, 8, 1, NULL),
(88, 'J2230442i', 2230442, 'Jasmin Istiqomah Putri', 'Jl. Sa\'adah 90', 'P', 3, 8, 1, NULL),
(89, 'J2230443a', 2230443, 'Jasmine Azzahra Putri Ayodya', 'Jl. Sa\'adah 91', 'P', 3, 8, 1, NULL),
(90, 'K2230444l', 2230444, 'Kayyisah Ashalina Farol', 'Jl. Sa\'adah 92', 'P', 3, 8, 1, NULL),
(91, 'K2230495D', 2230495, 'KEYLA ANANTA AHFAD', 'Jl. Sa\'adah 93', 'P', 3, 8, 1, NULL),
(92, 'K2230445a', 2230445, 'Keysheva Lashira Amadia Syifqa', 'Jl. Sa\'adah 94', 'P', 3, 8, 1, NULL),
(93, 'K2230446r', 2230446, 'Khansa Haura Akbar', 'Jl. Sa\'adah 95', 'P', 3, 8, 1, NULL),
(94, 'L2230496A', 2230496, 'LUBNA AISYA RANIA', 'Jl. Sa\'adah 96', 'P', 3, 8, 1, NULL),
(95, 'M2230497i', 2230497, 'Maritza Putri Ariyani', 'Jl. Sa\'adah 97', 'P', 3, 8, 1, NULL),
(96, 'M2230447a', 2230447, 'Milada Fariha', 'Jl. Sa\'adah 98', 'P', 3, 8, 1, NULL),
(97, 'M2230448y', 2230448, 'Mufida Nur Izzaty', 'Jl. Sa\'adah 99', 'P', 3, 8, 1, NULL),
(98, 'N2230499H', 2230499, 'NABILA AZKA HANIFAH', 'Jl. Sa\'adah 100', 'P', 3, 8, 1, NULL),
(99, 'N2230449a', 2230449, 'Nabila Shafa Khumaira', 'Jl. Sa\'adah 101', 'P', 3, 8, 1, NULL),
(100, 'N2230450h', 2230450, 'Nabilah Husna Hafizhah', 'Jl. Sa\'adah 102', 'P', 3, 8, 1, NULL),
(101, 'N2230451i', 2230451, 'Nadin Nur Asiyah Ramadhani', 'Jl. Sa\'adah 103', 'P', 3, 8, 1, NULL),
(102, 'N2230452i', 2230452, 'Nahla Al Katiri', 'Jl. Sa\'adah 104', 'P', 3, 8, 1, NULL),
(103, 'N2230453l', 2230453, 'Nahla Titer Zainal', 'Jl. Sa\'adah 105', 'P', 3, 8, 1, NULL),
(104, 'N2230454a', 2230454, 'Naila Janeeta', 'Jl. Sa\'adah 106', 'P', 3, 8, 1, NULL),
(105, 'N2230455a', 2230455, 'Najwa Hanifa', 'Jl. Sa\'adah 107', 'P', 3, 8, 1, NULL),
(106, 'N2230456h', 2230456, 'Naufa Afifah', 'Jl. Sa\'adah 108', 'P', 3, 8, 1, NULL),
(107, 'N2230457a', 2230457, 'Naura Qowiyatul Mara', 'Jl. Sa\'adah 109', 'P', 3, 8, 1, NULL),
(108, 'N2230458h', 2230458, 'Nayla Rahimah', 'Jl. Sa\'adah 110', 'P', 3, 8, 1, NULL),
(109, 'N2230459a', 2230459, 'Nayla Salsabila', 'Jl. Sa\'adah 111', 'P', 3, 8, 1, NULL),
(110, 'N2230460a', 2230460, 'Nazira Khanza Azzuhra', 'Jl. Sa\'adah 112', 'P', 3, 8, 1, NULL),
(111, 'N2230461a', 2230461, 'Nazwa Rizqia Maulida', 'Jl. Sa\'adah 113', 'P', 3, 8, 1, NULL),
(112, 'N2230462a', 2230462, 'Nova Fitriana', 'Jl. Sa\'adah 114', 'P', 3, 8, 1, NULL),
(113, 'Q2230463a', 2230463, 'Qanita Shalihatunnisa', 'Jl. Sa\'adah 115', 'P', 3, 8, 1, NULL),
(114, 'R2230465a', 2230465, 'Raisa', 'Jl. Sa\'adah 116', 'P', 3, 8, 1, NULL),
(115, 'R2230466o', 2230466, 'Raisha Wulandari Kesworo', 'Jl. Sa\'adah 117', 'P', 3, 8, 1, NULL),
(116, 'R2230500A', 2230500, 'RASYIFA ADHELIA PUTRI ASHARA', 'Jl. Sa\'adah 118', 'P', 3, 8, 1, NULL),
(117, 'R2230502I', 2230502, 'RAUDATHOL ELVA RIANI', 'Jl. Sa\'adah 119', 'P', 3, 8, 1, NULL),
(118, 'R2230467n', 2230467, 'Rianda Rizka Adrian', 'Jl. Sa\'adah 120', 'P', 3, 8, 1, NULL),
(119, 'R2230468k', 2230468, 'Rumaisha Hafidatus Syarik', 'Jl. Sa\'adah 121', 'P', 3, 8, 1, NULL),
(120, 'S2230470a', 2230470, 'Siti Zahra', 'Jl. Sa\'adah 122', 'P', 3, 8, 1, NULL),
(121, 'S2230471a', 2230471, 'Syifa Qotrunnada', 'Jl. Sa\'adah 123', 'P', 3, 8, 1, NULL),
(122, 'Z2230473h', 2230473, 'Zesyara Auzriani Abdillah', 'Jl. Sa\'adah 124', 'P', 3, 8, 1, NULL),
(123, 'A2220356a', 2220356, 'Aisya Afifa Azfa', 'Jl. Sa\'adah 125', 'P', 3, 9, 1, NULL),
(124, 'A2220318i', 2220318, 'Aisyah At Tamimi', 'Jl. Sa\'adah 126', 'P', 3, 9, 1, NULL),
(125, 'A2220319a', 2220319, 'Aisyah Fitri Humaira', 'Jl. Sa\'adah 127', 'P', 3, 9, 1, NULL),
(126, 'A2220320r', 2220320, 'Aisyah Misfir', 'Jl. Sa\'adah 128', 'P', 3, 9, 1, NULL),
(127, 'A2220323a', 2220323, 'Alisya Rizky Salsabila', 'Jl. Sa\'adah 129', 'P', 3, 9, 1, NULL),
(128, 'A2220322a', 2220322, 'Almira Zahra', 'Jl. Sa\'adah 130', 'P', 3, 9, 1, NULL),
(129, 'A2220357e', 2220357, 'Asyifa Salsa Nabila Rambe', 'Jl. Sa\'adah 131', 'P', 3, 9, 1, NULL),
(130, 'A2220358n', 2220358, 'Ataniya Salsabila Rahman', 'Jl. Sa\'adah 132', 'P', 3, 9, 1, NULL),
(131, 'C2220324b', 2220324, 'Chadizah Azzahra Thalib', 'Jl. Sa\'adah 133', 'P', 3, 9, 1, NULL),
(132, 'F2220359a', 2220359, 'Fadhilah Etika Maghfira', 'Jl. Sa\'adah 134', 'P', 3, 9, 1, NULL),
(133, 'F2220325l', 2220325, 'Faida Halimah Nawal', 'Jl. Sa\'adah 135', 'P', 3, 9, 1, NULL),
(134, 'F2220326i', 2220326, 'Faizah Nur Ramadhani', 'Jl. Sa\'adah 136', 'P', 3, 9, 1, NULL),
(135, 'F2220327h', 2220327, 'Fathina Dzakia Fauziyah', 'Jl. Sa\'adah 137', 'P', 3, 9, 1, NULL),
(136, 'H2220328a', 2220328, 'Halwa Jaziela', 'Jl. Sa\'adah 138', 'P', 3, 9, 1, NULL),
(137, 'H2220329h', 2220329, 'Hanifah Ayu Muslimah', 'Jl. Sa\'adah 139', 'P', 3, 9, 1, NULL),
(138, 'H2220330a', 2220330, 'Humaira', 'Jl. Sa\'adah 140', 'P', 3, 9, 1, NULL),
(139, 'K2220360z', 2220360, 'Khalilah Mumtaz', 'Jl. Sa\'adah 141', 'P', 3, 9, 1, NULL),
(140, 'L2220332i', 2220332, 'Luqiana Talita Sakhi Fariaturozi', 'Jl. Sa\'adah 142', 'P', 3, 9, 1, NULL),
(141, 'N2220362s', 2220362, 'Nada Annisa Firdaus', 'Jl. Sa\'adah 143', 'P', 3, 9, 1, NULL),
(142, 'N2220333i', 2220333, 'Naila Zahra Dwinova Ariyadi', 'Jl. Sa\'adah 144', 'P', 3, 9, 1, NULL),
(143, 'N2220334h', 2220334, 'Nur Almira Rahmah', 'Jl. Sa\'adah 145', 'P', 3, 9, 1, NULL),
(144, 'Q2220336h', 2220336, 'Quinsha Malika Daanish', 'Jl. Sa\'adah 146', 'P', 3, 9, 1, NULL),
(145, 'R2220337i', 2220337, 'Rahma Ramadhani', 'Jl. Sa\'adah 147', 'P', 3, 9, 1, NULL),
(146, 'R2220364a', 2220364, 'Rumaisya Aqilla Janeeta', 'Jl. Sa\'adah 148', 'P', 3, 9, 1, NULL),
(147, 'S2220338a', 2220338, 'Syifa Salsabila', 'Jl. Sa\'adah 149', 'P', 3, 9, 1, NULL),
(148, 'U2220339a', 2220339, 'Ulima', 'Jl. Sa\'adah 150', 'P', 3, 9, 1, NULL),
(149, 'Y2220340h', 2220340, 'Yasmine Afifah', 'Jl. Sa\'adah 151', 'P', 3, 9, 1, NULL),
(150, 'Y22110305a', 22110305, 'Yusriyyah Amaliah Sholeha', 'Jl. Sa\'adah 152', 'P', 3, 9, 1, NULL),
(151, 'Z2220341a', 2220341, 'Zahira Salwa', 'Jl. Sa\'adah 153', 'P', 4, 13, 1, NULL),
(152, 'A2400140n', 2400140, 'Alvisca Putri Irawan', 'Jl. Sa\'adah 154', 'P', 4, 13, 1, NULL),
(153, 'A2400170a', 2400170, 'Alya', 'Jl. Sa\'adah 155', 'P', 4, 13, 1, NULL),
(154, 'A2400128a', 2400128, 'An Nisa Khalifatul Huda', 'Jl. Sa\'adah 156', 'P', 4, 13, 1, NULL),
(155, 'A2400129a', 2400129, 'Asy-Syifa', 'Jl. Sa\'adah 157', 'P', 4, 13, 1, NULL),
(156, 'B2400162a', 2400162, 'Balqis Faiha Dira', 'Jl. Sa\'adah 158', 'P', 4, 13, 1, NULL),
(157, 'B2400130a', 2400130, 'Bunga Salsabila', 'Jl. Sa\'adah 159', 'P', 4, 13, 1, NULL),
(158, 'F2400131h', 2400131, 'Firlyarena Marsha Aisyah', 'Jl. Sa\'adah 160', 'P', 4, 13, 1, NULL),
(159, 'F2400132i', 2400132, 'Fitria Handayani', 'Jl. Sa\'adah 161', 'P', 4, 13, 1, NULL),
(160, 'M2400133a', 2400133, 'Mutia Humaira Dharma', 'Jl. Sa\'adah 162', 'P', 4, 13, 1, NULL),
(161, 'R2400165a', 2400165, 'Rizqeena Jundia Rifa', 'Jl. Sa\'adah 163', 'P', 4, 13, 1, NULL),
(162, 'S2400172l', 2400172, 'Shabrina Saugi Agil', 'Jl. Sa\'adah 164', 'P', 4, 13, 1, NULL),
(163, 'T2400135h', 2400135, 'Tiara Nurazizah', 'Jl. Sa\'adah 165', 'P', 4, 13, 1, NULL),
(164, 'V2400166i', 2400166, 'Vidya Meilani', 'Jl. Sa\'adah 166', 'P', 4, 13, 1, NULL),
(165, 'Z2400136i', 2400136, 'Zalfa Hafizhah Anshari', 'Jl. Sa\'adah 167', 'P', 4, 13, 1, NULL),
(166, 'R2400175i', 2400175, 'Rheina Lucya Putri', 'Jl. Sa\'adah 168', 'P', 4, 13, 1, NULL),
(167, 'R32230025A', 32230025, 'RAYSA AULIA SALSABILA', 'Jl. Sa\'adah 169', 'P', 4, 10, 1, NULL),
(168, 'A22110241r', 22110241, 'Ailsa Silviazayyan Aptanta Nadhif Imandar', 'Jl. Sa\'adah 170', 'P', 4, 10, 1, NULL),
(169, 'A22110242b', 22110242, 'Amira Nabil Thalib', 'Jl. Sa\'adah 171', 'P', 4, 10, 1, NULL),
(170, 'F22110245a', 22110245, 'Fadhlina Azzahra', 'Jl. Sa\'adah 172', 'P', 4, 10, 1, NULL),
(171, 'F22110293f', 22110293, 'Fairuzzahrin Atstaqif', 'Jl. Sa\'adah 173', 'P', 4, 10, 1, NULL),
(172, 'F22110294a', 22110294, 'Firda', 'Jl. Sa\'adah 174', 'P', 4, 10, 1, NULL),
(173, 'H22110247h', 22110247, 'Hafizhah Az Zahrah', 'Jl. Sa\'adah 175', 'P', 4, 10, 1, NULL),
(174, 'M22110249i', 22110249, 'Malika Annehli', 'Jl. Sa\'adah 176', 'P', 4, 10, 1, NULL),
(175, 'M22110250f', 22110250, 'Maryam Medinah Syarief', 'Jl. Sa\'adah 177', 'P', 4, 10, 1, NULL),
(176, 'M22110251a', 22110251, 'Mukhliza Qorima', 'Jl. Sa\'adah 178', 'P', 4, 10, 1, NULL),
(177, 'N22110295a', 22110295, 'Nafiah Metsa Sadina', 'Jl. Sa\'adah 179', 'P', 4, 10, 1, NULL),
(178, 'N22110297h', 22110297, 'Najmah', 'Jl. Sa\'adah 180', 'P', 4, 10, 1, NULL),
(179, 'Q22110299a', 22110299, 'Qonitah Khairunnisa', 'Jl. Sa\'adah 181', 'P', 4, 10, 1, NULL),
(180, 'R22110253i', 22110253, 'Rafika Aulia Sayid Filistiari', 'Jl. Sa\'adah 182', 'P', 4, 10, 1, NULL),
(181, 'R22110254r', 22110254, 'Raihana Aulianoor', 'Jl. Sa\'adah 183', 'P', 4, 10, 1, NULL),
(182, 'R22110255i', 22110255, 'Rezkita Ayu Prastiwi', 'Jl. Sa\'adah 184', 'P', 4, 10, 1, NULL),
(183, 'R22110301e', 22110301, 'Rissa Elvina Meidiyanie', 'Jl. Sa\'adah 185', 'P', 4, 10, 1, NULL),
(184, 'S22110256y', 22110256, 'Sava Myra Taqy', 'Jl. Sa\'adah 186', 'P', 4, 10, 1, NULL),
(185, 'V22110303a', 22110303, 'Vaurelia Evarista', 'Jl. Sa\'adah 187', 'P', 4, 10, 1, NULL),
(186, 'W22110304a', 22110304, 'Windry Callysta', 'Jl. Sa\'adah 188', 'P', 4, 10, 1, NULL),
(187, 'Z22110260a', 22110260, 'Zulfa Ramadhania', 'Jl. Sa\'adah 189', 'P', 4, 10, 1, NULL),
(188, 'A2230039A', 2230039, 'AMANDA NUR LUTFIANA', 'Jl. Sa\'adah 190', 'P', 4, 10, 1, NULL),
(189, 'A32230016H', 32230016, 'ANGGIT PRIDANINGSIH', 'Jl. Sa\'adah 191', 'P', 4, 10, 1, NULL),
(190, 'A2230038M', 2230038, 'ANNISA HALIM FADHLUL ULUM', 'Jl. Sa\'adah 192', 'P', 4, 10, 1, NULL),
(191, 'A2230045Z', 2230045, 'ARTHA DINAYA NUGRIAZ', 'Jl. Sa\'adah 193', 'P', 4, 10, 1, NULL),
(192, 'A32230017I', 32230017, 'ASHILA NUR TSABITUL AZMI', 'Jl. Sa\'adah 194', 'P', 4, 10, 1, NULL),
(193, 'F2230047A', 2230047, 'FADIA', 'Jl. Sa\'adah 195', 'P', 4, 10, 1, NULL),
(194, 'F2230042H', 2230042, 'FARISHA KARLIN MUSLIMAH', 'Jl. Sa\'adah 196', 'P', 4, 10, 1, NULL),
(195, 'G32230019H', 32230019, 'GHINA NAILAH', 'Jl. Sa\'adah 197', 'P', 4, 10, 1, NULL),
(196, 'K32230020I', 32230020, 'KHADIJAH NADZIRA RAMADHANI', 'Jl. Sa\'adah 198', 'P', 4, 10, 1, NULL),
(197, 'L2230041I', 2230041, 'LISDA FITRIANTI', 'Jl. Sa\'adah 199', 'P', 4, 10, 1, NULL),
(198, 'L2230046H', 2230046, 'LUBNAH', 'Jl. Sa\'adah 200', 'P', 4, 10, 1, NULL),
(199, 'N32230022A', 32230022, 'NAILA AHZA MEIDINA', 'Jl. Sa\'adah 201', 'P', 4, 10, 1, NULL),
(200, 'N32230023A', 32230023, 'NAJLAA', 'Jl. Sa\'adah 202', 'P', 4, 10, 1, NULL),
(201, 'R32230024A', 32230024, 'RAFIDA AUFA', 'Jl. Sa\'adah 203', 'P', 4, 10, 1, NULL),
(202, 'S32230026I', 32230026, 'SALSABILA FAUZI', 'Jl. Sa\'adah 204', 'P', 4, 10, 1, NULL),
(203, 'S2230044A', 2230044, 'SAUSAN SHAFA NASYWA', 'Jl. Sa\'adah 205', 'P', 4, 10, 1, NULL),
(204, 'S32230027A', 32230027, 'SHALSABILA SYIFA LAURA', 'Jl. Sa\'adah 206', 'P', 4, 10, 1, NULL),
(205, 'Z2230048A', 2230048, 'ZAHRA HUMAIRA', 'Jl. Sa\'adah 207', 'P', 4, 10, 1, NULL),
(206, 'A22010180A', 22010180, 'Awla Nada Shafira A', 'Jl. Sa\'adah 208', 'P', 4, 11, 1, NULL),
(207, 'B22010181a', 22010181, 'Balqis Azzahra Fajma', 'Jl. Sa\'adah 209', 'P', 4, 11, 1, NULL),
(208, 'B22010182i', 22010182, 'Bunga Sari Rahmadhani', 'Jl. Sa\'adah 210', 'P', 4, 11, 1, NULL),
(209, 'C22010183r', 22010183, 'Calya Salma Khaer', 'Jl. Sa\'adah 211', 'P', 4, 11, 1, NULL),
(210, 'E22010184h', 22010184, 'Eka Nurlailatul Fitroh', 'Jl. Sa\'adah 212', 'P', 4, 11, 1, NULL),
(211, 'F22010185a', 22010185, 'Fairuz Hasna', 'Jl. Sa\'adah 213', 'P', 4, 11, 1, NULL),
(212, 'F1142017h', 1142017, 'Fathimah Puteri Rahmatullah', 'Jl. Sa\'adah 214', 'P', 4, 11, 1, NULL),
(213, 'F22010186h', 22010186, 'Fatimah Noor Hidayah', 'Jl. Sa\'adah 215', 'P', 4, 11, 1, NULL),
(214, 'L22010187y', 22010187, 'Lauza Amany', 'Jl. Sa\'adah 216', 'P', 4, 11, 1, NULL),
(215, 'L22010188a', 22010188, 'Lu Lu An-Najwa', 'Jl. Sa\'adah 217', 'P', 4, 11, 1, NULL),
(216, 'N22010190a', 22010190, 'Naila Muna Atalina', 'Jl. Sa\'adah 218', 'P', 4, 11, 1, NULL),
(217, 'N22010193h', 22010193, 'NidaUl Hasanah', 'Jl. Sa\'adah 219', 'P', 4, 11, 1, NULL),
(218, 'N22010194a', 22010194, 'Nisrina Putri Handira', 'Jl. Sa\'adah 220', 'P', 4, 11, 1, NULL),
(219, 'N22010195a', 22010195, 'Nizma Khanza Maritsa', 'Jl. Sa\'adah 221', 'P', 4, 11, 1, NULL),
(220, 'Q22010197a', 22010197, 'Qanita Aprillya', 'Jl. Sa\'adah 222', 'P', 4, 11, 1, NULL),
(221, 'S1142021a', 1142021, 'Saskia Aulia Syifa', 'Jl. Sa\'adah 223', 'P', 4, 11, 1, NULL),
(222, 'S1142022a', 1142022, 'Shovia Varah Diba', 'Jl. Sa\'adah 224', 'P', 4, 11, 2, NULL),
(223, 'S1142024a', 1142024, 'Syaima', 'Jl. Sa\'adah 225', 'P', 4, 11, 1, NULL),
(224, 'S22010201h', 22010201, 'Syarifah Marisa Mutiah', 'Jl. Sa\'adah 226', 'P', 4, 11, 1, NULL),
(225, 'T22010202a', 22010202, 'Titi Hemida', 'Jl. Sa\'adah 227', 'P', 4, 11, 1, NULL),
(226, 'A3192096a', 3192096, 'Aghitsna Isaura Nashita', 'Jl. Sa\'adah 228', 'P', 4, 12, 1, NULL),
(227, 'A3192071a', 3192071, 'Ainayya Alfatiha', 'Jl. Sa\'adah 229', 'P', 4, 12, 1, NULL),
(228, 'A3192097a', 3192097, 'Aisya Noorsyifa', 'Jl. Sa\'adah 230', 'P', 4, 12, 1, NULL),
(229, 'A3192098h', 3192098, 'Aisyah Rufaidhah', 'Jl. Sa\'adah 231', 'P', 4, 12, 1, NULL),
(230, 'A3192099h', 3192099, 'Annisa Raudhatul Jannah', 'Jl. Sa\'adah 232', 'P', 4, 12, 1, NULL),
(231, 'A3192075a', 3192075, 'Aulia Azizah Azzahra', 'Jl. Sa\'adah 233', 'P', 4, 12, 1, NULL),
(232, 'A3192101i', 3192101, 'Aulia Riska Ramadhani', 'Jl. Sa\'adah 234', 'P', 4, 12, 1, NULL),
(233, 'C3192103i', 3192103, 'Citra Rahmasari', 'Jl. Sa\'adah 235', 'P', 4, 12, 1, NULL),
(234, 'D3192104a', 3192104, 'Dhea Syifa Amelia', 'Jl. Sa\'adah 236', 'P', 4, 12, 1, NULL),
(235, 'J3192107h', 3192107, 'Jauza Syahla Nabilah', 'Jl. Sa\'adah 237', 'P', 4, 12, 1, NULL),
(236, 'K3192084i', 3192084, 'Khairani Yulia Putri', 'Jl. Sa\'adah 238', 'P', 4, 12, 1, NULL),
(237, 'K3192109h', 3192109, 'Khairiatun Ni’Mah', 'Jl. Sa\'adah 239', 'P', 4, 12, 1, NULL),
(238, 'N3192087a', 3192087, 'Nada Aziza', 'Jl. Sa\'adah 240', 'P', 4, 12, 1, NULL),
(239, 'N3192088a', 3192088, 'Najwa Shofia Fitria', 'Jl. Sa\'adah 241', 'P', 4, 12, 1, NULL),
(240, 'R3192090a', 3192090, 'Revha Dwidya Sanika', 'Jl. Sa\'adah 242', 'P', 4, 12, 1, NULL),
(241, 'R3192116h', 3192116, 'Rozan Taqiyyah', 'Jl. Sa\'adah 243', 'P', 4, 12, 1, NULL),
(242, 'S3192091n', 3192091, 'Salsabila Az Zahra Rachmad Tiwan', 'Jl. Sa\'adah 244', 'P', 4, 12, 1, NULL),
(243, 'S3192117a', 3192117, 'Salwa Annie Nursyifa', 'Jl. Sa\'adah 245', 'P', 4, 12, 1, NULL),
(244, 'S3192092i', 3192092, 'Septiya Ramadhani', 'Jl. Sa\'adah 246', 'P', 4, 12, 1, NULL),
(245, 'S3192119a', 3192119, 'Syahda', 'Jl. Sa\'adah 247', 'P', 4, 12, 1, NULL),
(246, 'S3192094l', 3192094, 'Syifa Anindia Nabil', 'Jl. Sa\'adah 248', 'P', 4, 12, 1, NULL),
(247, 'A240584d', 240584, 'Abdurrasyid', 'Jl. Sa\'adah 249', 'L', 3, 7, 1, NULL),
(248, 'A240505q', 240505, 'Abdurrozzaq', 'Jl. Sa\'adah 250', 'L', 3, 7, 1, NULL),
(249, 'A240621o', 240621, 'Ahmad Danish Hanianto', 'Jl. Sa\'adah 251', 'L', 3, 7, 1, NULL),
(250, 'A240506z', 240506, 'Ahmad Fariz', 'Jl. Sa\'adah 252', 'L', 3, 7, 1, NULL),
(251, 'A240513a', 240513, 'Azayaka Ahnaf Sukarsa', 'Jl. Sa\'adah 253', 'L', 3, 7, 1, NULL),
(252, 'A240591n', 240591, 'Azzaki Zain Abidin', 'Jl. Sa\'adah 254', 'L', 3, 7, 1, NULL),
(253, 'A240514i', 240514, 'Azzam Algifari', 'Jl. Sa\'adah 255', 'L', 3, 7, 1, NULL),
(254, 'A240592f', 240592, 'Azzam Fatih Althaaf', 'Jl. Sa\'adah 256', 'L', 3, 7, 1, NULL),
(255, 'D240515a', 240515, 'Daffa Faatih Fadhila', 'Jl. Sa\'adah 257', 'L', 3, 7, 1, NULL),
(256, 'D240516a', 240516, 'Daffa', 'Jl. Sa\'adah 258', 'L', 3, 7, 1, NULL),
(257, 'D240517r', 240517, 'Danish Maulana Akbar', 'Jl. Sa\'adah 259', 'L', 3, 7, 1, NULL),
(258, 'F240520i', 240520, 'Faiz bin Junaidi', 'Jl. Sa\'adah 260', 'L', 3, 7, 1, NULL),
(259, 'F240594n', 240594, 'Faqih Hannan', 'Jl. Sa\'adah 261', 'L', 3, 7, 1, NULL),
(260, 'F240522h', 240522, 'Faris Syamil Makkah', 'Jl. Sa\'adah 262', 'L', 3, 7, 1, NULL),
(261, 'F240524s', 240524, 'Firnas', 'Jl. Sa\'adah 263', 'L', 3, 7, 1, NULL),
(262, 'G240597i', 240597, 'Gusti Muhammad Zaki', 'Jl. Sa\'adah 264', 'L', 3, 7, 1, NULL),
(263, 'G240526i', 240526, 'Gusti Nabil Fahrezi', 'Jl. Sa\'adah 265', 'L', 3, 7, 1, NULL),
(264, 'H240531i', 240531, 'Hanief Nabila Gani', 'Jl. Sa\'adah 266', 'L', 3, 7, 1, NULL),
(265, 'I240535y', 240535, 'Ibrahim Keenan Zhafran Ardziky', 'Jl. Sa\'adah 267', 'L', 3, 7, 1, NULL),
(266, 'I240598t', 240598, 'Isya Pashya Adhmart', 'Jl. Sa\'adah 268', 'L', 3, 7, 1, NULL),
(267, 'J240599i', 240599, 'Javier Mahza Albadi', 'Jl. Sa\'adah 269', 'L', 3, 7, 2, NULL),
(268, 'K240601n', 240601, 'Kimio Khalif Arkan', 'Jl. Sa\'adah 270', 'L', 3, 7, 1, NULL),
(269, 'M240603q', 240603, 'M. Umar Faruq', 'Jl. Sa\'adah 271', 'L', 3, 7, 1, NULL),
(270, 'M240538i', 240538, 'M.Baihaqi', 'Jl. Sa\'adah 272', 'L', 3, 7, 1, NULL),
(271, 'M240539h', 240539, 'M.Darul Lughah', 'Jl. Sa\'adah 273', 'L', 3, 7, 1, NULL),
(272, 'M240604n', 240604, 'Muhammad Abdurrohman', 'Jl. Sa\'adah 274', 'L', 3, 7, 1, NULL),
(273, 'M240540f', 240540, 'Muhammad Adzka Ubaidillah Yusuf', 'Jl. Sa\'adah 275', 'L', 3, 7, 1, NULL),
(274, 'M240541n', 240541, 'Muhammad Afnan Setiawan', 'Jl. Sa\'adah 276', 'L', 3, 7, 1, NULL),
(275, 'M240542l', 240542, 'Muhammad Aisy Althaf Syamil', 'Jl. Sa\'adah 277', 'L', 3, 7, 1, NULL),
(276, 'M240543h', 240543, 'Muhammad Akbar Fattah', 'Jl. Sa\'adah 278', 'L', 3, 7, 1, NULL),
(277, 'M240635s', 240635, 'Muhammad Alfarizky Firdaus', 'Jl. Sa\'adah 279', 'L', 3, 7, 1, NULL),
(278, 'M240544r', 240544, 'Muhammad Alfreza Abiezar', 'Jl. Sa\'adah 280', 'L', 3, 7, 1, NULL),
(279, 'M240605h', 240605, 'Muhammad Aqil Abdillah', 'Jl. Sa\'adah 281', 'L', 3, 7, 1, NULL),
(280, 'M240637a', 240637, 'Muhammad Aqil Faeyza Pratama', 'Jl. Sa\'adah 282', 'L', 3, 7, 1, NULL),
(281, 'M240546f', 240546, 'Muhammad Faeyza Ats Tsaqif', 'Jl. Sa\'adah 283', 'L', 3, 7, 1, NULL),
(282, 'M240547r', 240547, 'Muhammad Faith Akbar', 'Jl. Sa\'adah 284', 'L', 3, 7, 1, NULL),
(283, 'M240548n', 240548, 'Muhammad Fajar Faidz Ramadhan', 'Jl. Sa\'adah 285', 'L', 3, 7, 1, NULL),
(284, 'M240549z', 240549, 'Muhammad Fathir Attharizz', 'Jl. Sa\'adah 286', 'L', 3, 7, 1, NULL),
(285, 'M240550i', 240550, 'Muhammad Fatih Alayyubi', 'Jl. Sa\'adah 287', 'L', 3, 7, 1, NULL),
(286, 'M240551n', 240551, 'Muhammad Fauzan Rachmad Tiwan', 'Jl. Sa\'adah 288', 'L', 3, 7, 1, NULL),
(287, 'M240552n', 240552, 'Muhammad Febriyan', 'Jl. Sa\'adah 289', 'L', 3, 7, 1, NULL),
(288, 'M240554i', 240554, 'Muhammad Habibi', 'Jl. Sa\'adah 290', 'L', 3, 7, 1, NULL),
(289, 'M240555n', 240555, 'Muhammad Hafizhurrahman', 'Jl. Sa\'adah 291', 'L', 3, 7, 1, NULL),
(290, 'M240556i', 240556, 'Muhammad Hanun Aliyafi', 'Jl. Sa\'adah 292', 'L', 3, 7, 1, NULL),
(291, 'M240557i', 240557, 'Muhammad Hisyam Ghani', 'Jl. Sa\'adah 293', 'L', 3, 7, 1, NULL),
(292, 'M240606s', 240606, 'Muhammad Ilyas', 'Jl. Sa\'adah 294', 'L', 3, 7, 1, NULL),
(293, 'M240640a', 240640, 'Muhammad Luthfi Denanda', 'Jl. Sa\'adah 295', 'L', 3, 7, 1, NULL),
(294, 'M240558i', 240558, 'Muhammad Luthfi Hadi', 'Jl. Sa\'adah 296', 'L', 3, 7, 1, NULL),
(295, 'M240607n', 240607, 'Muhammad Nizam Rahman', 'Jl. Sa\'adah 297', 'L', 3, 7, 1, NULL),
(296, 'M240559i', 240559, 'Muhammad Rafa Khalfani', 'Jl. Sa\'adah 298', 'L', 3, 7, 1, NULL),
(297, 'M240560s', 240560, 'Muhammad Rafi Firdaus', 'Jl. Sa\'adah 299', 'L', 3, 7, 1, NULL),
(298, 'M240608i', 240608, 'Muhammad Rasya Firjatullah Akbar Putra Hadi', 'Jl. Sa\'adah 300', 'L', 3, 7, 1, NULL),
(299, 'M240561a', 240561, 'Muhammad Rifqi Falah Nugraha', 'Jl. Sa\'adah 301', 'L', 3, 7, 1, NULL),
(300, 'M240609m', 240609, 'Muhammad Rifqon Salim', 'Jl. Sa\'adah 302', 'L', 3, 7, 1, NULL),
(301, 'M240610y', 240610, 'Muhammad Rifqy Boby', 'Jl. Sa\'adah 303', 'L', 3, 7, 1, NULL),
(302, 'M240537n', 240537, 'Muhammad Syafiq Ar- Royyan', 'Jl. Sa\'adah 304', 'L', 3, 7, 1, NULL),
(303, 'M240562a', 240562, 'Muhammad Varay Piola', 'Jl. Sa\'adah 305', 'L', 3, 7, 1, NULL),
(304, 'M240563r', 240563, 'Muhammad Yassar', 'Jl. Sa\'adah 306', 'L', 3, 7, 1, NULL),
(305, 'N240636i', 240636, 'Nafis Gahtan Fahrezi', 'Jl. Sa\'adah 307', 'L', 3, 7, 1, NULL),
(306, 'R240573r', 240573, 'Rakhaa Muhammad Athaya Siregar', 'Jl. Sa\'adah 308', 'L', 3, 7, 1, NULL),
(307, 'R240613i', 240613, 'Raufa Ahza Kasyafani', 'Jl. Sa\'adah 309', 'L', 3, 7, 1, NULL),
(308, 'R240577a', 240577, 'Rizky Dwi Bagus Saputra', 'Jl. Sa\'adah 310', 'L', 3, 7, 1, NULL),
(309, 'S240616l', 240616, 'Said Akhmad Fadhil', 'Jl. Sa\'adah 311', 'L', 3, 7, 1, NULL),
(310, 'W240581t', 240581, 'Wahyu Hidayat', 'Jl. Sa\'adah 312', 'L', 3, 7, 1, NULL),
(311, 'Y240620i', 240620, 'Yaqdzan Assaid Ghani', 'Jl. Sa\'adah 313', 'L', 3, 7, 1, NULL),
(312, 'Z240582m', 240582, 'Zamzam', 'Jl. Sa\'adah 314', 'L', 3, 7, 1, NULL),
(313, 'Z240583d', 240583, 'Zeed', 'Jl. Sa\'adah 315', 'L', 3, 7, 2, NULL),
(314, 'A2230368i', 2230368, 'A. Dziky Almer Al Khalifi', 'Jl. Sa\'adah 316', 'L', 3, 8, 1, NULL),
(315, 'A2230369n', 2230369, 'Abdul Muhsin', 'Jl. Sa\'adah 317', 'L', 3, 8, 1, NULL),
(316, 'A2230370m', 2230370, 'Abdullah Ahmad Azzam', 'Jl. Sa\'adah 318', 'L', 3, 8, 1, NULL),
(317, 'A2230371o', 2230371, 'Abid Abdurrahman Raharjo', 'Jl. Sa\'adah 319', 'L', 3, 8, 1, NULL),
(318, 'A2230372i', 2230372, 'Aditya Fauzi', 'Jl. Sa\'adah 320', 'L', 3, 8, 1, NULL),
(319, 'A2230373n', 2230373, 'Afnan', 'Jl. Sa\'adah 321', 'L', 3, 8, 1, NULL),
(320, 'A2230374i', 2230374, 'Ahmad Aufa Anshari', 'Jl. Sa\'adah 322', 'L', 3, 8, 1, NULL),
(321, 'A2230375s', 2230375, 'Ahmad Rais', 'Jl. Sa\'adah 323', 'L', 3, 8, 1, NULL),
(322, 'A2230376a', 2230376, 'Ahmad Ukasyah Wijaya', 'Jl. Sa\'adah 324', 'L', 3, 8, 1, NULL),
(323, 'A2230474D', 2230474, 'AHMAD YAZID', 'Jl. Sa\'adah 325', 'L', 3, 8, 1, NULL),
(324, 'A2230377i', 2230377, 'Akhdan Ardana Anshari', 'Jl. Sa\'adah 326', 'L', 3, 8, 1, NULL),
(325, 'A2230378a', 2230378, 'Alrevan Maharensyah Zwanzig Excava', 'Jl. Sa\'adah 327', 'L', 3, 8, 1, NULL),
(326, 'A2230379r', 2230379, 'Amar', 'Jl. Sa\'adah 328', 'L', 3, 8, 1, NULL),
(327, 'A2230476H', 2230476, 'ANWAR ABDILLAH', 'Jl. Sa\'adah 329', 'L', 3, 8, 1, NULL),
(328, 'A2230380r', 2230380, 'Arfa Brilian Zavier', 'Jl. Sa\'adah 330', 'L', 3, 8, 1, NULL),
(329, 'A2230382o', 2230382, 'AzZia Azka Nugroho', 'Jl. Sa\'adah 331', 'L', 3, 8, 1, NULL),
(330, 'A2230381g', 2230381, 'Azhar Piliang', 'Jl. Sa\'adah 332', 'L', 3, 8, 1, NULL),
(331, 'B2230383a', 2230383, 'Biyandra Fitra Adilla', 'Jl. Sa\'adah 333', 'L', 3, 8, 1, NULL),
(332, 'F2230477I', 2230477, 'FADHIL RAUF PUTRA ABADI', 'Jl. Sa\'adah 334', 'L', 3, 8, 1, NULL),
(333, 'F2230384h', 2230384, 'Fariz Akhmad Fatah', 'Jl. Sa\'adah 335', 'L', 3, 8, 1, NULL),
(334, 'H2230385r', 2230385, 'Hakim Abdul Jabbar', 'Jl. Sa\'adah 336', 'L', 3, 8, 1, NULL),
(335, 'H2230478Y', 2230478, 'HAMZAH AL-ATSARY', 'Jl. Sa\'adah 337', 'L', 3, 8, 1, NULL),
(336, 'I2230386m', 2230386, 'Ibrahim', 'Jl. Sa\'adah 338', 'L', 3, 8, 1, NULL),
(337, 'I2230479L', 2230479, 'IKHWANUL ALIF SYAHRIL', 'Jl. Sa\'adah 339', 'L', 3, 8, 1, NULL),
(338, 'J2230387a', 2230387, 'Jean Aulia', 'Jl. Sa\'adah 340', 'L', 3, 8, 1, NULL),
(339, 'K2230388d', 2230388, 'Kemal Ali Muhammad', 'Jl. Sa\'adah 341', 'L', 3, 8, 1, NULL),
(340, 'M2230389k', 2230389, 'M. Rizqon Mubarok', 'Jl. Sa\'adah 342', 'L', 3, 8, 1, NULL),
(341, 'M2230391z', 2230391, 'M.Hanif Alhafiz', 'Jl. Sa\'adah 343', 'L', 3, 8, 1, NULL),
(342, 'M2230392a', 2230392, 'M.Nahsya', 'Jl. Sa\'adah 344', 'L', 3, 8, 1, NULL),
(343, 'M2230393h', 2230393, 'Machmud Nur Falah', 'Jl. Sa\'adah 345', 'L', 3, 8, 1, NULL),
(344, 'M2230394e', 2230394, 'Maulana Daffa El-Zikrie', 'Jl. Sa\'adah 346', 'L', 3, 8, 1, NULL),
(345, 'M2230395a', 2230395, 'Muhammad Agha Nabil Aqila', 'Jl. Sa\'adah 347', 'L', 3, 8, 1, NULL),
(346, 'M2230396i', 2230396, 'Muhammad Annas Al Ghifari', 'Jl. Sa\'adah 348', 'L', 3, 8, 1, NULL),
(347, 'M2230397q', 2230397, 'Muhammad Azzam Tsaaqif Al Afiq', 'Jl. Sa\'adah 349', 'L', 3, 8, 1, NULL),
(348, 'M2230480Y', 2230480, 'MUHAMMAD DAFI RIZKY', 'Jl. Sa\'adah 350', 'L', 3, 8, 1, NULL),
(349, 'M2230398i', 2230398, 'Muhammad Diyara Rabbani', 'Jl. Sa\'adah 351', 'L', 3, 8, 1, NULL),
(350, 'M2230399n', 2230399, 'Muhammad Falah Fakhrudin', 'Jl. Sa\'adah 352', 'L', 3, 8, 1, NULL),
(351, 'M2230481N', 2230481, 'MUHAMMAD HAFIDZ FURQON', 'Jl. Sa\'adah 353', 'L', 3, 8, 1, NULL),
(352, 'M2230400n', 2230400, 'Muhammad Hafidz Kurniawan', 'Jl. Sa\'adah 354', 'L', 3, 8, 1, NULL),
(353, 'M2230401h', 2230401, 'Muhammad Hanif Rizqullah', 'Jl. Sa\'adah 355', 'L', 3, 8, 1, NULL),
(354, 'M2230402h', 2230402, 'Muhammad Hanif Ubaidillah', 'Jl. Sa\'adah 356', 'L', 3, 8, 1, NULL),
(355, 'M2230482Y', 2230482, 'MUHAMMAD NABIL AL GHAAZIY', 'Jl. Sa\'adah 357', 'L', 3, 8, 1, NULL),
(356, 'M2230483I', 2230483, 'MUHAMMAD NASHIRUDDIN ALBANI', 'Jl. Sa\'adah 358', 'L', 3, 8, 1, NULL),
(357, 'M2230484I', 2230484, 'MUHAMMAD NAZHER AN NAFI', 'Jl. Sa\'adah 359', 'L', 3, 8, 1, NULL),
(358, 'M2230405n', 2230405, 'Muhammad Nizami Fawaz Arrahman', 'Jl. Sa\'adah 360', 'L', 3, 8, 1, NULL),
(359, 'M2230485I', 2230485, 'MUHAMMAD QAMIL ALFARISI', 'Jl. Sa\'adah 361', 'L', 3, 8, 1, NULL),
(360, 'M2230486A', 2230486, 'MUHAMMAD RAFA', 'Jl. Sa\'adah 362', 'L', 3, 8, 1, NULL),
(361, 'M2230406i', 2230406, 'Muhammad Rizqi Alfarizi', 'Jl. Sa\'adah 363', 'L', 3, 8, 1, NULL),
(362, 'M2230408i', 2230408, 'Muhammad Shaihan Azh Zhahiri', 'Jl. Sa\'adah 364', 'L', 3, 8, 1, NULL),
(363, 'M2230487Y', 2230487, 'MUHAMMAD SULTHAN AZIZY', 'Jl. Sa\'adah 365', 'L', 3, 8, 1, NULL),
(364, 'R2230412h', 2230412, 'R. Fadhil Affandi Fayyadh', 'Jl. Sa\'adah 366', 'L', 3, 8, 1, NULL),
(365, 'R2230413d', 2230413, 'Raffasya Ahmad', 'Jl. Sa\'adah 367', 'L', 3, 8, 1, NULL),
(366, 'R2230415i', 2230415, 'Rizky Al Bukhari', 'Jl. Sa\'adah 368', 'L', 3, 8, 1, NULL),
(367, 'S2230416a', 2230416, 'Shandy Pratama Poetra', 'Jl. Sa\'adah 369', 'L', 3, 8, 1, NULL),
(368, 'S2230417h', 2230417, 'Shaqueel El Fadillah', 'Jl. Sa\'adah 370', 'L', 3, 8, 1, NULL),
(369, 'S2230418q', 2230418, 'Syafiq', 'Jl. Sa\'adah 371', 'L', 3, 8, 1, NULL),
(370, 'S2230419h', 2230419, 'Syaihan Amri Asyaddu Hubbalillah', 'Jl. Sa\'adah 372', 'L', 3, 8, 1, NULL),
(371, 'S2230420f', 2230420, 'Syauqi Muayyad Syaf', 'Jl. Sa\'adah 373', 'L', 3, 8, 1, NULL),
(372, 'Y2230488S', 2230488, 'YAHYA AL-MAHRUS', 'Jl. Sa\'adah 374', 'L', 3, 8, 1, NULL),
(373, 'Z2230421m', 2230421, 'Zaidan Fawaz MuTashim', 'Jl. Sa\'adah 375', 'L', 3, 8, 1, NULL),
(374, 'A2220263f', 2220263, 'Abdul Lathif', 'Jl. Sa\'adah 376', 'L', 3, 9, 1, NULL),
(375, 'A2220317d', 2220317, 'Abdullah Ahmad', 'Jl. Sa\'adah 377', 'L', 3, 9, 1, NULL),
(376, 'A2220266i', 2220266, 'Abja Muhaini', 'Jl. Sa\'adah 378', 'L', 3, 9, 1, NULL),
(377, 'A2220347n', 2220347, 'Ahmad Hafidzuddin', 'Jl. Sa\'adah 379', 'L', 3, 9, 1, NULL),
(378, 'A2220345i', 2220345, 'Ahmad Nabil Hijazi', 'Jl. Sa\'adah 380', 'L', 3, 9, 1, NULL),
(379, 'A2220366n', 2220366, 'Ahmad Qourta Zaidan', 'Jl. Sa\'adah 381', 'L', 3, 9, 1, NULL),
(380, 'A2220277i', 2220277, 'Ahmad Rafi ALi', 'Jl. Sa\'adah 382', 'L', 3, 9, 1, NULL),
(381, 'A2220272q', 2220272, 'Ahmad Siddiq', 'Jl. Sa\'adah 383', 'L', 3, 9, 1, NULL),
(382, 'A2220352i', 2220352, 'Akhmad Ramadhani', 'Jl. Sa\'adah 384', 'L', 3, 9, 1, NULL),
(383, 'A2220311s', 2220311, 'Anas', 'Jl. Sa\'adah 385', 'L', 3, 9, 1, NULL),
(384, 'A2220313l', 2220313, 'Aqil', 'Jl. Sa\'adah 386', 'L', 3, 9, 1, NULL),
(385, 'A2220276n', 2220276, 'Ayman', 'Jl. Sa\'adah 387', 'L', 3, 9, 1, NULL),
(386, 'B2220267o', 2220267, 'Bilal Firdausy Baskoro', 'Jl. Sa\'adah 388', 'L', 3, 9, 1, NULL),
(387, 'D2220349o', 2220349, 'Daffa Aditya Yudhoyono', 'Jl. Sa\'adah 389', 'L', 3, 9, 1, NULL),
(388, 'D2220344n', 2220344, 'Dzulhilmi Rizqy Abdurrohman', 'Jl. Sa\'adah 390', 'L', 3, 9, 1, NULL),
(389, 'F2220270i', 2220270, 'Fairuz Syarif Al-Hadi', 'Jl. Sa\'adah 391', 'L', 3, 9, 1, NULL),
(390, 'H2220308i', 2220308, 'Hamzah Al Bahri', 'Jl. Sa\'adah 392', 'L', 3, 9, 1, NULL),
(391, 'K2220309h', 2220309, 'Khalid Hizbullah', 'Jl. Sa\'adah 393', 'L', 3, 9, 1, NULL),
(392, 'L2220353m', 2220353, 'Lukmanul Hakim', 'Jl. Sa\'adah 394', 'L', 3, 9, 1, NULL),
(393, 'M2220286d', 2220286, 'Muhammad', 'Jl. Sa\'adah 395', 'L', 3, 9, 1, NULL),
(394, 'M2220284n', 2220284, 'Muhammad Adnan Husnan', 'Jl. Sa\'adah 396', 'L', 3, 9, 1, NULL),
(395, 'M2220289n', 2220289, 'Muhammad Aza Ghaisan Ryan', 'Jl. Sa\'adah 397', 'L', 3, 9, 1, NULL),
(396, 'M2220300o', 2220300, 'Muhammad Daffa Ghathfan Renjiro', 'Jl. Sa\'adah 398', 'L', 3, 9, 1, NULL),
(397, 'M2220273o', 2220273, 'Muhammad Dwi Febriyanto', 'Jl. Sa\'adah 399', 'L', 3, 9, 1, NULL),
(398, 'M2220304n', 2220304, 'Muhammad Fachry Ramadhan', 'Jl. Sa\'adah 400', 'L', 3, 9, 1, NULL),
(399, 'M2220264l', 2220264, 'Muhammad Fadhil', 'Jl. Sa\'adah 401', 'L', 3, 9, 1, NULL),
(400, 'M2220295s', 2220295, 'Muhammad Farras', 'Jl. Sa\'adah 402', 'L', 3, 9, 1, NULL),
(401, 'M2220278i', 2220278, 'Muhammad Fikri Al Farisi Fauzi', 'Jl. Sa\'adah 403', 'L', 3, 9, 1, NULL),
(402, 'M2220301N', 2220301, 'Muhammad Hafizh Mumtaz N', 'Jl. Sa\'adah 404', 'L', 3, 9, 1, NULL),
(403, 'M2220316n', 2220316, 'Muhammad Hamdani Ihsan', 'Jl. Sa\'adah 405', 'L', 3, 9, 1, NULL),
(404, 'M2220367a', 2220367, 'Muhammad Hanif Nur Prasetya', 'Jl. Sa\'adah 406', 'L', 3, 9, 1, NULL),
(405, 'M2220312h', 2220312, 'Muhammad Kamal Al Faqih', 'Jl. Sa\'adah 407', 'L', 3, 9, 1, NULL),
(406, 'M2220287h', 2220287, 'Muhammad Luthfansyah', 'Jl. Sa\'adah 408', 'L', 3, 9, 1, NULL),
(407, 'M2220315l', 2220315, 'Muhammad Nabil', 'Jl. Sa\'adah 409', 'L', 3, 9, 1, NULL),
(408, 'M2220314m', 2220314, 'Muhammad Nabil Makarim', 'Jl. Sa\'adah 410', 'L', 3, 9, 1, NULL),
(409, 'M2220281n', 2220281, 'Muhammad Nazhir Yahya Fauzan', 'Jl. Sa\'adah 411', 'L', 3, 9, 1, NULL),
(410, 'M2220296l', 2220296, 'Muhammad Nazriel', 'Jl. Sa\'adah 412', 'L', 3, 9, 1, NULL),
(411, 'M2220307i', 2220307, 'Muhammad Rafa Jali', 'Jl. Sa\'adah 413', 'L', 3, 9, 1, NULL),
(412, 'M2220292a', 2220292, 'Muhammad Raffa', 'Jl. Sa\'adah 414', 'L', 3, 9, 1, NULL),
(413, 'M2220268a', 2220268, 'Muhammad Raffa Aqiela', 'Jl. Sa\'adah 415', 'L', 3, 9, 1, NULL),
(414, 'M2220351a', 2220351, 'Muhammad Rafif Mustafa', 'Jl. Sa\'adah 416', 'L', 3, 9, 1, NULL),
(415, 'M2220271i', 2220271, 'Muhammad Rayyan Islami', 'Jl. Sa\'adah 417', 'L', 3, 9, 1, NULL),
(416, 'M2220291q', 2220291, 'Muhammad Shiddiq', 'Jl. Sa\'adah 418', 'L', 3, 9, 1, NULL),
(417, 'M2220293a', 2220293, 'Muhammad Syafiq Arkana', 'Jl. Sa\'adah 419', 'L', 3, 9, 1, NULL),
(418, 'M2220282t', 2220282, 'Muhammad Tegar Langit', 'Jl. Sa\'adah 420', 'L', 3, 9, 1, NULL),
(419, 'M2220269t', 2220269, 'Muhammad Zidane Adilla Munajat', 'Jl. Sa\'adah 421', 'L', 3, 9, 1, NULL),
(420, 'M2220285n', 2220285, 'Muhammad Zidane Ramadhan', 'Jl. Sa\'adah 422', 'L', 3, 9, 1, NULL),
(421, 'M2220348r', 2220348, 'Muhammad Ziyad Al Mudhoffar', 'Jl. Sa\'adah 423', 'L', 3, 9, 1, NULL),
(422, 'N2220302a', 2220302, 'Naadir Fadhillah Raisya', 'Jl. Sa\'adah 424', 'L', 3, 9, 1, NULL),
(423, 'N2220279i', 2220279, 'Nabiel Ahmad Ruhbani', 'Jl. Sa\'adah 425', 'L', 3, 9, 1, NULL),
(424, 'S2220306i', 2220306, 'Sajid Haikal Al Katiri', 'Jl. Sa\'adah 426', 'L', 3, 9, 1, NULL),
(425, 'A2400151a', 2400151, 'Aidira Lintang Pratama', 'Jl. Sa\'adah 427', 'L', 4, 13, 1, NULL),
(426, 'A2400115h', 2400115, 'Aji Rizki Khairullah', 'Jl. Sa\'adah 428', 'L', 4, 13, 1, NULL),
(427, 'A2400116l', 2400116, 'Ali Abdulrahim Bafadhal', 'Jl. Sa\'adah 429', 'L', 4, 13, 1, NULL),
(428, 'B2400138f', 2400138, 'Bintang Felix Fauzi Arif', 'Jl. Sa\'adah 430', 'L', 4, 13, 1, NULL),
(429, 'F2400153r', 2400153, 'Fadhelyannur', 'Jl. Sa\'adah 431', 'L', 4, 13, 1, NULL),
(430, 'F2400117a', 2400117, 'Farid Fradifta', 'Jl. Sa\'adah 432', 'L', 4, 13, 1, NULL),
(431, 'F2400154a', 2400154, 'Fathan Narindrata', 'Jl. Sa\'adah 433', 'L', 4, 13, 1, NULL),
(432, 'J2400118a', 2400118, 'Junior Rafaleno Pramudya', 'Jl. Sa\'adah 434', 'L', 4, 13, 1, NULL),
(433, 'M2400155n', 2400155, 'M. Fakhri Husayn', 'Jl. Sa\'adah 435', 'L', 4, 13, 1, NULL),
(434, 'M2400156m', 2400156, 'M. Rakha Althaf Munazhzham', 'Jl. Sa\'adah 436', 'L', 4, 13, 1, NULL),
(435, 'M2400119s', 2400119, 'Marcellino Ciccio Aulia Kumayas', 'Jl. Sa\'adah 437', 'L', 4, 13, 1, NULL),
(436, 'M2400137h', 2400137, 'Maulana Malik Ibrahim Hamzah', 'Jl. Sa\'adah 438', 'L', 4, 13, 1, NULL),
(437, 'M2400120a', 2400120, 'Miftah Mirza', 'Jl. Sa\'adah 439', 'L', 4, 13, 1, NULL),
(438, 'M2400121z', 2400121, 'Muhammad Al Hafidz', 'Jl. Sa\'adah 440', 'L', 4, 13, 1, NULL),
(439, 'M2400123q', 2400123, 'Muhammad Aufa Akhtoriq', 'Jl. Sa\'adah 441', 'L', 4, 13, 1, NULL),
(440, 'M2400157y', 2400157, 'Muhammad Jacky', 'Jl. Sa\'adah 442', 'L', 4, 13, 1, NULL),
(441, 'M2400173o', 2400173, 'Muhammad Muhsin Protomo', 'Jl. Sa\'adah 443', 'L', 4, 13, 1, NULL),
(442, 'M2400167d', 2400167, 'Muhammad Rayyan Ibnu Rusyd', 'Jl. Sa\'adah 444', 'L', 4, 13, 1, NULL),
(443, 'M2400169h', 2400169, 'Muhammad Riyadh', 'Jl. Sa\'adah 445', 'L', 4, 13, 1, NULL),
(444, 'M2400174q', 2400174, 'Muhammad Shodiq', 'Jl. Sa\'adah 446', 'L', 4, 13, 1, NULL),
(445, 'R2400168i', 2400168, 'Rifky Razani', 'Jl. Sa\'adah 447', 'L', 4, 13, 1, NULL),
(446, 'R2400161n', 2400161, 'Rizqy Aditya Ramadhan', 'Jl. Sa\'adah 448', 'L', 4, 13, 1, NULL),
(447, 'S2400126n', 2400126, 'Sulaiman', 'Jl. Sa\'adah 449', 'L', 4, 13, 1, NULL),
(448, 'Y2400139n', 2400139, 'Yusuf Aditya Nasution', 'Jl. Sa\'adah 450', 'L', 4, 13, 1, NULL),
(449, 'M2400176l', 2400176, 'Muhammad Abid Akmal', 'Jl. Sa\'adah 451', 'L', 4, 13, 1, NULL),
(450, 'A2230030n', 2230030, 'Aditya Faizhal Rahman', 'Jl. Sa\'adah 452', 'L', 4, 10, 1, NULL),
(451, 'A2230094r', 2230094, 'Ammar', 'Jl. Sa\'adah 453', 'L', 4, 10, 1, NULL),
(452, 'A2230095h', 2230095, 'Andin Hassan Fadhilah', 'Jl. Sa\'adah 454', 'L', 4, 10, 1, NULL),
(453, 'A2230096s', 2230096, 'Angello Ciccio Aulia Kumayas', 'Jl. Sa\'adah 455', 'L', 4, 10, 1, NULL),
(454, 'A2230028i', 2230028, 'Arbi Muhammad Fajri', 'Jl. Sa\'adah 456', 'L', 4, 10, 1, NULL),
(455, 'A2230029r', 2230029, 'Arridho Sachio Syaifuddinnoor', 'Jl. Sa\'adah 457', 'L', 4, 10, 1, NULL),
(456, 'A2230097d', 2230097, 'Athaillah Mufid', 'Jl. Sa\'adah 458', 'L', 4, 10, 1, NULL),
(457, 'D2230099d', 2230099, 'Daffa Hafuza Al Mujahid', 'Jl. Sa\'adah 459', 'L', 4, 10, 1, NULL),
(458, 'H2230031k', 2230031, 'Hafiz Mubarak', 'Jl. Sa\'adah 460', 'L', 4, 10, 1, NULL),
(459, 'H2230032i', 2230032, 'Hamid Farihi', 'Jl. Sa\'adah 461', 'L', 4, 10, 1, NULL),
(460, 'H2230100n', 2230100, 'Hannan', 'Jl. Sa\'adah 462', 'L', 4, 10, 1, NULL),
(461, 'M2230033a', 2230033, 'M. Rashya Fadillah Pratama', 'Jl. Sa\'adah 463', 'L', 4, 10, 1, NULL),
(462, 'M2230034n', 2230034, 'M. Zein Syakieb Al Fathan', 'Jl. Sa\'adah 464', 'L', 4, 10, 1, NULL),
(463, 'M2230103n', 2230103, 'Muhammad Ali Rifqi Brillian', 'Jl. Sa\'adah 465', 'L', 4, 10, 1, NULL),
(464, 'M2230035n', 2230035, 'Muhammad Arrayyan', 'Jl. Sa\'adah 466', 'L', 4, 10, 1, NULL),
(465, 'M2230116i', 2230116, 'Muhammad Azhar Rosyadi', 'Jl. Sa\'adah 467', 'L', 4, 10, 1, NULL),
(466, 'M2230036i', 2230036, 'Muhammad Dzahabi', 'Jl. Sa\'adah 468', 'L', 4, 10, 1, NULL),
(467, 'M2230105m', 2230105, 'Muhammad Fathurrahman Halim', 'Jl. Sa\'adah 469', 'L', 4, 10, 1, NULL),
(468, 'M2230106q', 2230106, 'Muhammad Hilman Afiq', 'Jl. Sa\'adah 470', 'L', 4, 10, 1, NULL),
(469, 'M22010164i', 22010164, 'Muhammad Nabil Syauqi', 'Jl. Sa\'adah 471', 'L', 4, 10, 1, NULL),
(470, 'M2230109i', 2230109, 'Muhammad Ramadhan Fathoni', 'Jl. Sa\'adah 472', 'L', 4, 10, 1, NULL),
(471, 'M2230110i', 2230110, 'Muhammad Zaki', 'Jl. Sa\'adah 473', 'L', 4, 10, 1, NULL),
(472, 'R2230111z', 2230111, 'Rafat Afgant Faeroz', 'Jl. Sa\'adah 474', 'L', 4, 10, 1, NULL),
(473, 'S2230114r', 2230114, 'Sulthan Muhajir', 'Jl. Sa\'adah 475', 'L', 4, 10, 1, NULL),
(474, 'Y2230115t', 2230115, 'Yusfi Hidayat', 'Jl. Sa\'adah 476', 'L', 4, 10, 1, NULL),
(475, 'A22110282i', 22110282, 'Achmad Rofiful Hilmi', 'Jl. Sa\'adah 477', 'L', 4, 10, 1, NULL),
(476, 'A22110205l', 22110205, 'Abdullah Absyar Farol', 'Jl. Sa\'adah 478', 'L', 4, 10, 1, NULL),
(477, 'A22110273h', 22110273, 'Adieb Abdullah', 'Jl. Sa\'adah 479', 'L', 4, 10, 1, NULL),
(478, 'A22110206n', 22110206, 'Adwa Rizwadhan', 'Jl. Sa\'adah 480', 'L', 4, 10, 1, NULL),
(479, 'A22110207b', 22110207, 'Ahmad Adib', 'Jl. Sa\'adah 481', 'L', 4, 10, 1, NULL),
(480, 'A22010128h', 22010128, 'Ahmad Faiz Al Miqdad Muhibbulloh', 'Jl. Sa\'adah 482', 'L', 4, 10, 1, NULL),
(481, 'A22110290i', 22110290, 'Ahmad Izya Ramadhani', 'Jl. Sa\'adah 483', 'L', 4, 10, 1, NULL),
(482, 'A22110262h', 22110262, 'Aiman Khairullah', 'Jl. Sa\'adah 484', 'L', 4, 10, 1, NULL),
(483, 'A22110209r', 22110209, 'Ammar', 'Jl. Sa\'adah 485', 'L', 4, 10, 1, NULL),
(484, 'A22110265d', 22110265, 'Azka Danish Ahmad', 'Jl. Sa\'adah 486', 'L', 4, 10, 1, NULL),
(485, 'D22110212r', 22110212, 'Daffa Karunia Akbar', 'Jl. Sa\'adah 487', 'L', 4, 10, 1, NULL),
(486, 'F22110216n', 22110216, 'Fathurrahman', 'Jl. Sa\'adah 488', 'L', 4, 10, 1, NULL),
(487, 'F22110217n', 22110217, 'Fayyadh Abdul Hanan', 'Jl. Sa\'adah 489', 'L', 4, 10, 1, NULL),
(488, 'I22110276n', 22110276, 'Ied Abdurrahman Al Fauzan', 'Jl. Sa\'adah 490', 'L', 4, 10, 1, NULL),
(489, 'I22110278e', 22110278, 'Izzis Gaza Palestine', 'Jl. Sa\'adah 491', 'L', 4, 10, 1, NULL),
(490, 'K22110306a', 22110306, 'Khalil Al Ahza', 'Jl. Sa\'adah 492', 'L', 4, 10, 1, NULL),
(491, 'M22110280n', 22110280, 'M. Alif Rahman', 'Jl. Sa\'adah 493', 'L', 4, 10, 1, NULL),
(492, 'M22110291i', 22110291, 'M. Ismanullah Zwageri', 'Jl. Sa\'adah 494', 'L', 4, 10, 1, NULL),
(493, 'M22110219p', 22110219, 'M.Satria Wibowo.Wp', 'Jl. Sa\'adah 495', 'L', 4, 10, 1, NULL),
(494, 'M22110271a', 22110271, 'Moh. Aqso Demecca', 'Jl. Sa\'adah 496', 'L', 4, 10, 1, NULL),
(495, 'M22110220b', 22110220, 'Muhammad Abdul Wahhab', 'Jl. Sa\'adah 497', 'L', 4, 10, 1, NULL),
(496, 'M22110222d', 22110222, 'Muhammad Abrar Rasyid', 'Jl. Sa\'adah 498', 'L', 4, 10, 1, NULL),
(497, 'M2210263i', 2210263, 'Muhammad Dihyah Qalbi', 'Jl. Sa\'adah 499', 'L', 4, 10, 1, NULL),
(498, 'M22110224d', 22110224, 'Muhammad Fadli Rasyid', 'Jl. Sa\'adah 500', 'L', 4, 10, 1, NULL),
(499, 'M22110270n', 22110270, 'Muhammad Ghaisan Nur Rahman', 'Jl. Sa\'adah 501', 'L', 4, 10, 1, NULL),
(500, 'M22110225n', 22110225, 'Muhammad Ghazi Zia Al Affan', 'Jl. Sa\'adah 502', 'L', 4, 10, 1, NULL),
(501, 'M22110266r', 22110266, 'Muhammad Maulana Akbar', 'Jl. Sa\'adah 503', 'L', 4, 10, 1, NULL),
(502, 'M22110228q', 22110228, 'Muhammad Nabil Dhiyaul Haq', 'Jl. Sa\'adah 504', 'L', 4, 10, 1, NULL),
(503, 'M22110230i', 22110230, 'Muhammad Raihan Sauqi', 'Jl. Sa\'adah 505', 'L', 4, 10, 1, NULL),
(504, 'M22110281r', 22110281, 'Muhammad Rasyad Samir', 'Jl. Sa\'adah 506', 'L', 4, 10, 1, NULL),
(505, 'M22110289l', 22110289, 'Muhammad Yasir Akmal', 'Jl. Sa\'adah 507', 'L', 4, 10, 1, NULL),
(506, 'M22110261i', 22110261, 'Muhammad Yusuf Alif Al Ghifari', 'Jl. Sa\'adah 508', 'L', 4, 10, 1, NULL),
(507, 'M22110231d', 22110231, 'Muhammad Ziyad', 'Jl. Sa\'adah 509', 'L', 4, 10, 1, NULL),
(508, 'N22110274a', 22110274, 'Nur Adly Pratama', 'Jl. Sa\'adah 510', 'L', 4, 10, 1, NULL),
(509, 'R22110292n', 22110292, 'Rais Abduljabbar Ichsanuddin', 'Jl. Sa\'adah 511', 'L', 4, 10, 1, NULL),
(510, 'R22110285r', 22110285, 'Razan M Ikhsan Siregar', 'Jl. Sa\'adah 512', 'L', 4, 10, 1, NULL),
(511, 'S22110272u', 22110272, 'Syafiq Abimanyu', 'Jl. Sa\'adah 513', 'L', 4, 10, 1, NULL),
(512, 'W22110238r', 22110238, 'Wuquf Ibnu Fathir', 'Jl. Sa\'adah 514', 'L', 4, 10, 1, NULL),
(513, 'R22110234h', 22110234, 'Raihan Dede Fadillah', 'Jl. Sa\'adah 515', 'L', 4, 10, 1, NULL),
(514, 'A22010123d', 22010123, 'Abdul Ghanim Ar-Rasyid', 'Jl. Sa\'adah 516', 'L', 4, 11, 1, NULL),
(515, 'A22010124i', 22010124, 'Abdul Hakam Al Katiri', 'Jl. Sa\'adah 517', 'L', 4, 11, 1, NULL),
(516, 'A1141001q', 1141001, 'Abu Bakar Asy Shidiq', 'Jl. Sa\'adah 518', 'L', 4, 11, 1, NULL),
(517, 'A2321011r', 2321011, 'Abu Bakar Baasyir', 'Jl. Sa\'adah 519', 'L', 4, 11, 1, NULL),
(518, 'A1718902a', 1718902, 'Aditya Malik Saputra', 'Jl. Sa\'adah 520', 'L', 4, 11, 1, NULL),
(519, 'A1141002d', 1141002, 'Ahmad', 'Jl. Sa\'adah 521', 'L', 4, 11, 1, NULL),
(520, 'A22010126a', 22010126, 'Ahmad Fahmi Etika Praja', 'Jl. Sa\'adah 522', 'L', 4, 11, 1, NULL),
(521, 'A22010127i', 22010127, 'Ahmad Fahri', 'Jl. Sa\'adah 523', 'L', 4, 11, 1, NULL),
(522, 'A1141003i', 1141003, 'Ahmad Fathi', 'Jl. Sa\'adah 524', 'L', 4, 11, 1, NULL),
(523, 'A3306607h', 3306607, 'Ahmad Fauzan Fadillah', 'Jl. Sa\'adah 525', 'L', 4, 11, 1, NULL),
(524, 'A22010129m', 22010129, 'Ahmad Ilham', 'Jl. Sa\'adah 526', 'L', 4, 11, 1, NULL),
(525, 'A1938259i', 1938259, 'Akhmad Dhani Rupaidi', 'Jl. Sa\'adah 527', 'L', 4, 11, 1, NULL),
(526, 'A1141004r', 1141004, 'Al Hikam Author', 'Jl. Sa\'adah 528', 'L', 4, 11, 1, NULL),
(527, 'A22010131i', 22010131, 'Alun Paradipta Sakkai', 'Jl. Sa\'adah 529', 'L', 4, 11, 1, NULL),
(528, 'A22010132q', 22010132, 'Ansyari Shiddieq', 'Jl. Sa\'adah 530', 'L', 4, 11, 1, NULL),
(529, 'A32210013a', 32210013, 'Arya Bangkit Prasoja', 'Jl. Sa\'adah 531', 'L', 4, 11, 1, NULL),
(530, 'F22010135k', 22010135, 'Faiz Mubarak', 'Jl. Sa\'adah 532', 'L', 4, 11, 1, NULL),
(531, 'F22010204h', 22010204, 'Fatih Rifqiansyah', 'Jl. Sa\'adah 533', 'L', 4, 11, 1, NULL),
(532, 'H22010138a', 22010138, 'Haikal Dwi Aulia Ananta', 'Jl. Sa\'adah 534', 'L', 4, 11, 1, NULL),
(533, 'K32210012r', 32210012, 'Khairil Akbar', 'Jl. Sa\'adah 535', 'L', 4, 11, 1, NULL),
(534, 'L22010139m', 22010139, 'Luqman Hakim', 'Jl. Sa\'adah 536', 'L', 4, 11, 1, NULL),
(535, 'M22010145n', 22010145, 'M. Razan Zakwan', 'Jl. Sa\'adah 537', 'L', 4, 11, 1, NULL),
(536, 'M22010148f', 22010148, 'Moch. Naufal Hanif', 'Jl. Sa\'adah 538', 'L', 4, 11, 1, NULL),
(537, 'M1141005r', 1141005, 'Muhammad Abu Darda Al Atsar', 'Jl. Sa\'adah 539', 'L', 4, 11, 1, NULL),
(538, 'M22010150a', 22010150, 'Muhammad Afwan Musyaffa', 'Jl. Sa\'adah 540', 'L', 4, 11, 1, NULL),
(539, 'M22010154i', 22010154, 'Muhammad Azhari Ilmi', 'Jl. Sa\'adah 541', 'L', 4, 11, 1, NULL),
(540, 'M22010155i', 22010155, 'Muhammad Daffa Putra Rabbani', 'Jl. Sa\'adah 542', 'L', 4, 11, 1, NULL),
(541, 'M22010142g', 22010142, 'Muhammad Diyas Adhyaksa Tanjung', 'Jl. Sa\'adah 543', 'L', 4, 11, 1, NULL),
(542, 'M7923399n', 7923399, 'Muhammad Febrian Nasution', 'Jl. Sa\'adah 544', 'L', 4, 11, 1, NULL),
(543, 'M22010137d', 22010137, 'Muhammad Hafid', 'Jl. Sa\'adah 545', 'L', 4, 11, 1, NULL),
(544, 'M22010157y', 22010157, 'Muhammad Hasbullah Al-Banjary', 'Jl. Sa\'adah 546', 'L', 4, 11, 1, NULL),
(545, 'M22010158a', 22010158, 'Muhammad Havik Al Ridha', 'Jl. Sa\'adah 547', 'L', 4, 11, 1, NULL),
(546, 'M22010144l', 22010144, 'Muhammad Husein Haekal', 'Jl. Sa\'adah 548', 'L', 4, 11, 1, NULL),
(547, 'M22010159i', 22010159, 'Muhammad Ihsan Wahyudi', 'Jl. Sa\'adah 549', 'L', 4, 11, 1, NULL),
(548, 'M22010160q', 22010160, 'Muhammad Kenzhi Aqeela Shiddiq', 'Jl. Sa\'adah 550', 'L', 4, 11, 1, NULL),
(549, 'M22010161n', 22010161, 'Muhammad Luqman', 'Jl. Sa\'adah 551', 'L', 4, 11, 1, NULL),
(550, 'M22010162h', 22010162, 'Muhammad Miqdad Abdillah', 'Jl. Sa\'adah 552', 'L', 4, 11, 1, NULL),
(551, 'M22010165i', 22010165, 'Muhammad Nur Zayni Ghani', 'Jl. Sa\'adah 553', 'L', 4, 11, 1, NULL),
(552, 'M1141012a', 1141012, 'Muhammad Rasya Aditya Pradana', 'Jl. Sa\'adah 554', 'L', 4, 11, 1, NULL),
(553, 'M2200091o', 2200091, 'Muhammad Ridho', 'Jl. Sa\'adah 555', 'L', 4, 11, 1, NULL),
(554, 'M1638877n', 1638877, 'Muhammad Rivaldi Rahman', 'Jl. Sa\'adah 556', 'L', 4, 11, 1, NULL),
(555, 'M22010146i', 22010146, 'Muhammad Rizky Auliandy Akbar Putra Hadi', 'Jl. Sa\'adah 557', 'L', 4, 11, 1, NULL);
INSERT INTO `siswa` (`id`, `kode`, `nis`, `nama`, `alamat`, `jenis_kelamin`, `jenjang_id`, `kelas_id`, `status_id`, `foto`) VALUES
(556, 'M1607953a', 1607953, 'Muhammad Rohiid Al ALauna', 'Jl. Sa\'adah 558', 'L', 4, 11, 1, NULL),
(557, 'M22010169n', 22010169, 'Muhammad Yazid Al Ikhsan', 'Jl. Sa\'adah 559', 'L', 4, 11, 1, NULL),
(558, 'M22010171h', 22010171, 'Muhammad Zacky Saffarinoh', 'Jl. Sa\'adah 560', 'L', 4, 11, 1, NULL),
(559, 'M32210010n', 32210010, 'Muhammad Zaini Maulidan', 'Jl. Sa\'adah 561', 'L', 4, 11, 1, NULL),
(560, 'R22010174i', 22010174, 'Rizqullah Rafif Supandri', 'Jl. Sa\'adah 562', 'L', 4, 11, 1, NULL),
(561, 'N21910061a', 21910061, 'Nawfa', 'Jl. Sa\'adah 563', 'L', 4, 11, 1, NULL),
(562, 'A32110020l', 32110020, 'Ahmad Haikal', 'Jl. Sa\'adah 564', 'L', 4, 11, 1, NULL),
(563, 'A21910001q', 21910001, 'Abdul Rofiq', 'Jl. Sa\'adah 565', 'L', 4, 11, 1, NULL),
(564, 'A21910004n', 21910004, 'Adam Isrofun', 'Jl. Sa\'adah 566', 'L', 4, 11, 1, NULL),
(565, 'A21910006f', 21910006, 'Ahmad Ridwan Afif', 'Jl. Sa\'adah 567', 'L', 4, 11, 1, NULL),
(566, 'A21910007i', 21910007, 'Akmal Ibrahim Asy’Ari', 'Jl. Sa\'adah 568', 'L', 4, 11, 1, NULL),
(567, 'A21910008h', 21910008, 'Akmal Syafna Fatahillah', 'Jl. Sa\'adah 569', 'L', 4, 11, 1, NULL),
(568, 'A32110001n', 32110001, 'Ali Zein Muttaqien', 'Jl. Sa\'adah 570', 'L', 4, 11, 1, NULL),
(569, 'A21910010r', 21910010, 'Aly Azhar', 'Jl. Sa\'adah 571', 'L', 4, 11, 1, NULL),
(570, 'A32110002h', 32110002, 'Athief Abdullah', 'Jl. Sa\'adah 572', 'L', 4, 11, 1, NULL),
(571, 'A21910012n', 21910012, 'Azril Muhammad Dzira Febrian', 'Jl. Sa\'adah 573', 'L', 4, 11, 1, NULL),
(572, 'B21910013y', 21910013, 'Bagas Dwi Rizqy', 'Jl. Sa\'adah 574', 'L', 4, 11, 1, NULL),
(573, 'F32110004l', 32110004, 'Faisal', 'Jl. Sa\'adah 575', 'L', 4, 11, 1, NULL),
(574, 'F21910015a', 21910015, 'Farhan Guntur Saputra', 'Jl. Sa\'adah 576', 'L', 4, 11, 1, NULL),
(575, 'H21910019f', 21910019, 'Hamdan Fathurrahman Hanif', 'Jl. Sa\'adah 577', 'L', 4, 11, 1, NULL),
(576, 'H32110006s', 32110006, 'Haris Firdaus', 'Jl. Sa\'adah 578', 'L', 4, 11, 1, NULL),
(577, 'H32110025i', 32110025, 'Hasya Mecca Irfani', 'Jl. Sa\'adah 579', 'L', 4, 11, 1, NULL),
(578, 'M32110007a', 32110007, 'M. Akbar Saputra', 'Jl. Sa\'adah 580', 'L', 4, 11, 1, NULL),
(579, 'M21910049n', 21910049, 'M. Rayyan Zeeshan', 'Jl. Sa\'adah 581', 'L', 4, 11, 1, NULL),
(580, 'M21910051a', 21910051, 'M. Rifky Rava Vadilla', 'Jl. Sa\'adah 582', 'L', 4, 11, 1, NULL),
(581, 'M21910026q', 21910026, 'Maftuh Fauzan Syafiq', 'Jl. Sa\'adah 583', 'L', 4, 11, 1, NULL),
(582, 'M21910030d', 21910030, 'Muhammad Aksan Dinejad', 'Jl. Sa\'adah 584', 'L', 4, 11, 1, NULL),
(583, 'M32110021n', 32110021, 'Muhammad Alfi Syahrin', 'Jl. Sa\'adah 585', 'L', 4, 11, 1, NULL),
(584, 'M32110009n', 32110009, 'Muhammad Daffa Abdurrahman', 'Jl. Sa\'adah 586', 'L', 4, 11, 1, NULL),
(585, 'M21910032n', 21910032, 'Muhammad Farhan', 'Jl. Sa\'adah 587', 'L', 4, 11, 1, NULL),
(586, 'M21910035d', 21910035, 'Muhammad Fathir Rizky Abdad', 'Jl. Sa\'adah 588', 'L', 4, 11, 1, NULL),
(587, 'M21910036b', 21910036, 'Muhammad Fatih Naufal Labib', 'Jl. Sa\'adah 589', 'L', 4, 11, 1, NULL),
(588, 'M32110010z', 32110010, 'Muhammad Fayyaz Aldiaz', 'Jl. Sa\'adah 590', 'L', 4, 11, 1, NULL),
(589, 'M21910039i', 21910039, 'Muhammad Ihsan Nor Ilmi', 'Jl. Sa\'adah 591', 'L', 4, 11, 1, NULL),
(590, 'M21910042i', 21910042, 'Muhammad Nabil Yusuf Al Ghazali', 'Jl. Sa\'adah 592', 'L', 4, 11, 1, NULL),
(591, 'M32010046h', 32010046, 'Muhammad Naufal Rizqullah', 'Jl. Sa\'adah 593', 'L', 4, 11, 1, NULL),
(592, 'M32110011n', 32110011, 'Muhammad Nur Ikhsan', 'Jl. Sa\'adah 594', 'L', 4, 11, 1, NULL),
(593, 'M21910043a', 21910043, 'Muhammad Qowlan Sadida', 'Jl. Sa\'adah 595', 'L', 4, 11, 1, NULL),
(594, 'M21910046i', 21910046, 'Muhammad Radjha Putra Jayadi', 'Jl. Sa\'adah 596', 'L', 4, 11, 1, NULL),
(595, 'M21910045a', 21910045, 'Muhammad Rafa', 'Jl. Sa\'adah 597', 'L', 4, 11, 1, NULL),
(596, 'M21910048i', 21910048, 'Muhammad Raffy Islami', 'Jl. Sa\'adah 598', 'L', 4, 11, 1, NULL),
(597, 'M21910047i', 21910047, 'Muhammad Rafi', 'Jl. Sa\'adah 599', 'L', 4, 11, 1, NULL),
(598, 'M32110023h', 32110023, 'Muhammad Rizky Hamzah', 'Jl. Sa\'adah 600', 'L', 4, 11, 1, NULL),
(599, 'M21910053r', 21910053, 'Muhammad Sajid Al Munawar', 'Jl. Sa\'adah 601', 'L', 4, 11, 1, NULL),
(600, 'M32110013n', 32110013, 'Muhammad Sufyan Amin', 'Jl. Sa\'adah 602', 'L', 4, 11, 1, NULL),
(601, 'M21910057i', 21910057, 'Muhammad Wira Ghazali', 'Jl. Sa\'adah 603', 'L', 4, 11, 1, NULL),
(602, 'M21910058k', 21910058, 'Muhammad Zaki Mubarak', 'Jl. Sa\'adah 604', 'L', 4, 11, 1, NULL),
(603, 'N32110027e', 32110027, 'Nashih Ulwan Ar Rofiqie', 'Jl. Sa\'adah 605', 'L', 4, 11, 1, NULL),
(604, 'P32110017o', 32110017, 'Prayogo Dewantoro', 'Jl. Sa\'adah 606', 'L', 4, 11, 1, NULL),
(605, 'R32110018d', 32110018, 'Rafi Pratama Havid', 'Jl. Sa\'adah 607', 'L', 4, 11, 1, NULL),
(606, 'R21910064a', 21910064, 'Rido Saputra', 'Jl. Sa\'adah 608', 'L', 4, 11, 1, NULL),
(607, 'R21910065h', 21910065, 'Rizqi Attho Illah', 'Jl. Sa\'adah 609', 'L', 4, 11, 1, NULL),
(608, 'R32110019o', 32110019, 'Rovis Wahyu Sulistio', 'Jl. Sa\'adah 610', 'L', 4, 11, 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `status`
--

CREATE TABLE `status` (
  `id` int(11) NOT NULL,
  `status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `status`
--

INSERT INTO `status` (`id`, `status`) VALUES
(1, 'Aktif'),
(2, 'Keluar'),
(4, 'Cuti');

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `id` int(11) NOT NULL,
  `no_mitra` varchar(255) NOT NULL,
  `nama_supplier` varchar(255) NOT NULL,
  `no_telp` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tagihan_siswa`
--

CREATE TABLE `tagihan_siswa` (
  `id` int(20) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `tarif_pembayaran_id` int(11) NOT NULL,
  `tanggal_tagihan` date NOT NULL DEFAULT current_timestamp(),
  `jumlah_tagihan` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tahun_ajaran`
--

CREATE TABLE `tahun_ajaran` (
  `id` int(11) NOT NULL,
  `tahun_ajaran` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tahun_ajaran`
--

INSERT INTO `tahun_ajaran` (`id`, `tahun_ajaran`) VALUES
(1, '2023/2024'),
(2, '2024/2025'),
(3, '2025/2026');

-- --------------------------------------------------------

--
-- Table structure for table `tarif_pembayaran`
--

CREATE TABLE `tarif_pembayaran` (
  `id` int(11) NOT NULL,
  `jenjang_id` int(11) NOT NULL,
  `jenis_pembayaran_id` int(11) DEFAULT NULL,
  `tahun_ajaran_id` int(11) DEFAULT NULL,
  `tipe` enum('1','2','3') DEFAULT NULL,
  `nominal` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `id` int(11) NOT NULL,
  `tanggal` datetime NOT NULL,
  `uang_saku_id` int(11) NOT NULL,
  `barang_id` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `harga` float NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transaksi_keuangan`
--

CREATE TABLE `transaksi_keuangan` (
  `id` int(11) NOT NULL,
  `tagihan_siswa_id` int(20) NOT NULL,
  `jumlah` float NOT NULL,
  `tanggal_transaksi` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `uang_saku`
--

CREATE TABLE `uang_saku` (
  `id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `saldo` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `uang_saku`
--

INSERT INTO `uang_saku` (`id`, `siswa_id`, `saldo`) VALUES
(1, 1, 8490000),
(2, 2, 2489500),
(3, 3, 1000000),
(4, 4, 234500),
(5, 5, 1500000);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `jenis_kelamin` enum('L','P') NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nama`, `jenis_kelamin`, `username`, `password`, `role_id`) VALUES
(1, 'Kepala IT', 'L', 'Administrator', '$2y$10$5LbQ.ZPjzeNbb6ctXJ1NNORvh2leuT7gs3GSB1dVJIAdSXAQ.m9fW', 1),
(2, 'SALMIN', 'L', 'salmin', '$2y$10$tWVxByo9JsQTf8KvF4mOYOkciLuGZaiCkn7zswsHCa/WYGZa3YviK', 5),
(3, 'QUSAY ACHMAD FAUZI', 'L', 'qusay', '$2y$10$E1d3uxfmNZ9J2FtNa4KlNOpx4W.4gxImrCFMa7DtPHg12xtgntaPC', 3),
(4, 'ANISA', 'P', 'anisa', '$2y$10$nSVR26IzyVg86mxmjd2bMO96Ur1Tfz7b4bh6Mcc6WqJaM4ZRHWjc2', 6);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `barang`
--
ALTER TABLE `barang`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `barang_masuk`
--
ALTER TABLE `barang_masuk`
  ADD PRIMARY KEY (`id`),
  ADD KEY `barang_masuk_ibfk_2` (`supplier_id`),
  ADD KEY `barang_masuk_ibfk_1` (`barang_id`);

--
-- Indexes for table `jenis_pembayaran`
--
ALTER TABLE `jenis_pembayaran`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jenjang`
--
ALTER TABLE `jenjang`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kelas`
--
ALTER TABLE `kelas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `siswa`
--
ALTER TABLE `siswa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jenjang_id` (`jenjang_id`),
  ADD KEY `kelas_id` (`kelas_id`),
  ADD KEY `status_id` (`status_id`);

--
-- Indexes for table `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tagihan_siswa`
--
ALTER TABLE `tagihan_siswa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `siswa_id` (`siswa_id`),
  ADD KEY `tarif_pembayaran_id` (`tarif_pembayaran_id`);

--
-- Indexes for table `tahun_ajaran`
--
ALTER TABLE `tahun_ajaran`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tarif_pembayaran`
--
ALTER TABLE `tarif_pembayaran`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tipe_pembayaran_id` (`jenis_pembayaran_id`),
  ADD KEY `tahun_ajaran_id` (`tahun_ajaran_id`),
  ADD KEY `jenjang_id` (`jenjang_id`);

--
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `barang_id` (`barang_id`),
  ADD KEY `uang_saku_id` (`uang_saku_id`);

--
-- Indexes for table `transaksi_keuangan`
--
ALTER TABLE `transaksi_keuangan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tagihan_siswa_id` (`tagihan_siswa_id`);

--
-- Indexes for table `uang_saku`
--
ALTER TABLE `uang_saku`
  ADD PRIMARY KEY (`id`),
  ADD KEY `siswa_id` (`siswa_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `role_id` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `barang`
--
ALTER TABLE `barang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `barang_masuk`
--
ALTER TABLE `barang_masuk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jenis_pembayaran`
--
ALTER TABLE `jenis_pembayaran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `jenjang`
--
ALTER TABLE `jenjang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `kelas`
--
ALTER TABLE `kelas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `siswa`
--
ALTER TABLE `siswa`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=609;

--
-- AUTO_INCREMENT for table `status`
--
ALTER TABLE `status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tagihan_siswa`
--
ALTER TABLE `tagihan_siswa`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tahun_ajaran`
--
ALTER TABLE `tahun_ajaran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tarif_pembayaran`
--
ALTER TABLE `tarif_pembayaran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transaksi_keuangan`
--
ALTER TABLE `transaksi_keuangan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `uang_saku`
--
ALTER TABLE `uang_saku`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `barang_masuk`
--
ALTER TABLE `barang_masuk`
  ADD CONSTRAINT `barang_masuk_ibfk_1` FOREIGN KEY (`barang_id`) REFERENCES `barang` (`id`),
  ADD CONSTRAINT `barang_masuk_ibfk_2` FOREIGN KEY (`supplier_id`) REFERENCES `supplier` (`id`);

--
-- Constraints for table `siswa`
--
ALTER TABLE `siswa`
  ADD CONSTRAINT `siswa_ibfk_1` FOREIGN KEY (`jenjang_id`) REFERENCES `jenjang` (`id`),
  ADD CONSTRAINT `siswa_ibfk_2` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`),
  ADD CONSTRAINT `siswa_ibfk_3` FOREIGN KEY (`status_id`) REFERENCES `status` (`id`);

--
-- Constraints for table `tagihan_siswa`
--
ALTER TABLE `tagihan_siswa`
  ADD CONSTRAINT `tagihan_siswa_ibfk_1` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id`),
  ADD CONSTRAINT `tagihan_siswa_ibfk_2` FOREIGN KEY (`tarif_pembayaran_id`) REFERENCES `tarif_pembayaran` (`id`);

--
-- Constraints for table `tarif_pembayaran`
--
ALTER TABLE `tarif_pembayaran`
  ADD CONSTRAINT `tarif_pembayaran_ibfk_1` FOREIGN KEY (`jenis_pembayaran_id`) REFERENCES `jenis_pembayaran` (`id`),
  ADD CONSTRAINT `tarif_pembayaran_ibfk_2` FOREIGN KEY (`tahun_ajaran_id`) REFERENCES `tahun_ajaran` (`id`),
  ADD CONSTRAINT `tarif_pembayaran_ibfk_3` FOREIGN KEY (`jenjang_id`) REFERENCES `jenjang` (`id`);

--
-- Constraints for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `transaksi_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `transaksi_ibfk_2` FOREIGN KEY (`barang_id`) REFERENCES `barang` (`id`),
  ADD CONSTRAINT `transaksi_ibfk_3` FOREIGN KEY (`uang_saku_id`) REFERENCES `uang_saku` (`id`);

--
-- Constraints for table `transaksi_keuangan`
--
ALTER TABLE `transaksi_keuangan`
  ADD CONSTRAINT `transaksi_keuangan_ibfk_1` FOREIGN KEY (`tagihan_siswa_id`) REFERENCES `tagihan_siswa` (`id`);

--
-- Constraints for table `uang_saku`
--
ALTER TABLE `uang_saku`
  ADD CONSTRAINT `uang_saku_ibfk_1` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
