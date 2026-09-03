<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api_sync extends CI_Controller {

    private $api_key = 'MAN3BANJAR_SECRET_SYNC_KEY_2026';

    public function __construct(){
        parent::__construct();
        header('Content-Type: application/json; charset=utf-8');
    }

    private function validate_key(){
        $header_key = $this->input->get_request_header('X-API-KEY', TRUE);
        $post_key = $this->input->post('api_key');
        $key = !empty($header_key) ? $header_key : $post_key;

        if($key !== $this->api_key){
            echo json_encode([
                'status' => 'error',
                'message' => 'Akses ditolak: API Key tidak valid.'
            ]);
            exit;
        }
    }

    public function status(){
        $this->validate_key();

        $total_berita = $this->db->table_exists('berita') ? $this->db->count_all_results('berita') : 0;
        $total_ptk = $this->db->table_exists('ptk') ? $this->db->count_all_results('ptk') : 0;
        $total_jadwal = $this->db->table_exists('jadwal_mengajar') ? $this->db->count_all_results('jadwal_mengajar') : 0;
        $total_ppdb = $this->db->table_exists('ppdb') ? $this->db->count_all_results('ppdb') : 0;
        $total_banner = $this->db->table_exists('website_banner') ? $this->db->count_all_results('website_banner') : 0;
        $total_galeri = $this->db->table_exists('website_galeri') ? $this->db->count_all_results('website_galeri') : 0;
        $total_siswa = $this->db->table_exists('siswa') ? $this->db->count_all_results('siswa') : 0;

        echo json_encode([
            'status' => 'success',
            'server_time' => date('Y-m-d H:i:s'),
            'data' => [
                'berita' => $total_berita,
                'ptk' => $total_ptk,
                'jadwal_mengajar' => $total_jadwal,
                'ppdb' => $total_ppdb,
                'banner' => $total_banner,
                'galeri' => $total_galeri,
                'siswa' => $total_siswa
            ]
        ]);
    }

    // ═══ 1. PUSH DARI LOKAL KE CLOUD ═══

    public function sync_berita(){
        $this->validate_key();
        $payload = json_decode($this->input->raw_input_stream, true);

        if(empty($payload) || !isset($payload['berita'])){
            echo json_encode(['status' => 'error', 'message' => 'Payload berita kosong']);
            return;
        }

        $berita_list = $payload['berita'];
        $gambar_list = $payload['berita_gambar'] ?? [];
        $synced = 0;

        if($this->db->table_exists('berita')){
            foreach($berita_list as $b){
                $cek = $this->db->where('id', $b['id'])->count_all_results('berita');
                if($cek > 0){
                    $this->db->where('id', $b['id'])->update('berita', $b);
                } else {
                    $this->db->insert('berita', $b);
                }
                $synced++;
            }
        }

        if($this->db->table_exists('berita_gambar') && !empty($gambar_list)){
            foreach($gambar_list as $g){
                $cek = $this->db->where('id', $g['id'])->count_all_results('berita_gambar');
                if($cek > 0){
                    $this->db->where('id', $g['id'])->update('berita_gambar', $g);
                } else {
                    $this->db->insert('berita_gambar', $g);
                }
            }
        }

        echo json_encode([
            'status' => 'success',
            'message' => "Berhasil menyinkronkan {$synced} data berita ke website online."
        ]);
    }

    public function sync_website(){
        $this->validate_key();
        $payload = json_decode($this->input->raw_input_stream, true);

        if(empty($payload)){
            echo json_encode(['status' => 'error', 'message' => 'Payload konten website kosong']);
            return;
        }

        $tables = ['website_profil', 'website_banner', 'website_video', 'website_pamflet', 'website_galeri', 'website_download'];
        $stats = [];

        foreach($tables as $tbl){
            if(isset($payload[$tbl]) && $this->db->table_exists($tbl)){
                $this->db->empty_table($tbl);
                $rows = $payload[$tbl];
                if(!empty($rows)){
                    $this->db->insert_batch($tbl, $rows);
                }
                $stats[$tbl] = count($rows);
            }
        }

        echo json_encode([
            'status' => 'success',
            'message' => 'Berhasil mengirim profil, banner, pamflet, galeri, dan video ke website online.',
            'stats' => $stats
        ]);
    }

    public function sync_ptk(){
        $this->validate_key();
        $payload = json_decode($this->input->raw_input_stream, true);

        if(empty($payload)){
            echo json_encode(['status' => 'error', 'message' => 'Payload PTK kosong']);
            return;
        }

        $this->db->query("SET FOREIGN_KEY_CHECKS = 0");

        // 1. Pastikan tabel ptk ada
        $this->db->query("
            CREATE TABLE IF NOT EXISTS `ptk` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `nama_lengkap` varchar(150) NOT NULL,
              `nip` varchar(50) DEFAULT NULL,
              `nik` varchar(30) DEFAULT NULL,
              `nuptk` varchar(30) DEFAULT NULL,
              `jenis_ptk` enum('Pendidik','Tenaga Kependidikan') NOT NULL DEFAULT 'Pendidik',
              `status_kepegawaian` varchar(50) DEFAULT 'PNS',
              `jabatan` varchar(100) DEFAULT 'Guru',
              `tugas_tambahan` text DEFAULT NULL,
              `email` varchar(100) DEFAULT NULL,
              `no_hp` varchar(20) DEFAULT NULL,
              `foto` varchar(255) DEFAULT NULL,
              `status_aktif` varchar(20) DEFAULT 'Aktif',
              `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ");

        // 2. Pastikan tabel struktur_organisasi ada
        $this->db->query("
            CREATE TABLE IF NOT EXISTS `struktur_organisasi` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `kategori` varchar(50) NOT NULL,
              `ptk_id` int(11) NOT NULL,
              `jabatan` varchar(150) DEFAULT NULL,
              `urutan` int(11) NOT NULL DEFAULT 0,
              `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
              PRIMARY KEY (`id`),
              KEY `kategori` (`kategori`),
              KEY `ptk_id` (`ptk_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ");

        // 3. Pastikan tabel guru_tugas_tambahan ada
        $this->db->query("
            CREATE TABLE IF NOT EXISTS `guru_tugas_tambahan` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `ptk_id` int(11) NOT NULL,
              `tahun_ajaran` varchar(30) NOT NULL,
              `semester` varchar(20) DEFAULT 'Ganjil',
              `nama_tugas` varchar(150) NOT NULL,
              `kelas_id` int(11) DEFAULT NULL,
              `jam` int(11) DEFAULT 0,
              `status` varchar(20) DEFAULT 'Aktif',
              `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
              PRIMARY KEY (`id`),
              KEY `ptk_id` (`ptk_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ");

        // 4. Pastikan tabel wali_kelas ada
        $this->db->query("
            CREATE TABLE IF NOT EXISTS `wali_kelas` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `ptk_id` int(11) DEFAULT NULL,
              `kelas_id` int(11) DEFAULT NULL,
              `tahun_ajaran` varchar(30) DEFAULT NULL,
              `semester` varchar(20) DEFAULT 'Ganjil',
              `status` varchar(20) DEFAULT 'Aktif',
              `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
              PRIMARY KEY (`id`),
              KEY `ptk_id` (`ptk_id`),
              KEY `kelas_id` (`kelas_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ");

        // 5. Pastikan tabel tugas_mengajar ada
        $this->db->query("
            CREATE TABLE IF NOT EXISTS `tugas_mengajar` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `ptk_id` int(11) DEFAULT NULL,
              `mapel_id` int(11) DEFAULT NULL,
              `kelas_id` int(11) DEFAULT NULL,
              `tahun_ajaran` varchar(30) DEFAULT NULL,
              `semester` varchar(20) DEFAULT NULL,
              `jam_per_minggu` int(11) DEFAULT 0,
              `status` varchar(20) DEFAULT 'Aktif',
              `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
              PRIMARY KEY (`id`),
              KEY `ptk_id` (`ptk_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ");

        $tables = ['ptk', 'struktur_organisasi', 'guru_tugas_tambahan', 'wali_kelas', 'tugas_mengajar'];
        $stats = [];

        foreach($tables as $tbl){
            if(isset($payload[$tbl])){
                $this->db->empty_table($tbl);
                $rows = $payload[$tbl];
                if(!empty($rows)){
                    $chunks = array_chunk($rows, 100);
                    foreach($chunks as $chunk){
                        $this->db->insert_batch($tbl, $chunk);
                    }
                }
                $stats[$tbl] = count($rows);
            }
        }

        $this->db->query("SET FOREIGN_KEY_CHECKS = 1");

        echo json_encode([
            'status' => 'success',
            'message' => 'Berhasil menyinkronkan seluruh data PTK, struktur organisasi, tugas tambahan, dan wali kelas ke website online.',
            'stats' => $stats
        ]);
    }

    public function sync_kbm(){
        $this->validate_key();
        $payload = json_decode($this->input->raw_input_stream, true);

        if(empty($payload)){
            echo json_encode(['status' => 'error', 'message' => 'Payload KBM kosong']);
            return;
        }

        $tables = ['kelas', 'mapel', 'jadwal_jam', 'jadwal_mengajar', 'jadwal_piket', 'absensi_kelas'];
        $stats = [];

        foreach($tables as $tbl){
            if(isset($payload[$tbl]) && $this->db->table_exists($tbl)){
                $this->db->empty_table($tbl);
                $rows = $payload[$tbl];
                if(!empty($rows)){
                    $this->db->insert_batch($tbl, $rows);
                }
                $stats[$tbl] = count($rows);
            }
        }

        echo json_encode([
            'status' => 'success',
            'message' => 'Berhasil menyinkronkan seluruh jadwal KBM, kelas, dan mapel ke website online.',
            'stats' => $stats
        ]);
    }

    public function sync_siswa(){
        $this->validate_key();
        $payload = json_decode($this->input->raw_input_stream, true);

        if(empty($payload)){
            echo json_encode(['status' => 'error', 'message' => 'Payload data siswa kosong']);
            return;
        }

        $this->db->query("SET FOREIGN_KEY_CHECKS = 0");

        // 1. Buat tabel siswa segar
        $this->db->query("DROP TABLE IF EXISTS `siswa`");
        $this->db->query("
            CREATE TABLE `siswa` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `nis` varchar(50) DEFAULT NULL,
              `nisn` varchar(50) DEFAULT NULL,
              `nama_lengkap` varchar(150) DEFAULT NULL,
              `jk` enum('L','P') DEFAULT NULL,
              `tempat_lahir` varchar(100) DEFAULT NULL,
              `tanggal_lahir` date DEFAULT NULL,
              `agama` varchar(50) DEFAULT NULL,
              `status_siswa` varchar(50) DEFAULT 'Aktif',
              `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
              PRIMARY KEY (`id`),
              KEY `idx_siswa_nisn` (`nisn`),
              KEY `idx_siswa_nama` (`nama_lengkap`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ");

        // 2. Buat tabel kelas segar
        $this->db->query("DROP TABLE IF EXISTS `kelas`");
        $this->db->query("
            CREATE TABLE `kelas` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `nama_kelas` varchar(50) DEFAULT NULL,
              `tingkat` varchar(10) DEFAULT NULL,
              `jurusan` varchar(50) DEFAULT NULL,
              `tahun_ajaran` varchar(50) DEFAULT NULL,
              `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ");

        // 3. Buat tabel siswa_kelas segar
        $this->db->query("DROP TABLE IF EXISTS `siswa_kelas`");
        $this->db->query("
            CREATE TABLE `siswa_kelas` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `siswa_id` int(11) DEFAULT NULL,
              `kelas_id` int(11) DEFAULT NULL,
              `tahun_ajaran` varchar(50) DEFAULT NULL,
              `status` varchar(50) DEFAULT 'Aktif',
              `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
              PRIMARY KEY (`id`),
              KEY `idx_sk_siswa` (`siswa_id`),
              KEY `idx_sk_kelas` (`kelas_id`),
              KEY `idx_sk_ta` (`tahun_ajaran`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ");

        // 4. Buat tabel alumni segar
        $this->db->query("DROP TABLE IF EXISTS `alumni`");
        $this->db->query("
            CREATE TABLE `alumni` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `siswa_id` int(11) DEFAULT NULL,
              `nis` varchar(30) DEFAULT NULL,
              `nisn` varchar(20) DEFAULT NULL,
              `nama_lengkap` varchar(150) DEFAULT NULL,
              `jk` enum('L','P') DEFAULT NULL,
              `kelas_terakhir` varchar(100) DEFAULT NULL,
              `tahun_ajaran_lulus` varchar(20) DEFAULT NULL,
              `tanggal_lulus` date DEFAULT NULL,
              `no_hp` varchar(20) DEFAULT NULL,
              `alamat` text DEFAULT NULL,
              `status_lanjut` varchar(100) DEFAULT NULL,
              `keterangan` text DEFAULT NULL,
              `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
              `tahun_lulus` varchar(45) DEFAULT NULL,
              PRIMARY KEY (`id`),
              KEY `idx_alumni_nisn` (`nisn`),
              KEY `idx_alumni_thn` (`tahun_ajaran_lulus`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ");

        $stats = ['kelas' => 0, 'siswa' => 0, 'siswa_kelas' => 0, 'alumni' => 0];

        // Insert Kelas
        if(isset($payload['kelas']) && !empty($payload['kelas'])){
            $this->db->insert_batch('kelas', $payload['kelas']);
            $stats['kelas'] = count($payload['kelas']);
        }

        // Insert Siswa
        if(isset($payload['siswa']) && !empty($payload['siswa'])){
            $chunks = array_chunk($payload['siswa'], 100);
            foreach($chunks as $chunk){
                $this->db->insert_batch('siswa', $chunk);
            }
            $stats['siswa'] = count($payload['siswa']);
        }

        // Insert Siswa Kelas
        if(isset($payload['siswa_kelas']) && !empty($payload['siswa_kelas'])){
            $chunks = array_chunk($payload['siswa_kelas'], 100);
            foreach($chunks as $chunk){
                $this->db->insert_batch('siswa_kelas', $chunk);
            }
            $stats['siswa_kelas'] = count($payload['siswa_kelas']);
        }

        // Insert Alumni
        if(isset($payload['alumni']) && !empty($payload['alumni'])){
            $chunks = array_chunk($payload['alumni'], 100);
            foreach($chunks as $chunk){
                $this->db->insert_batch('alumni', $chunk);
            }
            $stats['alumni'] = count($payload['alumni']);
        }

        $this->db->query("SET FOREIGN_KEY_CHECKS = 1");

        $real_siswa = $this->db->count_all_results('siswa');
        $real_alumni = $this->db->count_all_results('alumni');

        echo json_encode([
            'status' => 'success',
            'message' => "Berhasil menyinkronkan data {$real_siswa} siswa, {$real_alumni} alumni, {$stats['siswa_kelas']} riwayat kelas, dan {$stats['kelas']} rombel ke website online.",
            'stats' => $stats,
            'real_siswa_count' => $real_siswa,
            'real_alumni_count' => $real_alumni
        ]);
    }

    // ═══ 2. PULL DARI CLOUD KE LOKAL (TWO-WAY SYNC) ═══

    public function pull_website(){
        $this->validate_key();

        $data = [];
        $tables = ['website_profil', 'website_banner', 'website_video', 'website_pamflet', 'website_galeri', 'website_download'];

        foreach($tables as $tbl){
            if($this->db->table_exists($tbl)){
                $data[$tbl] = $this->db->get($tbl)->result_array();
            }
        }

        echo json_encode([
            'status' => 'success',
            'message' => 'Data konten website online berhasil diambil.',
            'data' => $data
        ]);
    }

    public function pull_berita(){
        $this->validate_key();

        $berita = $this->db->table_exists('berita') ? $this->db->get('berita')->result_array() : [];
        $gambar = $this->db->table_exists('berita_gambar') ? $this->db->get('berita_gambar')->result_array() : [];

        echo json_encode([
            'status' => 'success',
            'message' => 'Data berita online berhasil diambil.',
            'data' => [
                'berita' => $berita,
                'berita_gambar' => $gambar
            ]
        ]);
    }

    public function migrate_ppdb_schema(){
        $this->validate_key();

        $ppdb_fields = [
            'jalur_pendaftaran' => "VARCHAR(50) NULL DEFAULT 'Reguler' AFTER asal_sekolah",
            'pilihan_jurusan_1' => "VARCHAR(50) NULL DEFAULT 'MIPA' AFTER jalur_pendaftaran",
            'pilihan_jurusan_2' => "VARCHAR(50) NULL DEFAULT 'IPS' AFTER pilihan_jurusan_1",
            'no_peserta_tes'    => "VARCHAR(50) NULL AFTER status",
            'tanggal_tes'       => "DATE NULL AFTER no_peserta_tes",
            'jam_tes'           => "VARCHAR(50) NULL AFTER tanggal_tes",
            'ruang_tes'         => "VARCHAR(100) NULL AFTER jam_tes",
            'nilai_tes'         => "DECIMAL(5,2) NULL AFTER ruang_tes",
            'catatan_verifikasi'=> "TEXT NULL AFTER nilai_tes"
        ];

        $results = [];

        if($this->db->table_exists('ppdb')){
            foreach($ppdb_fields as $col => $def){
                if(!$this->db->field_exists($col, 'ppdb')){
                    $this->db->query("ALTER TABLE `ppdb` ADD `{$col}` {$def}");
                    $results["ppdb.{$col}"] = 'Added';
                } else {
                    $results["ppdb.{$col}"] = 'Exists';
                }
            }
        }

        $settings_fields = [
            'default_tanggal_tes' => "DATE NULL AFTER persyaratan_ppdb",
            'default_jam_tes'     => "VARCHAR(50) NULL DEFAULT '08:00 - 11.30 WITA' AFTER default_tanggal_tes",
            'default_ruang_tes'   => "VARCHAR(100) NULL DEFAULT 'Kampus MAN 3 Banjar' AFTER default_jam_tes",
            'materi_tes_info'     => "TEXT NULL AFTER default_ruang_tes"
        ];

        if($this->db->table_exists('settings')){
            foreach($settings_fields as $col => $def){
                if(!$this->db->field_exists($col, 'settings')){
                    $this->db->query("ALTER TABLE `settings` ADD `{$col}` {$def}");
                    $results["settings.{$col}"] = 'Added';
                } else {
                    $results["settings.{$col}"] = 'Exists';
                }
            }
        }

        echo json_encode([
            'status' => 'success',
            'message' => 'Migrasi skema database PPDB cloud selesai.',
            'results' => $results
        ]);
    }
}
