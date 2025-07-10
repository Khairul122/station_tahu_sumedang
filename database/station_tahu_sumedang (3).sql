-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 10, 2025 at 11:28 AM
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
(1, 1, 'pembelian', 'Pembelian rutin setiap minggu', '2025-07-09 15:50:50'),
(2, 2, 'complaint', 'Mengeluh tahu kurang crispy', '2025-07-09 15:50:50'),
(3, 3, 'follow_up', 'Follow up customer baru', '2025-07-09 15:50:50'),
(4, 1, 'promo_info', 'Diberikan info promo membership gold', '2025-07-09 15:50:50'),
(5, 4, 'pembelian', 'Customer baru tanpa akun login', '2025-07-09 15:50:50'),
(6, 5, 'follow_up', 'Follow up customer silver member', '2025-07-09 15:50:50'),
(7, 6, 'pembelian', 'Transaksi #6 - Total: Rp 20,000', '2025-07-10 00:27:18'),
(8, 6, 'pembelian', 'Transaksi #7 - Total: Rp 20,000', '2025-07-10 00:40:07'),
(9, 6, 'pembelian', 'Transaksi #8 - Total: Rp 120,000', '2025-07-10 00:47:03'),
(10, 6, 'follow_up', 'Naik tier menjadi Silver', '2025-07-10 01:02:34'),
(11, 6, 'pembelian', 'Transaksi #9 - Total: Rp 418,000', '2025-07-10 01:37:06'),
(12, 6, 'pembelian', 'Transaksi #10 - Total: Rp 104,500', '2025-07-10 01:43:47'),
(13, 6, 'pembelian', 'Transaksi #11 - Total: Rp 20,900', '2025-07-10 01:44:16');

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
(6, 6, 'Kartika', '082165443677', 'Lhoksuemawe\r\nBlang Pulo', 'kartika@gmail.com', 2, '285400.00', 112, '2025-07-09', 'aktif');

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
(1, 1, 1, 2, '15000.00', '30000.00', 5, 30),
(2, 1, 3, 1, '20000.00', '20000.00', 8, 24),
(3, 1, 5, 1, '12000.00', '12000.00', 4, 0),
(4, 2, 2, 2, '16000.00', '32000.00', 6, 24),
(5, 2, 5, 1, '12000.00', '12000.00', 4, 8),
(6, 3, 1, 1, '15000.00', '15000.00', 5, 5),
(7, 3, 5, 1, '12000.00', '12000.00', 4, 4),
(8, 4, 1, 1, '15000.00', '15000.00', 5, 5),
(9, 5, 4, 2, '22000.00', '44000.00', 10, 40),
(10, 6, 3, 1, '20000.00', '20000.00', 8, 8),
(11, 7, 3, 1, '20000.00', '20000.00', 8, 8),
(12, 8, 3, 6, '20000.00', '120000.00', 8, 48),
(13, 9, 4, 20, '22000.00', '440000.00', 10, 400),
(14, 10, 4, 5, '22000.00', '110000.00', 10, 100),
(15, 11, 4, 1, '22000.00', '22000.00', 10, 20);

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
  `poin_reward` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_swedish_ci;

--
-- Dumping data for table `produk`
--

INSERT INTO `produk` (`produk_id`, `nama_produk`, `harga`, `stok`, `kategori`, `poin_reward`) VALUES
(1, 'Tahu Sumedang Original', '15000.00', 100, 'Original', 5),
(2, 'Tahu Sumedang Pedas', '16000.00', 80, 'Pedas', 6),
(3, 'Tahu Sumedang Isi Ayam', '20000.00', 52, 'Isi', 8),
(4, 'Tahu Sumedang Keju', '22000.00', 14, 'Isi', 10),
(5, 'Tahu Sumedang Mini', '12000.00', 120, 'Mini', 4);

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
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `transaksi_id` int NOT NULL,
  `customer_id` int DEFAULT NULL,
  `tanggal_transaksi` datetime DEFAULT CURRENT_TIMESTAMP,
  `total_sebelum_diskon` decimal(10,2) NOT NULL,
  `diskon_membership` decimal(10,2) DEFAULT '0.00',
  `total_bayar` decimal(10,2) NOT NULL,
  `poin_didapat` int DEFAULT '0',
  `metode_pembayaran` enum('tunai','transfer','kartu') CHARACTER SET utf8mb3 COLLATE utf8mb3_swedish_ci DEFAULT 'tunai'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_swedish_ci;

--
-- Dumping data for table `transaksi`
--

