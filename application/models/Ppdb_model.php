<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ppdb_model extends CI_Model {

    public function __construct(){
        parent::__construct();
    }

    public function get_setting(){
        $q = $this->db->get('ppdb_setting');
        return $q->num_rows() > 0 ? $q->row() : (object)[
            'tahun_ajaran' => '2026/2027',
            'kuota_siswa' => 216,
            'is_open' => 1,
            'kontak_panitia' => '0812-3456-7890'
        ];
    }

    public function generate_nomor_daftar(){
        $thn = date('Y');
        $prefix = "PPDB-{$thn}-";
        $last = $this->db->select('no_pendaftaran')
                         ->like('no_pendaftaran', $prefix, 'after')
                         ->order_by('id', 'DESC')
                         ->limit(1)
                         ->get('ppdb_pendaftar')
                         ->row();

        if ($last) {
            $num = (int)str_replace($prefix, '', $last->no_pendaftaran) + 1;
        } else {
            $num = 1;
        }

        return $prefix . str_pad($num, 4, '0', STR_PAD_LEFT);
    }

    public function simpan_pendaftar($data){
        $data['no_pendaftaran'] = $this->generate_nomor_daftar();
        $data['is_synced'] = 0;
        $this->db->insert('ppdb_pendaftar', $data);
        return [
            'id' => $this->db->insert_id(),
            'no_pendaftaran' => $data['no_pendaftaran']
        ];
    }

    public function get_pendaftar_by_no($no_daftar){
        return $this->db->where('no_pendaftaran', $no_daftar)->get('ppdb_pendaftar')->row();
    }

    public function get_pendaftar_by_nisn_tgl($nisn, $tgl_lahir){
        return $this->db->where('nisn', $nisn)
                        ->where('tanggal_lahir', $tgl_lahir)
                        ->get('ppdb_pendaftar')
                        ->row();
    }

    public function simpan_berkas($data){
        return $this->db->insert('ppdb_berkas', $data);
    }

    public function get_berkas_by_pendaftar($pendaftar_id){
        return $this->db->where('pendaftar_id', $pendaftar_id)->get('ppdb_berkas')->result();
    }

    // METHOD UNTUK REST API SINKRONISASI KE LABSYS LOKAL
    public function get_unsynced_pendaftar($limit = 50){
        $pendaftar = $this->db->where('is_synced', 0)
                              ->order_by('id', 'ASC')
                              ->limit($limit)
                              ->get('ppdb_pendaftar')
                              ->result_array();

        $result = [];
        foreach($pendaftar as $p){
            $berkas = $this->db->where('pendaftar_id', $p['id'])->get('ppdb_berkas')->result_array();
            // Berikan URL absolut file berkas agar server lokal bisa mengunduhnya langsung
            foreach($berkas as &$b){
                $b['full_download_url'] = base_url('uploads/ppdb/' . $b['nama_file']);
            }
            $p['berkas_list'] = $berkas;
            $result[] = $p;
        }

        return $result;
    }

    public function mark_as_synced($no_pendaftaran_list){
        if(empty($no_pendaftaran_list)) return 0;
        $this->db->where_in('no_pendaftaran', $no_pendaftaran_list);
        return $this->db->update('ppdb_pendaftar', [
            'is_synced' => 1,
            'synced_at' => date('Y-m-d H:i:s')
        ]);
    }
}
