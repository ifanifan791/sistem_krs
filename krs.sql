-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Waktu pembuatan: 03 Jan 2025 pada 11.08
-- Versi server: 9.1.0
-- Versi PHP: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `krs`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `inputmhs`
--

DROP TABLE IF EXISTS `inputmhs`;
CREATE TABLE IF NOT EXISTS `inputmhs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `namaMhs` varchar(255) NOT NULL,
  `nim` varchar(15) NOT NULL,
  `ipk` float NOT NULL,
  `sks` int NOT NULL,
  `matakuliah` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nim` (`nim`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `inputmhs`
--

INSERT INTO `inputmhs` (`id`, `namaMhs`, `nim`, `ipk`, `sks`, `matakuliah`) VALUES
(1, 'John Doe', '123456', 3.5, 24, 'Matematika, Fisika'),
(2, 'Jane Smith', '654321', 2.8, 20, 'Kimia');

-- --------------------------------------------------------

--
-- Struktur dari tabel `jwl_matakuliah`
--

DROP TABLE IF EXISTS `jwl_matakuliah`;
CREATE TABLE IF NOT EXISTS `jwl_matakuliah` (
  `id` int NOT NULL AUTO_INCREMENT,
  `matakuliah` varchar(250) NOT NULL,
  `sks` int NOT NULL,
  `kelp` varchar(10) DEFAULT NULL,
  `ruangan` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `jwl_matakuliah`
--

INSERT INTO `jwl_matakuliah` (`id`, `matakuliah`, `sks`, `kelp`, `ruangan`) VALUES
(1, 'Matematika', 3, 'A', 'R101'),
(2, 'Fisika', 3, 'B', 'R102'),
(3, 'Kimia', 3, 'C', 'R103');

-- --------------------------------------------------------

--
-- Struktur dari tabel `jwl_mhs`
--

DROP TABLE IF EXISTS `jwl_mhs`;
CREATE TABLE IF NOT EXISTS `jwl_mhs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `mhs_id` int NOT NULL,
  `matakuliah` varchar(255) NOT NULL,
  `sks` int NOT NULL,
  `kelp` varchar(50) DEFAULT NULL,
  `ruangan` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `mhs_id` (`mhs_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `jwl_mhs`
--

INSERT INTO `jwl_mhs` (`id`, `mhs_id`, `matakuliah`, `sks`, `kelp`, `ruangan`) VALUES
(1, 1, 'Matematika', 3, 'A', 'R101'),
(2, 1, 'Fisika', 3, 'B', 'R102'),
(3, 2, 'Kimia', 3, 'C', 'R103');

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `jwl_mhs`
--
ALTER TABLE `jwl_mhs`
  ADD CONSTRAINT `jwl_mhs_ibfk_1` FOREIGN KEY (`mhs_id`) REFERENCES `inputmhs` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
