-- Membuat tabel jamaah
CREATE TABLE IF NOT EXISTS `jamaah` (
  `idjamaah` VARCHAR(20) NOT NULL,
  `userid` INT NULL,
  `nik` VARCHAR(16) NOT NULL,
  `namajamaah` VARCHAR(100) NOT NULL,
  `jenkel` ENUM('L', 'P') NOT NULL,
  `alamat` TEXT NULL,
  `emailjamaah` VARCHAR(100) NULL,
  `nohpjamaah` VARCHAR(15) NULL,
  `status` BOOLEAN NOT NULL DEFAULT TRUE,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  PRIMARY KEY (`idjamaah`),
  UNIQUE INDEX `nik_UNIQUE` (`nik` ASC),
  UNIQUE INDEX `emailjamaah_UNIQUE` (`emailjamaah` ASC),
  INDEX `fk_jamaah_user_idx` (`userid` ASC),
  CONSTRAINT `fk_jamaah_user`
    FOREIGN KEY (`userid`)
    REFERENCES `user` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE
);

-- Contoh data untuk tabel jamaah
INSERT INTO `jamaah` (`idjamaah`, `userid`, `nik`, `namajamaah`, `jenkel`, `alamat`, `emailjamaah`, `nohpjamaah`, `status`, `created_at`, `updated_at`) VALUES
('JMH001', NULL, '1234567890123456', 'Ahmad Fauzi', 'L', 'Jl. Merdeka No. 123, Jakarta', 'ahmad@example.com', '081234567890', TRUE, NOW(), NOW()),
('JMH002', NULL, '2345678901234567', 'Siti Aminah', 'P', 'Jl. Pahlawan No. 45, Bandung', 'siti@example.com', '082345678901', TRUE, NOW(), NOW()),
('JMH003', NULL, '3456789012345678', 'Muhammad Rizki', 'L', 'Jl. Sudirman No. 78, Surabaya', 'rizki@example.com', '083456789012', TRUE, NOW(), NOW()); 