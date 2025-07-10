-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 08, 2025 at 05:18 PM
-- Server version: 8.0.30
-- PHP Version: 7.4.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_barang_fifo`
--

-- --------------------------------------------------------

--
-- Table structure for table `barang`
--

CREATE TABLE `barang` (
  `id_barang` int NOT NULL,
  `kode_barang` varchar(50) COLLATE utf8mb3_swedish_ci NOT NULL,
  `nama_barang` varchar(200) COLLATE utf8mb3_swedish_ci NOT NULL,
  `kategori` varchar(100) COLLATE utf8mb3_swedish_ci DEFAULT NULL,
  `satuan` varchar(20) COLLATE utf8mb3_swedish_ci DEFAULT 'pcs',
  `berat` decimal(10,2) DEFAULT NULL,
  `dimensi` varchar(100) COLLATE utf8mb3_swedish_ci DEFAULT NULL,
  `barcode` varchar(100) COLLATE utf8mb3_swedish_ci DEFAULT NULL,
  `rfid_tag` varchar(100) COLLATE utf8mb3_swedish_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_swedish_ci;

--
-- Dumping data for table `barang`
--

INSERT INTO `barang` (`id_barang`, `kode_barang`, `nama_barang`, `kategori`, `satuan`, `berat`, `dimensi`, `barcode`, `rfid_tag`, `created_at`, `updated_at`) VALUES
(1, 'BRG001', 'Laptop Dell Inspiron', 'Elektronik', 'unit', NULL, NULL, '1234567890123', NULL, '2025-07-01 18:33:00', '2025-07-01 18:33:00'),
(2, 'BRG002', 'Mouse Wireless', 'Aksesoris', 'pcs', NULL, NULL, '1234567890124', NULL, '2025-07-01 18:33:00', '2025-07-01 18:33:00'),
(3, 'BRG003', 'Kertas A4', 'ATK', 'rim', NULL, NULL, '1234567890125', NULL, '2025-07-01 18:33:00', '2025-07-01 18:33:00'),
(4, 'BRG004', 'Tes', 'Elektronik', 'pcs', '20.00', '30x30x30', NULL, NULL, '2025-07-08 15:40:19', '2025-07-08 15:40:19');

-- --------------------------------------------------------

--
-- Table structure for table `barang_keluar`
--

CREATE TABLE `barang_keluar` (
  `id_keluar` int NOT NULL,
  `no_dokumen` varchar(50) COLLATE utf8mb3_swedish_ci NOT NULL,
  `id_barang` int NOT NULL,
  `id_lokasi` int NOT NULL,
  `jumlah` int NOT NULL,
  `tanggal_keluar` datetime NOT NULL,
  `tujuan` varchar(200) COLLATE utf8mb3_swedish_ci DEFAULT NULL,
  `petugas` varchar(100) COLLATE utf8mb3_swedish_ci DEFAULT NULL,
  `keterangan` text COLLATE utf8mb3_swedish_ci,
  `status_keluar` enum('pending','terkirim','batal') COLLATE utf8mb3_swedish_ci DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_swedish_ci;

--
-- Dumping data for table `barang_keluar`
--

INSERT INTO `barang_keluar` (`id_keluar`, `no_dokumen`, `id_barang`, `id_lokasi`, `jumlah`, `tanggal_keluar`, `tujuan`, `petugas`, `keterangan`, `status_keluar`, `created_at`) VALUES
(1, 'OUT20250708001', 3, 1, 2, '2025-07-08 17:10:00', 'Gudang', 'Saipul', 'asa', 'terkirim', '2025-07-08 17:10:49');

-- --------------------------------------------------------

--
-- Table structure for table `barang_masuk`
--

CREATE TABLE `barang_masuk` (
  `id_masuk` int NOT NULL,
  `no_dokumen` varchar(50) COLLATE utf8mb3_swedish_ci NOT NULL,
  `id_barang` int NOT NULL,
  `id_lokasi` int NOT NULL,
  `jumlah` int NOT NULL,
  `tanggal_masuk` datetime NOT NULL,
  `tanggal_produksi` date DEFAULT NULL,
  `tanggal_expired` date DEFAULT NULL,
  `supplier` varchar(200) COLLATE utf8mb3_swedish_ci DEFAULT NULL,
  `no_batch` varchar(50) COLLATE utf8mb3_swedish_ci DEFAULT NULL,
  `petugas` varchar(100) COLLATE utf8mb3_swedish_ci DEFAULT NULL,
  `keterangan` text COLLATE utf8mb3_swedish_ci,
  `status_masuk` enum('pending','tersimpan','batal') COLLATE utf8mb3_swedish_ci DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_swedish_ci;

--
-- Dumping data for table `barang_masuk`
--

INSERT INTO `barang_masuk` (`id_masuk`, `no_dokumen`, `id_barang`, `id_lokasi`, `jumlah`, `tanggal_masuk`, `tanggal_produksi`, `tanggal_expired`, `supplier`, `no_batch`, `petugas`, `keterangan`, `status_masuk`, `created_at`) VALUES
(1, 'IN20250702722', 3, 1, 1, '2025-07-01 21:52:00', '2025-07-03', '2027-07-31', 'Budiman', '1', 'Saipul', '2', 'tersimpan', '2025-07-01 21:52:55'),
(2, 'IN20250708432', 3, 1, 1, '2025-07-08 15:40:00', '2025-07-08', '2027-07-30', 'Budiman', '1', 'Saipul', 'asa', 'tersimpan', '2025-07-08 15:41:15'),
(3, 'IN20250709617', 3, 1, 2, '2025-07-08 17:01:00', '2025-07-09', '2027-07-09', 'Budiman', '1', 'Saipul', 'asa', 'tersimpan', '2025-07-08 17:02:16'),
(4, 'IN20250709611', 3, 1, 2, '2025-07-08 17:02:00', '2025-07-09', '2027-07-30', 'Budiman', '1', 'Saipul', 'aca', 'tersimpan', '2025-07-08 17:02:55');

-- --------------------------------------------------------

--
-- Table structure for table `dashboard_summary`
--

CREATE TABLE `dashboard_summary` (
  `id_summary` int NOT NULL,
  `total_barang` int DEFAULT '0',
  `total_lokasi_aktif` int DEFAULT '0',
  `total_stok_keseluruhan` int DEFAULT '0',
  `barang_hampir_habis` int DEFAULT '0',
  `barang_expired_soon` int DEFAULT '0',
  `last_calculated` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_swedish_ci;

--
-- Dumping data for table `dashboard_summary`
--

INSERT INTO `dashboard_summary` (`id_summary`, `total_barang`, `total_lokasi_aktif`, `total_stok_keseluruhan`, `barang_hampir_habis`, `barang_expired_soon`, `last_calculated`) VALUES
(1, 3, 4, 0, 0, 0, '2025-07-01 19:23:21');

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `id_inventory` int NOT NULL,
  `id_barang` int NOT NULL,
  `id_lokasi` int NOT NULL,
  `jumlah_stok` int NOT NULL DEFAULT '0',
  `stok_minimum` int DEFAULT '10',
  `stok_maksimum` int DEFAULT '1000',
  `last_updated` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_swedish_ci;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`id_inventory`, `id_barang`, `id_lokasi`, `jumlah_stok`, `stok_minimum`, `stok_maksimum`, `last_updated`) VALUES
(2, 3, 1, 4, 10, 1000, '2025-07-08 17:10:49');

-- --------------------------------------------------------

--
-- Table structure for table `log_aktivitas`
--

CREATE TABLE `log_aktivitas` (
  `id_log` int NOT NULL,
  `jenis_aktivitas` enum('masuk','keluar','mutasi','adjustment') COLLATE utf8mb3_swedish_ci NOT NULL,
  `id_barang` int NOT NULL,
  `id_lokasi_asal` int DEFAULT NULL,
  `id_lokasi_tujuan` int DEFAULT NULL,
  `jumlah_sebelum` int DEFAULT NULL,
  `jumlah_sesudah` int DEFAULT NULL,
  `selisih` int DEFAULT NULL,
  `petugas` varchar(100) COLLATE utf8mb3_swedish_ci DEFAULT NULL,
  `keterangan` text COLLATE utf8mb3_swedish_ci,
  `timestamp_aktivitas` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_swedish_ci;

--
-- Dumping data for table `log_aktivitas`
--

INSERT INTO `log_aktivitas` (`id_log`, `jenis_aktivitas`, `id_barang`, `id_lokasi_asal`, `id_lokasi_tujuan`, `jumlah_sebelum`, `jumlah_sesudah`, `selisih`, `petugas`, `keterangan`, `timestamp_aktivitas`) VALUES
(1, 'masuk', 3, NULL, 1, 0, 10, 10, 'Saipul', 'Barang Masuk', '2025-07-01 19:48:48'),
(2, 'masuk', 3, NULL, 1, NULL, NULL, 2, 'Saipul', 'Barang Masuk', '2025-07-08 17:02:16'),
(3, 'masuk', 3, NULL, 1, NULL, NULL, 2, 'Saipul', 'Barang Masuk', '2025-07-08 17:02:55'),
(4, 'keluar', 3, 1, NULL, NULL, NULL, 2, 'Saipul', 'Barang Keluar', '2025-07-08 17:10:49');

-- --------------------------------------------------------

--
-- Table structure for table `lokasi_gudang`
--

CREATE TABLE `lokasi_gudang` (
  `id_lokasi` int NOT NULL,
  `kode_lokasi` varchar(20) COLLATE utf8mb3_swedish_ci NOT NULL,
  `nama_lokasi` varchar(100) COLLATE utf8mb3_swedish_ci NOT NULL,
  `zona` varchar(50) COLLATE utf8mb3_swedish_ci DEFAULT NULL,
  `rak` varchar(20) COLLATE utf8mb3_swedish_ci DEFAULT NULL,
  `tingkat` int DEFAULT NULL,
  `posisi` varchar(20) COLLATE utf8mb3_swedish_ci DEFAULT NULL,
  `kapasitas_maksimal` int DEFAULT '100',
  `status_lokasi` enum('aktif','maintenance','penuh') COLLATE utf8mb3_swedish_ci DEFAULT 'aktif',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_swedish_ci;

--
-- Dumping data for table `lokasi_gudang`
--

INSERT INTO `lokasi_gudang` (`id_lokasi`, `kode_lokasi`, `nama_lokasi`, `zona`, `rak`, `tingkat`, `posisi`, `kapasitas_maksimal`, `status_lokasi`, `created_at`) VALUES
(1, 'A01-01-01', 'Zona A Rak 1 Tingkat 1 Posisi 1', 'A', 'R01', 1, 'P01', 100, 'aktif', '2025-07-01 18:33:00'),
(2, 'A01-01-02', 'Zona A Rak 1 Tingkat 1 Posisi 2', 'A', 'R01', 1, 'P02', 100, 'aktif', '2025-07-01 18:33:00'),
(3, 'A01-02-01', 'Zona A Rak 1 Tingkat 2 Posisi 1', 'A', 'R01', 2, 'P01', 100, 'aktif', '2025-07-01 18:33:00'),
(4, 'B01-01-01', 'Zona B Rak 1 Tingkat 1 Posisi 1', 'B', 'R01', 1, 'P01', 100, 'aktif', '2025-07-01 18:33:00');

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_stok_realtime`
-- (See below for the actual view)
--
CREATE TABLE `v_stok_realtime` (
`jumlah_stok` int
,`kategori` varchar(100)
,`kode_barang` varchar(50)
,`kode_lokasi` varchar(20)
,`last_updated` timestamp
,`nama_barang` varchar(200)
,`nama_lokasi` varchar(100)
,`status_stok` varchar(12)
,`stok_minimum` int
);

