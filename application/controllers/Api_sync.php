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

        echo json_encode([
            'status' => 'success',
            'server_time' => date('Y-m-d H:i:s'),
            'data' => [
                'berita' => $total_berita,
                'ptk' => $total_ptk,
                'jadwal_mengajar' => $total_jadwal,
                'ppdb' => $total_ppdb,
                'banner' => $total_banner,
                'galeri' => $total_galeri
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

        if(empty($payload) || !isset($payload['ptk'])){
            echo json_encode(['status' => 'error', 'message' => 'Payload PTK kosong']);
            return;
        }

        $ptk_list = $payload['ptk'];
        $synced = 0;

        if($this->db->table_exists('ptk')){
            foreach($ptk_list as $p){
                $cek = $this->db->where('id', $p['id'])->count_all_results('ptk');
                if($cek > 0){
                    $this->db->where('id', $p['id'])->update('ptk', $p);
                } else {
                    $this->db->insert('ptk', $p);
                }
                $synced++;
            }
        }

        echo json_encode([
            'status' => 'success',
            'message' => "Berhasil menyinkronkan {$synced} data PTK ke website online."
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
}
