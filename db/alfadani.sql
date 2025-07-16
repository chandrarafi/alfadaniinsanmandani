/*
SQLyog Ultimate v13.1.1 (64 bit)
MySQL - 8.0.30 : Database - alfadani
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`alfadani` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;

USE `alfadani`;

/*Table structure for table `detail_pendaftaran` */

DROP TABLE IF EXISTS `detail_pendaftaran`;

CREATE TABLE `detail_pendaftaran` (
  `iddetail` int unsigned NOT NULL AUTO_INCREMENT,
  `idpendaftaran` varchar(15) COLLATE utf8mb4_general_ci NOT NULL,
  `jamaahid` varchar(15) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`iddetail`),
  KEY `detail_pendaftaran_idpendaftaran_foreign` (`idpendaftaran`),
  KEY `detail_pendaftaran_jamaahid_foreign` (`jamaahid`),
  CONSTRAINT `detail_pendaftaran_idpendaftaran_foreign` FOREIGN KEY (`idpendaftaran`) REFERENCES `pendaftaran` (`idpendaftaran`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `detail_pendaftaran_jamaahid_foreign` FOREIGN KEY (`jamaahid`) REFERENCES `jamaah` (`idjamaah`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `detail_pendaftaran` */

insert  into `detail_pendaftaran`(`iddetail`,`idpendaftaran`,`jamaahid`,`created_at`,`updated_at`) values 
(28,'PND202507001','JMH001','2025-07-16 19:33:55','2025-07-16 19:33:55'),
(29,'PND202507001','JMH002','2025-07-16 19:33:55','2025-07-16 19:33:55');

/*Table structure for table `detailpendaftaran` */

DROP TABLE IF EXISTS `detailpendaftaran`;

CREATE TABLE `detailpendaftaran` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `idpendaftaran` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `jamaahid` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `detailpendaftaran_idpendaftaran_foreign` (`idpendaftaran`),
  KEY `detailpendaftaran_jamaahid_foreign` (`jamaahid`),
  CONSTRAINT `detailpendaftaran_idpendaftaran_foreign` FOREIGN KEY (`idpendaftaran`) REFERENCES `pendaftaran` (`idpendaftaran`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `detailpendaftaran_jamaahid_foreign` FOREIGN KEY (`jamaahid`) REFERENCES `jamaah` (`idjamaah`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `detailpendaftaran` */

/*Table structure for table `dokumen` */

DROP TABLE IF EXISTS `dokumen`;

CREATE TABLE `dokumen` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `idjamaah` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `namadokumen` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `file` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `dokumen_idjamaah_foreign` (`idjamaah`),
  CONSTRAINT `dokumen_idjamaah_foreign` FOREIGN KEY (`idjamaah`) REFERENCES `jamaah` (`idjamaah`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `dokumen` */

insert  into `dokumen`(`id`,`idjamaah`,`namadokumen`,`file`,`created_at`,`updated_at`) values 
(1,'JMH001','KTP','1752612128_d6ca7c8b6544a4f9d11d.jpg','2025-07-15 20:42:08','2025-07-15 20:42:08'),
(2,'JMH001','KK','1752612128_1a48066a9a789f546630.jpg','2025-07-15 20:42:08','2025-07-15 20:42:08'),
(3,'JMH001','PASPOR','1752612128_5f615e67b7038e5a650f.jpg','2025-07-15 20:42:08','2025-07-15 20:42:08'),
(4,'JMH001','FOTO','1752612128_261ac6e329d0467aeffb.jpg','2025-07-15 20:42:08','2025-07-15 20:42:08'),
(5,'JMH001','AKTELAHIR','1752612128_fa4836a1edd6f30e2bdb.jpg','2025-07-15 20:42:08','2025-07-15 20:42:08'),
(14,'JMH002','KK','1752669908_89c811b59f2fc58bcac5.pdf','2025-07-16 19:45:08','2025-07-16 19:45:08');

/*Table structure for table `jamaah` */

DROP TABLE IF EXISTS `jamaah`;

CREATE TABLE `jamaah` (
  `idjamaah` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `userid` int unsigned DEFAULT NULL,
  `nik` varchar(16) COLLATE utf8mb4_general_ci NOT NULL,
  `namajamaah` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `jenkel` enum('L','P') COLLATE utf8mb4_general_ci NOT NULL,
  `alamat` text COLLATE utf8mb4_general_ci,
  `emailjamaah` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nohpjamaah` varchar(15) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `ref` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`idjamaah`),
  UNIQUE KEY `nik` (`nik`),
  UNIQUE KEY `emailjamaah` (`emailjamaah`),
  KEY `jamaah_userid_foreign` (`userid`),
  CONSTRAINT `jamaah_userid_foreign` FOREIGN KEY (`userid`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `jamaah` */

insert  into `jamaah`(`idjamaah`,`userid`,`nik`,`namajamaah`,`jenkel`,`alamat`,`emailjamaah`,`nohpjamaah`,`status`,`ref`,`created_at`,`updated_at`) values 
('JMH001',6,'1301071804030002','Jamaludin','L','Padang','jamal@gmail.com','083182423488',1,NULL,'2025-07-15 18:32:31','2025-07-15 18:32:31'),
('JMH002',6,'1301071804030009','WINDI PUTRI','L','Padang','','083182423488',1,'JMH001','2025-07-16 15:11:07','2025-07-16 15:11:07');

/*Table structure for table `kategori` */

DROP TABLE IF EXISTS `kategori`;

CREATE TABLE `kategori` (
  `idkategori` char(10) COLLATE utf8mb4_general_ci NOT NULL,
  `namakategori` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`idkategori`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `kategori` */

insert  into `kategori`(`idkategori`,`namakategori`,`status`,`created_at`,`updated_at`) values 
('KTGR001','Haji',1,'2025-07-15 18:03:06','2025-07-15 18:03:06'),
('KTGR002','Umroh',1,'2025-07-15 18:03:06','2025-07-15 18:03:06');

/*Table structure for table `migrations` */

DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `version` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `class` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `group` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `namespace` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `time` int NOT NULL,
  `batch` int unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `migrations` */

insert  into `migrations`(`id`,`version`,`class`,`group`,`namespace`,`time`,`batch`) values 
(3,'2025_07_09_000001','App\\Database\\Migrations\\CreateUserTable','default','App',1752057646,1),
(4,'2025_07_09_000002','App\\Database\\Migrations\\CreateKategoriTable','default','App',1752057646,1),
(5,'2025_07_09_000003','App\\Database\\Migrations\\CreatePaketTable','default','App',1752059945,2),
(6,'2025_07_16_000001','App\\Database\\Migrations\\CreateJamaahTable','default','App',1752603552,3),
(7,'2023-10-15-123000','App\\Database\\Migrations\\CreatePendaftaranTable','default','App',1752609970,4),
(8,'2023-10-15-123100','App\\Database\\Migrations\\CreateDetailPendaftaranTable','default','App',1752609970,4),
(9,'2023-10-15-123200','App\\Database\\Migrations\\CreatePembayaranTable','default','App',1752609970,4),
(10,'2025_07_16_000002','App\\Database\\Migrations\\CreateDokumenTable','default','App',1752612124,5),
(11,'2025_07_09_000004','App\\Database\\Migrations\\CreateDetailPendaftaranTable','default','App',1752632661,6);

/*Table structure for table `paket` */

DROP TABLE IF EXISTS `paket`;

CREATE TABLE `paket` (
  `idpaket` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `kategoriid` char(10) COLLATE utf8mb4_general_ci NOT NULL,
  `namapaket` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `kuota` int NOT NULL DEFAULT '0',
  `masatunggu` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `durasi` int NOT NULL DEFAULT '0' COMMENT 'Durasi dalam hari',
  `waktuberangkat` date DEFAULT NULL,
  `harga` decimal(15,2) NOT NULL DEFAULT '0.00',
  `deskripsi` text COLLATE utf8mb4_general_ci,
  `foto` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`idpaket`),
  KEY `paket_kategoriid_foreign` (`kategoriid`),
  CONSTRAINT `paket_kategoriid_foreign` FOREIGN KEY (`kategoriid`) REFERENCES `kategori` (`idkategori`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `paket` */

insert  into `paket`(`idpaket`,`kategoriid`,`namapaket`,`kuota`,`masatunggu`,`durasi`,`waktuberangkat`,`harga`,`deskripsi`,`foto`,`status`,`created_at`,`updated_at`) values 
('PKT001','KTGR001','Haji Reguler',31,'1-2 tahun',30,'2024-12-15',45000000.00,'Paket haji reguler dengan pelayanan standar dan fasilitas nyaman','1752610253_e09955f5522d589bbff9.jpg',1,'2025-07-15 18:03:06','2025-07-16 19:27:29'),
('PKT002','KTGR001','Haji Plus',24,'1 tahun',30,'2024-12-10',65000000.00,'Paket haji plus dengan pelayanan premium dan fasilitas mewah','1752610262_801058f557c659538182.jpg',1,'2025-07-15 18:03:06','2025-07-16 19:33:55'),
('PKT003','KTGR001','Haji Eksekutif',20,'6-8 bulan',30,'2024-12-05',85000000.00,'Paket haji eksekutif dengan pelayanan VIP dan fasilitas terbaik','1752610271_d439f6f4b04678d0c569.jpg',1,'2025-07-15 18:03:06','2025-07-16 11:42:07'),
('PKT004','KTGR002','Umroh Reguler',45,'1-2 bulan',9,'2024-09-15',25000000.00,'Paket umroh reguler dengan pelayanan standar dan fasilitas nyaman','1752610279_74d8d278942fb4aa55bf.jpg',1,'2025-07-15 18:03:06','2025-07-15 20:11:19'),
('PKT005','KTGR002','Umroh Plus Turki',30,'1-2 bulan',12,'2024-10-10',35000000.00,'Paket umroh plus wisata ke Turki dengan pelayanan premium','1752610292_cc926cf28049df345482.jpg',1,'2025-07-15 18:03:06','2025-07-15 20:11:32'),
('PKT006','KTGR002','Umroh Ramadhan',23,'3-4 bulan',15,'2025-03-05',40000000.00,'Paket umroh spesial di bulan Ramadhan dengan fasilitas premium','1752610299_6d9128911a769fcce2c0.jpg',1,'2025-07-15 18:03:06','2025-07-16 16:09:22'),
('PKT007','KTGR002','Umroh Plus Dubai',30,'1-2 bulan',12,'2024-11-20',38000000.00,'Paket umroh plus wisata ke Dubai dengan pelayanan ekslusif','1752610306_d717eaa6c3a6dd30eaab.jpg',1,'2025-07-15 18:03:06','2025-07-16 19:06:36');

/*Table structure for table `pembayaran` */

DROP TABLE IF EXISTS `pembayaran`;

CREATE TABLE `pembayaran` (
  `idpembayaran` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `pendaftaranid` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `tanggalbayar` date NOT NULL,
  `metodepembayaran` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `tipepembayaran` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `jumlahbayar` decimal(15,2) NOT NULL,
  `buktibayar` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `statuspembayaran` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`idpembayaran`),
  KEY `pembayaran_pendaftaranid_foreign` (`pendaftaranid`),
  CONSTRAINT `pembayaran_pendaftaranid_foreign` FOREIGN KEY (`pendaftaranid`) REFERENCES `pendaftaran` (`idpendaftaran`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `pembayaran` */

insert  into `pembayaran`(`idpembayaran`,`pendaftaranid`,`tanggalbayar`,`metodepembayaran`,`tipepembayaran`,`jumlahbayar`,`buktibayar`,`statuspembayaran`,`created_at`,`updated_at`) values 
('PMB202507001','PND202507001','2025-07-16','Transfer Bank','DP',39000000.00,'1752669261_4612496d18d7c9f55652.png',1,'2025-07-16 19:34:21','2025-07-16 19:34:38'),
('PMB202507002','PND202507001','2025-07-16','Transfer Bank','Cicilan',50000000.00,'1752669306_63d55cef27e7ab6fd83e.png',1,'2025-07-16 19:35:06','2025-07-16 19:35:14'),
('PMB202507003','PND202507001','2025-07-16','QRIS','Cicilan',41000000.00,'1752669340_1f3f2347505f1c2dc7c0.png',1,'2025-07-16 19:35:40','2025-07-16 19:35:48');

/*Table structure for table `pendaftaran` */

DROP TABLE IF EXISTS `pendaftaran`;

CREATE TABLE `pendaftaran` (
  `idpendaftaran` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `iduser` int unsigned NOT NULL,
  `paketid` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `tanggaldaftar` date NOT NULL,
  `totalbayar` decimal(15,2) NOT NULL,
  `sisabayar` decimal(15,2) NOT NULL,
  `status` enum('pending','confirmed','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `expired_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`idpendaftaran`),
  KEY `pendaftaran_iduser_foreign` (`iduser`),
  KEY `pendaftaran_paketid_foreign` (`paketid`),
  CONSTRAINT `pendaftaran_iduser_foreign` FOREIGN KEY (`iduser`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `pendaftaran_paketid_foreign` FOREIGN KEY (`paketid`) REFERENCES `paket` (`idpaket`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `pendaftaran` */

insert  into `pendaftaran`(`idpendaftaran`,`iduser`,`paketid`,`tanggaldaftar`,`totalbayar`,`sisabayar`,`status`,`expired_at`,`created_at`,`updated_at`) values 
('PND202507001',6,'PKT002','2025-07-16',130000000.00,0.00,'',NULL,'2025-07-16 19:33:55','2025-07-16 19:35:48');

/*Table structure for table `user` */

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `role` enum('admin','pimpinan','jamaah') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'jamaah',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `user` */

insert  into `user`(`id`,`username`,`email`,`nama`,`password`,`role`,`status`,`created_at`,`updated_at`) values 
(3,'admin','admin@gmail.com','Administrator','$2y$10$250aobyC8TZb8RJdS3M15ObFWNoLcqIftCVKH0atvHGHfbnxu5fLu','admin',1,'2025-07-09 10:52:38','2025-07-09 10:52:38'),
(4,'pimpinan','pimpinan@gmail.com','Pimpinan Alfadani','$2y$10$nUDacARFTTNiMgv699BcTep6ikUSZWHkrgzmdAonJlD4YcmUcPEkO','pimpinan',1,'2025-07-09 10:52:38','2025-07-09 10:52:38'),
(6,'jamaludin680','jamal@gmail.com','','$2y$10$Ijf5f6ZMa.1MfY4joFZTJ.jRjDiQf2u5g5UQZ461fASYLf8IK1Q2K','jamaah',1,NULL,NULL);

/*Table structure for table `user_token` */

DROP TABLE IF EXISTS `user_token`;

CREATE TABLE `user_token` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `expires_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_token_user_id_foreign` (`user_id`),
  CONSTRAINT `user_token_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `user_token` */

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
