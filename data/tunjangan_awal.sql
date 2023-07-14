-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 03, 2020 at 05:18 PM
-- Server version: 5.7.29-0ubuntu0.18.04.1
-- PHP Version: 7.2.24-0ubuntu0.18.04.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tunjangan`
--

-- --------------------------------------------------------

--
-- Table structure for table `jabatan_golongan`
--

CREATE TABLE `jabatan_golongan` (
  `id` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `jabatan_golongan`
--

INSERT INTO `jabatan_golongan` (`id`, `nama`) VALUES
(1, 'Golongan IV'),
(2, 'Golongan III'),
(3, 'Golongan II'),
(4, 'Golongan I');

-- --------------------------------------------------------

--
-- Table structure for table `jabatan_tunjangan_fungsional`
--

CREATE TABLE `jabatan_tunjangan_fungsional` (
  `id` int(11) NOT NULL,
  `id_instansi` int(11) NOT NULL,
  `id_tingkatan_fungsional` int(11) NOT NULL,
  `besaran_tpp` double(20,0) NOT NULL,
  `tanggal_mulai` date DEFAULT NULL,
  `tanggal_selesai` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `jabatan_tunjangan_fungsional`
--

INSERT INTO `jabatan_tunjangan_fungsional` (`id`, `id_instansi`, `id_tingkatan_fungsional`, `besaran_tpp`, `tanggal_mulai`, `tanggal_selesai`) VALUES
(1, 1, 1, 10000, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `jabatan_tunjangan_pelaksana`
--

CREATE TABLE `jabatan_tunjangan_pelaksana` (
  `id` int(11) NOT NULL,
  `id_instansi` int(11) NOT NULL,
  `id_jabatan_golongan` int(11) NOT NULL,
  `besaran_tpp` double(20,0) NOT NULL,
  `tanggal_mulai` date DEFAULT NULL,
  `tanggal_selesai` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `jabatan_tunjangan_pelaksana`
--

INSERT INTO `jabatan_tunjangan_pelaksana` (`id`, `id_instansi`, `id_jabatan_golongan`, `besaran_tpp`, `tanggal_mulai`, `tanggal_selesai`) VALUES
(1, 4, 1, 1000000, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `jabatan_tunjangan_struktural`
--

CREATE TABLE `jabatan_tunjangan_struktural` (
  `id` int(11) NOT NULL,
  `id_instansi` int(11) NOT NULL,
  `id_eselon` int(11) NOT NULL,
  `besaran_tpp` double(20,0) DEFAULT NULL,
  `tanggal_mulai` date DEFAULT NULL,
  `tanggal_selesai` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `jabatan_tunjangan_struktural`
--

INSERT INTO `jabatan_tunjangan_struktural` (`id`, `id_instansi`, `id_eselon`, `besaran_tpp`, `tanggal_mulai`, `tanggal_selesai`) VALUES
(1, 2, 2, 1000000, NULL, NULL),
(4, 2, 7, 100000, NULL, NULL),
(5, 1, 1, 10000, NULL, NULL),
(6, 4, 1, 10100000, NULL, NULL),
(7, 4, 1, 10000000, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tingkatan_fungsional`
--

CREATE TABLE `tingkatan_fungsional` (
  `id` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tingkatan_fungsional`
--

INSERT INTO `tingkatan_fungsional` (`id`, `nama`) VALUES
(1, 'Ahli Utama'),
(2, 'Ahli Madya'),
(3, 'Ahli Muda'),
(4, 'Ahli Pertama'),
(5, 'Terampil Penyelia'),
(6, 'Terampil Pelaksana Lanjutan'),
(7, 'Terampil Pelaksana'),
(8, 'Terampil Pemula');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `jabatan_golongan`
--
ALTER TABLE `jabatan_golongan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jabatan_tunjangan_fungsional`
--
ALTER TABLE `jabatan_tunjangan_fungsional`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jabatan_tunjangan_pelaksana`
--
ALTER TABLE `jabatan_tunjangan_pelaksana`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jabatan_tunjangan_struktural`
--
ALTER TABLE `jabatan_tunjangan_struktural`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tingkatan_fungsional`
--
ALTER TABLE `tingkatan_fungsional`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `jabatan_golongan`
--
ALTER TABLE `jabatan_golongan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `jabatan_tunjangan_fungsional`
--
ALTER TABLE `jabatan_tunjangan_fungsional`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `jabatan_tunjangan_pelaksana`
--
ALTER TABLE `jabatan_tunjangan_pelaksana`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `jabatan_tunjangan_struktural`
--
ALTER TABLE `jabatan_tunjangan_struktural`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `tingkatan_fungsional`
--
ALTER TABLE `tingkatan_fungsional`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
