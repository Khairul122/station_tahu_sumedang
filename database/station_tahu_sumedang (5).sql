-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 28, 2025 at 01:21 AM
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
-- Database: `station_tahu_sumedang`
--

-- --------------------------------------------------------

--
-- Table structure for table `aktivitas_customer`
--

CREATE TABLE `aktivitas_customer` (
  `aktivitas_id` int NOT NULL,
  `customer_id` int DEFAULT NULL,
  `jenis_aktivitas` enum('pembelian','complaint','follow_up','promo_info') CHARACTER SET utf8mb3 COLLATE utf8mb3_swedish_ci NOT NULL,
  `catatan` text CHARACTER SET utf8mb3 COLLATE utf8mb3_swedish_ci,
  `tanggal_aktivitas` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_swedish_ci;

--
-- Dumping data for table `aktivitas_customer`
--

INSERT INTO `aktivitas_customer` (`aktivitas_id`, `customer_id`, `jenis_aktivitas`, `catatan`, `tanggal_aktivitas`) VALUES
(14, 6, 'pembelian', 'Transaksi #14 - Total: Rp 1,900', '2025-07-27 18:07:13'),
(15, 6, 'pembelian', 'Transaksi #15 - Total: Rp 1,900', '2025-07-27 18:28:36');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `customer_id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `nama_customer` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_swedish_ci NOT NULL,
  `no_telepon` varchar(15) CHARACTER SET utf8mb3 COLLATE utf8mb3_swedish_ci DEFAULT NULL,
  `alamat` text CHARACTER SET utf8mb3 COLLATE utf8mb3_swedish_ci,
  `email` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_swedish_ci DEFAULT NULL,
  `membership_id` int DEFAULT '1',
  `total_pembelian` decimal(12,2) DEFAULT '0.00',
  `total_poin` int DEFAULT '0',
  `tanggal_daftar` date DEFAULT (curdate()),
  `status_aktif` enum('aktif','tidak_aktif') CHARACTER SET utf8mb3 COLLATE utf8mb3_swedish_ci DEFAULT 'aktif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_swedish_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`customer_id`, `user_id`, `nama_customer`, `no_telepon`, `alamat`, `email`, `membership_id`, `total_pembelian`, `total_poin`, `tanggal_daftar`, `status_aktif`) VALUES
(1, 3, 'Budi Santoso', '081234567890', 'Jl. Raya Sumedang No. 123', 'budi@email.com', 3, '350000.00', 150, '2025-07-09', 'aktif'),
(2, 4, 'Siti Rahayu', '081234567891', 'Jl. Pahlawan No. 45', 'siti@email.com', 2, '150000.00', 80, '2025-07-09', 'aktif'),
(3, 5, 'Ahmad Fauzi', '081234567892', 'Jl. Merdeka No. 67', 'ahmad@email.com', 1, '50000.00', 25, '2025-07-09', 'aktif'),
(4, NULL, 'Rina Permata', '081234567893', 'Jl. Sudirman No. 89', 'rina@email.com', 1, '25000.00', 12, '2025-07-09', 'aktif'),
(5, NULL, 'Dedi Kurniawan', '081234567894', 'Jl. Ahmad Yani No. 56', 'dedi@email.com', 2, '125000.00', 65, '2025-07-09', 'aktif'),
(6, 6, 'Kartika', '082165443677', 'Lhoksuemawe\r\nBlang Pulo', 'kartika@gmail.com', 2, '289200.00', 116, '2025-07-09', 'aktif');

--
-- Triggers `customers`
--
DELIMITER $$
CREATE TRIGGER `update_membership_after_update` AFTER UPDATE ON `customers` FOR EACH ROW BEGIN
    DECLARE new_membership INT;
    DECLARE membership_name VARCHAR(50);
    
    -- Hanya jalankan jika total_pembelian berubah DAN bukan dari trigger
    IF NEW.total_pembelian != OLD.total_pembelian AND 
       NEW.membership_id = OLD.membership_id THEN
       
        -- Tentukan membership baru
        IF NEW.total_pembelian >= 500000 THEN
            SET new_membership = 4;
            SET membership_name = 'Platinum';
        ELSEIF NEW.total_pembelian >= 300000 THEN
            SET new_membership = 3;
            SET membership_name = 'Gold';
        ELSEIF NEW.total_pembelian >= 100000 THEN
            SET new_membership = 2;
            SET membership_name = 'Silver';
        ELSE
            SET new_membership = 1;
            SET membership_name = 'Bronze';
        END IF;
        
        -- Update membership jika berubah (tanpa trigger recursive)
        IF new_membership != NEW.membership_id THEN
            -- Update membership langsung tanpa trigger
            UPDATE customers 
            SET membership_id = new_membership 
            WHERE customer_id = NEW.customer_id;
            
            -- Insert aktivitas upgrade
            INSERT INTO aktivitas_customer (customer_id, jenis_aktivitas, catatan)
            VALUES (NEW.customer_id, 'follow_up', CONCAT('Naik tier menjadi ', membership_name));
        END IF;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `detail_transaksi`
--

CREATE TABLE `detail_transaksi` (
  `detail_id` int NOT NULL,
  `transaksi_id` int DEFAULT NULL,
  `produk_id` int DEFAULT NULL,
  `jumlah` int NOT NULL,
  `harga_satuan` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `poin_produk` int DEFAULT '0',
  `total_poin_item` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_swedish_ci;

--
-- Dumping data for table `detail_transaksi`
--

INSERT INTO `detail_transaksi` (`detail_id`, `transaksi_id`, `produk_id`, `jumlah`, `harga_satuan`, `subtotal`, `poin_produk`, `total_poin_item`) VALUES
(18, 14, 13, 1, '2000.00', '2000.00', 1, 2),
(19, 15, 11, 1, '2000.00', '2000.00', 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `membership`
--

CREATE TABLE `membership` (
  `membership_id` int NOT NULL,
  `nama_membership` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_swedish_ci NOT NULL,
  `minimal_pembelian` decimal(10,2) NOT NULL,
  `diskon_persen` int DEFAULT '0',
  `poin_per_pembelian` int DEFAULT '1',
  `benefit` text CHARACTER SET utf8mb3 COLLATE utf8mb3_swedish_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_swedish_ci;

--
-- Dumping data for table `membership`
--

INSERT INTO `membership` (`membership_id`, `nama_membership`, `minimal_pembelian`, `diskon_persen`, `poin_per_pembelian`, `benefit`) VALUES
(1, 'Bronze', '0.00', 0, 1, 'Poin setiap pembelian'),
(2, 'Silver', '100000.00', 5, 2, 'Diskon 5% + 2x poin'),
(3, 'Gold', '300000.00', 10, 3, 'Diskon 10% + 3x poin + prioritas'),
(4, 'Platinum', '500000.00', 15, 5, 'Diskon 15% + 5x poin + free delivery');

-- --------------------------------------------------------

--
-- Table structure for table `produk`
--

CREATE TABLE `produk` (
  `produk_id` int NOT NULL,
  `nama_produk` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_swedish_ci NOT NULL,
  `harga` decimal(10,2) NOT NULL,
  `stok` int DEFAULT '0',
  `kategori` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_swedish_ci DEFAULT NULL,
  `poin_reward` int DEFAULT '0',
  `foto_produk` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_swedish_ci DEFAULT NULL,
  `store_id` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_swedish_ci;

--
-- Dumping data for table `produk`
--

INSERT INTO `produk` (`produk_id`, `nama_produk`, `harga`, `stok`, `kategori`, `poin_reward`, `foto_produk`, `store_id`) VALUES
(11, 'Sala Lauk', '2000.00', 99, 'Original', 1, 'produk_1753595686_6885bf2644899.jpg', 2),
(12, 'Sala Lauk', '2000.00', 100, 'Original', 1, 'produk_1753595686_6885bf2644899.jpg', 4),
(13, 'Sala Lauk', '2000.00', 99, 'Original', 1, 'produk_1753595686_6885bf2644899.jpg', 5),
(14, 'Sala Lauk', '2000.00', 100, 'Original', 1, 'produk_1753595686_6885bf2644899.jpg', 1),
(15, 'Sala Lauk', '2000.00', 100, 'Original', 1, 'produk_1753595686_6885bf2644899.jpg', 3);

-- --------------------------------------------------------

--
-- Table structure for table `rewards`
--

CREATE TABLE `rewards` (
  `reward_id` int NOT NULL,
  `nama_reward` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_swedish_ci NOT NULL,
  `poin_required` int NOT NULL,
  `stock` int DEFAULT '10',
  `status` enum('aktif','tidak_aktif') CHARACTER SET utf8mb3 COLLATE utf8mb3_swedish_ci DEFAULT 'aktif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_swedish_ci;

--
-- Dumping data for table `rewards`
--

INSERT INTO `rewards` (`reward_id`, `nama_reward`, `poin_required`, `stock`, `status`) VALUES
(1, 'Voucher Diskon 10%', 100, 19, 'aktif'),
(2, 'Voucher Diskon 20%', 200, 15, 'aktif'),
(3, 'Tahu Gratis 1 Porsi', 150, 25, 'aktif'),
(4, 'Merchandise Tote Bag', 300, 10, 'aktif'),
(5, 'Voucher Makan Gratis 50K', 500, 5, 'aktif');

-- --------------------------------------------------------

--
-- Table structure for table `store`
--

CREATE TABLE `store` (
  `id_store` int NOT NULL,
  `nama_store` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_swedish_ci NOT NULL,
  `alamat_store` text CHARACTER SET utf8mb3 COLLATE utf8mb3_swedish_ci NOT NULL,
  `manajer_store` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_swedish_ci DEFAULT NULL,
  `status_store` enum('aktif','tidak_aktif') CHARACTER SET utf8mb3 COLLATE utf8mb3_swedish_ci DEFAULT 'aktif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_swedish_ci;

--
-- Dumping data for table `store`
--

INSERT INTO `store` (`id_store`, `nama_store`, `alamat_store`, `manajer_store`, `status_store`) VALUES
(1, 'Station Tahu Sumedang - Pasar Usang', 'Pasar usang KM 27, Padang Pariaman', 'Reni', 'aktif'),
(2, 'Station Tahu Sumedang - Akses Bandara', 'Akses bandara no. 99, Padang Pariaman', 'Raihan', 'aktif'),
(3, 'Station Tahu Sumedang - Rimbo Datar', 'Rimbo Datar, Padang', 'Hagia', 'aktif'),
(4, 'Station Tahu Sumedang - Jl Raya Ketaping', 'Jl raya Ketaping', 'Susi', 'aktif'),
(5, 'Station Tahu Sumedang - Kayu Tanam', 'Kayu Tanam, 2 X 11 Kayu Tanam, Padang Pariaman', 'Kevin', 'aktif');

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `transaksi_id` int NOT NULL,
  `customer_id` int DEFAULT NULL,
  `store_id` int NOT NULL DEFAULT '1',
  `tanggal_transaksi` datetime DEFAULT CURRENT_TIMESTAMP,
  `total_sebelum_diskon` decimal(10,2) NOT NULL,
  `diskon_membership` decimal(10,2) DEFAULT '0.00',
  `total_bayar` decimal(10,2) NOT NULL,
  `poin_didapat` int DEFAULT '0',
  `metode_pembayaran` enum('tunai','transfer','kartu') CHARACTER SET utf8mb3 COLLATE utf8mb3_swedish_ci DEFAULT 'tunai',
  `bukti_pembayaran` varchar(255) COLLATE utf8mb3_swedish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_swedish_ci;

--
-- Dumping data for table `transaksi`
--

INSERT INTO `transaksi` (`transaksi_id`, `customer_id`, `store_id`, `tanggal_transaksi`, `total_sebelum_diskon`, `diskon_membership`, `total_bayar`, `poin_didapat`, `metode_pembayaran`, `bukti_pembayaran`) VALUES
(14, 6, 5, '2025-07-27 18:07:13', '2000.00', '100.00', '1900.00', 2, 'transfer', 'bukti_1753614433_6886086132ed9.png'),
(15, 6, 2, '2025-07-27 18:28:36', '2000.00', '100.00', '1900.00', 2, 'tunai', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tukar_poin`
--

CREATE TABLE `tukar_poin` (
  `tukar_id` int NOT NULL,
  `customer_id` int DEFAULT NULL,
  `reward_id` int DEFAULT NULL,
  `poin_digunakan` int NOT NULL,
  `reward` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_swedish_ci DEFAULT NULL,
  `status` enum('pending','selesai') CHARACTER SET utf8mb3 COLLATE utf8mb3_swedish_ci DEFAULT 'pending',
  `tanggal_tukar` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int NOT NULL,
  `username` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_swedish_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_swedish_ci NOT NULL,
  `nama_lengkap` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_swedish_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_swedish_ci DEFAULT NULL,
  `role` enum('admin','member','pimpinan','manajer') CHARACTER SET utf8mb3 COLLATE utf8mb3_swedish_ci NOT NULL,
  `store_id` int DEFAULT NULL,
  `status` enum('aktif','tidak_aktif') CHARACTER SET utf8mb3 COLLATE utf8mb3_swedish_ci DEFAULT 'aktif',
  `tanggal_dibuat` datetime DEFAULT CURRENT_TIMESTAMP,
  `terakhir_login` datetime DEFAULT NULL,
  `kode_konfirmasi` varchar(6) COLLATE utf8mb3_swedish_ci DEFAULT NULL,
  `kode_expired` datetime DEFAULT NULL,
  `email_verified` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_swedish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `nama_lengkap`, `email`, `role`, `store_id`, `status`, `tanggal_dibuat`, `terakhir_login`, `kode_konfirmasi`, `kode_expired`, `email_verified`) VALUES
(1, 'admin', 'admin123', 'Administrator', 'admin@stationtahu.com', 'admin', NULL, 'aktif', '2025-07-09 15:50:50', '2025-07-27 19:57:20', NULL, NULL, 0),
(2, 'pimpinan', 'pimpinan123', 'Pimpinan Station Tahu', 'pimpinan@stationtahu.com', 'pimpinan', NULL, 'aktif', '2025-07-09 15:50:50', '2025-07-27 19:57:32', NULL, NULL, 0),
(3, 'budi_member', 'budi123', 'Budi Santoso', 'budi@email.com', 'member', NULL, 'aktif', '2025-07-09 15:50:50', NULL, NULL, NULL, 0),
(4, 'siti_member', 'siti123', 'Siti Rahayu', 'siti@email.com', 'member', NULL, 'aktif', '2025-07-09 15:50:50', NULL, NULL, NULL, 0),
(5, 'ahmad_member', 'ahmad123', 'Ahmad Fauzi', 'ahmad@email.com', 'member', NULL, 'aktif', '2025-07-09 15:50:50', NULL, NULL, NULL, 0),
(6, 'Kartika', 'kartika', 'Kartika', 'kartika@gmail.com', 'member', NULL, 'aktif', '2025-07-09 20:38:48', '2025-07-27 18:28:20', NULL, NULL, 0),
(9, 'kevin', 'kevin123', 'Kevin', 'kevin@gmail.com', 'manajer', 5, 'aktif', '2025-07-27 15:11:02', '2025-07-27 18:29:47', NULL, NULL, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `aktivitas_customer`
--
ALTER TABLE `aktivitas_customer`
  ADD PRIMARY KEY (`aktivitas_id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`customer_id`),
  ADD UNIQUE KEY `user_id` (`user_id`),
  ADD KEY `membership_id` (`membership_id`);

--
-- Indexes for table `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  ADD PRIMARY KEY (`detail_id`),
  ADD KEY `transaksi_id` (`transaksi_id`),
  ADD KEY `produk_id` (`produk_id`);

--
-- Indexes for table `membership`
--
ALTER TABLE `membership`
  ADD PRIMARY KEY (`membership_id`);

--
-- Indexes for table `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`produk_id`),
  ADD KEY `produk_ibfk_1` (`store_id`);

--
-- Indexes for table `rewards`
--
ALTER TABLE `rewards`
  ADD PRIMARY KEY (`reward_id`);

--
-- Indexes for table `store`
--
ALTER TABLE `store`
  ADD PRIMARY KEY (`id_store`);

--
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`transaksi_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `transaksi_ibfk_2` (`store_id`);

--
-- Indexes for table `tukar_poin`
--
ALTER TABLE `tukar_poin`
  ADD PRIMARY KEY (`tukar_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `tukar_poin_ibfk_2` (`reward_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `fk_users_store` (`store_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `aktivitas_customer`
--
ALTER TABLE `aktivitas_customer`
  MODIFY `aktivitas_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `customer_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  MODIFY `detail_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `membership`
--
ALTER TABLE `membership`
  MODIFY `membership_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `produk`
--
ALTER TABLE `produk`
  MODIFY `produk_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `rewards`
--
ALTER TABLE `rewards`
  MODIFY `reward_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `store`
--
ALTER TABLE `store`
  MODIFY `id_store` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `transaksi_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `tukar_poin`
--
ALTER TABLE `tukar_poin`
  MODIFY `tukar_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `aktivitas_customer`
--
ALTER TABLE `aktivitas_customer`
  ADD CONSTRAINT `aktivitas_customer_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`);

--
-- Constraints for table `customers`
--
ALTER TABLE `customers`
  ADD CONSTRAINT `customers_ibfk_1` FOREIGN KEY (`membership_id`) REFERENCES `membership` (`membership_id`),
  ADD CONSTRAINT `customers_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  ADD CONSTRAINT `detail_transaksi_ibfk_1` FOREIGN KEY (`transaksi_id`) REFERENCES `transaksi` (`transaksi_id`),
  ADD CONSTRAINT `detail_transaksi_ibfk_2` FOREIGN KEY (`produk_id`) REFERENCES `produk` (`produk_id`);

--
-- Constraints for table `produk`
--
ALTER TABLE `produk`
  ADD CONSTRAINT `produk_ibfk_1` FOREIGN KEY (`store_id`) REFERENCES `store` (`id_store`);

--
-- Constraints for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `transaksi_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`),
  ADD CONSTRAINT `transaksi_ibfk_2` FOREIGN KEY (`store_id`) REFERENCES `store` (`id_store`);

--
-- Constraints for table `tukar_poin`
--
ALTER TABLE `tukar_poin`
  ADD CONSTRAINT `tukar_poin_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`),
  ADD CONSTRAINT `tukar_poin_ibfk_2` FOREIGN KEY (`reward_id`) REFERENCES `rewards` (`reward_id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_store` FOREIGN KEY (`store_id`) REFERENCES `store` (`id_store`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
