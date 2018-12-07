-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versi server:                 10.1.30-MariaDB - mariadb.org binary distribution
-- OS Server:                    Win32
-- HeidiSQL Versi:               9.5.0.5196
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- membuang struktur untuk table usbn.konfig
CREATE TABLE IF NOT EXISTS `konfig` (
  `konfig_id` varchar(20) NOT NULL,
  `nama_konfig` varchar(100) DEFAULT NULL,
  `nilai_konfig` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`konfig_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Membuang data untuk tabel usbn.konfig: ~6 rows (lebih kurang)
/*!40000 ALTER TABLE `konfig` DISABLE KEYS */;
INSERT INTO `konfig` (`konfig_id`, `nama_konfig`, `nilai_konfig`) VALUES
	('LOGIN_SERVICE', 'Login webservice', 'guru'),
	('LOGO_SEKOLAH', 'Logo Sekolah', 'logo.png'),
	('NAMA_SEKOLAH', 'Nama Sekolah', 'SMP Negeri 1 Probolinggo'),
	('PASS_SERVICE', 'Password webservice', 'rahasia'),
	('THEME_COLOR', 'Warna Theme yang Aktif', 'theme-pink'),
	('TOKEN', 'Token Ujian', 'EQA4K');
/*!40000 ALTER TABLE `konfig` ENABLE KEYS */;

-- membuang struktur untuk table usbn.peserta
CREATE TABLE IF NOT EXISTS `peserta` (
  `ujian_id` char(10) NOT NULL,
  `nis` varchar(20) NOT NULL,
  `login` varchar(50) NOT NULL,
  `password` varchar(50) DEFAULT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `kelas` varchar(20) DEFAULT NULL,
  `server` varchar(50) DEFAULT NULL,
  `sesi` varchar(50) DEFAULT NULL,
  `status` char(1) NOT NULL DEFAULT '0' COMMENT '0 : Belum ujian, 1 : Login tapi belum token, 2 : Sedang ujian, 3 : Selesai ujian',
  `last_login` datetime DEFAULT NULL,
  PRIMARY KEY (`ujian_id`,`nis`,`login`),
  KEY `last_login` (`last_login`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Membuang data untuk tabel usbn.peserta: ~2.125 rows (lebih kurang)
/*!40000 ALTER TABLE `peserta` DISABLE KEYS */;
/*!40000 ALTER TABLE `peserta` ENABLE KEYS */;

-- membuang struktur untuk table usbn.peserta_jawaban
CREATE TABLE IF NOT EXISTS `peserta_jawaban` (
  `ujian_id` char(10) NOT NULL,
  `nis` varchar(20) NOT NULL,
  `login` varchar(50) NOT NULL,
  `no_soal` int(11) NOT NULL,
  `pilihan` char(1) DEFAULT NULL COMMENT 'Jawaban pilihan ganda',
  `essay` longtext COMMENT 'Jawaban essay (pengembangan)',
  `ragu` char(1) DEFAULT '0' COMMENT '0 : tidak ragu, 1 : ragu',
  `pilihan_skor` float DEFAULT '0',
  `essay_skor` float DEFAULT NULL,
  PRIMARY KEY (`ujian_id`,`nis`,`login`,`no_soal`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- Membuang data untuk tabel usbn.peserta_jawaban: ~0 rows (lebih kurang)
/*!40000 ALTER TABLE `peserta_jawaban` DISABLE KEYS */;
/*!40000 ALTER TABLE `peserta_jawaban` ENABLE KEYS */;

-- membuang struktur untuk table usbn.pilihan_jawaban
CREATE TABLE IF NOT EXISTS `pilihan_jawaban` (
  `ujian_id` char(10) NOT NULL,
  `no_soal` int(11) NOT NULL,
  `pilihan_ke` char(1) NOT NULL,
  `konten` text,
  PRIMARY KEY (`ujian_id`,`no_soal`,`pilihan_ke`),
  CONSTRAINT `FK_pilihan_jawaban_ujian` FOREIGN KEY (`ujian_id`) REFERENCES `ujian` (`ujian_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Membuang data untuk tabel usbn.pilihan_jawaban: ~3.480 rows (lebih kurang)
/*!40000 ALTER TABLE `pilihan_jawaban` DISABLE KEYS */;
/*!40000 ALTER TABLE `pilihan_jawaban` ENABLE KEYS */;

-- membuang struktur untuk table usbn.proktor
CREATE TABLE IF NOT EXISTS `proktor` (
  `login` varchar(50) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `password` varchar(100) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`login`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Membuang data untuk tabel usbn.proktor: ~1 rows (lebih kurang)
/*!40000 ALTER TABLE `proktor` DISABLE KEYS */;
INSERT INTO `proktor` (`login`, `nama`, `password`, `email`) VALUES
	('proktor', 'Proktor xxx', 'yBÂ	lF»ÈàÙ\0´', 'zaini1983@gmail.com');
/*!40000 ALTER TABLE `proktor` ENABLE KEYS */;

-- membuang struktur untuk table usbn.soal
CREATE TABLE IF NOT EXISTS `soal` (
  `ujian_id` char(10) NOT NULL,
  `no_soal` int(11) NOT NULL,
  `essay` char(1) NOT NULL DEFAULT '0' COMMENT '1 = Essay, 0 = Pilihan ganda',
  `konten` text,
  `jawaban` text,
  `skor` float DEFAULT NULL,
  PRIMARY KEY (`ujian_id`,`no_soal`),
  CONSTRAINT `FK_soal_ujian` FOREIGN KEY (`ujian_id`) REFERENCES `ujian` (`ujian_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Membuang data untuk tabel usbn.soal: ~980 rows (lebih kurang)
/*!40000 ALTER TABLE `soal` DISABLE KEYS */;
/*!40000 ALTER TABLE `soal` ENABLE KEYS */;

-- membuang struktur untuk table usbn.ujian
CREATE TABLE IF NOT EXISTS `ujian` (
  `ujian_id` char(10) NOT NULL,
  `judul` varchar(100) DEFAULT NULL,
  `jenis_ujian` char(1) NOT NULL DEFAULT '0' COMMENT '0 : UH, 1 : USBN',
  `token` char(5) DEFAULT NULL COMMENT 'Token yang digunakan untuk memulai ujian',
  `status_soal` char(1) DEFAULT '0' COMMENT '0 : Belum ada soal, 1 : Sudah upload, 2 : Terkunci (karena sedang atau sudah dilaksanakan)',
  `tgl_unggah` datetime DEFAULT NULL,
  `mulai` datetime DEFAULT NULL COMMENT 'Tgl/Jam mulai ujian',
  `selesai` datetime DEFAULT NULL COMMENT 'Tgl/Jam selesai ujian',
  `alokasi` int(11) DEFAULT NULL COMMENT 'Alokasi tersedia dalam menit',
  `jml_soal` int(11) DEFAULT NULL,
  `acak` char(1) DEFAULT '1' COMMENT '0 : Tidak acak, 1 : acak soal saja, 2 : acak soal dan jawaban',
  `konten` longtext COMMENT 'Konten ms word dari soal ujian',
  `sync` char(1) DEFAULT '0' COMMENT '0 : Belum sync ke dinas, 1 : Sudah sync dengan dinas',
  PRIMARY KEY (`ujian_id`),
  KEY `tgl_upload` (`tgl_unggah`),
  KEY `tgl_ujian` (`mulai`),
  KEY `status_soal` (`status_soal`),
  KEY `judul` (`judul`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Membuang data untuk tabel usbn.ujian: ~30 rows (lebih kurang)
/*!40000 ALTER TABLE `ujian` DISABLE KEYS */;
/*!40000 ALTER TABLE `ujian` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
