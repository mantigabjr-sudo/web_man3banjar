<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if(!function_exists('admin_master_roles')){
    function admin_master_roles(){
        return ['admin', 'admin_master'];
    }
}

if(!function_exists('admin_panel_roles')){
    function admin_panel_roles(){
        return [
            'admin',
            'admin_master',
            'admin_humas',
            'wakil_humas',
            'operator_humas',
            'admin_kesiswaan',
            'admin_kurikulum',
            'admin_sarpras'
        ];
    }
}

if(!function_exists('is_admin_role_value')){
    function is_admin_role_value($role){
        return in_array($role, admin_panel_roles());
    }
}

if(!function_exists('is_master_role_value')){
    function is_master_role_value($role){
        return in_array($role, admin_master_roles());
    }
}

if(!function_exists('current_role')){
    function current_role(){
        $CI =& get_instance();
        return $CI->session->userdata('role');
    }
}

if(!function_exists('is_admin_panel')){
    function is_admin_panel(){
        return is_admin_role_value(current_role());
    }
}

if(!function_exists('is_admin_master')){
    function is_admin_master(){
        return is_master_role_value(current_role());
    }
}

if(!function_exists('can_admin_menu')){
    function can_admin_menu($module){

        $role = current_role();

        if(is_master_role_value($role)){
            return true;
        }

        $map = [
            'website'   => ['admin_humas', 'wakil_humas', 'operator_humas'],
            'berita'    => ['admin_humas', 'wakil_humas', 'operator_humas'],

            'ppdb'      => ['admin_kesiswaan'],
            'akademik'  => ['admin_kesiswaan'],

            'kurikulum' => ['admin_kurikulum'],

            'sarpras'   => ['admin_sarpras'],
            'tu_barang' => ['admin_sarpras'],
        ];

        if(empty($map[$module])){
            return false;
        }

        return in_array($role, $map[$module]);
    }
}

if(!function_exists('require_admin_module')){
    function require_admin_module($module){

        $CI =& get_instance();

        if(!$CI->session->userdata('logged_in')){
            redirect('auth');
        }

        if(!is_admin_panel()){
            show_error('Anda tidak memiliki akses ke halaman admin.', 403);
        }

        if(!can_admin_menu($module)){
            show_error('Anda tidak memiliki akses ke menu ini.', 403);
        }
    }
}

if(!function_exists('role_label')){
    function role_label($role){

        $labels = [
            'admin' => 'Admin Master',
            'admin_master' => 'Kepala Madrasah',
            'admin_humas' => 'Wakamad Humas',
            'wakil_humas' => 'Wakil Humas',
            'operator_humas' => 'Operator Humas',
            'admin_kesiswaan' => 'Wakamad Kesiswaan',
            'admin_kurikulum' => 'Wakamad Kurikulum',
            'admin_sarpras' => 'Wakamad Sarpras',
            'guru' => 'Guru',
            'teknisi' => 'Teknisi'
        ];

        return $labels[$role] ?? strtoupper($role);
    }
}

if(!function_exists('sync_tugas_tambahan_ptk')){
    function sync_tugas_tambahan_ptk($ptk_id, $tahun_ajaran, $semester){
        $CI =& get_instance();
        
        $tugas = [];

        // 1. Cek dari Wali Kelas
        $walas = $CI->db
            ->select('kelas.nama_kelas')
            ->from('wali_kelas')
            ->join('kelas','kelas.id = wali_kelas.kelas_id')
            ->where('wali_kelas.ptk_id', $ptk_id)
            ->where('wali_kelas.tahun_ajaran', $tahun_ajaran)
            ->where('wali_kelas.semester', $semester)
            ->get()
            ->result();

        foreach($walas as $w){
            $tugas[] = 'Wali Kelas ' . $w->nama_kelas;
        }

        // 2. Cek dari Tugas Tambahan Mengajar
        $tambahan = $CI->db
            ->select('guru_tugas_tambahan.*, kelas.nama_kelas')
            ->from('guru_tugas_tambahan')
            ->join('kelas', 'kelas.id = guru_tugas_tambahan.kelas_id', 'left')
            ->where('guru_tugas_tambahan.ptk_id', $ptk_id)
            ->where('guru_tugas_tambahan.tahun_ajaran', $tahun_ajaran)
            ->where('guru_tugas_tambahan.semester', $semester)
            ->where('guru_tugas_tambahan.status', 'Aktif')
            ->order_by('guru_tugas_tambahan.nama_tugas', 'ASC')
            ->get()
            ->result();

        foreach($tambahan as $t){
            $nama = trim($t->nama_tugas);
            if(!empty($t->nama_kelas) && stripos($nama, trim($t->nama_kelas)) === false){
                $nama .= ' ' . trim($t->nama_kelas);
            }
            // Hindari duplikasi dengan blok wali kelas di atas
            if(stripos($nama, 'wali kelas') === false){
                $tugas[] = $nama;
            }
        }

        // Gabungkan
        $string_tugas = implode(', ', array_unique($tugas));

        // Update ke Master PTK
        $CI->db->where('id', $ptk_id);
        $CI->db->update('ptk', [
            'tugas_tambahan' => $string_tugas
        ]);
    }
}

if(!function_exists('sync_mapel_ptk')){
    function sync_mapel_ptk($ptk_id, $tahun_ajaran, $semester){
        $CI =& get_instance();

        // Cari semua mapel dari tugas mengajar yang aktif
        $mengajar = $CI->db
            ->select('mapel.nama_mapel, mapel.kelompok, mapel.jurusan')
            ->from('tugas_mengajar')
            ->join('mapel', 'mapel.id = tugas_mengajar.mapel_id')
            ->where('tugas_mengajar.ptk_id', $ptk_id)
            ->where('tugas_mengajar.tahun_ajaran', $tahun_ajaran)
            ->where('tugas_mengajar.semester', $semester)
            ->where('tugas_mengajar.status', 'Aktif')
            ->group_by('mapel.id')
            ->get()
            ->result();

        $mapels = [];
        foreach($mengajar as $m){
            $mapels[] = format_nama_mapel($m->nama_mapel, $m->kelompok, $m->jurusan);
        }

        $string_mapel = implode(', ', array_unique($mapels));

        $CI->db->where('id', $ptk_id);
        $CI->db->update('ptk', [
            'mapel_utama' => $string_mapel
        ]);
    }
}

if(!function_exists('format_nama_mapel')){
    function format_nama_mapel($nama_mapel, $kelompok, $jurusan){
        $lbl = '';
        if(stripos($kelompok, 'Pilihan') !== false) {
            $lbl = 'Pilihan';
        } elseif(stripos($kelompok, 'Umum') !== false) {
            $lbl = 'Umum';
        } elseif(!empty($jurusan) && strtoupper($jurusan) !== 'UMUM') {
            $lbl = $jurusan;
        }
        return trim($nama_mapel) . ($lbl ? " ($lbl)" : '');
    }
}