-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 18, 2025 at 05:34 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tourism_guide`
--
CREATE DATABASE IF NOT EXISTS `tourism_guide` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `tourism_guide`;

-- --------------------------------------------------------

--
-- Table structure for table `Fasilitas`
--

DROP TABLE IF EXISTS `Fasilitas`;
CREATE TABLE `Fasilitas` (
  `id_fasilitas` int(11) NOT NULL,
  `nama_fasilitas` varchar(100) NOT NULL,
  `deskripsi_fasilitas` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Kategori`
--

DROP TABLE IF EXISTS `Kategori`;
CREATE TABLE `Kategori` (
  `id_kategori` int(11) NOT NULL,
  `nama_kategori` varchar(100) NOT NULL,
  `deskripsi_kategori` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Kategori`
--

INSERT INTO `Kategori` (`id_kategori`, `nama_kategori`, `deskripsi_kategori`) VALUES
(1, 'Destinasi', NULL),
(2, 'Kuliner', NULL),
(3, 'Penginapan', NULL),
(4, 'Budaya', NULL),
(5, 'Oleh-Oleh', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `Lokasi`
--

DROP TABLE IF EXISTS `Lokasi`;
CREATE TABLE `Lokasi` (
  `id_lokasi` int(11) NOT NULL,
  `nama_daerah` varchar(100) NOT NULL,
  `alamat` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Lokasi`
--

INSERT INTO `Lokasi` (`id_lokasi`, `nama_daerah`, `alamat`) VALUES
(1, 'Kota Yogyakarta', NULL),
(2, 'Sleman', NULL),
(3, 'Bantul', NULL),
(4, 'Gunungkidul', NULL),
(5, 'Kulon Progo', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `Pengguna`
--

DROP TABLE IF EXISTS `Pengguna`;
CREATE TABLE `Pengguna` (
  `id_pengguna` int(11) NOT NULL,
  `nama_pengguna` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `tanggal_daftar` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Pengguna`
--

INSERT INTO `Pengguna` (`id_pengguna`, `nama_pengguna`, `email`, `password`, `tanggal_daftar`) VALUES
(3, 'hilmy', 'hilmy@mail', '$2y$10$jO1Qp5anwCJ9Cve4ZlokReKCxiN3a7gjkb1MgRVLTX2IZq.tss7NW', '2025-12-10 05:42:38');

-- --------------------------------------------------------

--
-- Table structure for table `Review`
--

DROP TABLE IF EXISTS `Review`;
CREATE TABLE `Review` (
  `id_review` int(11) NOT NULL,
  `id_wisata` int(11) NOT NULL,
  `id_pengguna` int(11) NOT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` between 1 and 5),
  `komentar` text DEFAULT NULL,
  `tanggal_review` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Wisata`
--

DROP TABLE IF EXISTS `Wisata`;
CREATE TABLE `Wisata` (
  `id_wisata` int(11) NOT NULL,
  `nama_wisata` varchar(150) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `harga_tiket` decimal(10,2) DEFAULT NULL,
  `jam_operasional` varchar(100) DEFAULT NULL,
  `id_lokasi` int(11) DEFAULT NULL,
  `id_kategori` int(11) DEFAULT NULL,
  `gambar` varchar(255) DEFAULT 'default.jpg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Wisata`
--

INSERT INTO `Wisata` (`id_wisata`, `nama_wisata`, `deskripsi`, `harga_tiket`, `jam_operasional`, `id_lokasi`, `id_kategori`, `gambar`) VALUES
(1, 'Candi Prambanan', 'Kompleks candi Hindu terbesar di Indonesia, terkenal dengan relief Ramayana dan arsitektur megah.', 50000.00, '08:00 - 16:00', 2, 1, 'prambanan.jpg'),
(2, 'Candi Borobudur', 'Warisan dunia UNESCO dengan relief Buddha terbesar di dunia. Ramai saat sunrise.', 50000.00, '06:00 - 17:00', 5, 1, 'borobudur.jpg'),
(3, 'Malioboro', 'Ikon wisata belanja Yogyakarta, dipenuhi toko, pedagang kaki lima, dan seni jalanan.', 0.00, '24 Jam', 1, 1, 'malioboro.jpg'),
(4, 'HeHa Sky View', 'Destinasi foto populer dengan pemandangan citylight Yogyakarta.', 20000.00, '10:00 - 21:00', 4, 1, 'heha_sky.jpg'),
(5, 'Pantai Indrayanti', 'Pantai pasir putih yang bersih, cocok untuk santai dan kuliner.', 15000.00, '07:00 - 17:00', 4, 1, 'indrayanti.jpg'),
(6, 'Gudeg Yu Djum', 'Gudeg asli Yogyakarta dengan cita rasa manis dan legit.', 0.00, '07:00 - 21:00', 1, 2, 'gudeg_yudjum.jpg'),
(7, 'Sate Klathak Pak Pong', 'Sate kambing khas Pleret, ditusuk menggunakan jeruji besi.', 0.00, '10:00 - 23:00', 3, 2, 'sate_klathak.jpg'),
(8, 'Bakpia Kurnia Sari', 'Bakpia premium lembut dengan berbagai rasa.', 0.00, '08:00 - 21:00', 1, 2, 'bakpia.jpg'),
(9, 'Mangut Lele Mbah Marto', 'Kuliner tradisional lele asap dengan kuah pedas khas Bantul.', 0.00, '10:00 - 16:00', 3, 2, 'mangut_marto.jpg'),
(10, 'The Alana Hotel', 'Hotel bintang 4 dengan fasilitas lengkap dan swimming pool.', 600000.00, '24 Jam', 2, 3, 'alana.jpg'),
(11, 'Hotel Tentrem', 'Hotel mewah bintang 5 dengan nuansa Jawa modern.', 1500000.00, '24 Jam', 1, 3, 'tentrem.jpg'),
(12, 'Omah Kayu Gunungkidul', 'Penginapan kayu bernuansa alam yang nyaman dan sejuk.', 450000.00, '24 Jam', 4, 3, 'omah_kayu.jpg'),
(13, 'Keraton Yogyakarta', 'Istana resmi Sultan HB X, pusat kebudayaan Jawa.', 15000.00, '08:00 - 14:00', 1, 4, 'keraton.jpg'),
(14, 'Taman Sari', 'Kompleks pemandian kerajaan dengan arsitektur indah.', 15000.00, '09:00 - 15:00', 1, 4, 'tamansari.jpg'),
(15, 'Museum Ullen Sentalu', 'Museum budaya Jawa & Mataram, rekomendasi wisata edukatif.', 50000.00, '08:30 - 16:00', 2, 4, 'ullensentalu.jpg'),
(16, 'Bakpia Tugu Jogja', 'Oleh-oleh hits dengan rasa premium.', 0.00, '08:00 - 21:00', 1, 5, 'bakpia_tugu.jpg'),
(17, 'Dagadu Djokdja', 'Brand kaos khas Jogja yang terkenal sejak 1990-an.', 0.00, '09:00 - 21:00', 1, 5, 'dagadu.jpg'),
(18, 'Jogja Scrummy', 'Kue kekinian yang cocok untuk oleh-oleh wisatawan.', 0.00, '08:00 - 20:00', 1, 5, 'scrummy.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `Wisata_Fasilitas`
--

DROP TABLE IF EXISTS `Wisata_Fasilitas`;
CREATE TABLE `Wisata_Fasilitas` (
  `id_wisata` int(11) NOT NULL,
  `id_fasilitas` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Fasilitas`
--
ALTER TABLE `Fasilitas`
  ADD PRIMARY KEY (`id_fasilitas`);

--
-- Indexes for table `Kategori`
--
ALTER TABLE `Kategori`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indexes for table `Lokasi`
--
ALTER TABLE `Lokasi`
  ADD PRIMARY KEY (`id_lokasi`);

--
-- Indexes for table `Pengguna`
--
ALTER TABLE `Pengguna`
  ADD PRIMARY KEY (`id_pengguna`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `Review`
--
ALTER TABLE `Review`
  ADD PRIMARY KEY (`id_review`),
  ADD KEY `id_wisata` (`id_wisata`),
  ADD KEY `id_pengguna` (`id_pengguna`);

--
-- Indexes for table `Wisata`
--
ALTER TABLE `Wisata`
  ADD PRIMARY KEY (`id_wisata`),
  ADD KEY `id_lokasi` (`id_lokasi`),
  ADD KEY `id_kategori` (`id_kategori`);

--
-- Indexes for table `Wisata_Fasilitas`
--
ALTER TABLE `Wisata_Fasilitas`
  ADD PRIMARY KEY (`id_wisata`,`id_fasilitas`),
  ADD KEY `id_fasilitas` (`id_fasilitas`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Fasilitas`
--
ALTER TABLE `Fasilitas`
  MODIFY `id_fasilitas` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Kategori`
--
ALTER TABLE `Kategori`
  MODIFY `id_kategori` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Lokasi`
--
ALTER TABLE `Lokasi`
  MODIFY `id_lokasi` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Pengguna`
--
ALTER TABLE `Pengguna`
  MODIFY `id_pengguna` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Review`
--
ALTER TABLE `Review`
  MODIFY `id_review` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Wisata`
--
ALTER TABLE `Wisata`
  MODIFY `id_wisata` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Review`
--
ALTER TABLE `Review`
  ADD CONSTRAINT `review_ibfk_1` FOREIGN KEY (`id_wisata`) REFERENCES `Wisata` (`id_wisata`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `review_ibfk_2` FOREIGN KEY (`id_pengguna`) REFERENCES `Pengguna` (`id_pengguna`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Wisata`
--
ALTER TABLE `Wisata`
  ADD CONSTRAINT `wisata_ibfk_1` FOREIGN KEY (`id_lokasi`) REFERENCES `Lokasi` (`id_lokasi`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `wisata_ibfk_2` FOREIGN KEY (`id_kategori`) REFERENCES `Kategori` (`id_kategori`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `Wisata_Fasilitas`
--
ALTER TABLE `Wisata_Fasilitas`
  ADD CONSTRAINT `wisata_fasilitas_ibfk_1` FOREIGN KEY (`id_wisata`) REFERENCES `Wisata` (`id_wisata`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `wisata_fasilitas_ibfk_2` FOREIGN KEY (`id_fasilitas`) REFERENCES `Fasilitas` (`id_fasilitas`) ON DELETE CASCADE ON UPDATE CASCADE;
SET FOREIGN_KEY_CHECKS=1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