INSERT INTO `transaksi` (`transaksi_id`, `customer_id`, `tanggal_transaksi`, `total_sebelum_diskon`, `diskon_membership`, `total_bayar`, `poin_didapat`, `metode_pembayaran`) VALUES
(1, 1, '2025-07-09 15:50:50', '60000.00', '6000.00', '54000.00', 54, 'tunai'),
(2, 2, '2025-07-09 15:50:50', '32000.00', '1600.00', '30400.00', 32, 'tunai'),
(3, 3, '2025-07-09 15:50:50', '27000.00', '0.00', '27000.00', 13, 'tunai'),
(4, 4, '2025-07-09 15:50:50', '15000.00', '0.00', '15000.00', 5, 'tunai'),
(5, 5, '2025-07-09 15:50:50', '45000.00', '2250.00', '42750.00', 36, 'tunai'),
(6, 6, '2025-07-10 00:27:18', '20000.00', '0.00', '20000.00', 10, 'tunai'),
(7, 6, '2025-07-10 00:40:07', '20000.00', '0.00', '20000.00', 10, 'tunai'),
(8, 6, '2025-07-10 00:47:03', '120000.00', '0.00', '120000.00', 60, 'tunai'),
(9, 6, '2025-07-10 01:37:06', '440000.00', '22000.00', '418000.00', 441, 'tunai'),
(10, 6, '2025-07-10 01:43:47', '110000.00', '5500.00', '104500.00', 110, 'tunai'),
(11, 6, '2025-07-10 01:44:16', '22000.00', '1100.00', '20900.00', 22, 'tunai');

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

--
-- Dumping data for table `tukar_poin`
--

INSERT INTO `tukar_poin` (`tukar_id`, `customer_id`, `reward_id`, `poin_digunakan`, `reward`, `status`, `tanggal_tukar`) VALUES
(1, 6, 1, 100, 'Voucher Diskon 10%', 'selesai', '2025-07-10 01:43:56');

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
  `role` enum('admin','member','pimpinan') CHARACTER SET utf8mb3 COLLATE utf8mb3_swedish_ci NOT NULL,
  `status` enum('aktif','tidak_aktif') CHARACTER SET utf8mb3 COLLATE utf8mb3_swedish_ci DEFAULT 'aktif',
  `tanggal_dibuat` datetime DEFAULT CURRENT_TIMESTAMP,
  `terakhir_login` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_swedish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `nama_lengkap`, `email`, `role`, `status`, `tanggal_dibuat`, `terakhir_login`) VALUES
(1, 'admin', 'admin123', 'Administrator', 'admin@stationtahu.com', 'admin', 'aktif', '2025-07-09 15:50:50', '2025-07-10 17:39:50'),
(2, 'pimpinan', 'pimpinan123', 'Pimpinan Station Tahu', 'pimpinan@stationtahu.com', 'pimpinan', 'aktif', '2025-07-09 15:50:50', '2025-07-10 17:40:07'),
(3, 'budi_member', 'budi123', 'Budi Santoso', 'budi@email.com', 'member', 'aktif', '2025-07-09 15:50:50', NULL),
(4, 'siti_member', 'siti123', 'Siti Rahayu', 'siti@email.com', 'member', 'aktif', '2025-07-09 15:50:50', NULL),
(5, 'ahmad_member', 'ahmad123', 'Ahmad Fauzi', 'ahmad@email.com', 'member', 'aktif', '2025-07-09 15:50:50', NULL),
(6, 'Kartika', 'kartika', 'Kartika', 'kartika@gmail.com', 'member', 'aktif', '2025-07-09 20:38:48', '2025-07-10 00:39:02');

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
  ADD PRIMARY KEY (`produk_id`);

--
-- Indexes for table `rewards`
--
ALTER TABLE `rewards`
  ADD PRIMARY KEY (`reward_id`);

--
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`transaksi_id`),
  ADD KEY `customer_id` (`customer_id`);

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
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `aktivitas_customer`
--
ALTER TABLE `aktivitas_customer`
  MODIFY `aktivitas_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `customer_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  MODIFY `detail_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `membership`
--
ALTER TABLE `membership`
  MODIFY `membership_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `produk`
--
ALTER TABLE `produk`
  MODIFY `produk_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `rewards`
--
ALTER TABLE `rewards`
  MODIFY `reward_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `transaksi_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tukar_poin`
--
ALTER TABLE `tukar_poin`
  MODIFY `tukar_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

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
-- Constraints for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `transaksi_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`);

--
-- Constraints for table `tukar_poin`
--
ALTER TABLE `tukar_poin`
  ADD CONSTRAINT `tukar_poin_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`),
  ADD CONSTRAINT `tukar_poin_ibfk_2` FOREIGN KEY (`reward_id`) REFERENCES `rewards` (`reward_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
