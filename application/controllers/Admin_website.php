<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_website extends CI_Controller {

    public function __construct(){
        parent::__construct();

        $this->load->library('session');
        $this->load->helper(['url','form']);

        if(!$this->session->userdata('logged_in')){
            redirect('auth');
        }

        $role = $this->session->userdata('role');

        $allowed = [
            'admin',
            'admin_master',
            'admin_website',
            'admin_humas',
            'wakil_humas',
            'operator_humas'
        ];

        if(!in_array($role, $allowed)){
            show_error('Anda tidak memiliki akses ke menu Website Madrasah.', 403);
        }
    }

    public function profil(){

        $data['profil'] = $this->db
            ->limit(1)
            ->get('website_profil')
            ->row();

        $this->load->view('admin_website/profil', $data);
    }

    public function save_profil(){

        $data = [
            // Pengaturan Hero Beranda Portal
            'hero_badge'               => $this->input->post('hero_badge'),
            'hero_judul'               => $this->input->post('hero_judul'),
            'hero_deskripsi'           => $this->input->post('hero_deskripsi'),
            'hero_tombol_utama_teks'   => $this->input->post('hero_tombol_utama_teks'),
            'hero_tombol_utama_url'    => $this->input->post('hero_tombol_utama_url'),
            'hero_tombol_kedua_teks'   => $this->input->post('hero_tombol_kedua_teks'),
            'hero_tombol_kedua_url'    => $this->input->post('hero_tombol_kedua_url'),

            // Pengaturan Sambutan Kepala Madrasah
            'sambutan_judul'           => $this->input->post('sambutan_judul'),
            'sambutan_isi'             => $this->input->post('sambutan_isi'),
            'sambutan_nama'            => $this->input->post('sambutan_nama'),
            'sambutan_jabatan'         => $this->input->post('sambutan_jabatan'),

            // Profil & Visi Misi
            'judul_profil' => $this->input->post('judul_profil'),
            'isi_profil'  => $this->input->post('isi_profil'),
            'visi'        => $this->input->post('visi'),
            'misi'        => $this->input->post('misi'),
            'tujuan'      => $this->input->post('tujuan'),

            // Kontak & Identitas
            'alamat'        => $this->input->post('alamat'),
            'telepon'       => $this->input->post('telepon'),
            'email'         => $this->input->post('email'),
			'whatsapp'      => $this->input->post('whatsapp'),
			'jam_layanan'   => $this->input->post('jam_layanan'),
			'rdm_url'       => (!empty(trim($this->input->post('rdm_url'))) && strpos(trim($this->input->post('rdm_url')), 'http://') !== 0 && strpos(trim($this->input->post('rdm_url')), 'https://') !== 0)
			                   ? 'https://' . trim($this->input->post('rdm_url'))
			                   : trim($this->input->post('rdm_url')),
			'facebook_url'  => (!empty(trim($this->input->post('facebook_url'))) && strpos(trim($this->input->post('facebook_url')), 'http://') !== 0 && strpos(trim($this->input->post('facebook_url')), 'https://') !== 0)
			                   ? 'https://' . trim($this->input->post('facebook_url'))
			                   : trim($this->input->post('facebook_url')),
			'instagram_url' => (!empty(trim($this->input->post('instagram_url'))) && strpos(trim($this->input->post('instagram_url')), 'http://') !== 0 && strpos(trim($this->input->post('instagram_url')), 'https://') !== 0)
			                   ? 'https://' . trim($this->input->post('instagram_url'))
			                   : trim($this->input->post('instagram_url')),
			'youtube_url'   => (!empty(trim($this->input->post('youtube_url'))) && strpos(trim($this->input->post('youtube_url')), 'http://') !== 0 && strpos(trim($this->input->post('youtube_url')), 'https://') !== 0)
			                   ? 'https://' . trim($this->input->post('youtube_url'))
			                   : trim($this->input->post('youtube_url')),
			'nsm'           => $this->input->post('nsm'),
			'npsn'          => $this->input->post('npsn'),
            'updated_at'    => date('Y-m-d H:i:s')
        ];

        // Upload foto sambutan jika ada
        if(!empty($_FILES['sambutan_foto']['name'])){
            $upload_path = './uploads/website/';
            if(!is_dir($upload_path)){
                mkdir($upload_path, 0777, true);
            }

            $config['upload_path']   = $upload_path;
            $config['allowed_types'] = 'jpg|jpeg|png|webp';
            $config['max_size']      = 4096;
            $config['encrypt_name']  = TRUE;

            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            if($this->upload->do_upload('sambutan_foto')){
                $upload_data = $this->upload->data();
                $data['sambutan_foto'] = $upload_data['file_name'];
            }
        }

        $profil = $this->db
            ->limit(1)
            ->get('website_profil')
            ->row();

        if($profil){
            $this->db->where('id', $profil->id);
            $this->db->update('website_profil', $data);
        } else {
            $this->db->insert('website_profil', $data);
        }

        $this->session->set_flashdata('success', 'Profil dan informasi beranda website berhasil diperbarui.');
        redirect('admin_website/profil');
    }

    public function video(){

        $data['video'] = $this->db
            ->order_by('created_at', 'DESC')
            ->order_by('id', 'DESC')
            ->get('website_video')
            ->result();

        $this->load->view('admin_website/video', $data);
    }

    public function save_video(){

        $judul = trim($this->input->post('judul'));
        $youtube_url = trim($this->input->post('youtube_url'));

        if(empty($judul) || empty($youtube_url)){
            $this->session->set_flashdata('error', 'Judul dan URL YouTube wajib diisi.');
            redirect('admin_website/video');
        }

        $this->db->insert('website_video', [
            'judul' => $judul,
            'deskripsi' => $this->input->post('deskripsi'),
            'youtube_url' => $youtube_url,
            'status' => 'Draft',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        $this->session->set_flashdata('success', 'Video berhasil disimpan sebagai Draft.');
        redirect('admin_website/video');
    }

    public function publish_video($id){

        $video = $this->db
            ->where('id', $id)
            ->get('website_video')
            ->row();

        if(!$video){
            show_404();
        }

        /*
         * Supaya hanya satu video profil yang aktif,
         * semua video lain dikembalikan ke Draft.
         */
        $this->db->update('website_video', [
            'status' => 'Draft'
        ]);

        $this->db->where('id', $id);
        $this->db->update('website_video', [
            'status' => 'Published',
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        $this->session->set_flashdata('success', 'Video profil berhasil dipublish.');
        redirect('admin_website/video');
    }

    public function draft_video($id){

        $video = $this->db
            ->where('id', $id)
            ->get('website_video')
            ->row();

        if(!$video){
            show_404();
        }

        $this->db->where('id', $id);
        $this->db->update('website_video', [
            'status' => 'Draft',
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        $this->session->set_flashdata('success', 'Video dikembalikan menjadi Draft.');
        redirect('admin_website/video');
    }

    public function delete_video($id){

        $this->db->where('id', $id);
        $this->db->delete('website_video');

        $this->session->set_flashdata('success', 'Video berhasil dihapus.');
        redirect('admin_website/video');
    }
	public function pamflet(){

    $data['pamflet'] = $this->db
        ->order_by('tanggal', 'DESC')
        ->order_by('created_at', 'DESC')
        ->get('website_pamflet')
        ->result();

    $this->load->view('admin_website/pamflet', $data);
}

public function save_pamflet(){

    $judul = trim($this->input->post('judul'));

    if(empty($judul)){
        $this->session->set_flashdata('error', 'Judul pamflet wajib diisi.');
        redirect('admin_website/pamflet');
    }

    if(empty($_FILES['gambar']['name'])){
        $this->session->set_flashdata('error', 'Gambar pamflet wajib diupload.');
        redirect('admin_website/pamflet');
    }

    $upload_path = './assets/pamflet/';

    if(!is_dir($upload_path)){
        mkdir($upload_path, 0777, true);
    }

    $config['upload_path']   = $upload_path;
    $config['allowed_types'] = 'jpg|jpeg|png|webp';
    $config['max_size']      = 4096;
    $config['encrypt_name']  = TRUE;

    $this->load->library('upload');
    $this->upload->initialize($config);

    if(!$this->upload->do_upload('gambar')){
        $this->session->set_flashdata('error', $this->upload->display_errors('', ''));
        redirect('admin_website/pamflet');
    }

    $upload = $this->upload->data();

    $this->db->insert('website_pamflet', [
        'judul'      => $judul,
        'deskripsi'  => $this->input->post('deskripsi'),
        'gambar'     => $upload['file_name'],
        'tanggal'    => $this->input->post('tanggal') ?: date('Y-m-d'),
        'status'     => 'Draft',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
    ]);

    $this->session->set_flashdata('success', 'Pamflet berhasil disimpan sebagai Draft.');
    redirect('admin_website/pamflet');
}

public function publish_pamflet($id){

    $pamflet = $this->db
        ->where('id', $id)
        ->get('website_pamflet')
        ->row();

    if(!$pamflet){
        show_404();
    }

    $this->db->where('id', $id);
    $this->db->update('website_pamflet', [
        'status' => 'Published',
        'updated_at' => date('Y-m-d H:i:s')
    ]);

    $this->session->set_flashdata('success', 'Pamflet berhasil dipublish.');
    redirect('admin_website/pamflet');
}

public function draft_pamflet($id){

    $pamflet = $this->db
        ->where('id', $id)
        ->get('website_pamflet')
        ->row();

    if(!$pamflet){
        show_404();
    }

    $this->db->where('id', $id);
    $this->db->update('website_pamflet', [
        'status' => 'Draft',
        'updated_at' => date('Y-m-d H:i:s')
    ]);

    $this->session->set_flashdata('success', 'Pamflet dikembalikan menjadi Draft.');
    redirect('admin_website/pamflet');
}

public function delete_pamflet($id){

    $pamflet = $this->db
        ->where('id', $id)
        ->get('website_pamflet')
        ->row();

    if(!$pamflet){
        show_404();
    }

    if(!empty($pamflet->gambar)){
        $file = FCPATH.'assets/pamflet/'.$pamflet->gambar;

        if(file_exists($file)){
            unlink($file);
        }
    }

    $this->db->where('id', $id);
    $this->db->delete('website_pamflet');

    $this->session->set_flashdata('success', 'Pamflet berhasil dihapus.');
    redirect('admin_website/pamflet');
}
	public function ptk(){

    if(!$this->db->field_exists('tampil_website', 'ptk')){
        show_error('Kolom tampil_website belum ada di tabel ptk. Jalankan SQL update database terlebih dahulu.');
    }

    if(!$this->db->field_exists('urutan_website', 'ptk')){
        show_error('Kolom urutan_website belum ada di tabel ptk. Jalankan SQL update database terlebih dahulu.');
    }

    $data['ptk'] = $this->db
        ->order_by('urutan_website', 'ASC')
        ->order_by('nama_lengkap', 'ASC')
        ->get('ptk')
        ->result();

    $this->load->view('admin_website/ptk', $data);
}

public function save_ptk_urutan(){

    $urutan = $this->input->post('urutan');

    if(!empty($urutan)){
        foreach($urutan as $ptk_id => $nilai){
            $this->db->where('id', $ptk_id);
            $this->db->update('ptk', [
                'urutan_website' => (int)$nilai
            ]);
        }
    }

    $this->session->set_flashdata('success', 'Urutan PTK di website berhasil diperbarui.');
    redirect('admin_website/ptk');
}

public function show_ptk($id){

    $ptk = $this->db
        ->where('id', $id)
        ->get('ptk')
        ->row();

    if(!$ptk){
        show_404();
    }

    $this->db->where('id', $id);
    $this->db->update('ptk', [
        'tampil_website' => 1
    ]);

    $this->session->set_flashdata('success', 'PTK berhasil ditampilkan di website.');
    redirect('admin_website/ptk');
}

public function hide_ptk($id){

    $ptk = $this->db
        ->where('id', $id)
        ->get('ptk')
        ->row();

    if(!$ptk){
        show_404();
    }

    $this->db->where('id', $id);
    $this->db->update('ptk', [
        'tampil_website' => 0
    ]);

    $this->session->set_flashdata('success', 'PTK disembunyikan dari website.');
    redirect('admin_website/ptk');
}
public function tentang(){

    $data['profil'] = $this->db
        ->limit(1)
        ->get('website_profil')
        ->row();

    $this->load->view('admin_website/tentang', $data);
}

public function save_tentang(){

    $data = [
        'sejarah'          => $this->input->post('sejarah'),
        'fasilitas'        => $this->input->post('fasilitas'),
        'prestasi'         => $this->input->post('prestasi'),
        'ekstrakurikuler'  => $this->input->post('ekstrakurikuler'),
        'maps_embed_url'   => $this->input->post('maps_embed_url'),
        'updated_at'       => date('Y-m-d H:i:s')
    ];

    $profil = $this->db
        ->limit(1)
        ->get('website_profil')
        ->row();

    if($profil){
        $this->db->where('id', $profil->id);
        $this->db->update('website_profil', $data);
    } else {
        $this->db->insert('website_profil', $data);
    }

    $this->session->set_flashdata('success', 'Tentang madrasah berhasil diperbarui.');
    redirect('admin_website/tentang');
}

public function galeri(){

    $data['galeri'] = $this->db
        ->order_by('tanggal', 'DESC')
        ->order_by('created_at', 'DESC')
        ->get('website_galeri')
        ->result();

    $this->load->view('admin_website/galeri', $data);
}

public function save_galeri(){

    $judul = trim($this->input->post('judul'));

    if(empty($judul)){
        $this->session->set_flashdata('error', 'Judul galeri wajib diisi.');
        redirect('admin_website/galeri');
    }

    if(empty($_FILES['gambar']['name'])){
        $this->session->set_flashdata('error', 'Gambar galeri wajib diupload.');
        redirect('admin_website/galeri');
    }

    $upload_path = './assets/galeri/';

    if(!is_dir($upload_path)){
        mkdir($upload_path, 0777, true);
    }

    $config['upload_path']   = $upload_path;
    $config['allowed_types'] = 'jpg|jpeg|png|webp';
    $config['max_size']      = 4096;
    $config['encrypt_name']  = TRUE;

    $this->load->library('upload');
    $this->upload->initialize($config);

    if(!$this->upload->do_upload('gambar')){
        $this->session->set_flashdata('error', $this->upload->display_errors('', ''));
        redirect('admin_website/galeri');
    }

    $upload = $this->upload->data();

    $this->db->insert('website_galeri', [
        'judul'      => $judul,
        'deskripsi'  => $this->input->post('deskripsi'),
        'gambar'     => $upload['file_name'],
        'tanggal'    => $this->input->post('tanggal') ?: date('Y-m-d'),
        'status'     => 'Draft',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
    ]);

    $this->session->set_flashdata('success', 'Galeri berhasil disimpan sebagai Draft.');
    redirect('admin_website/galeri');
}

public function publish_galeri($id){

    $galeri = $this->db
        ->where('id', $id)
        ->get('website_galeri')
        ->row();

    if(!$galeri){
        show_404();
    }

    $this->db->where('id', $id);
    $this->db->update('website_galeri', [
        'status' => 'Published',
        'updated_at' => date('Y-m-d H:i:s')
    ]);

    $this->session->set_flashdata('success', 'Galeri berhasil dipublish.');
    redirect('admin_website/galeri');
}

public function draft_galeri($id){

    $galeri = $this->db
        ->where('id', $id)
        ->get('website_galeri')
        ->row();

    if(!$galeri){
        show_404();
    }

    $this->db->where('id', $id);
    $this->db->update('website_galeri', [
        'status' => 'Draft',
        'updated_at' => date('Y-m-d H:i:s')
    ]);

    $this->session->set_flashdata('success', 'Galeri dikembalikan menjadi Draft.');
    redirect('admin_website/galeri');
}

public function delete_galeri($id){

    $galeri = $this->db
        ->where('id', $id)
        ->get('website_galeri')
        ->row();

    if(!$galeri){
        show_404();
    }

    if(!empty($galeri->gambar)){
        $file = FCPATH.'assets/galeri/'.$galeri->gambar;

        if(file_exists($file)){
            unlink($file);
        }
    }

    $this->db->where('id', $id);
    $this->db->delete('website_galeri');

    $this->session->set_flashdata('success', 'Galeri berhasil dihapus.');
    redirect('admin_website/galeri');
}
    private function ensureDownloadDriveColumns() {
        // Pastikan kolom pin_zi ada di tabel settings
        if($this->db->table_exists('settings')){
            if(!$this->db->field_exists('pin_zi', 'settings')){
                $this->db->query("ALTER TABLE `settings` ADD COLUMN `pin_zi` varchar(20) DEFAULT '123456'");
            }
        }

        if(!$this->db->table_exists('website_download')){
            $this->db->query("CREATE TABLE IF NOT EXISTS `website_download` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `judul` varchar(255) NOT NULL,
                `file_path` varchar(255) DEFAULT NULL,
                `keterangan` text DEFAULT NULL,
                `tanggal` date NOT NULL,
                `kategori_pilar` varchar(50) DEFAULT 'zi',
                `area_zi` varchar(20) DEFAULT NULL,
                `pengunggah` varchar(150) DEFAULT NULL,
                `lini_unit` varchar(100) DEFAULT NULL,
                `link_drive` text DEFAULT NULL,
                `tipe_sumber` varchar(20) DEFAULT 'file',
                `is_public` tinyint(1) DEFAULT 1,
                `status_verifikasi` varchar(30) DEFAULT 'Sesuai',
                `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
            return;
        }

        $fields = $this->db->list_fields('website_download');
        if(!in_array('kategori_pilar', $fields)){
            $this->db->query("ALTER TABLE `website_download` ADD COLUMN `kategori_pilar` varchar(50) DEFAULT 'zi' AFTER `tanggal`");
        }
        if(!in_array('area_zi', $fields)){
            $this->db->query("ALTER TABLE `website_download` ADD COLUMN `area_zi` varchar(20) DEFAULT NULL AFTER `kategori_pilar`");
        }
        if(!in_array('pengunggah', $fields)){
            $this->db->query("ALTER TABLE `website_download` ADD COLUMN `pengunggah` varchar(150) DEFAULT NULL AFTER `area_zi`");
        }
        if(!in_array('lini_unit', $fields)){
            $this->db->query("ALTER TABLE `website_download` ADD COLUMN `lini_unit` varchar(100) DEFAULT NULL AFTER `pengunggah`");
        }
        if(!in_array('link_drive', $fields)){
            $this->db->query("ALTER TABLE `website_download` ADD COLUMN `link_drive` text DEFAULT NULL AFTER `lini_unit`");
        }
        if(!in_array('tipe_sumber', $fields)){
            $this->db->query("ALTER TABLE `website_download` ADD COLUMN `tipe_sumber` varchar(20) DEFAULT 'file' AFTER `link_drive`");
        }
        if(!in_array('is_public', $fields)){
            $this->db->query("ALTER TABLE `website_download` ADD COLUMN `is_public` tinyint(1) DEFAULT 1 AFTER `tipe_sumber`");
        }
        if(!in_array('status_verifikasi', $fields)){
            $this->db->query("ALTER TABLE `website_download` ADD COLUMN `status_verifikasi` varchar(30) DEFAULT 'Sesuai' AFTER `is_public`");
        }
    }

    public function download(){
        $this->ensureDownloadDriveColumns();

        $data['downloads'] = $this->db
            ->order_by('tanggal', 'DESC')
            ->order_by('id', 'DESC')
            ->get('website_download')
            ->result();

        // Calculate statistics
        $stats = [
            'total' => count($data['downloads']),
            'zi_total' => 0,
            'akademik' => 0,
            'kepegawaian' => 0,
            'kesiswaan' => 0,
            'sarpras' => 0,
            'umum' => 0
        ];
        foreach($data['downloads'] as $row){
            $pilar = strtolower($row->kategori_pilar ?? 'umum');
            if($pilar === 'zi') $stats['zi_total']++;
            elseif(isset($stats[$pilar])) $stats[$pilar]++;
            else $stats['umum']++;
        }
        // Ambil data PIN ZI dari settings
        $setting = $this->db->get('settings')->row();
        $data['pin_zi'] = !empty($setting->pin_zi) ? $setting->pin_zi : '123456';

        $this->load->view('admin_website/download', $data);
    }

    public function update_pin_zi(){
        $this->ensureDownloadDriveColumns();
        $pin_baru = trim((string)$this->input->post('pin_zi', TRUE));
        if(!empty($pin_baru)){
            $this->db->update('settings', ['pin_zi' => $pin_baru]);
            $this->session->set_flashdata('success', 'PIN Akses Zona Integritas berhasil diperbarui menjadi: "'.htmlspecialchars($pin_baru).'"');
        } else {
            $this->session->set_flashdata('error', 'PIN Akses tidak boleh kosong.');
        }
        redirect('admin_website/download');
    }

    public function save_download(){
        $this->ensureDownloadDriveColumns();

        $judul = trim((string)$this->input->post('judul', TRUE));
        $keterangan = trim((string)$this->input->post('keterangan', TRUE));
        $tanggal = $this->input->post('tanggal', TRUE) ? $this->input->post('tanggal', TRUE) : date('Y-m-d');
        $kategori_pilar = $this->input->post('kategori_pilar', TRUE) ? trim($this->input->post('kategori_pilar', TRUE)) : 'zi';
        $area_zi = $this->input->post('area_zi', TRUE) ? trim($this->input->post('area_zi', TRUE)) : NULL;
        $pengunggah = trim((string)$this->input->post('pengunggah', TRUE));
        $lini_unit = trim((string)$this->input->post('lini_unit', TRUE));
        $tipe_sumber = $this->input->post('tipe_sumber', TRUE) ? trim($this->input->post('tipe_sumber', TRUE)) : 'file';
        $link_drive = trim((string)$this->input->post('link_drive', TRUE));

        if(empty($judul)){
            $this->session->set_flashdata('error', 'Nama / Judul dokumen wajib diisi.');
            redirect('admin_website/download');
            return;
        }

        // Preserve area_zi / sub_kategori for all categories if provided
        $file_path = NULL;

        if($tipe_sumber === 'drive_link'){
            if(empty($link_drive) || !filter_var($link_drive, FILTER_VALIDATE_URL)){
                $this->session->set_flashdata('error', 'Tautan Google Drive / Cloud URL tidak valid.');
                redirect('admin_website/download');
                return;
            }
            $file_path = 'drive_link';
        } else {
            $upload_dir = './assets/downloads/';
            if(!is_dir($upload_dir)){
                @mkdir($upload_dir, 0777, true);
            }
            if(!is_dir($upload_dir) && defined('FCPATH')){
                $upload_dir = rtrim(FCPATH, '/\\') . '/assets/downloads/';
                if(!is_dir($upload_dir)){
                    @mkdir($upload_dir, 0777, true);
                }
            }
            if(realpath($upload_dir) !== false){
                $upload_dir = realpath($upload_dir);
            }

            $raw_ext = strtolower(pathinfo($_FILES['file_download']['name'] ?? '', PATHINFO_EXTENSION));
            $allowed_exts = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'zip', 'rar', 'csv', 'txt'];
            if(!in_array($raw_ext, $allowed_exts)){
                $this->session->set_flashdata('error', 'Format berkas (.'.$raw_ext.') tidak diizinkan. Gunakan format PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, atau ZIP.');
                redirect('admin_website/download');
                return;
            }

            $config['upload_path']   = $upload_dir;
            $config['allowed_types'] = '*';
            $config['detect_mime']   = FALSE;
            $config['max_size']      = 20480; // 20MB
            $safe_title = url_title(substr($judul, 0, 45), 'dash', true);
            $config['file_name']     = time().'_'.(!empty($safe_title) ? $safe_title : 'dokumen').'.'.$raw_ext;

            $this->load->library('upload');
            $this->upload->initialize($config, true);

            if($this->upload->do_upload('file_download')){
                $uploadData = $this->upload->data();
                $file_path = $uploadData['file_name'];
            } else {
                $error = $this->upload->display_errors('','');
                $this->session->set_flashdata('error', 'Gagal mengunggah file: '.$error);
                redirect('admin_website/download');
                return;
            }
        }

        $this->db->insert('website_download', [
            'judul'             => $judul,
            'keterangan'        => $keterangan,
            'file_path'         => $file_path,
            'tanggal'           => $tanggal,
            'kategori_pilar'    => $kategori_pilar,
            'area_zi'           => $area_zi,
            'pengunggah'        => !empty($pengunggah) ? $pengunggah : ($this->session->userdata('username') ?? 'Admin'),
            'lini_unit'         => !empty($lini_unit) ? $lini_unit : 'Pimpinan / Admin',
            'link_drive'        => $link_drive,
            'tipe_sumber'       => $tipe_sumber,
            'is_public'         => 1,
            'status_verifikasi' => 'Sesuai'
        ]);

        $this->session->set_flashdata('success', 'Dokumen / Eviden berhasil disimpan.');
        redirect('admin_website/download');
    }

    public function update_download(){
        $this->ensureDownloadDriveColumns();

        $id = (int)$this->input->post('id', TRUE);
        $download = $this->db->where('id', $id)->get('website_download')->row();
        if(!$download){
            $this->session->set_flashdata('error', 'Dokumen tidak ditemukan.');
            redirect('admin_website/download');
            return;
        }

        $judul = trim((string)$this->input->post('judul', TRUE));
        $keterangan = trim((string)$this->input->post('keterangan', TRUE));
        $tanggal = $this->input->post('tanggal', TRUE) ? $this->input->post('tanggal', TRUE) : date('Y-m-d');
        $kategori_pilar = $this->input->post('kategori_pilar', TRUE) ? trim($this->input->post('kategori_pilar', TRUE)) : 'zi';
        $area_zi = $this->input->post('area_zi', TRUE) ? trim($this->input->post('area_zi', TRUE)) : NULL;
        $pengunggah = trim((string)$this->input->post('pengunggah', TRUE));
        $lini_unit = trim((string)$this->input->post('lini_unit', TRUE));
        $tipe_sumber = $this->input->post('tipe_sumber', TRUE) ? trim($this->input->post('tipe_sumber', TRUE)) : 'file';
        $link_drive = trim((string)$this->input->post('link_drive', TRUE));

        if(empty($judul)){
            $this->session->set_flashdata('error', 'Nama / Judul dokumen wajib diisi.');
            redirect('admin_website/download');
            return;
        }

        $update_data = [
            'judul'             => $judul,
            'keterangan'        => $keterangan,
            'tanggal'           => $tanggal,
            'kategori_pilar'    => $kategori_pilar,
            'area_zi'           => $area_zi,
            'pengunggah'        => !empty($pengunggah) ? $pengunggah : $download->pengunggah,
            'lini_unit'         => !empty($lini_unit) ? $lini_unit : $download->lini_unit,
            'tipe_sumber'       => $tipe_sumber,
            'link_drive'        => $link_drive,
        ];

        if($tipe_sumber === 'drive_link'){
            if(empty($link_drive) || !filter_var($link_drive, FILTER_VALIDATE_URL)){
                $this->session->set_flashdata('error', 'Tautan Google Drive / Cloud URL tidak valid.');
                redirect('admin_website/download');
                return;
            }
            if(!empty($download->file_path) && $download->file_path !== 'drive_link'){
                $old_file = FCPATH.'assets/downloads/'.$download->file_path;
                if(file_exists($old_file)) @unlink($old_file);
            }
            $update_data['file_path'] = 'drive_link';
        } else {
            if(!empty($_FILES['file_download']['name'])){
                $upload_dir = './assets/downloads/';
                if(!is_dir($upload_dir)){
                    @mkdir($upload_dir, 0777, true);
                }
                if(!is_dir($upload_dir) && defined('FCPATH')){
                    $upload_dir = rtrim(FCPATH, '/\\') . '/assets/downloads/';
                    if(!is_dir($upload_dir)){
                        @mkdir($upload_dir, 0777, true);
                    }
                }
                if(realpath($upload_dir) !== false){
                    $upload_dir = realpath($upload_dir);
                }

                $raw_ext = strtolower(pathinfo($_FILES['file_download']['name'] ?? '', PATHINFO_EXTENSION));
                $allowed_exts = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'zip', 'rar', 'csv', 'txt'];
                if(!in_array($raw_ext, $allowed_exts)){
                    $this->session->set_flashdata('error', 'Format berkas (.'.$raw_ext.') tidak diizinkan. Gunakan format PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, atau ZIP.');
                    redirect('admin_website/download');
                    return;
                }

                $config['upload_path']   = $upload_dir;
                $config['allowed_types'] = '*';
                $config['detect_mime']   = FALSE;
                $config['max_size']      = 20480; // 20MB
                $safe_title = url_title(substr($judul, 0, 45), 'dash', true);
                $config['file_name']     = time().'_'.(!empty($safe_title) ? $safe_title : 'dokumen').'.'.$raw_ext;

                $this->load->library('upload');
                $this->upload->initialize($config, true);
                if($this->upload->do_upload('file_download')){
                    $uploadData = $this->upload->data();
                    if(!empty($download->file_path) && $download->file_path !== 'drive_link'){
                        $old_file = FCPATH.'assets/downloads/'.$download->file_path;
                        if(file_exists($old_file)) @unlink($old_file);
                    }
                    $update_data['file_path'] = $uploadData['file_name'];
                } else {
                    $error = $this->upload->display_errors('','');
                    $this->session->set_flashdata('error', 'Gagal mengunggah file baru: '.$error);
                    redirect('admin_website/download');
                    return;
                }
            }
        }

        $this->db->where('id', $id)->update('website_download', $update_data);
        $this->session->set_flashdata('success', 'Dokumen / Eviden "'.htmlspecialchars($judul).'" berhasil diperbarui.');
        redirect('admin_website/download');
    }

    public function delete_download($id){
        $download = $this->db->where('id', $id)->get('website_download')->row();

        if(!$download){
            show_404();
        }

        if(!empty($download->file_path) && $download->file_path !== 'drive_link'){
            $file = FCPATH.'assets/downloads/'.$download->file_path;
            if(file_exists($file)){
                unlink($file);
            }
        }

        $this->db->where('id', $id)->delete('website_download');

        $this->session->set_flashdata('success', 'File berhasil dihapus.');
        redirect('admin_website/download');
    }

    private function ensureTablePengumuman(){
        if(!$this->db->table_exists('website_pengumuman')){
            $sql = "CREATE TABLE IF NOT EXISTS `website_pengumuman` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `aktif` tinyint(1) NOT NULL DEFAULT 1,
                `badge` varchar(100) DEFAULT 'PENGUMUMAN PENTING',
                `judul` varchar(255) NOT NULL,
                `isi` text DEFAULT NULL,
                `tombol_teks` varchar(100) DEFAULT NULL,
                `tombol_url` varchar(255) DEFAULT NULL,
                `tema_warna` varchar(50) DEFAULT 'emerald',
                `updated_at` datetime DEFAULT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
            $this->db->query($sql);

            $this->db->insert('website_pengumuman', [
                'id'          => 1,
                'aktif'       => 1,
                'badge'       => 'PENGUMUMAN KELAS XII',
                'judul'       => 'Verifikasi Mandiri Foto Ijazah Siswa Telah Dibuka',
                'isi'         => 'Siswa kelas XII diharapkan memverifikasi fotonya agar tersimpan resmi dengan nama NISN sebelum dicetak.',
                'tombol_teks' => 'Verifikasi Sekarang →',
                'tombol_url'  => 'verifikasi_foto_ijazah',
                'tema_warna'  => 'emerald',
                'updated_at'  => date('Y-m-d H:i:s')
            ]);
        }
    }

    public function pengumuman(){
        $this->ensureTablePengumuman();

        $data['pengumuman'] = $this->db
            ->limit(1)
            ->get('website_pengumuman')
            ->row();

        $this->load->view('admin_website/pengumuman', $data);
    }

    public function toggle_pengumuman(){
        $this->ensureTablePengumuman();

        $row = $this->db->limit(1)->get('website_pengumuman')->row();
        if($row){
            $new_status = $row->aktif ? 0 : 1;
            $this->db->where('id', $row->id)->update('website_pengumuman', [
                'aktif' => $new_status,
                'updated_at' => date('Y-m-d H:i:s')
            ]);
            $msg = $new_status 
                ? 'Status pengumuman beranda berhasil diubah menjadi: AKTIF (Tampil di Beranda).' 
                : 'Status pengumuman beranda berhasil diubah menjadi: NONAKTIF (Disembunyikan dari Beranda).';
            $this->session->set_flashdata('success', $msg);
        }
        redirect('admin_website/pengumuman');
    }

    public function save_pengumuman(){
        $this->ensureTablePengumuman();

        $aktif = ($this->input->post('aktif') !== null) ? (int)$this->input->post('aktif') : 0;
        $badge = trim($this->input->post('badge') ?? '');
        $judul = trim($this->input->post('judul') ?? '');
        $isi   = trim($this->input->post('isi') ?? '');
        $tombol_teks = trim($this->input->post('tombol_teks') ?? '');
        $tombol_url  = trim($this->input->post('tombol_url') ?? '');
        $tema_warna  = trim($this->input->post('tema_warna') ?? 'emerald');

        $row = $this->db->limit(1)->get('website_pengumuman')->row();

        $payload = [
            'aktif'       => $aktif,
            'badge'       => $badge,
            'judul'       => $judul,
            'isi'         => $isi,
            'tombol_teks' => $tombol_teks,
            'tombol_url'  => $tombol_url,
            'tema_warna'  => $tema_warna,
            'updated_at'  => date('Y-m-d H:i:s')
        ];

        if($row){
            $this->db->where('id', $row->id)->update('website_pengumuman', $payload);
        } else {
            $this->db->insert('website_pengumuman', $payload);
        }

        $status_txt = ($aktif === 1) ? 'AKTIF (Tampil di Beranda)' : 'NONAKTIF (Disembunyikan)';
        $this->session->set_flashdata('success', 'Pengaturan pengumuman berhasil disimpan. Status: ' . $status_txt);
        redirect('admin_website/pengumuman');
    }
}