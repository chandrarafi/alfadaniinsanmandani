-- Tabel Pendaftaran
CREATE TABLE IF NOT EXISTS `pendaftaran` (
  `idpendaftaran` VARCHAR(20) NOT NULL,
  `iduser` INT NOT NULL,
  `paketid` VARCHAR(20) NOT NULL,
  `tanggaldaftar` DATE NOT NULL,
  `totalbayar` DECIMAL(15,2) NOT NULL,
  `sisabayar` DECIMAL(15,2) NOT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`idpendaftaran`),
  KEY `fk_pendaftaran_user` (`iduser`),
  KEY `fk_pendaftaran_paket` (`paketid`),
  CONSTRAINT `fk_pendaftaran_user` FOREIGN KEY (`iduser`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_pendaftaran_paket` FOREIGN KEY (`paketid`) REFERENCES `paket` (`idpaket`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabel Detail Pendaftaran
CREATE TABLE IF NOT EXISTS `detailpendaftaran` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `idpendaftaran` VARCHAR(20) NOT NULL,
  `jamaahid` VARCHAR(20) NOT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_detail_pendaftaran` (`idpendaftaran`),
  KEY `fk_detail_jamaah` (`jamaahid`),
  CONSTRAINT `fk_detail_pendaftaran` FOREIGN KEY (`idpendaftaran`) REFERENCES `pendaftaran` (`idpendaftaran`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_detail_jamaah` FOREIGN KEY (`jamaahid`) REFERENCES `jamaah` (`idjamaah`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabel Pembayaran
CREATE TABLE IF NOT EXISTS `pembayaran` (
  `idpembayaran` VARCHAR(20) NOT NULL,
  `pendaftaranid` VARCHAR(20) NOT NULL,
  `tanggalbayar` DATE NOT NULL,
  `metodepembayaran` VARCHAR(50) NOT NULL,
  `tipepembayaran` VARCHAR(50) NOT NULL,
  `jumlahbayar` DECIMAL(15,2) NOT NULL,
  `buktibayar` VARCHAR(255) DEFAULT NULL,
  `statuspembayaran` BOOLEAN DEFAULT FALSE,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`idpembayaran`),
  KEY `fk_pembayaran_pendaftaran` (`pendaftaranid`),
  CONSTRAINT `fk_pembayaran_pendaftaran` FOREIGN KEY (`pendaftaranid`) REFERENCES `pendaftaran` (`idpendaftaran`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4; 