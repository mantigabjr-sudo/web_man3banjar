<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ppdb extends CI_Controller {

    private function get_nama_ppdb() {
        $settings = $this->db->get('settings')->row();
        return (isset($settings->nama_ppdb) && $settings->nama_ppdb) ? $settings->nama_ppdb : 'PPDB';
    }

    public function index(){

    $data['settings'] = $this->db->get('settings')->row();

    if($data['settings'] && $data['settings']->status_ppdb == 'Ditutup'){
        $this->load->view('public/ppdb_closed',$data);
        return;
    }

    $data['nama_ppdb'] = $this->get_nama_ppdb();
    $this->load->view('public/ppdb',$data);
}
	//daftar_awal
    public function submit(){
		
		$nama = trim($this->input->post('nama_lengkap'));
				$nisn = trim($this->input->post('nisn'));
				$hp   = trim($this->input->post('no_hp'));
				$password_plain = $this->input->post('password');
		if(strlen($nama) < 3){
			die('Nama lengkap minimal 3 karakter');
		}

		if(!preg_match('/^[0-9]{10}$/', $nisn)){
			die('NISN harus 10 digit angka');
		}

		if(!preg_match('/^[0-9]{10,15}$/', $hp)){
			die('No HP harus 10-15 digit angka');
		}

		if(strlen($password_plain) < 6){
			die('Password minimal 6 karakter');
		}
		
        $tahun = date('Y');

        $last = $this->db
            ->order_by('id','DESC')
            ->get('ppdb')
            ->row();

        $nomor = $last ? $last->id + 1 : 1;

        $no_pendaftaran = 'PPDB'.$tahun.'-'.str_pad($nomor,4,'0',STR_PAD_LEFT);

        $username = $this->input->post('nisn');
		$password_plain = $this->input->post('password');
		
		$cek = $this->db
			->where('nisn',$this->input->post('nisn'))
			->get('ppdb')
			->row();

		if($cek){

			$this->session->set_flashdata('error','NISN sudah terdaftar. Gunakan NISN lain atau login.');

			redirect('ppdb');
		}

        $data = [
            'no_pendaftaran' => $no_pendaftaran,
            'nama_lengkap' => $this->input->post('nama_lengkap'),
            'nisn' => $this->input->post('nisn'),
            'jk' => $this->input->post('jk'),
            'asal_sekolah' => $this->input->post('asal_sekolah'),
            'no_hp' => $this->input->post('no_hp'),
            'nama_ortu' => $this->input->post('nama_ortu'),
            'tempat_lahir' => $this->input->post('tempat_lahir'),
			'tanggal_lahir' => $this->input->post('tanggal_lahir'),
            'username' => $username,
            'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
            'status' => 'Lengkapi Biodata'
        ];
		
		if(empty($this->input->post('tanggal_lahir'))){
			$this->session->set_flashdata('error','Tanggal lahir wajib diisi');
			redirect('ppdb');
		}
        $this->db->insert('ppdb',$data);

        $data['no_pendaftaran'] = $no_pendaftaran;
        $data['username'] = $username;
        $data['password'] = $password_plain;
		
        $data['nama_ppdb'] = $this->get_nama_ppdb();
    $this->load->view('public/ppdb_success',$data);
    }
	//Biodata
	public function biodata(){

    if(!$this->session->userdata('ppdb_login')){
        redirect('ppdb/login');
    }

    $data['siswa'] = $this->db
        ->where('id',$this->session->userdata('ppdb_id'))
        ->get('ppdb')
        ->row();

    $data['nama_ppdb'] = $this->get_nama_ppdb();
    $this->load->view('public/ppdb_biodata',$data);
}
	//savebiodata
public function save_biodata(){

    if(!$this->session->userdata('ppdb_login')){
        redirect('ppdb/login');
    }

    $id = $this->session->userdata('ppdb_id');

    $siswa = $this->db
        ->where('id', $id)
        ->get('ppdb')
        ->row();

    if(!$siswa){
        redirect('ppdb/login');
    }

    $nik = trim($this->input->post('nik'));
    $no_kk = trim($this->input->post('no_kk'));
    $kode_pos = trim($this->input->post('kode_pos'));

    if(!preg_match('/^[0-9]{16}$/', $nik)){
        $this->session->set_flashdata('error', 'NIK wajib 16 digit angka.');
        redirect('ppdb/biodata');
    }

    if(!preg_match('/^[0-9]{16}$/', $no_kk)){
        $this->session->set_flashdata('error', 'Nomor KK wajib 16 digit angka.');
        redirect('ppdb/biodata');
    }

    if(!preg_match('/^[0-9]{5}$/', $kode_pos)){
        $this->session->set_flashdata('error', 'Kode pos wajib 5 digit angka.');
        redirect('ppdb/biodata');
    }

    $data = [
        'nik' => $nik,
        'no_kk' => $no_kk,
        'agama' => $this->input->post('agama'),
        'anak_ke' => $this->input->post('anak_ke'),
        'jumlah_saudara' => $this->input->post('jumlah_saudara'),

        'alamat' => $this->input->post('alamat'),
        'rt' => $this->input->post('rt'),
        'rw' => $this->input->post('rw'),
        'desa' => $this->input->post('desa'),
        'kecamatan' => $this->input->post('kecamatan'),
        'kabupaten' => $this->input->post('kabupaten'),
        'provinsi' => $this->input->post('provinsi'),
        'kode_pos' => $kode_pos,

        'nama_ayah' => $this->input->post('nama_ayah'),
        'pekerjaan_ayah' => $this->input->post('pekerjaan_ayah'),
        'nama_ibu' => $this->input->post('nama_ibu'),
        'pekerjaan_ibu' => $this->input->post('pekerjaan_ibu'),
        'penghasilan_ortu' => $this->input->post('penghasilan_ortu')
    ];

    /*
     * Jangan ubah status kalau sudah final.
     */
    if(!in_array($siswa->status, ['Diterima', 'Ditolak'])){
        if($siswa->status == 'Perlu Perbaikan'){
            $data['status'] = 'Menunggu Verifikasi Berkas';
        } else {
            if($this->ppdb_required_upload_complete($siswa)){
                $data['status'] = 'Menunggu Verifikasi Berkas';
            } else {
                $data['status'] = 'Upload Berkas';
            }
        }
    }

    if($this->db->field_exists('updated_at', 'ppdb')){
        $data['updated_at'] = date('Y-m-d H:i:s');
    }

    $this->db->where('id', $id);
    $this->db->update('ppdb', $data);

    $this->session->set_flashdata('success', 'Biodata berhasil disimpan. Silakan lanjut upload berkas.');

    redirect('ppdb/dashboard');
}
	//biodata_upload
	
	public function upload(){

    if(!$this->session->userdata('ppdb_login')){
        redirect('ppdb/login');
    }

    $data['siswa'] = $this->db
        ->where('id',$this->session->userdata('ppdb_id'))
        ->get('ppdb')
        ->row();

    $data['nama_ppdb'] = $this->get_nama_ppdb();
    $this->load->view('public/ppdb_upload',$data);
}
private function ppdb_required_upload_fields(){

    return [
        'foto',
        'kk_file',
        'akta_file',
        'sk_kelas9_file'
    ];
}

private function ppdb_upload_file_map(){

    return [
        'foto'             => 'foto',
        'kk_file'          => 'kk',
        'akta_file'        => 'akta',
        'rapor_file'       => 'rapor',
        'skl_file'         => 'skl',
        'nisn_file'        => 'nisn',
        'sk_kelas9_file'   => 'sk_kelas9',
        'ijazah_file'      => 'ijazah',
        'sertifikat_file'  => 'sertifikat'
    ];
}

private function ppdb_biodata_complete($siswa){

    if(!$siswa){
        return false;
    }

    $fields = [
        'nik',
        'no_kk',
        'agama',
        'anak_ke',
        'jumlah_saudara',
        'alamat',
        'rt',
        'rw',
        'desa',
        'kecamatan',
        'kabupaten',
        'provinsi',
        'kode_pos',
        'nama_ayah',
        'pekerjaan_ayah',
        'nama_ibu',
        'pekerjaan_ibu',
        'penghasilan_ortu'
    ];

    foreach($fields as $field){
        if(empty($siswa->$field)){
            return false;
        }
    }

    return true;
}

private function ppdb_required_upload_complete($siswa){

    if(!$siswa){
        return false;
    }

    foreach($this->ppdb_required_upload_fields() as $field){
        if(empty($siswa->$field)){
            return false;
        }
    }

    return true;
}
	//biodata_saveupload
	public function save_upload(){

    if(!$this->session->userdata('ppdb_login')){
        redirect('ppdb/login');
    }

    $siswa = $this->db
        ->where('id', $this->session->userdata('ppdb_id'))
        ->get('ppdb')
        ->row();

    if(!$siswa){
        redirect('ppdb/login');
    }

    $upload_path = './uploads/temp/ppdb/';

    if(!is_dir($upload_path)){
        mkdir($upload_path, 0777, true);
    }

    /*
     * Validasi berkas wajib.
     * Wajib: foto, KK, akta, surat keterangan kelas 9.
     */
    foreach($this->ppdb_required_upload_fields() as $field){
        if(empty($siswa->$field) && empty($_FILES[$field]['name'])){
            $label = [
                'foto' => 'Pas Foto',
                'kk_file' => 'Kartu Keluarga',
                'akta_file' => 'Akta Kelahiran',
                'sk_kelas9_file' => 'Surat Keterangan Kelas 9'
            ];

            $this->session->set_flashdata(
                'error',
                ($label[$field] ?? $field).' wajib diupload.'
            );

            redirect('ppdb/upload');
        }
    }

    $nisn = !empty($siswa->nisn) ? $siswa->nisn : $siswa->id;
    $file_map = $this->ppdb_upload_file_map();

    $data = [];
    $verifikasi_berkas = [];
    if(!empty($siswa->verifikasi_berkas_json)){
        $verifikasi_berkas = json_decode($siswa->verifikasi_berkas_json, true);
    }
    $ada_upload_baru = false;

    foreach($file_map as $field => $nama_file){

        if(empty($_FILES[$field]['name'])){
            continue;
        }

        $ext = strtolower(pathinfo($_FILES[$field]['name'], PATHINFO_EXTENSION));
        $allowed_ext = ['pdf', 'jpg', 'jpeg', 'png'];

        if(!in_array($ext, $allowed_ext)){
            $this->session->set_flashdata(
                'error',
                'Format file '.$nama_file.' tidak valid. Gunakan PDF/JPG/PNG.'
            );

            redirect('ppdb/upload');
        }

        $_FILES['upload_temp']['name']     = $nama_file.'_'.$nisn.'.'.$ext;
        $_FILES['upload_temp']['type']     = $_FILES[$field]['type'];
        $_FILES['upload_temp']['tmp_name'] = $_FILES[$field]['tmp_name'];
        $_FILES['upload_temp']['error']    = $_FILES[$field]['error'];
        $_FILES['upload_temp']['size']     = $_FILES[$field]['size'];

        $config['upload_path']   = $upload_path;
        $config['allowed_types'] = 'pdf|jpg|jpeg|png';
        $config['max_size']      = 1000;
        $config['overwrite']     = TRUE;
        $config['file_name']     = $nama_file.'_'.$nisn;

        $this->load->library('upload');
        $this->upload->initialize($config);

        if(!$this->upload->do_upload('upload_temp')){

            $this->session->set_flashdata(
                'error',
                'Gagal upload '.$nama_file.': '.$this->upload->display_errors('', '')
            );

            redirect('ppdb/upload');
        }

        $upload = $this->upload->data();
        $ada_upload_baru = true;

        /*
         * Hapus file lama hanya jika nama file lama berbeda.
         * Ini aman kalau ekstensi berubah, misalnya PDF diganti JPG.
         */
        if(!empty($siswa->$field) && $siswa->$field != $upload['file_name']){
            $old_file = $upload_path.$siswa->$field;

            if(file_exists($old_file) && is_file($old_file)){
                unlink($old_file);
            }
        }

        $data[$field] = $upload['file_name'];

        // Reset status verifikasi dokumen ini jika sebelumnya salah
        if(isset($verifikasi_berkas[$field])){
            $verifikasi_berkas[$field]['status'] = 'Menunggu';
            $verifikasi_berkas[$field]['catatan'] = '';
        }
    }

    if($ada_upload_baru){
        $data['verifikasi_berkas_json'] = json_encode($verifikasi_berkas);
    }

    /*
     * Ambil ulang data setelah upload untuk cek kelengkapan final.
     */
    $merged = clone $siswa;

    foreach($data as $key => $value){
        $merged->$key = $value;
    }

    if(!in_array($siswa->status, ['Diterima', 'Ditolak'])){
        if($siswa->status == 'Perlu Perbaikan' && $ada_upload_baru){
            $data['status'] = 'Menunggu Verifikasi Berkas';
        } else {
            if($this->ppdb_biodata_complete($merged) && $this->ppdb_required_upload_complete($merged)){
                $data['status'] = 'Menunggu Verifikasi Berkas';
            } elseif($this->ppdb_biodata_complete($merged)){
                $data['status'] = 'Upload Berkas';
            } else {
                $data['status'] = 'Lengkapi Biodata';
            }
        }
    }

    if($this->db->field_exists('updated_at', 'ppdb')){
        $data['updated_at'] = date('Y-m-d H:i:s');
    }

    if(!empty($data)){
        $this->db->where('id', $siswa->id);
        $this->db->update('ppdb', $data);

        $this->session->set_flashdata('success', 'Berkas berhasil disimpan.');
    } else {
        $this->session->set_flashdata('success', 'Tidak ada file baru yang diupload.');
    }

    redirect('ppdb/dashboard');
}
	
	
	
	//login
	public function login(){
    $data['nama_ppdb'] = $this->get_nama_ppdb();
    $this->load->view('public/ppdb_login', $data);
	}

	public function auth(){

    $nisn = trim($this->input->post('username'));
    $password = trim($this->input->post('password'));

    $user = $this->db
        ->group_start()
            ->where('username', $nisn)
            ->or_where('nisn', $nisn)
        ->group_end()
        ->get('ppdb')
        ->row();

    if(!$user){
        $this->session->set_flashdata('error', 'NISN tidak ditemukan.');
        redirect('ppdb/login');
    }

    $login_ok = false;

    if(!empty($user->password) && password_verify($password, $user->password)){
        $login_ok = true;
    }

    /*
     * Fallback untuk password lama yang masih teks biasa.
     */
    if(!$login_ok && !empty($user->password) && $user->password === $password){
        $login_ok = true;

        $this->db->where('id', $user->id);
        $this->db->update('ppdb', [
            'password' => password_hash($password, PASSWORD_DEFAULT)
        ]);
    }

    /*
     * Fallback untuk password lama md5.
     */
    if(!$login_ok && !empty($user->password) && $user->password === md5($password)){
        $login_ok = true;

        $this->db->where('id', $user->id);
        $this->db->update('ppdb', [
            'password' => password_hash($password, PASSWORD_DEFAULT)
        ]);
    }

    if(!$login_ok){
        $this->session->set_flashdata('error', 'Password salah.');
        redirect('ppdb/login');
    }

    $this->session->set_userdata([
        'ppdb_login' => true,
        'ppdb_id'    => $user->id,
        'ppdb_nisn'  => $user->nisn,
        'ppdb_nama'  => $user->nama_lengkap
    ]);

    redirect('ppdb/dashboard');
}
	//dasboard
	public function dashboard(){

    if(!$this->session->userdata('ppdb_login')){
        redirect('ppdb/login');
    }

    $data['siswa'] = $this->db
        ->where('id', $this->session->userdata('ppdb_id'))
        ->get('ppdb')
        ->row();

    if(!$data['siswa']){
        redirect('ppdb/login');
    }

    $siswa = $data['siswa'];

    $biodata_complete = $this->ppdb_biodata_complete($siswa);
    $upload_complete = $this->ppdb_required_upload_complete($siswa);

    if($siswa->status == 'Perlu Perbaikan'){

        $data['progress'] = 70;
        $data['status_text'] = 'Perlu Perbaikan';
        $data['status_badge'] = 'bg-warning text-dark';
        $data['status_seleksi'] = 'Berkas atau biodata perlu diperbaiki.';
        $data['action_text'] = 'Perbaiki Data / Berkas';
        $data['action_link'] = base_url('ppdb/upload');
        $data['desc'] = 'Silakan periksa kembali data dan dokumen yang telah diunggah.';

    } elseif($siswa->status == 'Diterima'){

        $data['progress'] = 100;
        $data['status_text'] = 'Diterima';
        $data['status_badge'] = 'bg-success';
        $data['status_seleksi'] = 'Selamat, Anda dinyatakan diterima.';
        $data['action_text'] = 'Lihat Detail Pendaftaran';
        $data['action_link'] = base_url('ppdb/detail');
        $data['desc'] = 'Proses pendaftaran Anda telah selesai. Silakan menunggu informasi daftar ulang dari madrasah.';

    } elseif($siswa->status == 'Ditolak'){

        $data['progress'] = 100;
        $data['status_text'] = 'Tidak Diterima';
        $data['status_badge'] = 'bg-danger';
        $data['status_seleksi'] = 'Mohon maaf, Anda belum diterima.';
        $data['action_text'] = 'Lihat Data Pendaftaran';
        $data['action_link'] = base_url('ppdb/detail');
        $data['desc'] = 'Status seleksi Anda telah selesai.';

    } elseif(!$biodata_complete){

        $data['progress'] = 35;
        $data['status_text'] = 'Lengkapi Biodata';
        $data['status_badge'] = 'bg-warning text-dark';
        $data['status_seleksi'] = 'Belum masuk tahap seleksi.';
        $data['action_text'] = 'Isi Biodata Tahap 2';
        $data['action_link'] = base_url('ppdb/biodata');
        $data['desc'] = 'Lengkapi biodata lanjutan untuk melanjutkan proses pendaftaran.';

    } elseif(!$upload_complete){

        $data['progress'] = 60;
        $data['status_text'] = 'Upload Berkas';
        $data['status_badge'] = 'bg-info text-dark';
        $data['status_seleksi'] = 'Belum diverifikasi.';
        $data['action_text'] = 'Upload Dokumen';
        $data['action_link'] = base_url('ppdb/upload');
        $data['desc'] = 'Upload berkas wajib: Pas Foto, Kartu Keluarga, Akta Kelahiran, dan Surat Keterangan Kelas 9.';

    } else {

        $data['progress'] = 85;
        $data['status_text'] = 'Menunggu Verifikasi';
        $data['status_badge'] = 'bg-primary';
        $data['status_seleksi'] = 'Sedang diverifikasi panitia.';
        $data['action_text'] = 'Lihat / Update Berkas';
        $data['action_link'] = base_url('ppdb/upload');
        $data['desc'] = 'Dokumen wajib telah lengkap dan sedang menunggu verifikasi admin.';
    }
	$data['pengumuman_ppdb'] = $this->get_ppdb_pengumuman($siswa->status ?? null, false);
	$data['popup_pengumuman'] = $this->get_ppdb_pengumuman($siswa->status ?? null, true);
    $this->load->view('public/ppdb_dashboard', $data);
}
	//Detail_peserta
	public function detail(){

    if(!$this->session->userdata('ppdb_login')){
        redirect('ppdb/login');
    }

    $data['siswa'] = $this->db
        ->where('id',$this->session->userdata('ppdb_id'))
        ->get('ppdb')
        ->row();

    $data['nama_ppdb'] = $this->get_nama_ppdb();
    $this->load->view('public/ppdb_detail',$data);
}
	//edit_peserta
	public function edit_detail(){

    if(!$this->session->userdata('ppdb_login')){
        redirect('ppdb/login');
    }

    $data['siswa'] = $this->db
        ->where('id',$this->session->userdata('ppdb_id'))
        ->get('ppdb')
        ->row();

    $this->load->view('public/ppdb_edit_detail',$data);
}
	//update_peserta
	public function update_detail(){

    if(!$this->session->userdata('ppdb_login')){
        redirect('ppdb/login');
    }

    $id = $this->session->userdata('ppdb_id');

    $data = [
        'nama_lengkap' => $this->input->post('nama_lengkap'),
        'tempat_lahir' => $this->input->post('tempat_lahir'),
        'tanggal_lahir' => $this->input->post('tanggal_lahir'),
        'jk' => $this->input->post('jk'),
        'asal_sekolah' => $this->input->post('asal_sekolah'),
        'no_hp' => $this->input->post('no_hp'),
        'nama_ortu' => $this->input->post('nama_ortu'),

        'nik' => $this->input->post('nik'),
        'no_kk' => $this->input->post('no_kk'),
        'agama' => $this->input->post('agama'),
        'anak_ke' => $this->input->post('anak_ke'),
        'jumlah_saudara' => $this->input->post('jumlah_saudara'),

        'alamat' => $this->input->post('alamat'),
        'rt' => $this->input->post('rt'),
        'rw' => $this->input->post('rw'),
        'desa' => $this->input->post('desa'),
        'kecamatan' => $this->input->post('kecamatan'),
        'kabupaten' => $this->input->post('kabupaten'),
        'provinsi' => $this->input->post('provinsi'),
        'kode_pos' => $this->input->post('kode_pos'),

        'nama_ayah' => $this->input->post('nama_ayah'),
        'pekerjaan_ayah' => $this->input->post('pekerjaan_ayah'),
        'nama_ibu' => $this->input->post('nama_ibu'),
        'pekerjaan_ibu' => $this->input->post('pekerjaan_ibu'),
        'penghasilan_ortu' => $this->input->post('penghasilan_ortu')
    ];

    $this->db->where('id',$id);
    $this->db->update('ppdb',$data);

    $this->session->set_flashdata('success','Detail pendaftaran berhasil diperbarui');

    redirect('ppdb/detail');
}
	//logout
	public function logout(){

		$this->session->unset_userdata('ppdb_login');
		$this->session->unset_userdata('ppdb_id');
		$this->session->unset_userdata('ppdb_nama');

		redirect('ppdb/login');
	}
	
	//pdf
	public function download_pdf(){

    if(!$this->session->userdata('ppdb_login')){
        redirect('ppdb/login');
    }

    $data['siswa'] = $this->db
        ->where('id',$this->session->userdata('ppdb_id'))
        ->get('ppdb')
        ->row();

    $data['nama_ppdb'] = $this->get_nama_ppdb();
    $this->load->view('public/ppdb_pdf',$data);
}
	//setting
	private function getTahunAjaran()
{
    $setting = $this->db->get('settings')->row();
    return $setting->tahun_ajaran ?? date('Y').'-'.(date('Y')+1);
}
	//reset ppdb
	public function reset(){
    show_404();
}
private function get_ppdb_pengumuman($status_peserta = null, $popup_only = false){

    $today = date('Y-m-d');

    $this->db->where('status', 'Aktif');

    $this->db->where(
        '(tanggal_mulai IS NULL OR tanggal_mulai <= '.$this->db->escape($today).')',
        null,
        false
    );

    $this->db->where(
        '(tanggal_selesai IS NULL OR tanggal_selesai >= '.$this->db->escape($today).')',
        null,
        false
    );

    if(!empty($status_peserta)){
        $this->db->where(
            '(target_status = '.$this->db->escape('Semua').' OR target_status = '.$this->db->escape($status_peserta).')',
            null,
            false
        );
    } else {
        $this->db->where('target_status', 'Semua');
    }

    if($popup_only){
        $this->db->where('tampil_popup', 1);
        $this->db->order_by('id', 'DESC');
        return $this->db->get('ppdb_pengumuman', 1)->row();
    }

    $this->db->order_by('id', 'DESC');
    return $this->db->get('ppdb_pengumuman', 5)->result();
}
}