-- --------------------------------------------------------

--
-- Structure for view `v_stok_realtime`
--
DROP TABLE IF EXISTS `v_stok_realtime`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_stok_realtime`  AS SELECT `b`.`kode_barang` AS `kode_barang`, `b`.`nama_barang` AS `nama_barang`, `b`.`kategori` AS `kategori`, `lg`.`kode_lokasi` AS `kode_lokasi`, `lg`.`nama_lokasi` AS `nama_lokasi`, `i`.`jumlah_stok` AS `jumlah_stok`, `i`.`stok_minimum` AS `stok_minimum`, (case when (`i`.`jumlah_stok` <= `i`.`stok_minimum`) then 'Stok Minimum' when (`i`.`jumlah_stok` = 0) then 'Habis' else 'Normal' end) AS `status_stok`, `i`.`last_updated` AS `last_updated` FROM ((`inventory` `i` join `barang` `b` on((`i`.`id_barang` = `b`.`id_barang`))) join `lokasi_gudang` `lg` on((`i`.`id_lokasi` = `lg`.`id_lokasi`))) WHERE (`i`.`jumlah_stok` > 0) ORDER BY `b`.`nama_barang` ASC, `lg`.`kode_lokasi` ASC  ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `barang`
--
ALTER TABLE `barang`
  ADD PRIMARY KEY (`id_barang`),
  ADD UNIQUE KEY `kode_barang` (`kode_barang`),
  ADD UNIQUE KEY `barcode` (`barcode`),
  ADD UNIQUE KEY `rfid_tag` (`rfid_tag`);

--
-- Indexes for table `barang_keluar`
--
ALTER TABLE `barang_keluar`
  ADD PRIMARY KEY (`id_keluar`),
  ADD UNIQUE KEY `no_dokumen` (`no_dokumen`),
  ADD KEY `id_barang` (`id_barang`),
  ADD KEY `id_lokasi` (`id_lokasi`);

--
-- Indexes for table `barang_masuk`
--
ALTER TABLE `barang_masuk`
  ADD PRIMARY KEY (`id_masuk`),
  ADD UNIQUE KEY `no_dokumen` (`no_dokumen`),
  ADD KEY `id_barang` (`id_barang`),
  ADD KEY `id_lokasi` (`id_lokasi`);

--
-- Indexes for table `dashboard_summary`
--
ALTER TABLE `dashboard_summary`
  ADD PRIMARY KEY (`id_summary`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`id_inventory`),
  ADD KEY `id_lokasi` (`id_lokasi`),
  ADD KEY `idx_barang_lokasi` (`id_barang`,`id_lokasi`);

--
-- Indexes for table `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  ADD PRIMARY KEY (`id_log`),
  ADD KEY `id_barang` (`id_barang`),
  ADD KEY `id_lokasi_asal` (`id_lokasi_asal`),
  ADD KEY `id_lokasi_tujuan` (`id_lokasi_tujuan`);

