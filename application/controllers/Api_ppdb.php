<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api_ppdb extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('Ppdb_model');
        header('Content-Type: application/json; charset=utf-8');
    }

    private function verify_token(){
        $valid_key = $this->config->item('ppdb_api_key');
        
        $req_key = $this->input->get_request_header('X-API-KEY', TRUE);
        if (!$req_key) {
            $req_key = $this->input->get_request_header('Authorization', TRUE);
            if ($req_key) {
                $req_key = str_replace('Bearer ', '', $req_key);
            }
        }
        if (!$req_key) {
            $req_key = $this->input->get('api_key', TRUE);
        }

        if (empty($req_key) || $req_key !== $valid_key) {
            http_response_code(401);
            echo json_encode([
                'status' => 'error',
                'code' => 401,
                'message' => 'Unauthorized: Invalid or missing API Key Token.'
            ]);
            exit;
        }
    }

    /**
     * Endpoint: GET /api/ppdb/sync
     * Mengambil daftar pendaftar baru yang belum ditarik ke server lokal
     */
    public function sync_pendaftar(){
        $this->verify_token();

        $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 50;
        $data_unsynced = $this->Ppdb_model->get_unsynced_pendaftar($limit);

        echo json_encode([
            'status' => 'success',
            'code' => 200,
            'server_time' => date('Y-m-d H:i:s'),
            'total_data' => count($data_unsynced),
            'data' => $data_unsynced
        ]);
    }

    /**
     * Endpoint: POST /api/ppdb/confirm_sync
     * Menandai data pendaftar telah sukses disimpan di LabSys Lokal (is_synced = 1)
     */
    public function confirm_sync(){
        $this->verify_token();

        $input = json_decode(file_get_contents('php://input'), true);
        $no_pendaftaran_list = $input['no_pendaftaran_list'] ?? [];

        if (empty($no_pendaftaran_list)) {
            echo json_encode([
                'status' => 'error',
                'code' => 400,
                'message' => 'Daftar nomor pendaftaran kosong.'
            ]);
            return;
        }

        $updated = $this->Ppdb_model->mark_as_synced($no_pendaftaran_list);

        echo json_encode([
            'status' => 'success',
            'code' => 200,
            'message' => 'Status sinkronisasi berhasil diperbarui.',
            'rows_affected' => $updated
        ]);
    }

    /**
     * Endpoint: GET /api/ppdb/stats
     * Informasi statistik jumlah pendaftar di server cloud
     */
    public function stats(){
        $this->verify_token();

        $total = $this->db->count_all('ppdb_pendaftar');
        $unsynced = $this->db->where('is_synced', 0)->count_all_results('ppdb_pendaftar');
        $synced = $this->db->where('is_synced', 1)->count_all_results('ppdb_pendaftar');

        echo json_encode([
            'status' => 'success',
            'code' => 200,
            'stats' => [
                'total_pendaftar' => $total,
                'belum_ditarik' => $unsynced,
                'sudah_ditarik' => $synced
            ]
        ]);
    }
}
