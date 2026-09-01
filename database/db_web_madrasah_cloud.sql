SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
START TRANSACTION;
SET time_zone = '+08:00';

DROP TABLE IF EXISTS `website_profil`;
CREATE TABLE `website_profil` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `judul_profil` varchar(200) DEFAULT NULL,
  `isi_profil` text DEFAULT NULL,
  `visi` text DEFAULT NULL,
  `misi` text DEFAULT NULL,
  `tujuan` text DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `telepon` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `sejarah` text DEFAULT NULL,
  `fasilitas` text DEFAULT NULL,
  `prestasi` text DEFAULT NULL,
  `ekstrakurikuler` text DEFAULT NULL,
  `maps_embed_url` text DEFAULT NULL,
  `whatsapp` varchar(50) DEFAULT NULL,
  `facebook_url` text DEFAULT NULL,
  `instagram_url` text DEFAULT NULL,
  `youtube_url` text DEFAULT NULL,
  `jam_layanan` varchar(150) DEFAULT NULL,
  `nsm` varchar(30) DEFAULT NULL,
  `npsn` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `website_profil` VALUES 
('1', 'Madrasah Digital dan Berkarakter', 'MAN 3 Banjar berkomitmen menghadirkan pendidikan yang memadukan ilmu pengetahuan, nilai keislaman, teknologi, dan pelayanan yang transparan.', 'Terwujudnya madrasah yang unggul dalam prestasi, berakhlak mulia, dan berwawasan teknologi.', 'Menyelenggarakan pendidikan yang berkualitas, membentuk karakter islami, mengembangkan potensi siswa, dan meningkatkan layanan berbasis digital.', 'Meningkatkan mutu pendidikan, pelayanan, prestasi, dan tata kelola madrasah.', 'Jl. A. Yani No.Km. 15.200, Gambut, Kec. Gambut, Kabupaten Banjar, Kalimantan Selatan 70652', '08xxxxxx', 'man.tigabjr@gmail.com', '2026-06-15 08:00:04', 'Sejarah Singkat MAN 3 Banjar\r\nPerjalanan MAN 3 Banjar memiliki sejarah yang cukup panjang dan telah mengalami beberapa kali perubahan status serta nama sebelum akhirnya dikenal dengan nama saat ini. Berikut adalah kronologinya:\r\n\r\n1958 – 1969: Madrasah ini bermula sebagai Yayasan Pendidikan Sinar Harapan. Kegiatan belajar mengajar dimulai pada rentang tahun 1958-1960 di bawah pimpinan H. Hasan sebagai kepala madrasah yang pertama.\r\n\r\n1970 – 1977: Lembaga pendidikan ini berubah nama menjadi PGAN (Pendidikan Guru Agama Negeri).\r\n\r\n1978: Terjadi alih fungsi menjadi MAN Gambut, berdasarkan Surat Keputusan Direktur Jenderal Binbaga Islam Departemen Agama RI Nomor: E.IV/PP.00.6/Kep/17.A/1978.\r\n\r\n1996: Berdasarkan keputusan Kepala Kantor Wilayah Departemen Agama Provinsi Kalimantan Selatan, nama MAN Gambut berubah menjadi MAN 1 Martapura.\r\n\r\n2016 – Sekarang: Melalui Surat Keputusan Menteri Agama Republik Indonesia Nomor 671 tentang Perubahan Nama Madrasah Aliyah Negeri di Provinsi Kalimantan Selatan, MAN 1 Martapura resmi berubah nama menjadi MAN 3 Banjar hingga saat ini.', '', '', '', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4765.802898583569!2d114.67185177497234!3d-3.4137733965607673!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2de69d70e1cc07ed%3A0x56b455113188c315!2sMAN%203%20BANJAR%20%2F%20MAN%201%20MARTAPURA%20GAMBUT!5e1!3m2!1sid!2sid!4v1781481435828!5m2!1sid!2sid', '', '', '', '', 'Senin - Kamis: 07:30 - 15:30 Jumat: 07:30 - 11:30 Sabtu - Minggu: Tutup', '131163030003', '30315526');

DROP TABLE IF EXISTS `website_banner`;
CREATE TABLE `website_banner` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `judul` varchar(180) NOT NULL,
  `subjudul` varchar(180) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `gambar` varchar(255) NOT NULL,
  `button_text` varchar(80) DEFAULT NULL,
  `button_url` varchar(255) DEFAULT NULL,
  `status` enum('Draft','Published') NOT NULL DEFAULT 'Draft',
  `urutan` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_website_banner_status` (`status`),
  KEY `idx_website_banner_urutan` (`urutan`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `website_video`;
CREATE TABLE `website_video` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `judul` varchar(200) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `youtube_url` text NOT NULL,
  `status` enum('Draft','Published') NOT NULL DEFAULT 'Draft',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `website_video` VALUES 
('1', 'SPESIAL MILAD KE-68', 'MILAD MAN 3 BANJAR KE-68', 'https://www.youtube.com/watch?v=cLqGOpwjYP0', 'Published', '2026-05-22 14:18:23', '2026-05-22 14:20:53'),
('2', 'JUM\'AT LITERASI', 'JUM\'AT LITERASI MAN 3 BANJAR', 'https://www.youtube.com/watch?v=N2GNpxWIzsQ', 'Draft', '2026-05-22 14:20:34', '2026-05-22 14:20:38');

DROP TABLE IF EXISTS `website_pamflet`;
CREATE TABLE `website_pamflet` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `judul` varchar(200) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `gambar` varchar(255) NOT NULL,
  `tanggal` date DEFAULT NULL,
  `status` enum('Draft','Published') NOT NULL DEFAULT 'Draft',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `website_pamflet` VALUES 
('1', 'Hari raya', 'Raya', '648792ed4785ab0755ece5f8bbb71899.jpeg', '2026-05-22', 'Published', '2026-05-22 15:53:54', '2026-05-22 15:53:59');

DROP TABLE IF EXISTS `website_galeri`;
CREATE TABLE `website_galeri` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `judul` varchar(200) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `gambar` varchar(255) NOT NULL,
  `tanggal` date DEFAULT NULL,
  `status` enum('Draft','Published') NOT NULL DEFAULT 'Draft',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `website_download`;
CREATE TABLE `website_download` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `judul` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `tanggal` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `website_download` VALUES 
('1', 'Pakta Integritas', '1781344008_pakta-integritas.docx', '', '2026-06-13', '2026-06-13 17:46:48');

DROP TABLE IF EXISTS `berita`;
CREATE TABLE `berita` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `judul` varchar(255) DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `view_count` int(11) NOT NULL DEFAULT 0,
  `last_viewed_at` datetime DEFAULT NULL,
  `kategori` varchar(50) NOT NULL DEFAULT 'Kegiatan',
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `featured_order` int(11) NOT NULL DEFAULT 0,
  `featured_at` datetime DEFAULT NULL,
  `isi` text DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `status_berita` enum('Draft','Published') NOT NULL DEFAULT 'Draft',
  `published_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `poster_gambar` varchar(255) DEFAULT NULL,
  `poster_generated_at` datetime DEFAULT NULL,
  `poster_fit_mode` varchar(20) NOT NULL DEFAULT 'cover',
  `poster_focus` varchar(20) NOT NULL DEFAULT 'center',
  `poster_layout` varchar(20) NOT NULL DEFAULT 'auto',
  PRIMARY KEY (`id`),
  KEY `idx_berita_kategori` (`kategori`),
  KEY `idx_berita_featured` (`is_featured`,`featured_order`,`featured_at`),
  KEY `idx_berita_slug` (`slug`),
  KEY `idx_berita_views` (`view_count`,`last_viewed_at`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `berita` VALUES 
('15', 'Hari Kemerdekaan Indonesia', 'hari-kemerdekaan-indonesia', '2', '2026-06-11 23:58:49', 'Kegiatan', '0', '0', NULL, 'Amuntai (MAN 2 HSU) – MAN 2 HSU kembali menggelar kegiatan tahunan bertajuk SWARNABHASA MAN FEST 2026 yang berlangsung mulai tanggal 10 hingga 18 Juni 2026 di lingkungan MAN 2 HSU. Kegiatan ini menjadi ajang bagi peserta didik untuk menyalurkan bakat, minat, kreativitas, serta semangat sportivitas melalui berbagai perlombaan yang menarik dan edukatif. Rabu (10/06/2026).\r\n\r\nSWARNABHASA MAN FEST 2026 menghadirkan beragam cabang lomba yang diikuti oleh siswa-siswi MAN 2 HSU. Adapun perlombaan yang digelar antara lain Futsal, Voli, Tenis Meja (Ping Pong), Video Kreatif, Mobile Legends, Catur, Tarik Tambang, serta berbagai lomba seni dan keterampilan lainnya. Seluruh rangkaian kegiatan berlangsung meriah dengan antusiasme tinggi dari para peserta maupun warga madrasah.\r\n\r\nKetua panitia kegiatan menyampaikan bahwa SWARNABHASA MAN FEST tidak hanya menjadi sarana kompetisi, tetapi juga wadah untuk mempererat silaturahmi, menumbuhkan jiwa kepemimpinan, kerja sama tim, serta mengembangkan potensi peserta didik di bidang akademik maupun nonakademik.\r\n\r\nPlt. Kepala MAN 2 HSU , Irwan dalam tanggapannya mengapresiasi terselenggaranya kegiatan tersebut. Menurutnya, SWARNABHASA MAN FEST 2026 merupakan salah satu bentuk komitmen madrasah dalam mendukung pengembangan karakter dan potensi peserta didik secara menyeluruh.\r\n\"Kegiatan SWARNABHASA MAN FEST 2026 menjadi momentum yang sangat baik bagi siswa untuk mengekspresikan bakat, kreativitas, dan kemampuan yang mereka miliki. Kami berharap melalui kegiatan ini lahir generasi yang tidak hanya unggul dalam bidang akademik, tetapi juga memiliki karakter yang kuat, sportif, kreatif, dan mampu berkolaborasi dengan baik. Terima kasih kepada seluruh panitia, guru, dan peserta yang telah berpartisipasi sehingga kegiatan ini dapat terlaksana dengan lancar dan meriah.\" Kata Irwan.\r\n\r\nMelalui SWARNABHASA MAN FEST 2026, MAN 2 HSU berharap dapat terus menciptakan lingkungan pendidikan yang aktif, inovatif, dan inspiratif, sekaligus menjadi wadah bagi peserta didik untuk mengembangkan potensi diri serta meraih berbagai prestasi di masa depan.', 'd30125d213a463278f30ba9e1f01e3b4.png', 'Published', '2026-05-24 02:51:05', '2026-05-23 11:50:18', 'pamflet-berita-15-1781219161.jpg', '2026-06-12 01:06:01', 'cover', 'top', 'auto'),
('16', 'Pramuka', 'pramuka', '2', '2026-06-25 08:07:58', 'Kegiatan', '0', '0', NULL, 'Amuntai (MAN 2 HSU) – MAN 2 HSU kembali menggelar kegiatan tahunan bertajuk SWARNABHASA MAN FEST 2026 yang berlangsung mulai tanggal 10 hingga 18 Juni 2026 di lingkungan MAN 2 HSU. Kegiatan ini menjadi ajang bagi peserta didik untuk menyalurkan bakat, minat, kreativitas, serta semangat sportivitas melalui berbagai perlombaan yang menarik dan edukatif. Rabu (10/06/2026).\r\n\r\nSWARNABHASA MAN FEST 2026 menghadirkan beragam cabang lomba yang diikuti oleh siswa-siswi MAN 2 HSU. Adapun perlombaan yang digelar antara lain Futsal, Voli, Tenis Meja (Ping Pong), Video Kreatif, Mobile Legends, Catur, Tarik Tambang, serta berbagai lomba seni dan keterampilan lainnya. Seluruh rangkaian kegiatan berlangsung meriah dengan antusiasme tinggi dari para peserta maupun warga madrasah.\r\n\r\nKetua panitia kegiatan menyampaikan bahwa SWARNABHASA MAN FEST tidak hanya menjadi sarana kompetisi, tetapi juga wadah untuk mempererat silaturahmi, menumbuhkan jiwa kepemimpinan, kerja sama tim, serta mengembangkan potensi peserta didik di bidang akademik maupun nonakademik.\r\n\r\nPlt. Kepala MAN 2 HSU , Irwan dalam tanggapannya mengapresiasi terselenggaranya kegiatan tersebut. Menurutnya, SWARNABHASA MAN FEST 2026 merupakan salah satu bentuk komitmen madrasah dalam mendukung pengembangan karakter dan potensi peserta didik secara menyeluruh.\r\n\"Kegiatan SWARNABHASA MAN FEST 2026 menjadi momentum yang sangat baik bagi siswa untuk mengekspresikan bakat, kreativitas, dan kemampuan yang mereka miliki. Kami berharap melalui kegiatan ini lahir generasi yang tidak hanya unggul dalam bidang akademik, tetapi juga memiliki karakter yang kuat, sportif, kreatif, dan mampu berkolaborasi dengan baik. Terima kasih kepada seluruh panitia, guru, dan peserta yang telah berpartisipasi sehingga kegiatan ini dapat terlaksana dengan lancar dan meriah.\" Kata Irwan.\r\n\r\nMelalui SWARNABHASA MAN FEST 2026, MAN 2 HSU berharap dapat terus menciptakan lingkungan pendidikan yang aktif, inovatif, dan inspiratif, sekaligus menjadi wadah bagi peserta didik untuk mengembangkan potensi diri serta meraih berbagai prestasi di masa depan.', '215d4ffcd0ffe13ee020fc01a8267bdc.jpg', 'Published', '2026-06-12 07:09:15', '2026-05-24 02:59:15', 'pamflet-berita-16-1781219112.jpg', '2026-06-12 01:05:12', 'cover', 'center', 'auto');

DROP TABLE IF EXISTS `berita_gambar`;
CREATE TABLE `berita_gambar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `berita_id` int(11) NOT NULL,
  `gambar` varchar(255) NOT NULL,
  `urutan` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `berita_id` (`berita_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `berita_gambar` VALUES 
('7', '15', 'f25f55d663a7310ed564eb7b674230cb.jpg', '1', '2026-05-23 11:50:19'),
('8', '15', '8ea6daa65ffb1b3173f19fbf83073642.jpg', '2', '2026-05-23 11:50:19'),
('9', '16', 'a1c356c6b3b6294a15b1a91746ccaa51.jpg', '1', '2026-05-24 02:59:16'),
('10', '16', 'c5ceadfadd7cf7c069d809c9f909bdca.jpg', '2', '2026-05-24 02:59:16'),
('11', '16', '609ca2e16393650646f8843d97c7efa4.png', '3', '2026-05-24 02:59:16'),
('12', '16', '2ae548504426a8493a6ab9f11bcabf70.jpg', '4', '2026-05-24 02:59:16'),
('13', '16', '0a8ea3512f2a8b0b3f07cfb6cd5ce044.jpg', '5', '2026-05-24 02:59:16'),
('14', '16', 'e7579311fddd67192bd5620321e28be8.jpg', '6', '2026-05-24 02:59:16'),
('15', '16', '6cab6d6ff8c9ce964759bc5afc354ed8.jpg', '7', '2026-05-24 02:59:16');

DROP TABLE IF EXISTS `ptk`;
CREATE TABLE `ptk` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `jenis_ptk` enum('Pendidik','Kependidikan') DEFAULT 'Pendidik',
  `nama_lengkap` varchar(150) DEFAULT NULL,
  `nuptk` varchar(30) DEFAULT NULL,
  `nik` varchar(30) DEFAULT NULL,
  `no_kk` varchar(50) DEFAULT NULL,
  `nama_ibu_kandung` varchar(150) DEFAULT NULL,
  `nip` varchar(30) DEFAULT NULL,
  `niy` varchar(30) DEFAULT NULL,
  `tempat_lahir` varchar(100) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `jenis_kelamin` enum('L','P') DEFAULT NULL,
  `agama` varchar(30) DEFAULT NULL,
  `status_perkawinan` varchar(50) DEFAULT NULL,
  `nama_pasangan` varchar(150) DEFAULT NULL,
  `jumlah_anak` int(11) DEFAULT NULL,
  `status_kepegawaian` varchar(50) DEFAULT NULL,
  `pangkat_golongan` varchar(80) DEFAULT NULL,
  `tmt_pangkat` date DEFAULT NULL,
  `masa_kerja_tahun` int(11) DEFAULT NULL,
  `masa_kerja_bulan` int(11) DEFAULT NULL,
  `no_sk_cpns` varchar(100) DEFAULT NULL,
  `tanggal_sk_cpns` date DEFAULT NULL,
  `tmt_cpns` date DEFAULT NULL,
  `no_sk_pns` varchar(100) DEFAULT NULL,
  `tanggal_sk_pns` date DEFAULT NULL,
  `tmt_pns` date DEFAULT NULL,
  `no_sk_pengangkatan` varchar(100) DEFAULT NULL,
  `tanggal_sk_pengangkatan` date DEFAULT NULL,
  `lembaga_pengangkat` varchar(150) DEFAULT NULL,
  `jenis_kepegawaian` varchar(50) DEFAULT NULL,
  `pendidikan_terakhir` varchar(50) DEFAULT NULL,
  `jurusan_pendidikan` varchar(100) DEFAULT NULL,
  `tahun_lulus` varchar(10) DEFAULT NULL,
  `jabatan` varchar(100) DEFAULT NULL,
  `tugas_utama` varchar(150) DEFAULT NULL,
  `tugas_tambahan` varchar(150) DEFAULT NULL,
  `mapel_utama` varchar(100) DEFAULT NULL,
  `sertifikasi` enum('Ya','Tidak') DEFAULT 'Tidak',
  `no_sertifikat_pendidik` varchar(100) DEFAULT NULL,
  `nrg` varchar(50) DEFAULT NULL,
  `mapel_nrg` varchar(150) DEFAULT NULL,
  `kurikulum_sertifikasi` varchar(255) DEFAULT NULL,
  `no_peserta_sertifikasi` varchar(100) DEFAULT NULL,
  `tgl_piagam_sertifikasi` date DEFAULT NULL,
  `lptk_penyelenggara` varchar(150) DEFAULT NULL,
  `jalur_sertifikasi` varchar(150) DEFAULT NULL,
  `dokumen_sertifikasi` varchar(255) DEFAULT NULL,
  `tmt_masuk` date DEFAULT NULL,
  `tmt_tugas` date DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `rt` varchar(10) DEFAULT NULL,
  `rw` varchar(10) DEFAULT NULL,
  `desa` varchar(100) DEFAULT NULL,
  `kecamatan` varchar(100) DEFAULT NULL,
  `kabupaten` varchar(100) DEFAULT NULL,
  `provinsi` varchar(100) DEFAULT NULL,
  `kode_pos` varchar(10) DEFAULT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `npwp` varchar(50) DEFAULT NULL,
  `no_rekening` varchar(80) DEFAULT NULL,
  `nama_bank` varchar(100) DEFAULT NULL,
  `atas_nama_rekening` varchar(150) DEFAULT NULL,
  `status_aktif` enum('Aktif','Nonaktif','Pensiun','Mutasi') DEFAULT 'Aktif',
  `foto` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_id` int(11) DEFAULT NULL,
  `tampil_website` tinyint(1) NOT NULL DEFAULT 1,
  `urutan_website` int(11) NOT NULL DEFAULT 0,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_ptk_username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=65 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `ptk` VALUES 
('4', 'Pendidik', 'Nor Ifansyah, S.Pd., M.Sc', '', '', '', '', '197103152001121001', '', '', '0000-00-00', 'L', 'Islam', '', '', '0', 'PNS', 'Pembina / IV/a', '0000-00-00', '0', '0', '', '0000-00-00', '0000-00-00', '', '0000-00-00', '0000-00-00', '', '0000-00-00', '', '', 'SMA/MA', '', '', 'Kepala Madrasah', '', 'KAMAD', '', 'Ya', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0000-00-00', '0000-00-00', '', '', '', '', '', '', '', '', NULL, '', '', '', '', '', 'Aktif', NULL, '2026-05-25 10:57:59', NULL, '1', '0', '197103152001121001', 'aa49b847ddd7bacb1def3146eaad6047'),
('5', 'Pendidik', 'Said Wajidi, S. Pd, M. Pfis', '', '', NULL, NULL, '197409032000121001', NULL, '', '0000-00-00', 'L', 'Islam', NULL, NULL, NULL, 'PNS', 'Pembina / IV/a', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Guru Madya', NULL, 'Wakamad Kesiswaan', 'Fisika (Pilihan)', 'Ya', NULL, NULL, 'Fisika', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '+62 858-4931-4240', '', NULL, NULL, NULL, NULL, 'Aktif', NULL, '2026-05-25 10:57:59', NULL, '1', '0', '197409032000121001', 'e10adc3949ba59abbe56e057f20f883e'),
('6', 'Pendidik', 'Drs. Hermansyah', '', '', '', '', '196701081994031004', '', '', '0000-00-00', 'L', 'Islam', '', '', '0', 'PNS', 'Pembina / IV/a', '0000-00-00', '0', '0', '', '0000-00-00', '0000-00-00', '', '0000-00-00', '0000-00-00', '', '0000-00-00', '', '', 'S1', '', '', 'Guru Madya', '', 'Koordinator Kookurikuler Kelas XIIC,XIID dan XII E', 'Matematika (Umum)', 'Ya', '', NULL, 'Matematika', NULL, NULL, NULL, NULL, NULL, NULL, '0000-00-00', '0000-00-00', '', '', '', '', '', '', '', '', '+62 821-5396-0028', '', '', '', '', '', 'Aktif', NULL, '2026-05-25 10:57:59', NULL, '1', '0', '196701081994031004', '5a675f4499da95fc0b1c38c758f32027'),
('7', 'Pendidik', 'Ramlah, S. Ag', '', '', NULL, NULL, '196810131996032001', NULL, '', '0000-00-00', 'P', 'Islam', NULL, NULL, NULL, 'PNS', 'Pembina / IV/a', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Guru Madya', NULL, 'Walikelas XII D', 'Fikih (Umum), Usul Fiqih (Pilihan)', 'Ya', NULL, NULL, 'Fikih', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '+62 852-4888-1405', '', NULL, NULL, NULL, NULL, 'Aktif', NULL, '2026-05-25 10:57:59', NULL, '1', '0', '196810131996032001', 'b4f4097a3a5cc637555a872fa018bbfd'),
('8', 'Pendidik', 'Saidah, S. Pd', '', '', NULL, NULL, '197009151995032001', NULL, '', '0000-00-00', 'P', 'Islam', NULL, NULL, NULL, 'PNS', 'Pembina / IV/a', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Guru Madya', NULL, 'Koordinator Kookurikuler XA', 'Biologi (Umum), Biologi (Pilihan)', 'Ya', NULL, NULL, 'Biologi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '+62 813-5124-0242', '', NULL, NULL, NULL, NULL, 'Aktif', NULL, '2026-05-25 10:57:59', NULL, '1', '0', '197009151995032001', 'd54ebbce0f48712a40d58172f640ce33'),
('9', 'Pendidik', 'Nurbariyah, S. Pd, M. Si', '', '', NULL, NULL, '197011191998032002', NULL, '', '0000-00-00', 'P', 'Islam', NULL, NULL, NULL, 'PNS', 'Pembina / IV/a', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Guru Madya', NULL, '', 'Kimia (Umum), Prakarya (Umum)', 'Ya', NULL, NULL, 'Kimia', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '+62 813-4867-1218', '', NULL, NULL, NULL, NULL, 'Aktif', NULL, '2026-05-25 10:57:59', NULL, '1', '0', '197011191998032002', 'b1ba49f90d30bdcd3807a8835837779b'),
('10', 'Pendidik', 'Megawati, S. Pd', '', '', NULL, NULL, '197305201999032001', NULL, '', '0000-00-00', 'P', 'Islam', NULL, NULL, NULL, 'PNS', 'Pembina / IV/a', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Guru Madya', NULL, 'Wakamad Sarana Prasarana', '', 'Ya', NULL, NULL, 'Ekonomi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '+62 852-4868-0277', '', NULL, NULL, NULL, NULL, 'Aktif', NULL, '2026-05-25 10:58:00', NULL, '1', '0', '197305201999032001', '635e76bcc310cbaa2971a276b66bcc9e'),
('11', 'Pendidik', 'Noorlaily, S. Pd', '', '', NULL, NULL, '197306131999032001', NULL, '', '0000-00-00', 'P', 'Islam', NULL, NULL, NULL, 'PNS', 'Pembina / IV/a', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Guru Madya', NULL, 'Walikelas X B', '', 'Ya', NULL, NULL, 'Matematika', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '+62 821-5262-1373', '', NULL, NULL, NULL, NULL, 'Aktif', NULL, '2026-05-25 10:58:00', NULL, '1', '0', '197306131999032001', 'd2a148c7d75c9c41d19e0eedd1e5beb2'),
('12', 'Pendidik', 'Naimah, S. Pd', '', '', NULL, NULL, '197508092005012006', NULL, '', '0000-00-00', 'P', 'Islam', NULL, NULL, NULL, 'PNS', 'Pembina / IV/a', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Guru Madya', NULL, 'Waka Bid. Kurikulum', 'Kimia (Pilihan)', 'Ya', NULL, NULL, 'Kimia', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '+62 812-5324-9901', '', NULL, NULL, NULL, NULL, 'Aktif', NULL, '2026-05-25 10:58:00', NULL, '1', '0', '197508092005012006', '39ff0b5ec60946447362cfc3cdcea03d'),
('13', 'Pendidik', 'Hj. Tumnah, S. Pd. I', '', '', NULL, NULL, '197612122005012006', NULL, '', '0000-00-00', 'P', 'Islam', NULL, NULL, NULL, 'PNS', 'Pembina / IV/a', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Guru Madya', NULL, '', '', 'Ya', NULL, NULL, 'Bahasa Inggris', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '+62 857-8760-3995', '', NULL, NULL, NULL, NULL, 'Aktif', NULL, '2026-05-25 10:58:00', NULL, '1', '0', '197612122005012006', '67ac81673f2d90c3f3c09a17d2b97e3c'),
('14', 'Pendidik', 'Afwah, S. Pd', '', '', '', '', '197612252005012017', '', '', '0000-00-00', 'P', 'Islam', '', '', '0', 'PNS', 'Pembina / IV/a', '0000-00-00', '0', '0', '', '0000-00-00', '0000-00-00', '', '0000-00-00', '0000-00-00', '', '0000-00-00', '', '', 'SMA/MA', '', '', 'Guru Madya', '', 'Walikelas XII B', '', 'Ya', '', NULL, 'Bahasa Indonesia', NULL, NULL, NULL, NULL, NULL, NULL, '0000-00-00', '0000-00-00', '', '', '', '', '', '', '', '', '+62 857-8626-8374', '', '', '', '', '', 'Aktif', NULL, '2026-05-25 10:58:00', NULL, '1', '0', '197612252005012017', '014c0417569f4aa94eceb8aab59f8611'),
('15', 'Kependidikan', 'Haris Padilah, S.Pd', '', '', NULL, NULL, '197504051998031002', NULL, '', '0000-00-00', 'L', 'Islam', NULL, NULL, NULL, 'PNS', 'Penata Tk. I / III/d', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Kepala TU', NULL, '', '', 'Tidak', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, 'Aktif', NULL, '2026-05-25 10:58:00', NULL, '1', '0', '197504051998031002', '6b8c6ada6fcb81bdb1610065fb1a0b10'),
('16', 'Pendidik', 'Rusmaniah, S. Ag', '', '', NULL, NULL, '197101092006042003', NULL, '', '0000-00-00', 'P', 'Islam', NULL, NULL, NULL, 'PNS', 'Penata Tk. I / III/d', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Guru Muda', NULL, 'Walikelas XE', '', 'Ya', NULL, NULL, 'Bahasa Arab', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '+62 877-4271-8094', '', NULL, NULL, NULL, NULL, 'Aktif', NULL, '2026-05-25 10:58:00', NULL, '1', '0', '197101092006042003', '0fdcee635f6c6beafa3d7a845c66cb9b'),
('17', 'Pendidik', 'Aulia Azazi Rahmah, S. Ag', '', '', NULL, NULL, '197308032005012006', NULL, '', '0000-00-00', 'P', 'Islam', NULL, NULL, NULL, 'PNS', 'Penata Tk. I / III/d', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Guru Muda', NULL, 'Walikelas XII C', 'Fikih (Umum), Usul Fiqih (Pilihan)', 'Ya', NULL, NULL, 'Fikih', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '+62 852-4823-3182', '', NULL, NULL, NULL, NULL, 'Aktif', NULL, '2026-05-25 10:58:01', NULL, '1', '0', '197308032005012006', 'bf9ce0b1859c85df5ee0530e609c6a58'),
('18', 'Kependidikan', 'Fifrian Irma, S.Pd', '', '', NULL, NULL, '197505192002122006', NULL, '', '0000-00-00', 'P', 'Islam', NULL, NULL, NULL, 'PNS', 'Penata Tk. I / III/d', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Pelaksana TU', NULL, '', '', 'Tidak', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '+62 821-4891-4840', '', NULL, NULL, NULL, NULL, 'Aktif', NULL, '2026-05-25 10:58:01', NULL, '1', '0', '197505192002122006', '5bc9b4b4c0badc39c461e50e5f36c3f4'),
('19', 'Pendidik', 'Hj. Hasnah, S. Pd. I', '', '', NULL, NULL, '197805132006042012', NULL, '', '0000-00-00', 'P', 'Islam', NULL, NULL, NULL, 'PNS', 'Pembina / IV/a', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Guru Muda', NULL, '', '', 'Ya', NULL, NULL, 'Bahasa Inggris', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '+62 821-5771-1563', '', NULL, NULL, NULL, NULL, 'Aktif', NULL, '2026-05-25 10:58:01', NULL, '1', '0', '197805132006042012', '1e49725cd3a001604f29f748b4fa4269'),
('20', 'Pendidik', 'Hendra Andrian, S.Pd', '', '', NULL, NULL, '197910012005011003', NULL, '', '0000-00-00', 'L', 'Islam', NULL, NULL, NULL, 'PNS', 'Penata Tk. I / III/d', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Guru Muda', NULL, 'Walikelas XI B', '', 'Ya', NULL, NULL, 'Bahasa Indonesia', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '+62 823-1115-8882', '', NULL, NULL, NULL, NULL, 'Aktif', NULL, '2026-05-25 10:58:01', NULL, '1', '0', '197910012005011003', '12f5ec18af6b65118eca7ba6ff414bed'),
('21', 'Pendidik', 'Dewi Sulistiani, S.Sos.I', '', '', NULL, NULL, '198311282011012012', NULL, '', '0000-00-00', 'P', 'Islam', NULL, NULL, NULL, 'PNS', 'Penata Tk. I / III/d', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Guru Muda', NULL, 'Binaan Kelas X', 'BK', 'Ya', NULL, NULL, 'BK', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '+62 852-4842-8338', '', NULL, NULL, NULL, NULL, 'Aktif', NULL, '2026-05-25 10:58:01', NULL, '1', '0', '198311282011012012', 'e10adc3949ba59abbe56e057f20f883e'),
('22', 'Pendidik', 'Nur Anisah, S. Pd. I', '', '', NULL, NULL, '198005292014112001', NULL, '', '0000-00-00', 'P', 'Islam', NULL, NULL, NULL, 'PNS', 'Penata Muda Tk.I / III/b', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Guru Pertama', NULL, 'Wakamad HUMAS', '', 'Ya', NULL, NULL, 'Bahasa Arab', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '+62 852-4949-7871', '', NULL, NULL, NULL, NULL, 'Aktif', NULL, '2026-05-25 10:58:01', NULL, '1', '0', '198005292014112001', '7b65b74b29ffa0e8f72550d5339c9115'),
('23', 'Pendidik', 'Untung Rakhmat Wijaya, Lc, M.H', '', '', NULL, NULL, '198310142019031006', NULL, '', '0000-00-00', 'L', 'Islam', NULL, NULL, NULL, 'PNS', 'Penata Muda Tk.I / III/b', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Guru Pertama', NULL, 'Koordinator Keagamaan', '', 'Ya', NULL, NULL, 'Qur\'an Hadist', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '+62 812-5704-8484', '', NULL, NULL, NULL, NULL, 'Aktif', NULL, '2026-05-25 10:58:02', NULL, '1', '0', '198310142019031006', '704731c227a2834f2437f89ae5612020'),
('24', 'Pendidik', 'Hasan, S.Pd.I', '', '', NULL, NULL, '198506042019031006', NULL, '', '0000-00-00', 'L', 'Islam', NULL, NULL, NULL, 'PNS', 'Penata Muda Tk.I / III/b', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Guru Pertama', NULL, 'Kepala Lab Bahasa', '', 'Ya', NULL, NULL, 'Bahasa Arab', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '+62 852-4854-4431', '', NULL, NULL, NULL, NULL, 'Aktif', NULL, '2026-05-25 10:58:02', NULL, '1', '0', '198506042019031006', '14f60319ff07ffd35c3216ccc6184cb2'),
('25', 'Pendidik', 'Diah Wardani, S.Pd.', '', '', NULL, NULL, '198712062019032016', NULL, '', '0000-00-00', 'P', 'Islam', NULL, NULL, NULL, 'PNS', 'Penata Muda Tk.I / III/b', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Guru Pertama', NULL, 'Kepala Perpustakaan', '', 'Ya', NULL, NULL, 'Bahasa Indonesia', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '+62 853-8994-7034', '', NULL, NULL, NULL, NULL, 'Aktif', NULL, '2026-05-25 10:58:02', NULL, '1', '0', '198712062019032016', '396cbe9220a0a060708416826f8b2814'),
('26', 'Pendidik', 'Perdini Adma Sari, S.Pd', '', '', NULL, NULL, '199104122019032025', NULL, '', '0000-00-00', 'P', 'Islam', NULL, NULL, NULL, 'PNS', 'Penata Muda Tk.I / III/b', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Guru Pertama', NULL, 'Walikelas XII E', 'Ekonomi (Umum), Prakarya (Umum), Ekonomi (Pilihan)', 'Ya', NULL, NULL, 'Ekonomi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '+62 853-4859-4434', '', NULL, NULL, NULL, NULL, 'Aktif', NULL, '2026-05-25 10:58:02', NULL, '1', '0', '199104122019032025', '4d4a1a2800075014a5171934e23aaf94'),
('27', 'Pendidik', 'Muhammad Al Hafidz, S.Pd.I', '', '', NULL, NULL, '199201142019031003', NULL, '', '0000-00-00', 'L', 'Islam', NULL, NULL, NULL, 'PNS', 'Penata Muda Tk.I / III/b', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Guru Pertama', NULL, 'Koordinator Kookurikuler Kelas XIC Dan XI D', '', 'Ya', NULL, NULL, 'SKI', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '+62 857-5434-3925', '', NULL, NULL, NULL, NULL, 'Aktif', NULL, '2026-05-25 10:58:02', NULL, '1', '0', '199201142019031003', '87b666895d088b5396a515b0b1c2c863'),
('28', 'Pendidik', 'Raihanah, S. Pd', '', '', NULL, NULL, '199208162019032016', NULL, '', '0000-00-00', 'P', 'Islam', NULL, NULL, NULL, 'PNS', 'Penata Muda Tk.I / III/b', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Guru Pertama', NULL, 'Walikelas X A/ Koordinator 5 K', '', 'Ya', NULL, NULL, 'Bahasa Arab', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '+62 878-1577-7610', '', NULL, NULL, NULL, NULL, 'Aktif', NULL, '2026-05-25 10:58:02', NULL, '1', '0', '199208162019032016', 'e8be8269e57dfc5626bd9e5b862a1360'),
('29', 'Pendidik', 'Agustina Aulia, S. Pd', '', '', NULL, NULL, '199408042019032015', NULL, '', '0000-00-00', 'P', 'Islam', NULL, NULL, NULL, 'PNS', 'Penata Muda Tk.I / III/b', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Guru Pertama', NULL, 'Walikelas XI A', 'Matematika (Umum), Informatika (Umum), Matematika Tingkat Lanjut (Pilihan)', 'Ya', NULL, NULL, 'Matematika', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '+62 859-3951-2669', '', NULL, NULL, NULL, NULL, 'Aktif', NULL, '2026-05-25 10:58:02', NULL, '1', '0', '199408042019032015', '162c73a08323cc8424a8f1c2cfe4907a'),
('30', 'Pendidik', 'Maimunah, S.H', '', '', NULL, NULL, '199409252019032018', NULL, '', '0000-00-00', 'P', 'Islam', NULL, NULL, NULL, 'PNS', 'Penata Muda Tk.I / III/b', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Guru Pertama', NULL, 'Walikelas X D', '', 'Ya', NULL, NULL, 'Fikih', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '+62 822-5498-8157', '', NULL, NULL, NULL, NULL, 'Aktif', NULL, '2026-05-25 10:58:02', NULL, '1', '0', '199409252019032018', '0bc3905ceae617b0a554b84bf9ca787c'),
('31', 'Pendidik', 'Muhammad Irfan Alfian Noor, S. Pd', '', '', NULL, NULL, '199511092019031009', NULL, '', '0000-00-00', 'L', 'Islam', NULL, NULL, NULL, 'PNS', 'Penata Muda Tk.I / III/b', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Guru Pertama', NULL, 'Pembina Ekskul Pramuka Putra', 'Geografi (Umum), Geografi (Pilihan)', 'Ya', NULL, NULL, 'Geografi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '+62 812-5871-3197', '', NULL, NULL, NULL, NULL, 'Aktif', NULL, '2026-05-25 10:58:03', NULL, '1', '0', '199511092019031009', 'a9eed5bc5261302e31d144c56729cb51'),
('32', 'Pendidik', 'Dina Ratnasari, S. Si', '', '', NULL, NULL, '199702042019032006', NULL, '', '0000-00-00', 'P', 'Islam', NULL, NULL, NULL, 'PNS', 'Penata Muda Tk.I / III/b', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Guru Pertama', NULL, 'Koordinator Kookurikuler XI A, XI B dan XII A', 'Matematika (Umum), Matematika Tingkat Lanjut (Pilihan)', 'Ya', NULL, NULL, 'Matematika', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '+62 821-5009-3247', '', NULL, NULL, NULL, NULL, 'Aktif', NULL, '2026-05-25 10:58:03', NULL, '1', '0', '199702042019032006', 'ea4f2f4f12de6fad9dbe0f8d41d6c7d5'),
('33', 'Pendidik', 'M. Rifqi Rahman, S. Pd.', '', '', NULL, NULL, '198906302020121010', NULL, '', '0000-00-00', 'L', 'Islam', NULL, NULL, NULL, 'PNS', 'Penata Muda / III/b', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Guru Pertama', NULL, 'Menambah Jam Di MAN 4 Banjar', '', 'Ya', NULL, NULL, 'Penjaskes', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '+62 882-4522-9172', '', NULL, NULL, NULL, NULL, 'Aktif', NULL, '2026-05-25 10:58:03', NULL, '1', '0', '198906302020121010', '1168a6b1afdba8cedf69c20942ead68b'),
('34', 'Pendidik', 'Hamdani, S.Pd.', '', '', NULL, NULL, '199302202020121014', NULL, '', '0000-00-00', 'L', 'Islam', NULL, NULL, NULL, 'PNS', 'Penata Muda / III/b', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Guru Pertama', NULL, 'Waklikelas XII A', '', 'Ya', NULL, NULL, 'Penjaskes', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '+62 812-5094-8313', '', NULL, NULL, NULL, NULL, 'Aktif', NULL, '2026-05-25 10:58:03', NULL, '1', '0', '199302202020121014', '70421b2a51011772744451b93924ccb6'),
('35', 'Pendidik', 'Muhammad Syamsul Arifin. S.Pd.I', '', '', NULL, NULL, '198701312019031009', NULL, '', '0000-00-00', 'L', 'Islam', NULL, NULL, NULL, 'PNS', 'Penata Muda / III/a', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Guru Pertama', NULL, 'Kepala Lab Komputer/ Pembina Ekskul Habsy', '', 'Ya', NULL, NULL, 'Akidah Akhlak', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '+62 852-4857-3261', '', NULL, NULL, NULL, NULL, 'Aktif', NULL, '2026-05-25 10:58:03', NULL, '1', '0', '198701312019031009', '7bb7c90e0d011dee40435854da59804b'),
('36', 'Pendidik', 'Muhammad Tanthawi Jauhari, S.Th.I', '', '', NULL, NULL, '198909242019031014', NULL, '', '0000-00-00', 'L', 'Islam', NULL, NULL, NULL, 'PNS', 'Penata Muda / III/a', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Guru Pertama', NULL, 'Walikelas XI D', '', 'Ya', NULL, NULL, 'Qur\'an Hadist', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '+62 821-5492-6144', '', NULL, NULL, NULL, NULL, 'Aktif', NULL, '2026-05-25 10:58:04', NULL, '1', '0', '198909242019031014', 'ce67eede5f703dabc03c859c283f330d'),
('37', 'Pendidik', 'SRI MAULIDA KHAIRIYAH, S.Pd.I, M.Pd', '', '', NULL, NULL, '199209262019032027', NULL, '', '0000-00-00', 'P', 'Islam', NULL, NULL, NULL, 'PNS', 'Penata Muda / III/a', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Guru Pertama', NULL, 'Walikelas XIC', 'Akidah Akhlak (Umum)', 'Ya', NULL, NULL, 'Akidah Akhlak', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '+62 852-5180-1067', '', NULL, NULL, NULL, NULL, 'Aktif', NULL, '2026-05-25 10:58:04', NULL, '1', '0', '199209262019032027', 'df1b3df2b31365ac06e84f19264939ee'),
('38', 'Pendidik', 'Muhammad Abi Saleh, S.Pd.', '', '', NULL, NULL, '199812192025051005', NULL, '', '0000-00-00', 'L', 'Islam', NULL, NULL, NULL, 'PNS', 'Penata Muda / III/a', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Guru Pertama', NULL, 'Walikelas XC', '', 'Ya', NULL, NULL, 'Qur\'an Hadist', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '+62 821-5420-9725', '', NULL, NULL, NULL, NULL, 'Aktif', NULL, '2026-05-25 10:58:04', NULL, '1', '0', '199812192025051005', '3a4bc470d4deb9332d42e5fa6524a121'),
('39', 'Pendidik', 'Hadiannor, S, Pd. I', '', '', NULL, NULL, '198106042023211014', NULL, '', '0000-00-00', 'L', 'Islam', NULL, NULL, NULL, 'PPPK', 'Penata Muda / IX', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Ahli pertama', NULL, 'Pembina OSIM', '', 'Ya', NULL, NULL, 'Bahasa Arab', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '+62 887-0477-5687', '', NULL, NULL, NULL, NULL, 'Aktif', NULL, '2026-05-25 10:58:04', NULL, '1', '0', '198106042023211014', '1c2053c034a4b7afb86ff86918bf69ea'),
('40', 'Pendidik', 'Ahmad Robianto, S.Pd', '', '', NULL, NULL, '199310162023211012', NULL, '', '0000-00-00', 'L', 'Islam', NULL, NULL, NULL, 'PPPK', 'Penata Muda / IX', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Ahli Pertama', NULL, 'Koordinator Kookurikuler Kelas XIE Dan XI F', '', 'Ya', NULL, NULL, 'Sejarah', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '+62 823-5021-1616', '', NULL, NULL, NULL, NULL, 'Aktif', NULL, '2026-05-25 10:58:04', NULL, '1', '0', '199310162023211012', '02ef5cc51d71620b416603c71915a692'),
('41', 'Pendidik', 'Saliah, S. Pd.', '', '', NULL, NULL, '199010172023212031', NULL, '', '0000-00-00', 'P', 'Islam', NULL, NULL, NULL, 'PPPK', 'Penata Muda / IX', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Ahli Pertama', NULL, 'Binaan Kelas XI', 'BK', 'Ya', NULL, NULL, 'BK', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '+62 815-2284-4849', '', NULL, NULL, NULL, NULL, 'Aktif', NULL, '2026-05-25 10:58:04', NULL, '1', '0', '199010172023212031', 'c28d0fcf1cfc97f071b778f9970e9d90'),
('42', 'Pendidik', 'M. Agus Salim, S. Pd. I, M. H', '', '', NULL, NULL, '199108172023211029', NULL, '', '0000-00-00', 'L', 'Islam', NULL, NULL, NULL, 'PPPK', 'Penata Muda / IX', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Ahli Pertama', NULL, 'Walikelas XI F/ Pembina Ekskul Paskib', '', 'Ya', NULL, NULL, 'Akidah Akhlak', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '+62 857-5242-7506', '', NULL, NULL, NULL, NULL, 'Aktif', NULL, '2026-05-25 10:58:05', NULL, '1', '0', '199108172023211029', '4497dda815fd9c6066acf0f71445f949'),
('43', 'Pendidik', 'Rizki Handa Firdaus, S. Pd', '', '', NULL, NULL, '199206092025211015', NULL, '', '0000-00-00', 'L', 'Islam', NULL, NULL, NULL, 'PPPK', 'Penata Muda / IX', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Ahli Pertama', NULL, 'Pembina Ekskul Futsal', '', 'Tidak', NULL, NULL, 'Seni Budaya', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '+62 856-5227-8938', '', NULL, NULL, NULL, NULL, 'Aktif', NULL, '2026-05-25 10:58:05', NULL, '1', '0', '199206092025211015', '8bde830b51036b201f9062d821dbd3db'),
('44', 'Pendidik', 'Ahmad Riadi, S. Pd', '', '', NULL, NULL, '198102262025211006', NULL, '', '0000-00-00', 'L', 'Islam', NULL, NULL, NULL, 'PPPK', 'Penata Muda / IX', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Ahli Pertama', NULL, 'Kepala Lab IPA', '', 'Ya', NULL, NULL, 'Biologi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '+62 815-2198-0211', '', NULL, NULL, NULL, NULL, 'Aktif', NULL, '2026-05-25 10:58:05', NULL, '1', '0', '198102262025211006', 'b133c2d7cd2b7d0b5fa5d420a9202a39'),
('45', 'Pendidik', 'Rusyda Ariani, S. Pd. I', '', '', NULL, NULL, '198601062025212015', NULL, '', '0000-00-00', 'P', 'Islam', NULL, NULL, NULL, 'PPPK', 'Penata Muda / IX', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Ahli Pertama', NULL, 'Walikelas XI E', 'Al Qur`an Hadis (Umum), Ilmu Hadis (Pilihan)', 'Ya', NULL, NULL, 'Qur\'an Hadist', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '+62 851-9894-4409', '', NULL, NULL, NULL, NULL, 'Aktif', NULL, '2026-05-25 10:58:05', NULL, '1', '0', '198601062025212015', '4b41f7c1893fc4d6a404792da740625a'),
('46', 'Kependidikan', 'Muhammad Ramdhani, M. AP', '', '', NULL, NULL, '198901042025211016', NULL, '', '0000-00-00', 'L', 'Islam', NULL, NULL, NULL, 'PPPK', 'IX', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Penata Layanan Operasional', NULL, '', '', 'Tidak', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '+62 853-4561-6012', '', NULL, NULL, NULL, NULL, 'Aktif', NULL, '2026-05-25 10:58:05', NULL, '1', '0', '198901042025211016', '8f025755ee099ae21ae8db3e8dc96657'),
('47', 'Kependidikan', 'Dina Hafizah, S.Pd.', '', '', NULL, NULL, '199306262025212014', NULL, '', '0000-00-00', 'P', 'Islam', NULL, NULL, NULL, 'PPPK', 'IX', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Penata Layanan Operasional', NULL, '', '', 'Tidak', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '+62 878-9302-2193', '', NULL, NULL, NULL, NULL, 'Aktif', NULL, '2026-05-25 10:58:05', NULL, '1', '0', '199306262025212014', 'eff26a73a335dde427205b87d8d0ee0a'),
('48', 'Kependidikan', 'Muhammad Khaidir, S.Pd.', '', '', '', '', '199808292025211008', '', '', '0000-00-00', 'L', 'Islam', '', '', '0', 'PPPK', 'IX', '0000-00-00', '0', '0', '', '0000-00-00', '0000-00-00', '', '0000-00-00', '0000-00-00', '', '0000-00-00', '', '', 'SMA/MA', '', '', 'Penata Layanan Operasional', '', '', '', 'Tidak', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0000-00-00', '0000-00-00', '', '', '', '', '', '', '', '', '+62 896-3116-4642', '', '', '', '', '', 'Aktif', NULL, '2026-05-25 10:58:05', NULL, '1', '0', '199808292025211008', 'cf03f76be63798a8c180f80f79d4aa2e'),
('49', 'Kependidikan', 'Norliana, S.Ag', '', '', NULL, NULL, '197207112025212005', NULL, '', '0000-00-00', 'P', 'Islam', NULL, NULL, NULL, 'PPPK', 'IX', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Penata Layanan Operasional', NULL, '', '', 'Tidak', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '+62 852-4982-6380', '', NULL, NULL, NULL, NULL, 'Aktif', NULL, '2026-05-25 10:58:05', NULL, '1', '0', '197207112025212005', 'f69770ae7096e00ac03a0363d3c98061'),
('50', 'Kependidikan', 'Puteri Fajriyati Maryam, A. Md. Kes', '', '', NULL, NULL, '200004232025212024', NULL, '', '0000-00-00', 'P', 'Islam', NULL, NULL, NULL, 'PPPK', 'VII', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Pengelola Layanan Operasional', NULL, '', '', 'Tidak', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '+62 812-5801-8183', '', NULL, NULL, NULL, NULL, 'Aktif', NULL, '2026-05-25 10:58:06', NULL, '1', '0', '200004232025212024', 'b2886e5f2fc8d2660d30cd39043ac6b0'),
('51', 'Kependidikan', 'Ardiansyah', '', '', NULL, NULL, '197009152025211003', NULL, '', '0000-00-00', 'L', 'Islam', NULL, NULL, NULL, 'PPPK', 'V', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Pengadministrasi Perkantoran', NULL, '', '', 'Tidak', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '+62 812-5522-5435', '', NULL, NULL, NULL, NULL, 'Aktif', NULL, '2026-05-25 10:58:06', NULL, '1', '0', '197009152025211003', '2aa7b1788e03a4771dab94fdc252222b'),
('52', 'Kependidikan', 'Muhammad Akhyat', '', '', NULL, NULL, '198806222025211010', NULL, '', '0000-00-00', 'L', 'Islam', NULL, NULL, NULL, 'PPPK', 'V', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Pengadministrasi Perkantoran', NULL, '', '', 'Tidak', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '+62 856-5478-1431', '', NULL, NULL, NULL, NULL, 'Aktif', NULL, '2026-05-25 10:58:06', NULL, '1', '0', '198806222025211010', '475f977bad059499e7c7f9c1602a59a3'),
('53', 'Kependidikan', 'Abd. Hafidz Fakhruddin', '', '', '', '', '198901102025211015', '', '', '0000-00-00', 'L', 'Islam', '', '', '0', 'PPPK', 'V', '0000-00-00', '0', '0', '', '0000-00-00', '0000-00-00', '', '0000-00-00', '0000-00-00', '', '0000-00-00', '', '', 'SMA/MA', '', '', 'Pengadministrasi Perkantoran', '', '', '', 'Tidak', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0000-00-00', '0000-00-00', '', '', '', '', '', '', '', '', '+62 822-4977-4269', '', '', '', '', '', 'Aktif', NULL, '2026-05-25 10:58:06', NULL, '1', '0', '198901102025211015', '0618f0c8fd79d9acc9b7e1651e82771c'),
('54', 'Kependidikan', 'Hafidz Muslim', '', '', NULL, NULL, '198606022025211103', NULL, '', '0000-00-00', 'L', 'Islam', NULL, NULL, NULL, 'PPPK', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Pengadministrasi Perkantoran', NULL, '', '', 'Tidak', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '+62 822-5503-1015', '', NULL, NULL, NULL, NULL, 'Aktif', NULL, '2026-05-25 10:58:06', NULL, '1', '0', '198606022025211103', '444e948f566b2b0389081b5e3a370069'),
('55', 'Kependidikan', 'Muhammad Aryadi', '', '', NULL, NULL, NULL, NULL, '', NULL, 'L', 'Islam', NULL, NULL, NULL, 'SATPAM', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'PTT', NULL, '', '', 'Tidak', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '+62 896-0185-6866', '', NULL, NULL, NULL, NULL, 'Aktif', NULL, '2026-05-25 10:58:06', NULL, '1', '0', 'guru55muha', 'c7f47e4a953ce8e5c12f3c42db1f1ac6'),
('56', 'Pendidik', 'Mariana Ulfah, SH, S.Pd', '', '', NULL, NULL, '197703132023212008', NULL, '', '0000-00-00', 'P', 'Islam', NULL, NULL, NULL, 'PPPK', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Ahli Pertama', NULL, 'Binaan Kelas XII', 'BK', 'Tidak', NULL, NULL, 'BK', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, 'Aktif', NULL, '2026-05-25 10:58:07', NULL, '1', '0', '197703132023212008', '03d47e4a630e1cefd87acf4e5fa77728'),
('58', 'Pendidik', 'Shellyna Rofiyanti, S. Pd', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P', NULL, NULL, NULL, NULL, 'GTT', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'GTT', NULL, 'Koordinator Kookurikuler XD dan XE/Pembina Ekskul PMR', '', 'Tidak', NULL, NULL, 'Sosiologi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Aktif', NULL, '2026-06-25 10:09:09', NULL, '1', '0', 'guru58shel', '9e34b0f1a12a898ffdcc807a03da7542'),
('59', 'Pendidik', 'Muhammad Arsyad, S.Pd', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'L', NULL, NULL, NULL, NULL, 'GTT', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'GTT', NULL, 'Pembina Ekskul Tari', 'Informatika (Umum), Seni dan Budaya (Umum)', 'Tidak', NULL, NULL, 'Seni Budaya', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Aktif', NULL, '2026-06-25 10:09:09', NULL, '1', '0', 'guru59muha', 'd0bfed094c8181ace33fe773d53a4859'),
('60', 'Pendidik', 'Alfina Putri, S.Pd', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P', NULL, NULL, NULL, NULL, 'GTT', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'GTT', NULL, 'Pembina Ekslul Pramuka Putri', 'Sosiologi (Umum), Sosiologi (Pilihan)', 'Tidak', NULL, NULL, 'Sosiologi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Aktif', NULL, '2026-06-25 10:09:09', NULL, '1', '0', 'guru60alfi', '61a8f3cbf86fb7db822ff420ce8acd28'),
('61', 'Pendidik', 'Yastri Saidaturrahmah, S.Pd', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'P', NULL, NULL, NULL, NULL, 'GTT', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'GTT', NULL, 'Koordinator Kookurikuler Kelas XB dan XC/ Pembina Ekskul KIR', 'Informatika (Umum), Fisika (Pilihan)', 'Tidak', NULL, NULL, 'Fisika', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Aktif', NULL, '2026-06-25 10:09:09', NULL, '1', '0', 'guru61yast', 'c6e4455452b3ce73f17b547ae4ca268d'),
('63', 'Pendidik', 'Annisa Damayanti, S.Pd., M.Pd', NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, 'P', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Koordinator Kookurikuler Kelas XII B', 'Informatika (Umum), Sejarah (Umum)', 'Tidak', NULL, NULL, 'Informatika', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Aktif', NULL, '2026-07-15 08:01:08', NULL, '1', '0', NULL, NULL),
('64', 'Pendidik', 'Jamiaturrasyidah, S.Pd', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0000-00-00', 'P', NULL, NULL, NULL, NULL, 'Guru Menambah Jam', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Tidak', NULL, NULL, 'Fisika', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Aktif', NULL, '2026-08-14 15:15:58', NULL, '1', '0', NULL, NULL);

DROP TABLE IF EXISTS `ppdb`;
CREATE TABLE `ppdb` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `no_pendaftaran` varchar(30) DEFAULT NULL,
  `nama_lengkap` varchar(150) DEFAULT NULL,
  `nisn` varchar(20) DEFAULT NULL,
  `jk` enum('L','P') DEFAULT NULL,
  `asal_sekolah` varchar(150) DEFAULT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `nama_ortu` varchar(150) DEFAULT NULL,
  `jurusan_pilihan` varchar(100) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Lengkapi Biodata',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `tempat_lahir` varchar(100) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `nik` varchar(20) DEFAULT NULL,
  `no_kk` varchar(20) DEFAULT NULL,
  `agama` varchar(30) DEFAULT NULL,
  `anak_ke` int(11) DEFAULT NULL,
  `jumlah_saudara` int(11) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `rt` varchar(5) DEFAULT NULL,
  `rw` varchar(5) DEFAULT NULL,
  `desa` varchar(100) DEFAULT NULL,
  `kecamatan` varchar(100) DEFAULT NULL,
  `kabupaten` varchar(100) DEFAULT NULL,
  `provinsi` varchar(100) DEFAULT NULL,
  `kode_pos` varchar(10) DEFAULT NULL,
  `nama_ayah` varchar(150) DEFAULT NULL,
  `pekerjaan_ayah` varchar(100) DEFAULT NULL,
  `nama_ibu` varchar(150) DEFAULT NULL,
  `pekerjaan_ibu` varchar(100) DEFAULT NULL,
  `penghasilan_ortu` varchar(50) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `kk_file` varchar(255) DEFAULT NULL,
  `akta_file` varchar(255) DEFAULT NULL,
  `rapor_file` varchar(255) DEFAULT NULL,
  `skl_file` varchar(255) DEFAULT NULL,
  `nisn_file` varchar(255) DEFAULT NULL,
  `sk_kelas9_file` varchar(255) DEFAULT NULL,
  `sertifikat_file` varchar(255) DEFAULT NULL,
  `ijazah_file` varchar(255) DEFAULT NULL,
  `verifikasi_berkas_json` text DEFAULT NULL,
  `ocr_scanned_at` datetime DEFAULT NULL,
  `ocr_results_json` text DEFAULT NULL,
  `accepted_at` datetime DEFAULT NULL,
  `rejected_at` datetime DEFAULT NULL,
  `migrated_at` datetime DEFAULT NULL,
  `is_migrated` tinyint(1) DEFAULT 0,
  `password_updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nisn` (`nisn`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tahun_ajaran` varchar(20) DEFAULT NULL,
  `status_ppdb` varchar(20) DEFAULT 'Dibuka',
  `tanggal_mulai` date DEFAULT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  `pengumuman_ppdb` text DEFAULT NULL,
  `semester_aktif` enum('Ganjil','Genap') DEFAULT 'Ganjil',
  `tanggal_update` datetime DEFAULT current_timestamp(),
  `kepala_madrasah_ptk_id` int(11) DEFAULT NULL,
  `nama_ppdb` varchar(50) DEFAULT 'PPDB',
  `pamflet_ppdb` varchar(255) DEFAULT NULL,
  `persyaratan_ppdb` text DEFAULT NULL,
  `judul_panjang_ppdb` varchar(150) DEFAULT 'Penerimaan Murid Baru',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `settings` VALUES 
('1', '2026/2027', 'Dibuka', '2026-06-12', '2026-06-30', 'PPDB MAN 3 Banjar telah dibuka.', 'Ganjil', '2026-06-23 16:03:27', '4', 'PMB', NULL, '', 'Penerimaan Murid Baru');

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ptk_id` int(11) DEFAULT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_lengkap` varchar(100) DEFAULT NULL,
  `role` enum('admin','ptk','user','siswa','admin_master','admin_humas','wakil_humas','Operator Humas','admin_kesiswaan','admin_kurikulum','admin_sarpras','guru','teknisi') DEFAULT 'admin',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `users` VALUES 
('1', NULL, 'admin', '3b297a5113893e788faa01ca9c1f94dc', 'Administrator MAN 3 Banjar', 'admin', '2026-05-13 16:17:09'),
('6', NULL, 'kamad', '43634957898dd720a1a6348885940932', 'Kepala Madrasah', 'admin_master', '2026-05-21 10:38:01'),
('7', NULL, 'humas', '94da7343e47802652a24444298012b8c', 'Wakamad Humas', 'admin_humas', '2026-05-21 10:38:01'),
('8', NULL, 'wakilhumas', '623fd154cfb410a5c45dc30f7b251031', 'Wakil Humas', 'wakil_humas', '2026-05-21 10:38:01'),
('9', NULL, 'operator_humas', '3c15133e19346048ad1a51d4e5801e52', 'Operator Humas', 'Operator Humas', '2026-05-21 10:38:01'),
('10', NULL, 'kesiswaan', 'accc7841ce41b0f788a737bf9798ea4f', 'Wakamad Kesiswaan', 'admin_kesiswaan', '2026-05-21 10:38:01'),
('11', NULL, 'kurikulum', '4e7f2477836fa0c289105740fee0ebb1', 'Wakamad Kurikulum', 'admin_kurikulum', '2026-05-21 10:38:01'),
('12', NULL, 'sarpras', '379563d4cc020b27338863c063b9368d', 'Admin Sarpras', 'admin_sarpras', '2026-05-21 10:38:01'),
('16', NULL, 'admin_pmb', '21232f297a57a5a743894a0e4a801fc3', 'Panitia PMB MAN 3 Banjar', '', '2026-09-01 11:37:25');

SET FOREIGN_KEY_CHECKS=1;
COMMIT;
