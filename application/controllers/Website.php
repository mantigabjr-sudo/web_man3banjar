<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Website extends CI_Controller {

    private function base_data($title = 'Website Madrasah'){

        $data['nama_madrasah'] = 'MAN 3 Banjar';
        $data['page_title'] = $title;

        $data['profil_website'] = null;

        if($this->db->table_exists('website_profil')){
            $data['profil_website'] = $this->db
                ->limit(1)
                ->get('website_profil')
                ->row();
        }

        return $data;
    }

    private function setup_pagination($base_url, $total_rows, $per_page = 9){

        $this->load->library('pagination');

        $config['base_url'] = base_url($base_url);
        $config['total_rows'] = $total_rows;
        $config['per_page'] = $per_page;

        $config['page_query_string'] = TRUE;
        $config['query_string_segment'] = 'page';
        $config['reuse_query_string'] = TRUE;
        $config['use_page_numbers'] = TRUE;

        $config['full_tag_open'] = '<div class="web-pagination">';
        $config['full_tag_close'] = '</div>';

        $config['first_link'] = 'Awal';
        $config['last_link'] = 'Akhir';
        $config['next_link'] = 'Berikutnya';
        $config['prev_link'] = 'Sebelumnya';

        $config['first_tag_open'] = '<span>';
        $config['first_tag_close'] = '</span>';
        $config['last_tag_open'] = '<span>';
        $config['last_tag_close'] = '</span>';
        $config['next_tag_open'] = '<span>';
        $config['next_tag_close'] = '</span>';
        $config['prev_tag_open'] = '<span>';
        $config['prev_tag_close'] = '</span>';
        $config['num_tag_open'] = '<span>';
        $config['num_tag_close'] = '</span>';
        $config['cur_tag_open'] = '<span class="active">';
        $config['cur_tag_close'] = '</span>';

        $this->pagination->initialize($config);

        return $this->pagination->create_links();
    }

    private function current_page(){
        $page = (int)$this->input->get('page');
        return $page > 0 ? $page : 1;
    }

    private function offset($page, $per_page){
        return ($page - 1) * $per_page;
    }

    private function kategori_berita_options(){
        return ['Prestasi','Kegiatan','Pengumuman','PPDB','Akademik','Keagamaan','Ekstrakurikuler'];
    }

    private function apply_berita_filter($q, $bulan, $kategori){
        if($this->db->field_exists('status_berita', 'berita')){
            $this->db->where('status_berita', 'Published');
        }

        if(!empty($kategori) && $this->db->field_exists('kategori', 'berita')){
            $this->db->where('kategori', $kategori);
        }

        if(!empty($q)){
            $this->db->group_start();
            $this->db->like('judul', $q);
            $this->db->or_like('isi', $q);
            $this->db->group_end();
        }

        if(!empty($bulan)){
            $date_col = $this->db->field_exists('published_at', 'berita') ? 'published_at' : 'created_at';
            $this->db->where("DATE_FORMAT($date_col, '%Y-%m') =", $bulan);
        }
    }

    public function berita(){

        $data = $this->base_data('Arsip Berita');

        $q = trim($this->input->get('q'));
        $bulan = trim($this->input->get('bulan'));
        $kategori = trim($this->input->get('kategori'));
        $kategori_options = $this->kategori_berita_options();

        if(!in_array($kategori, $kategori_options)){
            $kategori = '';
        }

        $per_page = 9;
        $page = $this->current_page();
        $offset = $this->offset($page, $per_page);

        $this->apply_berita_filter($q, $bulan, $kategori);
        $total_rows = $this->db->count_all_results('berita');

        $this->apply_berita_filter($q, $bulan, $kategori);
        $order_col = $this->db->field_exists('published_at', 'berita') ? 'published_at' : 'created_at';

        $data['berita'] = $this->db
            ->order_by($order_col, 'DESC')
            ->order_by('id', 'DESC')
            ->limit($per_page, $offset)
            ->get('berita')
            ->result();

        $data['berita_populer'] = [];
        if($this->db->field_exists('view_count', 'berita')){
            if($this->db->field_exists('status_berita', 'berita')){
                $this->db->where('status_berita', 'Published');
            }
            $data['berita_populer'] = $this->db
                ->order_by('view_count', 'DESC')
                ->order_by('id', 'DESC')
                ->limit(5)
                ->get('berita')
                ->result();
        }

        $data['q'] = $q;
        $data['bulan'] = $bulan;
        $data['kategori'] = $kategori;
        $data['kategori_options'] = $kategori_options;
        $data['pagination'] = $this->setup_pagination('website/berita', $total_rows, $per_page);
        $data['total_rows'] = $total_rows;
        $data['per_page'] = $per_page;
        $data['current_page'] = $page;

        $this->load->view('public/arsip_berita', $data);
    }

    public function pamflet(){

        $data = $this->base_data('Arsip Pamflet');

        $q = trim($this->input->get('q'));
        $bulan = trim($this->input->get('bulan'));

        $per_page = 9;
        $page = $this->current_page();
        $offset = $this->offset($page, $per_page);

        $this->db->where('status', 'Published');

        if(!empty($q)){
            $this->db->group_start();
            $this->db->like('judul', $q);
            $this->db->or_like('deskripsi', $q);
            $this->db->group_end();
        }

        if(!empty($bulan)){
            $this->db->where("DATE_FORMAT(tanggal, '%Y-%m') =", $bulan);
        }

        $total_rows = $this->db->count_all_results('website_pamflet');

        $this->db->where('status', 'Published');

        if(!empty($q)){
            $this->db->group_start();
            $this->db->like('judul', $q);
            $this->db->or_like('deskripsi', $q);
            $this->db->group_end();
        }

        if(!empty($bulan)){
            $this->db->where("DATE_FORMAT(tanggal, '%Y-%m') =", $bulan);
        }

        $data['pamflet'] = $this->db
            ->order_by('tanggal', 'DESC')
            ->order_by('created_at', 'DESC')
            ->limit($per_page, $offset)
            ->get('website_pamflet')
            ->result();

        $data['q'] = $q;
        $data['bulan'] = $bulan;
        $data['pagination'] = $this->setup_pagination('website/pamflet', $total_rows, $per_page);
        $data['total_rows'] = $total_rows;
        $data['per_page'] = $per_page;
        $data['current_page'] = $page;

        $this->load->view('public/arsip_pamflet', $data);
    }

    public function galeri(){

        $data = $this->base_data('Arsip Galeri');

        $q = trim($this->input->get('q'));
        $bulan = trim($this->input->get('bulan'));

        $per_page = 12;
        $page = $this->current_page();
        $offset = $this->offset($page, $per_page);

        $this->db->where('status', 'Published');

        if(!empty($q)){
            $this->db->group_start();
            $this->db->like('judul', $q);
            $this->db->or_like('deskripsi', $q);
            $this->db->group_end();
        }

        if(!empty($bulan)){
            $this->db->where("DATE_FORMAT(tanggal, '%Y-%m') =", $bulan);
        }

        $total_rows = $this->db->count_all_results('website_galeri');

        $this->db->where('status', 'Published');

        if(!empty($q)){
            $this->db->group_start();
            $this->db->like('judul', $q);
            $this->db->or_like('deskripsi', $q);
            $this->db->group_end();
        }

        if(!empty($bulan)){
            $this->db->where("DATE_FORMAT(tanggal, '%Y-%m') =", $bulan);
        }

        $data['galeri'] = $this->db
            ->order_by('tanggal', 'DESC')
            ->order_by('created_at', 'DESC')
            ->limit($per_page, $offset)
            ->get('website_galeri')
            ->result();

        $data['q'] = $q;
        $data['bulan'] = $bulan;
        $data['pagination'] = $this->setup_pagination('website/galeri', $total_rows, $per_page);
        $data['total_rows'] = $total_rows;
        $data['per_page'] = $per_page;
        $data['current_page'] = $page;

        $this->load->view('public/arsip_galeri', $data);
    }

    public function ptk(){

        $data = $this->base_data('Direktori & Statistik PTK');

        $q = trim($this->input->get('q'));
        $jenis = trim($this->input->get('jenis'));

        $per_page = 12;
        $page = $this->current_page();
        $offset = $this->offset($page, $per_page);

        // 1. Calculate Comprehensive Statistics for Active PTK
        $this->db->select("
            COUNT(*) as total_ptk,
            SUM(CASE WHEN jenis_ptk = 'Pendidik' THEN 1 ELSE 0 END) as total_pendidik,
            SUM(CASE WHEN jenis_ptk = 'Kependidikan' THEN 1 ELSE 0 END) as total_kependidikan,
            SUM(CASE WHEN jenis_kelamin = 'L' THEN 1 ELSE 0 END) as total_l,
            SUM(CASE WHEN jenis_kelamin = 'P' THEN 1 ELSE 0 END) as total_p,
            SUM(CASE WHEN status_kepegawaian = 'PNS' THEN 1 ELSE 0 END) as total_pns,
            SUM(CASE WHEN status_kepegawaian = 'PPPK' THEN 1 ELSE 0 END) as total_pppk,
            SUM(CASE WHEN status_kepegawaian NOT IN ('PNS', 'PPPK') OR status_kepegawaian IS NULL OR status_kepegawaian = '' THEN 1 ELSE 0 END) as total_non_asn
        ");
        if($this->db->field_exists('status_aktif', 'ptk')){
            $this->db->group_start();
            $this->db->where('status_aktif', 'Aktif');
            $this->db->or_where('status_aktif', '');
            $this->db->or_where('status_aktif IS NULL', null, false);
            $this->db->group_end();
        }
        $data['stats_summary'] = $this->db->get('ptk')->row();

        // 2. Matrix Breakdown for Table and Charts
        $matrix = [
            'pendidik' => [
                'pns_l' => 0, 'pns_p' => 0, 'pns_total' => 0,
                'pppk_l' => 0, 'pppk_p' => 0, 'pppk_total' => 0,
                'non_asn_l' => 0, 'non_asn_p' => 0, 'non_asn_total' => 0,
                'total_l' => 0, 'total_p' => 0, 'total' => 0
            ],
            'kependidikan' => [
                'pns_l' => 0, 'pns_p' => 0, 'pns_total' => 0,
                'pppk_l' => 0, 'pppk_p' => 0, 'pppk_total' => 0,
                'non_asn_l' => 0, 'non_asn_p' => 0, 'non_asn_total' => 0,
                'total_l' => 0, 'total_p' => 0, 'total' => 0
            ]
        ];

        $this->db->select('jenis_ptk, status_kepegawaian, jenis_kelamin, COUNT(*) as cnt');
        if($this->db->field_exists('status_aktif', 'ptk')){
            $this->db->group_start();
            $this->db->where('status_aktif', 'Aktif');
            $this->db->or_where('status_aktif', '');
            $this->db->or_where('status_aktif IS NULL', null, false);
            $this->db->group_end();
        }
        $raw_matrix = $this->db->group_by(['jenis_ptk', 'status_kepegawaian', 'jenis_kelamin'])->get('ptk')->result();

        foreach($raw_matrix as $r){
            $jp = ($r->jenis_ptk == 'Kependidikan') ? 'kependidikan' : 'pendidik';
            $jk = ($r->jenis_kelamin == 'P') ? 'p' : 'l';
            $sk = strtoupper(trim((string)$r->status_kepegawaian));
            $cnt = (int)$r->cnt;

            if($sk == 'PNS'){
                $matrix[$jp]['pns_'.$jk] += $cnt;
                $matrix[$jp]['pns_total'] += $cnt;
            } elseif($sk == 'PPPK'){
                $matrix[$jp]['pppk_'.$jk] += $cnt;
                $matrix[$jp]['pppk_total'] += $cnt;
            } else {
                $matrix[$jp]['non_asn_'.$jk] += $cnt;
                $matrix[$jp]['non_asn_total'] += $cnt;
            }

            $matrix[$jp]['total_'.$jk] += $cnt;
            $matrix[$jp]['total'] += $cnt;
        }
        $data['matrix'] = $matrix;

        // 3. Paginated and Filtered PTK Directory List
        if($this->db->field_exists('tampil_website', 'ptk')){
            $this->db->where('tampil_website', 1);
        }

        if($this->db->field_exists('status_aktif', 'ptk')){
            $this->db->group_start();
            $this->db->where('status_aktif', 'Aktif');
            $this->db->or_where('status_aktif', '');
            $this->db->or_where('status_aktif IS NULL', null, false);
            $this->db->group_end();
        }

        if(!empty($jenis)){
            $this->db->where('jenis_ptk', $jenis);
        }

        if(!empty($q)){
            $this->db->group_start();
            $this->db->like('nama_lengkap', $q);
            $this->db->or_like('jabatan', $q);
            $this->db->or_like('mapel_utama', $q);
            $this->db->or_like('tugas_utama', $q);
            $this->db->group_end();
        }

        $total_rows = $this->db->count_all_results('ptk');

        if($this->db->field_exists('tampil_website', 'ptk')){
            $this->db->where('tampil_website', 1);
        }

        if($this->db->field_exists('status_aktif', 'ptk')){
            $this->db->group_start();
            $this->db->where('status_aktif', 'Aktif');
            $this->db->or_where('status_aktif', '');
            $this->db->or_where('status_aktif IS NULL', null, false);
            $this->db->group_end();
        }

        if(!empty($jenis)){
            $this->db->where('jenis_ptk', $jenis);
        }

        if(!empty($q)){
            $this->db->group_start();
            $this->db->like('nama_lengkap', $q);
            $this->db->or_like('jabatan', $q);
            $this->db->or_like('mapel_utama', $q);
            $this->db->or_like('tugas_utama', $q);
            $this->db->group_end();
        }

        if($this->db->field_exists('urutan_website', 'ptk')){
            $this->db->order_by('urutan_website', 'ASC');
        }

        $data['ptk'] = $this->db
            ->order_by('nama_lengkap', 'ASC')
            ->limit($per_page, $offset)
            ->get('ptk')
            ->result();

        $data['q'] = $q;
        $data['jenis'] = $jenis;
        $data['pagination'] = $this->setup_pagination('website/ptk', $total_rows, $per_page);
        $data['total_rows'] = $total_rows;
        $data['per_page'] = $per_page;
        $data['current_page'] = $page;

        $this->load->view('public/arsip_ptk', $data);
    }

    public function struktur($slug = '') {
        $slug = strtolower(trim($slug));
        
        $map = [
            'tenaga-pendidik' => 'Tenaga Pendidik',
            'kependidikan' => 'Kependidikan',
            'koordinator' => 'Koordinator'
        ];

        if(!array_key_exists($slug, $map)){
            show_404();
        }

        $kategori_asli = $map[$slug];
        $data = $this->base_data('Struktur Organisasi - '.$kategori_asli);

        $data['kategori_nama'] = $kategori_asli;
        $data['kategori_slug'] = $slug;

        if ($slug == 'tenaga-pendidik') {
            // 1. Fetch Kepala Madrasah
            $data['kepala_madrasah'] = $this->db->select('so.*, ptk.id as ptk_id, ptk.nama_lengkap, ptk.nip, ptk.foto')
                ->from('struktur_organisasi so')
                ->join('ptk', 'ptk.id = so.ptk_id')
                ->where('so.kategori', 'Kepala Madrasah')
                ->order_by('so.urutan', 'ASC')
                ->get()->result();

            // Fallback jika belum diatur di struktur_organisasi
            if(empty($data['kepala_madrasah'])){
                $data['kepala_madrasah'] = $this->db->select('0 as id, id as ptk_id, "Kepala Madrasah" as jabatan, "Kepala Madrasah" as kategori, nama_lengkap, nip, foto, 1 as urutan')
                    ->from('ptk')
                    ->where('nama_lengkap LIKE', '%Nor Ifansyah%')
                    ->get()->result();
            }

            // 2. Fetch Kaur TU (Kepala Tata Usaha)
            $data['kaur_tu'] = $this->db->select('so.*, ptk.id as ptk_id, ptk.nama_lengkap, ptk.nip, ptk.foto')
                ->from('struktur_organisasi so')
                ->join('ptk', 'ptk.id = so.ptk_id')
                ->where('so.kategori', 'Tata Usaha')
                ->group_start()
                    ->where('so.jabatan LIKE', '%Kaur%')
                    ->or_where('so.urutan', 1)
                ->group_end()
                ->order_by('so.urutan', 'ASC')
                ->limit(1)
                ->get()->result();

            if(empty($data['kaur_tu'])){
                $data['kaur_tu'] = $this->db->select('0 as id, id as ptk_id, "Kepala Urusan Tata Usaha" as jabatan, "Tata Usaha" as kategori, nama_lengkap, nip, foto, 1 as urutan')
                    ->from('ptk')
                    ->where('nama_lengkap LIKE', '%Haris Padilah%')
                    ->get()->result();
            }

            // 3. Fetch Wakamad (Wakil Kepala Madrasah 4 Bidang)
            $wakamad_list = $this->db->select('p.id as ptk_id, p.nama_lengkap, p.nip, p.foto, 
                                COALESCE(so.jabatan, gt.nama_tugas, p.tugas_tambahan) as jabatan,
                                COALESCE(so.urutan, 99) as urutan')
                ->from('ptk p')
                ->join('struktur_organisasi so', 'so.ptk_id = p.id AND so.kategori = "Wakamad"', 'left')
                ->join('guru_tugas_tambahan gt', 'gt.ptk_id = p.id AND gt.nama_tugas LIKE "%Wakamad%" AND gt.status = "Aktif"', 'left')
                ->group_start()
                    ->where('so.kategori', 'Wakamad')
                    ->or_like('gt.nama_tugas', 'Wakamad')
                    ->or_like('p.tugas_tambahan', 'Wakamad')
                ->group_end()
                ->group_by('p.id')
                ->order_by('urutan', 'ASC')
                ->order_by('p.nama_lengkap', 'ASC')
                ->get()->result();

            foreach($wakamad_list as $wk){
                if(stripos($wk->jabatan, 'Kurikulum') !== false){
                    $wk->bidang = 'Kurikulum & Pengajaran';
                    $wk->jabatan_clean = 'Wakamad Kurikulum';
                    $wk->icon = 'bi-journal-check';
                    $wk->color = '#2563eb';
                    $wk->color_bg = '#eff6ff';
                } elseif(stripos($wk->jabatan, 'Kesiswaan') !== false){
                    $wk->bidang = 'Kesiswaan & Ekstrakurikuler';
                    $wk->jabatan_clean = 'Wakamad Kesiswaan';
                    $wk->icon = 'bi-people-fill';
                    $wk->color = '#16a34a';
                    $wk->color_bg = '#f0fdf4';
                } elseif(stripos($wk->jabatan, 'Sarana') !== false || stripos($wk->jabatan, 'Sarpras') !== false){
                    $wk->bidang = 'Sarana & Prasarana';
                    $wk->jabatan_clean = 'Wakamad Sarpras';
                    $wk->icon = 'bi-building-fill-gear';
                    $wk->color = '#ea580c';
                    $wk->color_bg = '#fff7ed';
                } elseif(stripos($wk->jabatan, 'Humas') !== false){
                    $wk->bidang = 'Hubungan Masyarakat & Kemitraan';
                    $wk->jabatan_clean = 'Wakamad Humas';
                    $wk->icon = 'bi-megaphone-fill';
                    $wk->color = '#9333ea';
                    $wk->color_bg = '#faf5ff';
                } else {
                    $wk->bidang = 'Wakil Kepala Madrasah';
                    $wk->jabatan_clean = $wk->jabatan;
                    $wk->icon = 'bi-person-badge-fill';
                    $wk->color = '#0d9488';
                    $wk->color_bg = '#f0fdfa';
                }
            }
            $data['wakamad'] = $wakamad_list;

            // 4. Fetch Tugas Tambahan Khusus (Kepala Lab, Koordinator Kokurikuler, BK/Binaan, Pembina Ekskul)
            $kelas_rows = $this->db->select('id, nama_kelas, tingkat')->get('kelas')->result();
            $kelas_map = [];
            foreach($kelas_rows as $kr){
                $kelas_map[$kr->id] = $kr->nama_kelas;
            }

            $all_pendidik = $this->db->select('p.id as ptk_id, p.nama_lengkap, p.nip, p.foto, p.tugas_tambahan')
                ->from('ptk p')
                ->where('p.jenis_ptk', 'Pendidik')
                ->order_by('p.nama_lengkap', 'ASC')
                ->get()->result();

            $tugas_khusus_result = [];

            // Daftar PTK ID Kamad & Kaur TU agar tidak muncul ganda di level bawah
            $excluded_ptk_ids = [];
            foreach($data['kepala_madrasah'] as $km) { if(!empty($km->ptk_id)) $excluded_ptk_ids[] = $km->ptk_id; }
            foreach($data['kaur_tu'] as $kt) { if(!empty($kt->ptk_id)) $excluded_ptk_ids[] = $kt->ptk_id; }

            // Juga kecualikan Wakamad dari tugas khusus agar hirarki bersih
            foreach($wakamad_list as $wk) { if(!empty($wk->ptk_id)) $excluded_ptk_ids[] = $wk->ptk_id; }

            foreach($all_pendidik as $p){
                $ptk_id = $p->ptk_id;
                if(in_array($ptk_id, $excluded_ptk_ids)) continue;
                if(stripos($p->nama_lengkap, 'Nor Ifansyah') !== false) continue;
                if(stripos($p->nama_lengkap, 'Haris Padilah') !== false) continue;

                $gt_rows = $this->db->select('id, nama_tugas, kelas_id, tahun_ajaran')
                    ->from('guru_tugas_tambahan')
                    ->where('ptk_id', $ptk_id)
                    ->where('status', 'Aktif')
                    ->get()->result();

                $raw_items = [];
                $kokurikuler_classes = [];

                // 1. Ekstrak kelas kokurikuler dari seluruh string tugas_tambahan
                $tt_full = (string)$p->tugas_tambahan;
                if(stripos($tt_full, 'kuri') !== false || stripos($tt_full, 'koku') !== false){
                    if(preg_match_all('/(XII|XI|X)\s*([A-Z])/i', $tt_full, $matches, PREG_SET_ORDER)){
                        foreach($matches as $m){
                            $kokurikuler_classes[] = strtoupper($m[1]) . ' ' . strtoupper($m[2]);
                        }
                    }
                }

                foreach($gt_rows as $g){
                    if(strpos((string)$g->tahun_ajaran, '2025') !== false) continue;
                    $t = trim((string)$g->nama_tugas);
                    if(!empty($t)){
                        $parts = explode('/', $t);
                        foreach($parts as $prt) $raw_items[] = trim($prt);
                    }
                    if(!empty($g->kelas_id) && isset($kelas_map[$g->kelas_id])){
                        if(stripos($t, 'kuri') !== false || stripos($t, 'koku') !== false){
                            $kokurikuler_classes[] = $kelas_map[$g->kelas_id];
                        }
                    }
                }

                $ptk_tt = array_filter(array_map('trim', explode(',', (string)$p->tugas_tambahan)));
                foreach($ptk_tt as $t){
                    $parts = explode('/', $t);
                    foreach($parts as $prt) $raw_items[] = trim($prt);
                }

                $clean_tags = [];
                foreach($raw_items as $item){
                    if(empty($item)) continue;
                    if(stripos($item, 'Wali Kelas') !== false || stripos($item, 'Walikelas') !== false) continue;
                    if(stripos($item, 'Wakamad') !== false || stripos($item, 'Waka Bid') !== false) continue;
                    if(stripos($item, 'Kamad') !== false || stripos($item, 'Kepala Madrasah') !== false) continue;
                    if(stripos($item, 'Satmingkal') !== false || stripos($item, 'Menambah Jam') !== false) continue;

                    // Lewati string mentah kokurikuler karena sudah diproses secara terpusat
                    if(stripos($item, 'Kokurikuler') !== false || stripos($item, 'Kurikuler') !== false || stripos($item, 'Kookurikuler') !== false){
                        continue;
                    }

                    // Deteksi Lab & Perpustakaan
                    if(stripos($item, 'Lab IPA') !== false) { $clean_tags['lab_ipa'] = 'Kepala Lab IPA'; continue; }
                    if(stripos($item, 'Lab Komputer') !== false) { $clean_tags['lab_komp'] = 'Kepala Lab Komputer'; continue; }
                    if(stripos($item, 'Lab Bahasa') !== false) { $clean_tags['lab_bahasa'] = 'Kepala Lab Bahasa'; continue; }
                    if(stripos($item, 'Perpustakaan') !== false) { $clean_tags['perpus'] = 'Kepala Perpustakaan'; continue; }
                    if(stripos($item, 'Keagamaan') !== false) { $clean_tags['keagamaan'] = 'Koordinator Keagamaan'; continue; }
                    if(stripos($item, '5 K') !== false || stripos($item, '5K') !== false) { $clean_tags['5k'] = 'Koordinator 5K'; continue; }

                    // Ekskul & Pembina
                    $item = str_ireplace('Ekslul', 'Ekskul', $item);
                    if(stripos($item, 'Habsy') !== false) { $clean_tags['habsy'] = 'Pembina Ekskul Habsy'; continue; }
                    if(stripos($item, 'KIR') !== false) { $clean_tags['kir'] = 'Pembina Ekskul KIR'; continue; }
                    if(stripos($item, 'PMR') !== false) { $clean_tags['pmr'] = 'Pembina Ekskul PMR'; continue; }
                    if(stripos($item, 'Pramuka Putra') !== false) { $clean_tags['pramuka_pa'] = 'Pembina Pramuka Putra'; continue; }
                    if(stripos($item, 'Pramuka Putri') !== false) { $clean_tags['pramuka_pi'] = 'Pembina Pramuka Putri'; continue; }
                    if(stripos($item, 'Pramuka') !== false) { $clean_tags['pramuka'] = 'Pembina Pramuka'; continue; }
                    if(stripos($item, 'Paskib') !== false) { $clean_tags['paskib'] = 'Pembina Paskibra'; continue; }
                    if(stripos($item, 'Tari') !== false) { $clean_tags['tari'] = 'Pembina Ekskul Seni Tari'; continue; }
                    if(stripos($item, 'Futsal') !== false) { $clean_tags['futsal'] = 'Pembina Ekskul Futsal'; continue; }
                    if(stripos($item, 'OSIM') !== false) { $clean_tags['osim'] = 'Pembina OSIM'; continue; }

                    if(stripos($item, 'Binaan') !== false || stripos($item, 'BK') !== false || stripos($item, 'Pembina Kelas') !== false){
                        $cl = preg_replace('/(Binaan|Pembina)\s*(Kelas)?\s*/i', 'Pembina Kelas ', $item);
                        $clean_tags['binaan_'.md5($cl)] = trim($cl);
                        continue;
                    }
                }

                $kokurikuler_classes = array_values(array_unique($kokurikuler_classes));
                if(!empty($kokurikuler_classes)){
                    usort($kokurikuler_classes, function($a, $b){
                        $weight = function($c){
                            if(preg_match('/^X\s+/i', $c)) return 10;
                            if(preg_match('/^XI\s+/i', $c)) return 20;
                            if(preg_match('/^XII\s+/i', $c)) return 30;
                            return 99;
                        };
                        $wA = $weight($a); $wB = $weight($b);
                        if($wA !== $wB) return $wA <=> $wB;
                        return strnatcasecmp($a, $b);
                    });
                    $clean_tags['kokurikuler'] = 'Koordinator Kokurikuler (' . implode(', ', $kokurikuler_classes) . ')';
                }

                $unique_tags = array_values($clean_tags);

                if(!empty($unique_tags)){
                    $min_weight = 999;
                    foreach($unique_tags as $ut){
                        $w = 900;
                        if(stripos($ut, 'Kepala Lab IPA') !== false) $w = 110;
                        elseif(stripos($ut, 'Kepala Lab Komputer') !== false) $w = 120;
                        elseif(stripos($ut, 'Kepala Lab Bahasa') !== false) $w = 130;
                        elseif(stripos($ut, 'Kepala Lab') !== false) $w = 140;
                        elseif(stripos($ut, 'Kepala Perpustakaan') !== false) $w = 200;
                        elseif(stripos($ut, 'Keagamaan') !== false) $w = 310;
                        elseif(stripos($ut, '5 K') !== false || stripos($ut, '5K') !== false) $w = 320;
                        elseif(stripos($ut, 'Kokurikuler') !== false){
                            if(preg_match('/\(X\s+[A-Z]/i', $ut)) $w = 410;
                            elseif(preg_match('/\(XI\s+[A-Z]/i', $ut)) $w = 420;
                            elseif(preg_match('/\(XII\s+[A-Z]/i', $ut)) $w = 430;
                            else $w = 440;
                        }
                        elseif(stripos($ut, 'Pembina Kelas X') !== false && stripos($ut, 'XI') === false && stripos($ut, 'XII') === false) $w = 510;
                        elseif(stripos($ut, 'Pembina Kelas XI') !== false && stripos($ut, 'XII') === false) $w = 520;
                        elseif(stripos($ut, 'Pembina Kelas XII') !== false) $w = 530;
                        elseif(stripos($ut, 'OSIM') !== false) $w = 600;
                        elseif(stripos($ut, 'Pramuka Putra') !== false) $w = 610;
                        elseif(stripos($ut, 'Pramuka Putri') !== false) $w = 615;
                        elseif(stripos($ut, 'Pramuka') !== false) $w = 618;
                        elseif(stripos($ut, 'Paskib') !== false) $w = 620;
                        elseif(stripos($ut, 'Tari') !== false) $w = 630;
                        elseif(stripos($ut, 'Habsy') !== false) $w = 640;
                        elseif(stripos($ut, 'KIR') !== false) $w = 650;
                        elseif(stripos($ut, 'Futsal') !== false) $w = 660;
                        else $w = 700;

                        if($w < $min_weight) $min_weight = $w;
                    }

                    $p->cleaned_tags = $unique_tags;
                    $p->sort_weight = $min_weight;
                    $tugas_khusus_result[] = $p;
                }
            }

            usort($tugas_khusus_result, function($a, $b){
                if($a->sort_weight !== $b->sort_weight){
                    return $a->sort_weight <=> $b->sort_weight;
                }
                return strcasecmp($a->nama_lengkap, $b->nama_lengkap);
            });

            $data['tugas_khusus'] = $tugas_khusus_result;

            // 5. Fetch Wali Kelas per Tingkat (X, XI, XII)
            $wali_raw = $this->db->select('wk.id, k.id as kelas_id, k.nama_kelas, k.tingkat, k.jurusan, p.id as ptk_id, p.nama_lengkap, p.nip, p.foto')
                ->from('wali_kelas wk')
                ->join('kelas k', 'k.id = wk.kelas_id')
                ->join('ptk p', 'p.id = wk.ptk_id')
                ->where('k.tahun_ajaran', '2026/2027')
                ->order_by("FIELD(k.tingkat, 'X', 'XI', 'XII', '10', '11', '12')", 'ASC', false)
                ->order_by('k.nama_kelas', 'ASC')
                ->get()->result();

            if(empty($wali_raw)){
                $wali_raw = $this->db->select('wk.id, k.id as kelas_id, k.nama_kelas, k.tingkat, k.jurusan, p.id as ptk_id, p.nama_lengkap, p.nip, p.foto')
                    ->from('wali_kelas wk')
                    ->join('kelas k', 'k.id = wk.kelas_id')
                    ->join('ptk p', 'p.id = wk.ptk_id')
                    ->group_by('k.nama_kelas')
                    ->order_by("FIELD(k.tingkat, 'X', 'XI', 'XII', '10', '11', '12')", 'ASC', false)
                    ->order_by('k.nama_kelas', 'ASC')
                    ->get()->result();
            }

            $data['wali_kelas_grouped'] = [
                'Tingkat X' => [],
                'Tingkat XI' => [],
                'Tingkat XII' => []
            ];
            foreach($wali_raw as $w){
                $key = 'Tingkat ' . $w->tingkat;
                if(!isset($data['wali_kelas_grouped'][$key])){
                    $data['wali_kelas_grouped'][$key] = [];
                }
                $data['wali_kelas_grouped'][$key][] = $w;
            }
            $data['wali_kelas'] = $wali_raw;

            // 6. Fetch Dewan Guru / Tenaga Pendidik Lengkap
            $data['guru'] = $this->db->select('p.id as ptk_id, p.nama_lengkap, p.nip, p.foto, p.jenis_ptk, p.tugas_tambahan,
                                GROUP_CONCAT(DISTINCT m.nama_mapel SEPARATOR ", ") as mapel_diampu')
                ->from('ptk p')
                ->join('tugas_mengajar tm', 'tm.ptk_id = p.id AND tm.status = "Aktif"', 'left')
                ->join('mapel m', 'm.id = tm.mapel_id', 'left')
                ->where('p.jenis_ptk', 'Pendidik')
                ->or_where('p.id IN (SELECT ptk_id FROM struktur_organisasi WHERE kategori = "Guru")', NULL, FALSE)
                ->group_by('p.id')
                ->order_by('p.nama_lengkap', 'ASC')
                ->get()->result();
        } elseif ($slug == 'kependidikan') {
            // Tata Usaha (Kependidikan)
            // Fetch all Tata Usaha members
            $data['anggota'] = $this->db->select('so.*, ptk.nama_lengkap, ptk.nip, ptk.foto')
                ->from('struktur_organisasi so')
                ->join('ptk', 'ptk.id = so.ptk_id')
                ->where('so.kategori', 'Tata Usaha')
                ->order_by('so.urutan', 'ASC')
                ->order_by('so.id', 'ASC')
                ->get()->result();
        } else {
            // Koordinator
            // Fetch Koordinator Eskul
            $data['anggota'] = $this->db->select('so.*, ptk.nama_lengkap, ptk.nip, ptk.foto')
                ->from('struktur_organisasi so')
                ->join('ptk', 'ptk.id = so.ptk_id')
                ->where('so.kategori', 'Koordinator Eskul')
                ->order_by('so.urutan', 'ASC')
                ->order_by('so.id', 'ASC')
                ->get()->result();
        }

        $this->load->view('public/arsip_struktur', $data);
    }

    public function sejarah() {
        $data = $this->base_data('Sejarah Madrasah');
        // Pastikan fasilitas dan prestasi juga tersedia jika dibutuhkan di sidebar
        if(!empty($data['profil_website']->fasilitas)){
            $data['fasilitas_items'] = array_filter(array_map('trim', explode("\n", $data['profil_website']->fasilitas)));
        } else {
            $data['fasilitas_items'] = [];
        }
        $this->load->view('public/sejarah', $data);
    }

    public function visi_misi() {
        $data = $this->base_data('Visi & Misi Madrasah');
        $this->load->view('public/visi_misi', $data);
    }

    public function fasilitas() {
        $data = $this->base_data('Fasilitas Madrasah');
        if(!empty($data['profil_website']->fasilitas)){
            $data['fasilitas_items'] = array_filter(array_map('trim', explode("\n", $data['profil_website']->fasilitas)));
        } else {
            $data['fasilitas_items'] = [];
        }
        $this->load->view('public/fasilitas', $data);
    }

    public function download() {
        $data = $this->base_data('Download File');
        $data['downloads'] = $this->db
            ->order_by('tanggal', 'DESC')
            ->get('website_download')
            ->result();
            
        $this->load->view('public/download', $data);
    }

    public function alumni() {
        $data = $this->base_data('Direktori Alumni');
        
        $tahun = $this->input->get('tahun');
        $data['tahun_pilihan'] = $tahun;

        $data['list_tahun'] = $this->db
            ->select('DISTINCT(tahun_ajaran_lulus) as tahun')
            ->from('alumni')
            ->order_by('tahun_ajaran_lulus', 'DESC')
            ->get()
            ->result();

        $this->db->select('alumni.*, siswa.jk')
                 ->from('alumni')
                 ->join('siswa', 'siswa.id = alumni.siswa_id', 'left');

        if (!empty($tahun)) {
            $this->db->where('alumni.tahun_ajaran_lulus', $tahun);
        }

        $data['alumni'] = $this->db->order_by('alumni.tahun_ajaran_lulus', 'DESC')
                                   ->order_by('alumni.nama_lengkap', 'ASC')
                                   ->get()
                                   ->result();

        $this->load->view('public/alumni', $data);
    }
    public function data_siswa() {
        $data = $this->base_data('Keadaan Siswa');
        
        $setting = $this->db->get('settings')->row();
        $default_ta = ($setting && !empty($setting->tahun_ajaran)) ? $setting->tahun_ajaran : '2026/2027';
        
        // Year filter from GET, otherwise fallback to default
        $req_ta = $this->input->get('ta', TRUE);
        $tahun_ajaran = !empty($req_ta) ? trim($req_ta) : $default_ta;
        
        // Fetch all distinct available tahun_ajaran
        $ta_list_raw = $this->db->distinct()->select('tahun_ajaran')->get('siswa_kelas')->result();
        $list_ta = [];
        foreach($ta_list_raw as $t) {
            if(!empty($t->tahun_ajaran)) {
                $list_ta[$t->tahun_ajaran] = $t->tahun_ajaran;
            }
        }
        if(!isset($list_ta[$default_ta])) {
            $list_ta[$default_ta] = $default_ta;
        }
        krsort($list_ta);
        $data['list_ta'] = array_values($list_ta);
        $data['tahun_ajaran'] = $tahun_ajaran;
        $data['is_active_ta'] = ($tahun_ajaran == $default_ta);

        // Status filter: If active year check Aktif, if historical fallback to all valid states
        $has_aktif = $this->db->where('tahun_ajaran', $tahun_ajaran)->where('status', 'Aktif')->count_all_results('siswa_kelas');
        $status_filter = ($has_aktif > 0) ? ['Aktif'] : ['Aktif', 'Naik', 'Lulus', 'Tinggal'];

        $total = $this->db->from('siswa_kelas')
            ->where('tahun_ajaran', $tahun_ajaran)
            ->where_in('status', $status_filter)
            ->count_all_results();
            
        $gender_query = $this->db->select('siswa.jk, count(*) as count')
            ->from('siswa_kelas')
            ->join('siswa', 'siswa.id = siswa_kelas.siswa_id')
            ->where('siswa_kelas.tahun_ajaran', $tahun_ajaran)
            ->where_in('siswa_kelas.status', $status_filter)
            ->group_by('siswa.jk')
            ->get()->result();
            
        $lk = 0; $pr = 0;
        foreach($gender_query as $g) {
            if($g->jk == 'L') $lk = (int)$g->count;
            if($g->jk == 'P') $pr = (int)$g->count;
        }
        $data['total'] = $total;
        $data['total_l'] = $lk;
        $data['total_p'] = $pr;
        
        $kelas = $this->db->where('tahun_ajaran', $tahun_ajaran)
            ->order_by('tingkat', 'ASC')
            ->order_by('nama_kelas', 'ASC')
            ->get('kelas')->result();
            
        $kelas_counts = $this->db->select('siswa_kelas.kelas_id, siswa.jk, count(*) as count')
            ->from('siswa_kelas')
            ->join('siswa', 'siswa.id = siswa_kelas.siswa_id')
            ->where('siswa_kelas.tahun_ajaran', $tahun_ajaran)
            ->where_in('siswa_kelas.status', $status_filter)
            ->group_by('siswa_kelas.kelas_id, siswa.jk')
            ->get()->result();
            
        $rekaps = [];
        $tingkats = [
            'X' => ['total' => 0, 'L' => 0, 'P' => 0, 'rombel' => 0],
            'XI' => ['total' => 0, 'L' => 0, 'P' => 0, 'rombel' => 0],
            'XII' => ['total' => 0, 'L' => 0, 'P' => 0, 'rombel' => 0]
        ];

        foreach($kelas as $k) {
            $rekaps[$k->id] = [
                'id' => $k->id,
                'nama_kelas' => $k->nama_kelas,
                'tingkat' => $k->tingkat,
                'L' => 0,
                'P' => 0,
                'Total' => 0
            ];
            if (!isset($tingkats[$k->tingkat])) {
                $tingkats[$k->tingkat] = ['total' => 0, 'L' => 0, 'P' => 0, 'rombel' => 0];
            }
            $tingkats[$k->tingkat]['rombel']++;
        }
        
        foreach($kelas_counts as $kc) {
            if(isset($rekaps[$kc->kelas_id])) {
                $t_code = $rekaps[$kc->kelas_id]['tingkat'];
                if($kc->jk == 'L') {
                    $rekaps[$kc->kelas_id]['L'] += (int)$kc->count;
                    $tingkats[$t_code]['L'] += (int)$kc->count;
                }
                if($kc->jk == 'P') {
                    $rekaps[$kc->kelas_id]['P'] += (int)$kc->count;
                    $tingkats[$t_code]['P'] += (int)$kc->count;
                }
                $rekaps[$kc->kelas_id]['Total'] += (int)$kc->count;
                $tingkats[$t_code]['total'] += (int)$kc->count;
            }
        }

        // Count total active rombel
        $total_rombel = count($kelas);
        $avg_siswa = ($total > 0 && $total_rombel > 0) ? round($total / $total_rombel, 1) : 0;
        
        $data['total_rombel'] = $total_rombel;
        $data['avg_siswa'] = $avg_siswa;
        $data['rekap_kelas'] = $rekaps;
        $data['rekap_tingkat'] = $tingkats;

        $this->load->view('public/data_siswa', $data);
    }

    private function hari_indo($n){
        $hari = [
            1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu', 7 => 'Minggu'
        ];
        return $hari[$n] ?? 'Senin';
    }

    public function monitoring_kbm(){

        $data = $this->base_data('Live Monitoring Jadwal KBM & Guru Piket');

        $setting = $this->db->get('settings')->row();
        $tahun_ajaran = ($setting && !empty($setting->tahun_ajaran)) ? $setting->tahun_ajaran : date('Y').'-'.(date('Y')+1);
        $semester = ($setting && !empty($setting->semester_aktif)) ? $setting->semester_aktif : 'Ganjil';

        $tanggal = $this->input->get('tanggal');
        if(empty($tanggal)){
            $tanggal = date('Y-m-d');
        }

        $hari = $this->hari_indo(date('N', strtotime($tanggal)));
        $tingkat = $this->input->get('tingkat');
        $kelas_id = $this->input->get('kelas_id');
        $filter_jam = $this->input->get('jam_ke');
        $only_active = $this->input->get('only_active');

        $data['tanggal'] = $tanggal;
        $data['hari'] = $hari;
        $data['tahun_ajaran'] = $tahun_ajaran;
        $data['semester'] = $semester;
        $data['tingkat'] = $tingkat;
        $data['kelas_id'] = $kelas_id;
        $data['filter_jam'] = $filter_jam;
        $data['only_active'] = $only_active;

        // Normalisasi & Filter Tingkat Kelas
        $this->db->where('tahun_ajaran', $tahun_ajaran);
        if(!empty($tingkat)){
            $t_upper = strtoupper(trim($tingkat));
            if($t_upper == '10' || $t_upper == 'X'){
                $this->db->where_in('tingkat', ['10', 'X']);
            } elseif($t_upper == '11' || $t_upper == 'XI'){
                $this->db->where_in('tingkat', ['11', 'XI']);
            } elseif($t_upper == '12' || $t_upper == 'XII'){
                $this->db->where_in('tingkat', ['12', 'XII']);
            } else {
                $this->db->where('tingkat', $t_upper);
            }
        }
        $data['kelas_list'] = $this->db->order_by('tingkat', 'ASC')->order_by('nama_kelas', 'ASC')->get('kelas')->result();

        // Slot Jam Hari Ini
        $is_senin_kamis = in_array($hari, ['Senin', 'Selasa', 'Rabu', 'Kamis']);
        $jam_map = [];
        $slots = [];
        if($this->db->table_exists('jadwal_jam')){
            $cek_khusus = $this->db->where('status', 'Aktif')->where('hari', $hari)->count_all_results('jadwal_jam');
            $this->db->where('status', 'Aktif');
            if($cek_khusus > 0){
                $this->db->where('hari', $hari);
            } else {
                if ($is_senin_kamis) {
                    $this->db->where_in('hari', ['Semua', 'Senin - Kamis']);
                } else {
                    $this->db->where('hari', 'Semua');
                }
            }
            $slots = $this->db->order_by('CAST(jam_ke AS UNSIGNED)', 'ASC', FALSE)->get('jadwal_jam')->result();
            foreach($slots as $s){
                $jam_map[(string)$s->jam_ke] = [
                    'mulai' => substr($s->jam_mulai, 0, 5),
                    'selesai' => substr($s->jam_selesai, 0, 5),
                ];
            }
        }
        $data['jam_slots'] = $slots;

        // Deteksi Jam Keaktifan KBM Saat Ini (Real-Time Clock)
        $current_time = date('H:i:s');
        $jam_ke_aktif = null;
        $jam_aktif_label = 'Di Luar Jam Pelajaran';
        $jam_aktif_range = '';
        $is_today = ($tanggal == date('Y-m-d'));

        if(!empty($slots)){
            foreach($slots as $s){
                $mulai = $s->jam_mulai;
                $selesai = $s->jam_selesai;
                if($current_time >= $mulai && $current_time <= $selesai){
                    $jam_ke_aktif = (string)$s->jam_ke;
                    $jam_aktif_range = substr($mulai, 0, 5) . ' - ' . substr($selesai, 0, 5);
                    $jam_aktif_label = 'Jam Ke-' . $s->jam_ke . ' (' . $jam_aktif_range . ')';
                    break;
                }
            }

            if(!$jam_ke_aktif){
                $first_slot = $slots[0]->jam_mulai;
                $last_slot = end($slots)->jam_selesai;
                if($current_time >= $first_slot && $current_time <= $last_slot){
                    $jam_aktif_label = 'Waktu Istirahat / Transisi Jam KBM';
                } elseif($current_time < $first_slot) {
                    $jam_aktif_label = 'Sebelum KBM Dimulai (Mulai ' . substr($first_slot, 0, 5) . ')';
                } else {
                    $jam_aktif_label = 'KBM Hari Ini Telah Selesai';
                }
            }
        }

        $data['jam_ke_aktif'] = $jam_ke_aktif;
        $data['jam_aktif_label'] = $jam_aktif_label;
        $data['jam_aktif_range'] = $jam_aktif_range;
        $data['is_today'] = $is_today;

        // Ambil Jadwal Hari Ini
        $jadwal_list = [];
        if($this->db->table_exists('jadwal_mengajar')){
            $this->db
                ->select('
                    jadwal_mengajar.ptk_id,
                    jadwal_mengajar.kelas_id,
                    jadwal_mengajar.mapel_id,
                    MIN(CAST(jadwal_mengajar.jam_ke AS UNSIGNED)) as jam_mulai_ke,
                    MAX(CAST(jadwal_mengajar.jam_ke AS UNSIGNED)) as jam_selesai_ke,
                    GROUP_CONCAT(jadwal_mengajar.jam_ke ORDER BY CAST(jadwal_mengajar.jam_ke AS UNSIGNED) SEPARATOR ", ") as daftar_jam,
                    ptk.nama_lengkap as nama_guru,
                    ptk.no_hp,
                    kelas.nama_kelas,
                    kelas.tingkat,
                    mapel.nama_mapel,
                    absensi_kelas.id as absen_id,
                    absensi_kelas.status_input,
                    absensi_kelas.materi_pembahasan,
                    absensi_kelas.catatan
                ', false)
                ->from('jadwal_mengajar')
                ->join('ptk','ptk.id = jadwal_mengajar.ptk_id')
                ->join('kelas','kelas.id = jadwal_mengajar.kelas_id')
                ->join('mapel','mapel.id = jadwal_mengajar.mapel_id')
                ->join(
                    'absensi_kelas',
                    'absensi_kelas.tanggal = '.$this->db->escape($tanggal).'
                     AND absensi_kelas.ptk_id = jadwal_mengajar.ptk_id
                     AND absensi_kelas.kelas_id = jadwal_mengajar.kelas_id
                     AND absensi_kelas.mapel_id = jadwal_mengajar.mapel_id',
                    'left'
                )
                ->where('jadwal_mengajar.tahun_ajaran', $tahun_ajaran)
                ->where('jadwal_mengajar.semester', $semester)
                ->where('jadwal_mengajar.hari', $hari)
                ->where('jadwal_mengajar.status', 'Aktif');

            if(!empty($kelas_id)){
                $this->db->where('jadwal_mengajar.kelas_id', $kelas_id);
            }
            if(!empty($tingkat)){
                $t_upper = strtoupper(trim($tingkat));
                if($t_upper == '10' || $t_upper == 'X'){
                    $this->db->where_in('kelas.tingkat', ['10', 'X']);
                } elseif($t_upper == '11' || $t_upper == 'XI'){
                    $this->db->where_in('kelas.tingkat', ['11', 'XI']);
                } elseif($t_upper == '12' || $t_upper == 'XII'){
                    $this->db->where_in('kelas.tingkat', ['12', 'XII']);
                } else {
                    $this->db->where('kelas.tingkat', $t_upper);
                }
            }

            if(!empty($filter_jam)){
                $this->db->where('jadwal_mengajar.jam_ke', (string)$filter_jam);
            }

            $jadwal_list = $this->db
                ->group_by('jadwal_mengajar.ptk_id, jadwal_mengajar.kelas_id, jadwal_mengajar.mapel_id')
                ->order_by("FIELD(kelas.tingkat, 'X', 'XI', 'XII', '10', '11', '12')", 'ASC', false)
                ->order_by('kelas.nama_kelas','ASC')
                ->order_by('jam_mulai_ke','ASC')
                ->get()
                ->result();
        }

        // Cek Status TU
        $tu_status_map = [];

        // 1. Surat Tugas
        if($this->db->table_exists('tu_surat_keluar') && $this->db->table_exists('tu_surat_keluar_ptk')){
            $surat_tugas = $this->db
                ->select('tu_surat_keluar_ptk.ptk_id, tu_surat_keluar.nama_kegiatan, tu_surat_keluar.perihal, tu_surat_keluar.tempat_kegiatan')
                ->from('tu_surat_keluar_ptk')
                ->join('tu_surat_keluar', 'tu_surat_keluar.id = tu_surat_keluar_ptk.surat_keluar_id')
                ->where('tu_surat_keluar.status !=', 'Nonaktif')
                ->where('tu_surat_keluar.tanggal_mulai <=', $tanggal)
                ->where('tu_surat_keluar.tanggal_selesai >=', $tanggal)
                ->get()
                ->result();

            foreach($surat_tugas as $st){
                $ket = !empty($st->nama_kegiatan) ? $st->nama_kegiatan : $st->perihal;
                $tu_status_map[$st->ptk_id] = [
                    'status' => 'Tugas Luar',
                    'keterangan' => $ket . (!empty($st->tempat_kegiatan) ? ' ('.$st->tempat_kegiatan.')' : '')
                ];
            }
        }

        // 2. Izin Guru
        if($this->db->table_exists('tu_izin_ptk')){
            $izin_guru = $this->db
                ->select('ptk_id, jenis_izin, keperluan, tujuan')
                ->from('tu_izin_ptk')
                ->where('status', 'Aktif')
                ->where('tanggal_mulai <=', $tanggal)
                ->where('tanggal_selesai >=', $tanggal)
                ->get()
                ->result();

            foreach($izin_guru as $ig){
                $status_nama = 'Izin Guru';
                if($ig->jenis_izin == 'Dinas Luar'){
                    $status_nama = 'Tugas Luar';
                } elseif($ig->jenis_izin == 'Sakit'){
                    $status_nama = 'Sakit';
                }

                $tu_status_map[$ig->ptk_id] = [
                    'status' => $status_nama,
                    'keterangan' => $ig->keperluan . (!empty($ig->tujuan) ? ' ('.$ig->tujuan.')' : '')
                ];
            }
        }

        // 3. Cuti ASN
        if($this->db->table_exists('tu_cuti_asn')){
            $cuti_asn = $this->db
                ->select('ptk_id, jenis_cuti, alasan_cuti')
                ->from('tu_cuti_asn')
                ->where_in('status', ['Aktif', 'Disetujui Kepala'])
                ->where('tanggal_mulai <=', $tanggal)
                ->where('tanggal_selesai >=', $tanggal)
                ->get()
                ->result();

            foreach($cuti_asn as $ca){
                $status_nama = 'Sedang Cuti';
                if(stripos($ca->jenis_cuti, 'Sakit') !== false){
                    $status_nama = 'Sakit';
                }

                $tu_status_map[$ca->ptk_id] = [
                    'status' => $status_nama,
                    'keterangan' => $ca->jenis_cuti . (!empty($ca->alasan_cuti) ? ': '.$ca->alasan_cuti : '')
                ];
            }
        }

        // Ambil Data CCTV Terdaftar
        $cctv_map = [];
        $cctv_list = [];
        if($this->db->table_exists('cctv_kelas')){
            $cctv_query = $this->db
                ->select('cctv_kelas.*, kelas.nama_kelas, kelas.tingkat')
                ->from('cctv_kelas')
                ->join('kelas', 'kelas.id = cctv_kelas.kelas_id', 'left')
                ->where('cctv_kelas.status', 'Aktif')
                ->order_by("FIELD(kelas.tingkat, 'X', 'XI', 'XII', '10', '11', '12')", 'ASC', false)
                ->order_by('cctv_kelas.urutan', 'ASC')
                ->order_by('cctv_kelas.id', 'ASC')
                ->get()
                ->result();

            foreach($cctv_query as $c){
                $cctv_list[] = $c;
                if(!empty($c->kelas_id)){
                    $cctv_map[$c->kelas_id] = $c;
                }
            }
        }

        // Pasangkan rentang jam, status TU, penanda aktif, dan data CCTV
        $count_hadir = 0;
        $count_tu = 0;
        $count_pending = 0;
        $count_active_now = 0;
        $class_active_map = [];

        foreach($jadwal_list as $j){
            $mulai_str = '';
            $selesai_str = '';
            if(isset($jam_map[(string)$j->jam_mulai_ke])){
                $mulai_str = $jam_map[(string)$j->jam_mulai_ke]['mulai'];
            }
            if(isset($jam_map[(string)$j->jam_selesai_ke])){
                $selesai_str = $jam_map[(string)$j->jam_selesai_ke]['selesai'];
            }

            if(!empty($mulai_str) && !empty($selesai_str)){
                $j->jam_rentang = $mulai_str . ' - ' . $selesai_str;
            } else {
                $j->jam_rentang = '';
            }

            // Deteksi apakah sedang aktif saat ini
            $j->is_active_now = false;
            if($is_today && $jam_ke_aktif !== null){
                $jam_mulai_int = (int)$j->jam_mulai_ke;
                $jam_selesai_int = (int)$j->jam_selesai_ke;
                $aktif_int = (int)$jam_ke_aktif;
                if($aktif_int >= $jam_mulai_int && $aktif_int <= $jam_selesai_int){
                    $j->is_active_now = true;
                    $count_active_now++;
                    $class_active_map[$j->kelas_id] = $j;
                }
            }

            // Pasangkan CCTV
            $j->cctv = isset($cctv_map[$j->kelas_id]) ? $cctv_map[$j->kelas_id] : null;

            // Integrasi Status TU
            if(empty($j->absen_id) && isset($tu_status_map[$j->ptk_id])){
                $j->absen_id = 'tu_'.$j->ptk_id;
                $j->status_input = $tu_status_map[$j->ptk_id]['status'];
                $j->keterangan_status = $tu_status_map[$j->ptk_id]['keterangan'];
            }

            if(!empty($j->absen_id)){
                if(in_array($j->status_input, ['Tugas Luar', 'Izin Guru', 'Sakit', 'Sedang Cuti'])){
                    $count_tu++;
                } else {
                    $count_hadir++;
                }
            } else {
                $count_pending++;
            }
        }

        // Pasangkan info jadwal aktif ke cctv_list untuk Multi-Grid View
        foreach($cctv_list as $c){
            $c->active_kbm = isset($class_active_map[$c->kelas_id]) ? $class_active_map[$c->kelas_id] : null;
        }

        $data['cctv_list'] = $cctv_list;

        if(!empty($only_active) && $is_today && $jam_ke_aktif !== null){
            $jadwal_list = array_values(array_filter($jadwal_list, function($item){
                return !empty($item->is_active_now);
            }));
        }

        // Urutkan kelas secara ketat: Tingkat (X, XI, XII) -> Nama Kelas (X A, X B, dst.) -> Jam Ke
        $tingkat_weight = function($t) {
            $t = strtoupper(trim((string)$t));
            if($t === 'X' || $t === '10') return 1;
            if($t === 'XI' || $t === '11') return 2;
            if($t === 'XII' || $t === '12') return 3;
            return 99;
        };

        usort($jadwal_list, function($a, $b) use ($tingkat_weight) {
            $wA = $tingkat_weight($a->tingkat ?? '');
            $wB = $tingkat_weight($b->tingkat ?? '');
            if ($wA !== $wB) {
                return $wA <=> $wB;
            }
            $cmpKelas = strnatcasecmp((string)($a->nama_kelas ?? ''), (string)($b->nama_kelas ?? ''));
            if ($cmpKelas !== 0) {
                return $cmpKelas;
            }
            return ((int)($a->jam_mulai_ke ?? 0)) <=> ((int)($b->jam_mulai_ke ?? 0));
        });

        $data['jadwal_list'] = $jadwal_list;
        $data['count_hadir'] = $count_hadir;
        $data['count_tu'] = $count_tu;
        $data['count_pending'] = $count_pending;
        $data['count_active_now'] = $count_active_now;
        $data['total_jadwal'] = count($jadwal_list);

        $this->load->view('public/monitoring_kbm', $data);
    }

    /**
     * Halaman Publik Direktori Alumni & Statistik Kelulusan
     */
    public function alumni(){
        $data = $this->base_data('Direktori Alumni');

        $q = trim($this->input->get('q') ?? '');
        $tahun = trim($this->input->get('tahun') ?? '');

        // Pastikan tabel alumni ada
        if($this->db->table_exists('alumni')){
            $this->db->select('alumni.*, siswa.nisn, siswa.nis, siswa.nama_lengkap, siswa.jk, siswa.agama')
                ->from('alumni')
                ->join('siswa', 'siswa.id = alumni.siswa_id', 'left');

            if(!empty($q)){
                $this->db->group_start();
                $this->db->like('siswa.nama_lengkap', $q);
                $this->db->or_like('alumni.nama_lengkap', $q);
                $this->db->or_like('siswa.nisn', $q);
                $this->db->or_like('alumni.nisn', $q);
                $this->db->or_like('alumni.status_lanjut', $q);
                $this->db->or_like('alumni.keterangan', $q);
                $this->db->group_end();
            }

            if(!empty($tahun)){
                $this->db->group_start();
                $this->db->where('alumni.tahun_ajaran_lulus', $tahun);
                if($this->db->field_exists('tahun_lulus', 'alumni')){
                    $this->db->or_where('alumni.tahun_lulus', $tahun);
                }
                $this->db->group_end();
            }

            $order_thn = $this->db->field_exists('tahun_ajaran_lulus', 'alumni') ? 'alumni.tahun_ajaran_lulus' : 'alumni.id';
            $data['alumni'] = $this->db
                ->order_by($order_thn, 'DESC')
                ->order_by('siswa.nama_lengkap', 'ASC')
                ->get()
                ->result();

            // Ambil daftar tahun lulus unik untuk dropdown filter
            $data['tahun_list'] = $this->db
                ->select('DISTINCT(tahun_ajaran_lulus) as tahun')
                ->from('alumni')
                ->where('tahun_ajaran_lulus IS NOT NULL', null, false)
                ->where('tahun_ajaran_lulus !=', '')
                ->order_by('tahun_ajaran_lulus', 'DESC')
                ->get()
                ->result();
        } else {
            $data['alumni'] = [];
            $data['tahun_list'] = [];
        }

        $data['list_tahun'] = $data['tahun_list'];
        $data['tahun_pilihan'] = $tahun;
        $data['q'] = $q;
        $data['tahun'] = $tahun;

        $this->load->view('public/alumni', $data);
    }

    /**
     * Halaman Publik Kontak & Layanan Informasi Madrasah
     */
    public function kontak(){
        $data = $this->base_data('Kontak & Layanan Informasi');

        // Handle POST kirim pesan aspirasi / kontak
        if($this->input->method() === 'post'){
            $nama = trim($this->input->post('nama_lengkap') ?? '');
            $email = trim($this->input->post('email') ?? '');
            $telepon = trim($this->input->post('no_hp') ?? '');
            $subjek = trim($this->input->post('subjek') ?? '');
            $pesan = trim($this->input->post('pesan') ?? '');

            if(!empty($nama) && !empty($pesan)){
                // Jika ada tabel pesan_masuk / kontak_masuk bisa disimpan
                if($this->db->table_exists('kontak_masuk')){
                    $this->db->insert('kontak_masuk', [
                        'nama_lengkap' => $nama,
                        'email' => $email,
                        'no_hp' => $telepon,
                        'subjek' => $subjek,
                        'pesan' => $pesan,
                        'ip_address' => $this->input->ip_address(),
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
                }
                $this->session->set_flashdata('success', 'Terima kasih! Pesan dan aspirasi Anda telah berhasil dikirimkan ke pihak madrasah.');
                redirect('website/kontak');
            } else {
                $this->session->set_flashdata('error', 'Mohon lengkapi Nama Lengkap dan Isi Pesan Anda.');
            }
        }

        $this->load->view('public/kontak', $data);
    }
}