--
-- Indexes for table `lokasi_gudang`
--
ALTER TABLE `lokasi_gudang`
  ADD PRIMARY KEY (`id_lokasi`),
  ADD UNIQUE KEY `kode_lokasi` (`kode_lokasi`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `barang`
--
ALTER TABLE `barang`
  MODIFY `id_barang` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `barang_keluar`
--
ALTER TABLE `barang_keluar`
  MODIFY `id_keluar` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `barang_masuk`
--
ALTER TABLE `barang_masuk`
  MODIFY `id_masuk` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `dashboard_summary`
--
ALTER TABLE `dashboard_summary`
  MODIFY `id_summary` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `id_inventory` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  MODIFY `id_log` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `lokasi_gudang`
--
ALTER TABLE `lokasi_gudang`
  MODIFY `id_lokasi` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `barang_keluar`
--
ALTER TABLE `barang_keluar`
  ADD CONSTRAINT `barang_keluar_ibfk_1` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id_barang`),
  ADD CONSTRAINT `barang_keluar_ibfk_2` FOREIGN KEY (`id_lokasi`) REFERENCES `lokasi_gudang` (`id_lokasi`);

--
-- Constraints for table `barang_masuk`
--
ALTER TABLE `barang_masuk`
  ADD CONSTRAINT `barang_masuk_ibfk_1` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id_barang`),
  ADD CONSTRAINT `barang_masuk_ibfk_2` FOREIGN KEY (`id_lokasi`) REFERENCES `lokasi_gudang` (`id_lokasi`);

--
-- Constraints for table `inventory`
--
ALTER TABLE `inventory`
  ADD CONSTRAINT `inventory_ibfk_1` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id_barang`),
  ADD CONSTRAINT `inventory_ibfk_2` FOREIGN KEY (`id_lokasi`) REFERENCES `lokasi_gudang` (`id_lokasi`);

--
-- Constraints for table `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  ADD CONSTRAINT `log_aktivitas_ibfk_1` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id_barang`),
  ADD CONSTRAINT `log_aktivitas_ibfk_2` FOREIGN KEY (`id_lokasi_asal`) REFERENCES `lokasi_gudang` (`id_lokasi`),
  ADD CONSTRAINT `log_aktivitas_ibfk_3` FOREIGN KEY (`id_lokasi_tujuan`) REFERENCES `lokasi_gudang` (`id_lokasi`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
