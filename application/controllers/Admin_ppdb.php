<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_ppdb extends CI_Controller {

    public function __construct(){
        parent::__construct();
		$this->load->helper('access');
		require_admin_module('ppdb');
    }

    private function getTahunAjaran(){

    $setting = $this->db->get('settings')->row();

    if($setting && !empty($setting->tahun_ajaran)){
        return $setting->tahun_ajaran;
    }

		return date('Y').'-'.(date('Y') + 1);
	}

	private function count_status($status){

		return $this->db
			->where('status',$status)
			->count_all_results('ppdb');
	}

	private function load_list($title, $where = []){
		$keyword = $this->input->get('keyword');

		if(!empty($where)){
			foreach($where as $key => $value){
				$this->db->where($key,$value);
			}
		}

		if(!empty($keyword)){
			$this->db->group_start();
			$this->db->like('nama_lengkap', $keyword);
			$this->db->or_like('nisn', $keyword);
			$this->db->or_like('no_pendaftaran', $keyword);
			$this->db->group_end();
		}

		$data['pendaftar'] = $this->db
			->order_by('created_at','DESC')
			->get('ppdb')
			->result();

		$data['title'] = $title;
		$data['keyword'] = $keyword;
		$data['status_filter'] = isset($where['status']) ? $where['status'] : '';

		$this->load->view('admin_ppdb/index',$data);
	}
	//dashboard
	public function dashboard(){

    $data['total'] = $this->db->count_all('ppdb');
    $data['lengkapi'] = $this->count_status('Lengkapi Biodata');
    $data['upload'] = $this->count_status('Upload Berkas');
    $data['verifikasi'] = $this->count_status('Menunggu Verifikasi Berkas');
    $data['lulus_verifikasi'] = $this->count_status('Lulus Verifikasi') + $this->count_status('Menuju Tes');
    $data['perbaikan'] = $this->count_status('Perlu Perbaikan');
    $data['diterima'] = $this->count_status('Diterima');
    $data['ditolak'] = $this->count_status('Ditolak');

    $data['sudah_migrasi'] = $this->db
        ->where('is_migrated',1)
        ->count_all_results('ppdb');

    $data['belum_migrasi'] = $this->db
        ->where('status','Diterima')
        ->where('is_migrated',0)
        ->count_all_results('ppdb');

    $data['settings'] = $this->db->get('settings')->row();

    $data['terbaru'] = $this->db
        ->order_by('created_at','DESC')
        ->limit(8)
        ->get('ppdb')
        ->result();

    $this->load->view('admin_ppdb/dashboard',$data);
}
	//index dashboard
    public function index(){

		$status = $this->input->get('status');

		if(!empty($status)){
			$this->load_list('Calon Peserta - '.$status, ['status'=>$status]);
		} else {
			$this->load_list('Semua Calon Peserta');
		}
	}

	public function verifikasi(){
		$this->load_list('Verifikasi Berkas', ['status'=>'Menunggu Verifikasi Berkas']);
	}

	public function diterima(){
		$this->load_list('Peserta Diterima', ['status'=>'Diterima']);
	}

	public function ditolak(){
		$this->load_list('Peserta Ditolak', ['status'=>'Ditolak']);
	}

	public function diterima_page(){
		$this->diterima();
	}

	public function ditolak_page(){
		$this->ditolak();
	}

	public function migrasi_data(){

		$data['pendaftar'] = $this->db
			->where('status','Diterima')
			->where('is_migrated',0)
			->order_by('accepted_at','DESC')
			->get('ppdb')
			->result();

		$data['title'] = 'Migrasi Data Peserta Diterima';

		$this->load->view('admin_ppdb/migrasi_data',$data);
	}

    /**
     * Pembagian Jadwal Ujian Masuk PMB Otomatis (Batch Scheduling)
     */
    public function generate_jadwal_otomatis(){
        $tanggal_mulai   = $this->input->post('tanggal_mulai') ? $this->input->post('tanggal_mulai') : date('Y-m-d', strtotime('+1 day'));
        $kuota_per_hari  = (int)$this->input->post('kuota_per_hari');
        if($kuota_per_hari < 1) $kuota_per_hari = 50;

        $jam_tes         = $this->input->post('jam_tes') ? $this->input->post('jam_tes') : '08:00 - 11.30 WITA';
        $ruang_tes       = $this->input->post('ruang_tes') ? $this->input->post('ruang_tes') : 'Kampus MAN 3 Banjar';
        $prefix_nomor    = $this->input->post('prefix_nomor') ? $this->input->post('prefix_nomor') : 'TES-'.date('Y').'-';
        $start_number    = (int)$this->input->post('start_number');
        if($start_number < 1) $start_number = 1;

        $target_peserta  = $this->input->post('target_peserta') ? $this->input->post('target_peserta') : 'lulus_verifikasi';
        $skip_minggu     = $this->input->post('skip_minggu') !== null ? (int)$this->input->post('skip_minggu') : 1;

        // Query peserta target
        $this->db->order_by('id', 'ASC');
        if($target_peserta == 'lulus_verifikasi'){
            $this->db->group_start();
            $this->db->where('status', 'Lulus Verifikasi');
            $this->db->or_where('status', 'Menuju Tes');
            $this->db->group_end();
        } elseif($target_peserta == 'belum_jadwal'){
            $this->db->group_start();
            $this->db->where('tanggal_tes IS NULL', null, false);
            $this->db->or_where('tanggal_tes', '0000-00-00');
            $this->db->or_where('no_peserta_tes IS NULL', null, false);
            $this->db->or_where('no_peserta_tes', '');
            $this->db->group_end();
            $this->db->where_not_in('status', ['Ditolak']);
        } else {
            // Semua peserta yang tidak ditolak
            $this->db->where_not_in('status', ['Ditolak']);
        }

        $peserta_list = $this->db->get('ppdb')->result();

        if(empty($peserta_list)){
            $this->session->set_flashdata('error', 'Tidak ditemukan peserta yang sesuai kriteria target pembagian jadwal.');
            redirect('admin_ppdb');
            return;
        }

        $current_date_ts = strtotime($tanggal_mulai);
        // Jika mulai di hari Minggu dan skip_minggu aktif
        if($skip_minggu && date('N', $current_date_ts) == 7){
            $current_date_ts = strtotime('+1 day', $current_date_ts);
        }

        $current_count_today = 0;
        $total_assigned = 0;
        $day_count = 1;
        $running_number = $start_number;

        foreach($peserta_list as $p){
            // Jika kapasitas hari ini sudah penuh, ganti ke hari berikutnya
            if($current_count_today >= $kuota_per_hari){
                $current_date_ts = strtotime('+1 day', $current_date_ts);
                if($skip_minggu && date('N', $current_date_ts) == 7){
                    $current_date_ts = strtotime('+1 day', $current_date_ts);
                }
                $current_count_today = 0;
                $day_count++;
            }

            $current_date_str = date('Y-m-d', $current_date_ts);
            $nomor_peserta = $prefix_nomor . str_pad($running_number, 4, '0', STR_PAD_LEFT);

            $update_data = [
                'no_peserta_tes' => $nomor_peserta,
                'tanggal_tes'    => $current_date_str,
                'jam_tes'        => $jam_tes,
                'ruang_tes'      => $ruang_tes
            ];

            // Jika status masih pendaftaran awal, ubah ke Lulus Verifikasi agar kartu terbuka
            if(!in_array($p->status, ['Lulus Verifikasi', 'Diterima'])){
                $update_data['status'] = 'Lulus Verifikasi';
            }

            $this->db->where('id', $p->id);
            $this->db->update('ppdb', $update_data);

            $current_count_today++;
            $running_number++;
            $total_assigned++;
        }

        $msg = "Berhasil membagikan jadwal seleksi untuk <strong>{$total_assigned} peserta</strong> ke dalam <strong>{$day_count} hari ujian</strong> (Maksimal {$kuota_per_hari} peserta/hari mulai tanggal ".date('d-m-Y', strtotime($tanggal_mulai)).").";
        $this->session->set_flashdata('success', $msg);

        redirect('admin_ppdb');
    }

	public function monitoring_berkas(){
        $status = $this->input->get('status');
        
        $this->db->order_by('created_at', 'DESC');
        
        if(!empty($status)){
            $this->db->where('status', $status);
        } else {
            $status = 'Menunggu Verifikasi Berkas';
            $this->db->where('status', $status);
        }

        $data['pendaftar'] = $this->db->get('ppdb')->result();
        $data['status_filter'] = $status;
        $data['title'] = 'Monitoring Berkas PPDB';

        $this->load->view('admin_ppdb/monitoring_berkas', $data);
	}
	//fungsi
	// Proses Verifikasi Berkas & Penerbitan Nomor Peserta Tes
    public function proses_verifikasi(){
        $id = $this->input->post('id');
        $status_tujuan = $this->input->post('status') ? $this->input->post('status') : 'Menunggu Verifikasi Berkas';
        $tanggal_tes = $this->input->post('tanggal_tes');
        $jam_tes = $this->input->post('jam_tes');
        $ruang_tes = $this->input->post('ruang_tes');
        $catatan = trim($this->input->post('catatan_verifikasi') ?? '');

        $peserta = $this->db->where('id', $id)->get('ppdb')->row();
        if(!$peserta){
            $this->session->set_flashdata('error', 'Data peserta tidak ditemukan.');
            redirect('admin_ppdb/monitoring_berkas');
            return;
        }

        $setting = $this->db->get('settings')->row();
        $tahun_short = date('y'); // e.g. 26

        $update_data = [
            'status' => $status_tujuan,
            'catatan_verifikasi' => $catatan
        ];

        // Jika Lulus Verifikasi / Menuju Tes / Diterima
        if(in_array($status_tujuan, ['Lulus Verifikasi', 'Menuju Tes', 'Menunggu Verifikasi Berkas', 'Diterima'])){
            // Gunakan jadwal dari form atau fallback ke default settings
            $update_data['tanggal_tes'] = !empty($tanggal_tes) ? $tanggal_tes : ($setting->default_tanggal_tes ?? NULL);
            $update_data['jam_tes']     = !empty($jam_tes) ? $jam_tes : ($setting->default_jam_tes ?? '08:00 - 11.30 WITA');
            $update_data['ruang_tes']   = !empty($ruang_tes) ? $ruang_tes : ($setting->default_ruang_tes ?? 'Kampus MAN 3 Banjar');

            // Generate No Peserta Tes jika belum ada dan statusnya Lulus Verifikasi / Menuju Tes / Diterima
            if(in_array($status_tujuan, ['Lulus Verifikasi', 'Menuju Tes', 'Diterima']) && empty($peserta->no_peserta_tes)){
                $prefix = 'TES' . $tahun_short . '-';
                
                // Cari nomor urut tes terakhir
                $last_tes = $this->db
                    ->like('no_peserta_tes', $prefix, 'after')
                    ->order_by('id', 'DESC')
                    ->get('ppdb')
                    ->row();

                $urut = 1;
                if($last_tes && !empty($last_tes->no_peserta_tes)){
                    $parts = explode('-', $last_tes->no_peserta_tes);
                    if(isset($parts[1]) && is_numeric($parts[1])){
                        $urut = (int)$parts[1] + 1;
                    }
                } else {
                    // Alternatif hitung jumlah yang sudah punya no tes
                    $count_tes = $this->db->where('no_peserta_tes IS NOT NULL', null, false)->count_all_results('ppdb');
                    $urut = $count_tes + 1;
                }

                $no_peserta_tes = $prefix . str_pad($urut, 4, '0', STR_PAD_LEFT);
                $update_data['no_peserta_tes'] = $no_peserta_tes;
            }
        } elseif($status_tujuan == 'Perlu Perbaikan') {
            $update_data['status'] = 'Perlu Perbaikan';
        } elseif($status_tujuan == 'Ditolak') {
            $update_data['status'] = 'Ditolak';
            $update_data['rejected_at'] = date('Y-m-d H:i:s');
        }

        $this->db->where('id', $id)->update('ppdb', $update_data);

        $peserta_updated = $this->db->where('id', $id)->get('ppdb')->row();

        // Notifikasi Sukses
        $msg = "Berkas pendaftar <strong>" . htmlspecialchars($peserta->nama_lengkap) . "</strong> berhasil diperbarui ke status: <strong>" . $status_tujuan . "</strong>.";
        if(!empty($peserta_updated->no_peserta_tes)){
            $msg .= " Nomor Peserta Tes: <span class='badge bg-success'>" . $peserta_updated->no_peserta_tes . "</span>.";
        }

        $this->session->set_flashdata('success', $msg);

        // Jika request via AJAX
        if($this->input->is_ajax_request()){
            echo json_encode([
                'status' => 'success',
                'message' => $msg,
                'data' => $peserta_updated
            ]);
            return;
        }

        $redirect_to = $this->input->post('redirect_to') ? $this->input->post('redirect_to') : 'admin_ppdb/monitoring_berkas';
        redirect($redirect_to);
    }

    public function terima($id){

    $this->db->where('id',$id);
    $this->db->update('ppdb',[
        'status' => 'Diterima',
        'accepted_at' => date('Y-m-d H:i:s'),
        'rejected_at' => NULL
    ]);

    $this->session->set_flashdata(
        'success',
        'Peserta berhasil diterima. Data belum dimigrasikan ke tabel siswa.'
    );

    redirect('admin_ppdb/diterima');
}

	public function tolak($id){

		$this->db->where('id',$id);
		$this->db->update('ppdb',[
			'status' => 'Ditolak',
			'rejected_at' => date('Y-m-d H:i:s'),
			'accepted_at' => NULL
		]);

		$this->session->set_flashdata('success','Peserta berhasil ditolak');

		redirect('admin_ppdb/ditolak');
	}

	public function batal_status($id){

    $p = $this->db
        ->where('id',$id)
        ->get('ppdb')
        ->row();

    if(!$p){
        show_404();
    }

    if($p->is_migrated == 1){
        $this->session->set_flashdata(
            'error',
            'Status tidak bisa dibatalkan karena data sudah dimigrasikan ke tabel siswa.'
        );

        redirect('admin_ppdb/detail/'.$id);
    }

    $this->db->where('id',$id);
    $this->db->update('ppdb',[
        'status' => 'Menunggu Verifikasi Berkas',
        'accepted_at' => NULL,
        'rejected_at' => NULL
    ]);

    $this->session->set_flashdata(
        'success',
        'Status peserta berhasil dibatalkan dan dikembalikan ke Menunggu Verifikasi Berkas.'
    );

    redirect('admin_ppdb/verifikasi');
}

	public function perbaikan($id){

    $this->db->where('id',$id);
    $this->db->update('ppdb',[
        'status' => 'Perlu Perbaikan'
    ]);

    $this->session->set_flashdata(
        'success',
        'Status peserta diubah menjadi Perlu Perbaikan.'
    );

    redirect('admin_ppdb?status=Perlu Perbaikan');
}
	//detail
	public function detail($id = null){

    if($id == null){
        echo "ID tidak dikirim";
        exit;
    }

    $data['p'] = $this->db
        ->where('id',$id)
        ->get('ppdb')
        ->row();

    if(!$data['p']){
        echo "Data PPDB dengan ID ".$id." tidak ditemukan";
        exit;
    }

    $this->load->view('admin_ppdb/detail',$data);
}
	//edit
	public function edit($id){

    $data['p'] = $this->db
        ->where('id',$id)
        ->get('ppdb')
        ->row();

    if(!$data['p']){
        show_404();
    }

    $this->load->view('admin_ppdb/edit',$data);
}
	//update
	public function update($id){
        $data = [
            'nama_lengkap'       => $this->input->post('nama_lengkap'),
            'nisn'               => $this->input->post('nisn'),
            'status'             => $this->input->post('status'),
            'jalur_pendaftaran'  => $this->input->post('jalur_pendaftaran'),
            'tempat_lahir'       => $this->input->post('tempat_lahir'),
            'tanggal_lahir'      => $this->input->post('tanggal_lahir'),
            'jk'                 => $this->input->post('jk'),
            'asal_sekolah'       => $this->input->post('asal_sekolah'),
            'no_hp'              => $this->input->post('no_hp'),
            'email'              => $this->input->post('email'),
            'nama_ortu'          => $this->input->post('nama_ortu'),
            'no_peserta_tes'     => $this->input->post('no_peserta_tes'),
            'tanggal_tes'        => !empty($this->input->post('tanggal_tes')) ? $this->input->post('tanggal_tes') : NULL,
            'jam_tes'            => $this->input->post('jam_tes'),
            'ruang_tes'          => $this->input->post('ruang_tes'),
            'nilai_tes'          => !empty($this->input->post('nilai_tes')) ? $this->input->post('nilai_tes') : NULL,
            'catatan_verifikasi' => $this->input->post('catatan_verifikasi'),
            'nik'                => $this->input->post('nik'),
            'no_kk'              => $this->input->post('no_kk'),
            'agama'              => $this->input->post('agama'),
            'anak_ke'            => $this->input->post('anak_ke'),
            'jumlah_saudara'     => $this->input->post('jumlah_saudara'),
            'alamat'             => $this->input->post('alamat'),
            'rt'                 => $this->input->post('rt'),
            'rw'                 => $this->input->post('rw'),
            'desa'               => $this->input->post('desa'),
            'kecamatan'          => $this->input->post('kecamatan'),
            'kabupaten'          => $this->input->post('kabupaten'),
            'provinsi'           => $this->input->post('provinsi'),
            'kode_pos'           => $this->input->post('kode_pos'),
            'nama_ayah'          => $this->input->post('nama_ayah'),
            'pekerjaan_ayah'     => $this->input->post('pekerjaan_ayah'),
            'nama_ibu'           => $this->input->post('nama_ibu'),
            'pekerjaan_ibu'      => $this->input->post('pekerjaan_ibu'),
            'penghasilan_ortu'   => $this->input->post('penghasilan_ortu')
        ];

        $this->db->where('id',$id);
        $this->db->update('ppdb',$data);

        $this->session->set_flashdata('success','Data peserta berhasil diperbarui');
        redirect('admin_ppdb/detail/'.$id);
    }
	//delete
	public function delete($id){

    $p = $this->db
        ->where('id',$id)
        ->get('ppdb')
        ->row();

    if(!$p){
        show_404();
    }

    if($p->is_migrated == 1){
        $this->session->set_flashdata(
            'error',
            'Data sudah dimigrasikan ke tabel siswa, tidak boleh dihapus dari PPDB.'
        );

        redirect('admin_ppdb/detail/'.$id);
    }

    $file_fields = [
        'foto',
        'kk_file',
        'akta_file',
        'rapor_file',
        'skl_file',
        'nisn_file',
        'ijazah_file',
        'sertifikat_file'
    ];

    foreach($file_fields as $field){

        if(!empty($p->$field)){

            $file = './uploads/temp/ppdb/'.$p->$field;

            if(file_exists($file)){
                unlink($file);
            }
        }
    }

    $this->db->delete('ppdb',['id'=>$id]);

    $this->session->set_flashdata('success','Data peserta dan file upload berhasil dihapus');

    redirect('admin_ppdb');
}
	//migrasi
	public function migrasi($id){

    $ppdb = $this->db
        ->where('id',$id)
        ->get('ppdb')
        ->row();

    if(!$ppdb){
        show_404();
    }

    if($ppdb->status != 'Diterima'){
        $this->session->set_flashdata('error','Hanya peserta diterima yang bisa dimigrasikan');
        redirect('admin_ppdb/detail/'.$id);
    }

    if($ppdb->is_migrated == 1){
        $this->session->set_flashdata('error','Data sudah pernah dimigrasikan');
        redirect('admin_ppdb/detail/'.$id);
    }

    $cek = $this->db
        ->where('nisn',$ppdb->nisn)
        ->get('siswa')
        ->row();

    if($cek){
        $this->session->set_flashdata('error','NISN sudah ada di tabel siswa');
        redirect('admin_ppdb/detail/'.$id);
    }

    $tahun_ajaran = $this->getTahunAjaran();

    $file_map = [
        'foto'             => 'foto',
        'kk_file'          => 'kk',
        'akta_file'        => 'akta',
        'nisn_file'        => 'nisn',
        'ijazah_file'      => 'ijazah',
        'sertifikat_file'  => 'sertifikat'
    ];

    foreach($file_map as $field => $folder){

        if(!empty($ppdb->$field)){

            $from = './uploads/temp/ppdb/'.$ppdb->$field;
            $to_dir = './uploads/siswa/'.$tahun_ajaran.'/'.$folder.'/';

            if(!is_dir($to_dir)){
                mkdir($to_dir,0777,true);
            }

            $to = $to_dir.$ppdb->$field;

            if(file_exists($from)){
                copy($from,$to);
            }
        }
    }

    $tahun_masuk = substr($tahun_ajaran,2,2);

    $last_siswa = $this->db
        ->order_by('id','DESC')
        ->get('siswa')
        ->row();

    $nomor_urut = $last_siswa ? $last_siswa->id + 1 : 1;
    $nis = $tahun_masuk.str_pad($nomor_urut,4,'0',STR_PAD_LEFT);

    $data_siswa = [
		'nis' => $nis,
		'nisn' => $ppdb->nisn,
		'nama_lengkap' => $ppdb->nama_lengkap,
		'nik' => $ppdb->nik,
		'no_kk' => $ppdb->no_kk,
		'tempat_lahir' => $ppdb->tempat_lahir,
		'tanggal_lahir' => $ppdb->tanggal_lahir,
		'jk' => $ppdb->jk,
		'agama' => $ppdb->agama,
		'alamat' => $ppdb->alamat,
		'rt' => $ppdb->rt,
		'rw' => $ppdb->rw,
		'desa' => $ppdb->desa,
		'kecamatan' => $ppdb->kecamatan,
		'kabupaten' => $ppdb->kabupaten,
		'provinsi' => $ppdb->provinsi,
		'kode_pos' => $ppdb->kode_pos,
		'nama_ayah' => $ppdb->nama_ayah,
		'pekerjaan_ayah' => $ppdb->pekerjaan_ayah,
		'nama_ibu' => $ppdb->nama_ibu,
		'pekerjaan_ibu' => $ppdb->pekerjaan_ibu,
		'no_hp' => $ppdb->no_hp,
		'asal_sekolah' => $ppdb->asal_sekolah,
		'status_siswa' => 'Aktif',

		'tahun_ajaran_masuk' => $tahun_ajaran,
		'foto' => $ppdb->foto,
		'kk_file' => $ppdb->kk_file,
		'akta_file' => $ppdb->akta_file,
		'nisn_file' => $ppdb->nisn_file,
		'ijazah_file' => $ppdb->ijazah_file,
		'sertifikat_file' => $ppdb->sertifikat_file
	];

    $this->db->insert('siswa',$data_siswa);

    $this->db->where('id',$id);
    $this->db->update('ppdb',[
        'is_migrated' => 1,
        'migrated_at' => date('Y-m-d H:i:s')
    ]);

    $this->session->set_flashdata('success','Data berhasil dimigrasikan ke tabel siswa');

    redirect('admin_ppdb/migrasi_data');
}
	//cleanup
	public function cleanup_temp(){

    $batas = date('Y-m-d H:i:s', strtotime('-2 months'));

    $data = $this->db
        ->group_start()
            ->where('status','Diterima')
            ->where('accepted_at <=',$batas)
        ->group_end()
        ->or_group_start()
            ->where('status','Ditolak')
            ->where('rejected_at <=',$batas)
        ->group_end()
        ->get('ppdb')
        ->result();

    $file_fields = [
        'foto',
        'kk_file',
        'akta_file',
        'rapor_file',
        'skl_file',
        'nisn_file',
        'ijazah_file',
        'sertifikat_file'
    ];

    $hapus = 0;

    foreach($data as $p){

        foreach($file_fields as $field){

            if(!empty($p->$field)){

                $file = './uploads/temp/ppdb/'.$p->$field;

                if(file_exists($file)){
                    unlink($file);
                    $hapus++;
                }
            }
        }
    }

    $this->session->set_flashdata(
        'success',
        'Cleanup selesai. Total file temp terhapus: '.$hapus
    );

    redirect('admin_ppdb/dashboard');
}
	//upload_berkas
	public function upload_berkas($id){

    $data['p'] = $this->db
        ->where('id',$id)
        ->get('ppdb')
        ->row();

    if(!$data['p']){
        show_404();
    }

    $this->load->view('admin_ppdb/upload_berkas',$data);
}
	//save_uploadberkas
	public function save_upload_berkas($id){

    $p = $this->db
        ->where('id',$id)
        ->get('ppdb')
        ->row();

    if(!$p){
        show_404();
    }

    $upload_path = './uploads/temp/ppdb/';

    if(!is_dir($upload_path)){
        mkdir($upload_path,0777,true);
    }

    $file_map = [
        'foto'             => 'foto',
        'kk_file'          => 'kk',
        'akta_file'        => 'akta',
        'rapor_file'       => 'rapor',
        'skl_file'         => 'skl',
        'nisn_file'        => 'nisn',
        'ijazah_file'      => 'ijazah',
        'sertifikat_file'  => 'sertifikat'
    ];

    $data = [];

    foreach($file_map as $field => $nama_file){

        if(!empty($_FILES[$field]['name'])){

            if(!empty($p->$field)){
                $old_file = $upload_path.$p->$field;

                if(file_exists($old_file)){
                    unlink($old_file);
                }
            }

            $ext = strtolower(pathinfo($_FILES[$field]['name'], PATHINFO_EXTENSION));

            $new_file_name = $nama_file.'_'.$p->nisn.'.'.$ext;

            $_FILES['upload_temp']['name']     = $new_file_name;
            $_FILES['upload_temp']['type']     = $_FILES[$field]['type'];
            $_FILES['upload_temp']['tmp_name'] = $_FILES[$field]['tmp_name'];
            $_FILES['upload_temp']['error']    = $_FILES[$field]['error'];
            $_FILES['upload_temp']['size']     = $_FILES[$field]['size'];

            $config['upload_path']   = $upload_path;
            $config['allowed_types'] = 'pdf|jpg|jpeg|png';
            $config['max_size']      = 5000;
            $config['overwrite']     = TRUE;
            $config['file_name']     = $nama_file.'_'.$p->nisn;

            $this->load->library('upload');
            $this->upload->initialize($config);

            if($this->upload->do_upload('upload_temp')){

                $upload = $this->upload->data();
                $data[$field] = $upload['file_name'];

            } else {

                $this->session->set_flashdata(
                    'error',
                    'Gagal upload '.$nama_file.': '.$this->upload->display_errors('', '')
                );

                redirect('admin_ppdb/upload_berkas/'.$id);
            }
        }
    }

    if(!empty($data)){
        $this->db->where('id',$id);
        $this->db->update('ppdb',$data);

        $this->session->set_flashdata('success','Berkas peserta berhasil diperbarui');
    }

    redirect('admin_ppdb/detail/'.$id);
}
	//pdf
	public function pdf($id){

    $data['p'] = $this->db
        ->where('id',$id)
        ->get('ppdb')
        ->row();

    if(!$data['p']){
        show_404();
    }

    $this->load->view('admin_ppdb/pdf',$data);
}
	//setting
	public function settings(){

    $data['settings'] = $this->db->get('settings')->row();

    $this->load->view('admin_ppdb/settings',$data);
}

	public function update_settings(){

		$data = [
			'tahun_ajaran' => $this->input->post('tahun_ajaran'),
			'status_ppdb' => $this->input->post('status_ppdb'),
			'tanggal_mulai' => $this->input->post('tanggal_mulai'),
			'tanggal_selesai' => $this->input->post('tanggal_selesai'),
			'pengumuman_ppdb' => $this->input->post('pengumuman_ppdb'),
			'nama_ppdb' => $this->input->post('nama_ppdb') ? $this->input->post('nama_ppdb') : 'PMB',
			'judul_panjang_ppdb' => $this->input->post('judul_panjang_ppdb') ? $this->input->post('judul_panjang_ppdb') : 'Penerimaan Murid Baru',
			'persyaratan_ppdb' => $this->input->post('persyaratan_ppdb'),
			'default_tanggal_tes' => $this->input->post('default_tanggal_tes') ? $this->input->post('default_tanggal_tes') : NULL,
			'default_jam_tes' => $this->input->post('default_jam_tes') ? $this->input->post('default_jam_tes') : '08:00 - 11.30 WITA',
			'default_ruang_tes' => $this->input->post('default_ruang_tes') ? $this->input->post('default_ruang_tes') : 'Kampus MAN 3 Banjar',
			'materi_tes_info' => $this->input->post('materi_tes_info')
		];

        // Handle pamphlet upload
        if (!empty($_FILES['pamflet_ppdb']['name'])) {
            $upload_path = './uploads/ppdb_pamflet/';
            if (!is_dir($upload_path)) {
                mkdir($upload_path, 0777, true);
            }

            $config['upload_path']   = $upload_path;
            $config['allowed_types'] = 'jpg|jpeg|png';
            $config['max_size']      = 2048;
            $config['file_name']     = 'pamflet_' . time();

            $this->load->library('upload');
            $this->upload->initialize($config);

            if ($this->upload->do_upload('pamflet_ppdb')) {
                $upload_data = $this->upload->data();
                $data['pamflet_ppdb'] = $upload_data['file_name'];
            } else {
                $this->session->set_flashdata('error', 'Gagal upload pamflet: ' . $this->upload->display_errors('',''));
                redirect('admin_ppdb/settings');
            }
        }

		$cek = $this->db->get('settings')->row();

		if($cek){
			$this->db->where('id',$cek->id);
			$this->db->update('settings',$data);
		} else {
			$this->db->insert('settings',$data);
		}

		$this->session->set_flashdata('success','Pengaturan PPDB berhasil disimpan');

		redirect('admin_ppdb/settings');
	}

    public function delete_pamflet() {
        $settings = $this->db->get('settings')->row();
        if ($settings && !empty($settings->pamflet_ppdb)) {
            $path = './uploads/ppdb_pamflet/' . $settings->pamflet_ppdb;
            if (file_exists($path)) {
                unlink($path);
            }
            $this->db->where('id', $settings->id);
            $this->db->update('settings', ['pamflet_ppdb' => NULL]);
            $this->session->set_flashdata('success', 'Pamflet berhasil dihapus.');
        }
        redirect('admin_ppdb/settings');
    }
	//exportppdbcsv
	private function export_ppdb_csv($filename, $status = null){

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="'.$filename.'"');

    $output = fopen('php://output', 'w');

    // BOM agar Excel Windows membaca UTF-8 dengan benar
    fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

    fputcsv($output, [
        'No Pendaftaran',
        'Nama Lengkap',
        'NISN',
        'NIK',
        'No KK',
        'Tempat Lahir',
        'Tanggal Lahir',
        'Jenis Kelamin',
        'Agama',
        'Asal Sekolah',
        'No HP',
        'Nama Ayah',
        'Pekerjaan Ayah',
        'Nama Ibu',
        'Pekerjaan Ibu',
        'Alamat',
        'RT',
        'RW',
        'Desa',
        'Kecamatan',
        'Kabupaten',
        'Provinsi',
        'Kode Pos',
        'Status',
        'Tanggal Daftar',
        'Tanggal Diterima',
        'Tanggal Ditolak',
        'Migrasi'
    ]);

    $this->db->order_by('created_at','DESC');

    if($status != null){
        $this->db->where('status',$status);
    }

    $data = $this->db->get('ppdb')->result();

    foreach($data as $p){

        fputcsv($output, [
            $p->no_pendaftaran,
            $p->nama_lengkap,
            $p->nisn,
            $p->nik,
            $p->no_kk,
            $p->tempat_lahir,
            $p->tanggal_lahir,
            $p->jk == 'L' ? 'Laki-laki' : 'Perempuan',
            $p->agama,
            $p->asal_sekolah,
            $p->no_hp,
            $p->nama_ayah,
            $p->pekerjaan_ayah,
            $p->nama_ibu,
            $p->pekerjaan_ibu,
            $p->alamat,
            $p->rt,
            $p->rw,
            $p->desa,
            $p->kecamatan,
            $p->kabupaten,
            $p->provinsi,
            $p->kode_pos,
            $p->status,
            $p->created_at,
            $p->accepted_at,
            $p->rejected_at,
            $p->is_migrated == 1 ? 'Sudah Migrasi' : 'Belum Migrasi'
        ]);
    }

    fclose($output);
    exit;
}
	//exportall
	public function export_all(){

    $this->export_ppdb_csv('rekap-ppdb-semua.csv');
}
	//exportditerima
public function export_diterima(){

    $this->export_ppdb_csv('rekap-ppdb-diterima.csv','Diterima');
}

public function reset_password($id){

    if(!$this->session->userdata('logged_in')){
        redirect('auth');
    }

    $peserta = $this->db
        ->where('id', $id)
        ->get('ppdb')
        ->row();

    if(!$peserta){
        show_404();
    }

    $password_baru = trim($this->input->post('password_baru'));

    if(empty($password_baru)){
        $this->session->set_flashdata('error', 'Password baru wajib diisi.');
        redirect('admin_ppdb/detail/'.$id);
    }

    if(strlen($password_baru) < 6){
        $this->session->set_flashdata('error', 'Password minimal 6 karakter.');
        redirect('admin_ppdb/detail/'.$id);
    }

    $data = [
        'password' => password_hash($password_baru, PASSWORD_DEFAULT)
    ];

    if($this->db->field_exists('password_updated_at', 'ppdb')){
        $data['password_updated_at'] = date('Y-m-d H:i:s');
    }

    if($this->db->field_exists('updated_at', 'ppdb')){
        $data['updated_at'] = date('Y-m-d H:i:s');
    }

    $this->db->where('id', $id);
    $this->db->update('ppdb', $data);

    $this->session->set_flashdata('success', 'Password peserta berhasil direset.');
    $this->session->set_flashdata('password_baru_ppdb', $password_baru);

    redirect('admin_ppdb/detail/'.$id);
}
public function reset(){

    // hapus semua file temp ppdb
    $folder = './uploads/temp/ppdb/';

    if(is_dir($folder)){
        $files = glob($folder.'*');

        foreach($files as $file){
            if(is_file($file)){
                unlink($file);
            }
        }
    }

    // kosongkan tabel ppdb
    $this->db->truncate('ppdb');

    $this->session->set_flashdata(
        'success',
        'Data PPDB berhasil dikosongkan. Data siswa resmi tetap aman.'
    );

    redirect('admin_ppdb/dashboard');
}
private function ppdb_pengumuman_post_data(){

    $tanggal_mulai = trim($this->input->post('tanggal_mulai'));
    $tanggal_selesai = trim($this->input->post('tanggal_selesai'));

    return [
        'judul' => trim($this->input->post('judul')),
        'kategori' => trim($this->input->post('kategori')),
        'isi' => trim($this->input->post('isi')),
        'tanggal_mulai' => !empty($tanggal_mulai) ? $tanggal_mulai : null,
        'tanggal_selesai' => !empty($tanggal_selesai) ? $tanggal_selesai : null,
        'waktu' => trim($this->input->post('waktu')),
        'lokasi' => trim($this->input->post('lokasi')),
        'link' => trim($this->input->post('link')),
        'target_status' => trim($this->input->post('target_status')),
        'tampil_popup' => $this->input->post('tampil_popup') ? 1 : 0,
        'status' => trim($this->input->post('status'))
    ];
}

private function ppdb_nonaktifkan_popup_lain($id){

    $this->db->where('id !=', $id);
    $this->db->update('ppdb_pengumuman', [
        'tampil_popup' => 0
    ]);
}

public function pengumuman(){

    if(!$this->session->userdata('logged_in')){
        redirect('auth');
    }

    $data['pengumuman'] = $this->db
        ->order_by('id', 'DESC')
        ->get('ppdb_pengumuman')
        ->result();

    $this->load->view('admin_ppdb/pengumuman', $data);
}

public function add_pengumuman(){

    if(!$this->session->userdata('logged_in')){
        redirect('auth');
    }

    $data = $this->ppdb_pengumuman_post_data();

    if(empty($data['judul'])){
        $this->session->set_flashdata('error', 'Judul pengumuman wajib diisi.');
        redirect('admin_ppdb/pengumuman');
    }

    if(empty($data['kategori'])){
        $data['kategori'] = 'Informasi';
    }

    if(empty($data['target_status'])){
        $data['target_status'] = 'Semua';
    }

    if(empty($data['status'])){
        $data['status'] = 'Draft';
    }

    $data['created_at'] = date('Y-m-d H:i:s');
    $data['updated_at'] = date('Y-m-d H:i:s');

    $this->db->insert('ppdb_pengumuman', $data);
    $id = $this->db->insert_id();

    if($data['tampil_popup'] == 1 && $data['status'] == 'Aktif'){
        $this->ppdb_nonaktifkan_popup_lain($id);
    }

    $this->session->set_flashdata('success', 'Pengumuman PPDB berhasil ditambahkan.');
    redirect('admin_ppdb/pengumuman');
}

public function update_pengumuman($id){

    if(!$this->session->userdata('logged_in')){
        redirect('auth');
    }

    $cek = $this->db
        ->where('id', $id)
        ->get('ppdb_pengumuman')
        ->row();

    if(!$cek){
        show_404();
    }

    $data = $this->ppdb_pengumuman_post_data();

    if(empty($data['judul'])){
        $this->session->set_flashdata('error', 'Judul pengumuman wajib diisi.');
        redirect('admin_ppdb/pengumuman');
    }

    if(empty($data['kategori'])){
        $data['kategori'] = 'Informasi';
    }

    if(empty($data['target_status'])){
        $data['target_status'] = 'Semua';
    }

    if(empty($data['status'])){
        $data['status'] = 'Draft';
    }

    $data['updated_at'] = date('Y-m-d H:i:s');

    $this->db->where('id', $id);
    $this->db->update('ppdb_pengumuman', $data);

    if($data['tampil_popup'] == 1 && $data['status'] == 'Aktif'){
        $this->ppdb_nonaktifkan_popup_lain($id);
    }

    $this->session->set_flashdata('success', 'Pengumuman PPDB berhasil diperbarui.');
    redirect('admin_ppdb/pengumuman');
}

public function delete_pengumuman($id){

    if(!$this->session->userdata('logged_in')){
        redirect('auth');
    }

    $cek = $this->db
        ->where('id', $id)
        ->get('ppdb_pengumuman')
        ->row();

    if(!$cek){
        show_404();
    }

    $this->db->where('id', $id);
    $this->db->delete('ppdb_pengumuman');

    $this->session->set_flashdata('success', 'Pengumuman PPDB berhasil dihapus.');
    redirect('admin_ppdb/pengumuman');
}
private function filter_existing_fields($table, $data){

    $result = [];

    foreach($data as $field => $value){
        if($this->db->field_exists($field, $table)){
            $result[$field] = $value;
        }
    }

    return $result;
}

private function ppdb_tahun_ajaran_aktif(){

    if($this->db->table_exists('ppdb_settings')){
        $settings = $this->db->get('ppdb_settings')->row();

        if($settings && !empty($settings->tahun_ajaran)){
            return $settings->tahun_ajaran;
        }
    }

    return date('Y').'/'.(date('Y') + 1);
}

private function copy_ppdb_file_to_siswa($filename, $nisn, $jenis){

    if(empty($filename)){
        return null;
    }

    $source = FCPATH.'uploads/temp/ppdb/'.$filename;

    if(!file_exists($source) || !is_file($source)){
        return null;
    }

    $folder = FCPATH.'uploads/siswa/';

    if(!is_dir($folder)){
        mkdir($folder, 0777, true);
    }

    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

    if(empty($ext)){
        $ext = 'file';
    }

    $safe_nisn = preg_replace('/[^0-9A-Za-z_-]/', '', $nisn);

    if(empty($safe_nisn)){
        $safe_nisn = 'siswa';
    }

    $new_name = $jenis.'_'.$safe_nisn.'.'.$ext;
    $dest = $folder.$new_name;

    copy($source, $dest);

    return $new_name;
}

private function insert_siswa_dokumen_from_ppdb($siswa_id, $ppdb, $copied_files){

    if(!$this->db->table_exists('siswa_dokumen')){
        return;
    }

    $dokumen = [
        'foto' => 'Pas Foto',
        'kk_file' => 'Kartu Keluarga',
        'akta_file' => 'Akta Kelahiran',
        'rapor_file' => 'Rapor / Nilai',
        'skl_file' => 'Surat Keterangan Lulus',
        'nisn_file' => 'Surat Aktif NISN',
        'sk_kelas9_file' => 'Surat Keterangan Kelas 9',
        'ijazah_file' => 'Ijazah',
        'sertifikat_file' => 'Sertifikat Prestasi / Tahfidz'
    ];

    foreach($dokumen as $field => $label){

        if(empty($copied_files[$field])){
            continue;
        }

        $this->db->insert('siswa_dokumen', [
            'siswa_id' => $siswa_id,
            'jenis_dokumen' => $label,
            'nama_file' => $copied_files[$field],
            'folder' => 'uploads/siswa/',
            'keterangan' => 'Migrasi dari PPDB',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }
}

private function insert_siswa_detail_from_ppdb($siswa_id, $ppdb){

    if(!$this->db->table_exists('siswa_detail')){
        return;
    }

    $data = [
        'siswa_id' => $siswa_id,
        'anak_ke' => $ppdb->anak_ke ?? null,
        'jumlah_saudara' => $ppdb->jumlah_saudara ?? null,
        'kewarganegaraan' => 'Indonesia',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
    ];

    $data = $this->filter_existing_fields('siswa_detail', $data);

    $this->db->insert('siswa_detail', $data);
}

private function insert_siswa_orangtua_from_ppdb($siswa_id, $ppdb){

    if(!$this->db->table_exists('siswa_orangtua')){
        return;
    }

    $data = [
        'siswa_id' => $siswa_id,

        'nama_ayah' => $ppdb->nama_ayah ?? null,
        'pekerjaan_ayah' => $ppdb->pekerjaan_ayah ?? null,
        'penghasilan_ayah' => $ppdb->penghasilan_ortu ?? null,

        'nama_ibu' => $ppdb->nama_ibu ?? null,
        'pekerjaan_ibu' => $ppdb->pekerjaan_ibu ?? null,
        'penghasilan_ibu' => $ppdb->penghasilan_ortu ?? null,

        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
    ];

    $data = $this->filter_existing_fields('siswa_orangtua', $data);

    $this->db->insert('siswa_orangtua', $data);
}

private function proses_migrasi_ppdb_ke_siswa($ppdb_id){

    $p = $this->db
        ->where('id', $ppdb_id)
        ->get('ppdb')
        ->row();

    if(!$p){
        return [
            'status' => false,
            'message' => 'Data PPDB tidak ditemukan.'
        ];
    }

    if($p->status != 'Diterima'){
        return [
            'status' => false,
            'message' => 'Peserta belum berstatus Diterima.'
        ];
    }

    if(!empty($p->is_migrated) && $p->is_migrated == 1){
        return [
            'status' => false,
            'message' => 'Peserta ini sudah pernah dimigrasikan.'
        ];
    }

    /*
     * Cegah data siswa dobel.
     */
    $cek = null;

    if(!empty($p->id) && $this->db->field_exists('ppdb_id', 'siswa')){
        $cek = $this->db
            ->where('ppdb_id', $p->id)
            ->get('siswa')
            ->row();
    }

    if(!$cek && !empty($p->nisn)){
        $cek = $this->db
            ->where('nisn', $p->nisn)
            ->get('siswa')
            ->row();
    }

    if(!$cek && !empty($p->nik)){
        $cek = $this->db
            ->where('nik', $p->nik)
            ->get('siswa')
            ->row();
    }

    if($cek){
        $this->db->where('id', $p->id);
        $this->db->update('ppdb', [
            'is_migrated' => 1,
            'migrated_at' => date('Y-m-d H:i:s')
        ]);

        return [
            'status' => false,
            'message' => 'Data siswa sudah ada. Status PPDB ditandai sudah migrasi.'
        ];
    }

    $copied_files = [
        'foto' => $this->copy_ppdb_file_to_siswa($p->foto ?? null, $p->nisn ?? '', 'foto'),
        'kk_file' => $this->copy_ppdb_file_to_siswa($p->kk_file ?? null, $p->nisn ?? '', 'kk'),
        'akta_file' => $this->copy_ppdb_file_to_siswa($p->akta_file ?? null, $p->nisn ?? '', 'akta'),
        'rapor_file' => $this->copy_ppdb_file_to_siswa($p->rapor_file ?? null, $p->nisn ?? '', 'rapor'),
        'skl_file' => $this->copy_ppdb_file_to_siswa($p->skl_file ?? null, $p->nisn ?? '', 'skl'),
        'nisn_file' => $this->copy_ppdb_file_to_siswa($p->nisn_file ?? null, $p->nisn ?? '', 'nisn'),
        'sk_kelas9_file' => $this->copy_ppdb_file_to_siswa($p->sk_kelas9_file ?? null, $p->nisn ?? '', 'sk_kelas9'),
        'ijazah_file' => $this->copy_ppdb_file_to_siswa($p->ijazah_file ?? null, $p->nisn ?? '', 'ijazah'),
        'sertifikat_file' => $this->copy_ppdb_file_to_siswa($p->sertifikat_file ?? null, $p->nisn ?? '', 'sertifikat')
    ];

    $now = date('Y-m-d H:i:s');

    $data_siswa = [
        'nis' => generate_nis_baru(),
        'nisn' => $p->nisn ?? null,
        'nama_lengkap' => $p->nama_lengkap ?? null,
        'nik' => $p->nik ?? null,
        'no_kk' => $p->no_kk ?? null,
        'tempat_lahir' => $p->tempat_lahir ?? null,
        'tanggal_lahir' => $p->tanggal_lahir ?? null,
        'jk' => $p->jk ?? null,
        'agama' => $p->agama ?? null,

        'alamat' => $p->alamat ?? null,
        'rt' => $p->rt ?? null,
        'rw' => $p->rw ?? null,
        'desa' => $p->desa ?? null,
        'kecamatan' => $p->kecamatan ?? null,
        'kabupaten' => $p->kabupaten ?? null,
        'provinsi' => $p->provinsi ?? null,
        'kode_pos' => $p->kode_pos ?? null,

        'nama_ayah' => $p->nama_ayah ?? null,
        'pekerjaan_ayah' => $p->pekerjaan_ayah ?? null,
        'nama_ibu' => $p->nama_ibu ?? null,
        'pekerjaan_ibu' => $p->pekerjaan_ibu ?? null,

        'no_hp' => $p->no_hp ?? null,
        'asal_sekolah' => $p->asal_sekolah ?? null,
        'status_siswa' => 'Aktif',
        'tahun_ajaran_masuk' => $this->ppdb_tahun_ajaran_aktif(),

        'foto' => $copied_files['foto'],
        'kk_file' => $copied_files['kk_file'],
        'akta_file' => $copied_files['akta_file'],
        'rapor_file' => $copied_files['rapor_file'],
        'skl_file' => $copied_files['skl_file'],
        'nisn_file' => $copied_files['nisn_file'],
        'ijazah_file' => $copied_files['ijazah_file'],
        'sertifikat_file' => $copied_files['sertifikat_file'],
        'sk_kelas9_file' => $copied_files['sk_kelas9_file'],

        'jenis_masuk' => 'PPDB',
        'tanggal_masuk' => date('Y-m-d'),

        'ppdb_id' => $p->id,
        'mutasi_id' => null,
        'sumber_data' => 'PPDB',
        'no_pendaftaran' => $p->no_pendaftaran ?? null,
        'jurusan_pilihan' => $p->jurusan_pilihan ?? null,
        'nama_ortu' => $p->nama_ortu ?? null,

        'accepted_at' => $p->accepted_at ?? null,
        'rejected_at' => $p->rejected_at ?? null,
        'migrated_at' => $now,

        'created_at' => $now,
        'updated_at' => $now
    ];

    $data_siswa = $this->filter_existing_fields('siswa', $data_siswa);

    $this->db->trans_start();

    $this->db->insert('siswa', $data_siswa);
    $siswa_id = $this->db->insert_id();

    $this->insert_siswa_detail_from_ppdb($siswa_id, $p);
    $this->insert_siswa_orangtua_from_ppdb($siswa_id, $p);
    $this->insert_siswa_dokumen_from_ppdb($siswa_id, $p, $copied_files);

    $this->db->where('id', $p->id);
    $this->db->update('ppdb', [
        'is_migrated' => 1,
        'migrated_at' => $now
    ]);

    $this->db->trans_complete();

    if($this->db->trans_status() === false){
        return [
            'status' => false,
            'message' => 'Migrasi gagal. Transaksi database dibatalkan.'
        ];
    }

    return [
        'status' => true,
        'message' => 'Peserta berhasil dimigrasikan ke data siswa.',
        'siswa_id' => $siswa_id
    ];
}

public function migrasi_ppdb($id){

    if(!$this->session->userdata('logged_in')){
        redirect('auth');
    }

    $result = $this->proses_migrasi_ppdb_ke_siswa($id);

    if($result['status']){
        $this->session->set_flashdata('success', $result['message']);
    } else {
        $this->session->set_flashdata('error', $result['message']);
    }

    redirect('admin_ppdb/migrasi_data');
}

public function migrasi_ppdb_all(){

    if(!$this->session->userdata('logged_in')){
        redirect('auth');
    }

    $peserta = $this->db
        ->where('status', 'Diterima')
        ->group_start()
            ->where('is_migrated', 0)
            ->or_where('is_migrated IS NULL', null, false)
        ->group_end()
        ->get('ppdb')
        ->result();

    $berhasil = 0;
    $gagal = 0;

    foreach($peserta as $p){
        $result = $this->proses_migrasi_ppdb_ke_siswa($p->id);

        if($result['status']){
            $berhasil++;
        } else {
            $gagal++;
        }
    }

    $this->session->set_flashdata(
        'success',
        'Migrasi selesai. Berhasil: '.$berhasil.', Gagal/Dilewati: '.$gagal.'.'
    );

    redirect('admin_ppdb/migrasi_data');
}

    public function update_verifikasi_berkas($id){
        if(!$this->session->userdata('logged_in')){
            redirect('auth');
        }

        $p = $this->db->where('id', $id)->get('ppdb')->row();
        if(!$p){
            show_404();
        }

        $status_berkas = $this->input->post('status_berkas');
        $catatan_berkas = $this->input->post('catatan_berkas');

        if(!is_array($status_berkas)){
            $status_berkas = [];
        }
        if(!is_array($catatan_berkas)){
            $catatan_berkas = [];
        }

        $file_fields = [
            'foto',
            'kk_file',
            'akta_file',
            'rapor_file',
            'skl_file',
            'nisn_file',
            'sk_kelas9_file',
            'sertifikat_file',
            'ijazah_file'
        ];

        $verifikasi_data = [];
        $ada_yang_salah = false;

        foreach($file_fields as $field){
            if(!empty($p->$field)){
                $status = isset($status_berkas[$field]) ? $status_berkas[$field] : 'Menunggu';
                $catatan = isset($catatan_berkas[$field]) ? trim($catatan_berkas[$field]) : '';

                $verifikasi_data[$field] = [
                    'status' => $status,
                    'catatan' => $catatan
                ];

                if($status == 'Perlu Perbaikan'){
                    $ada_yang_salah = true;
                }
            }
        }

        $update_data = [
            'verifikasi_berkas_json' => json_encode($verifikasi_data)
        ];

        if($ada_yang_salah){
            $update_data['status'] = 'Perlu Perbaikan';
        } else {
            if($p->status == 'Perlu Perbaikan'){
                $update_data['status'] = 'Menunggu Verifikasi Berkas';
            }
        }

        if($this->db->field_exists('updated_at', 'ppdb')){
            $update_data['updated_at'] = date('Y-m-d H:i:s');
        }

        $this->db->where('id', $id);
        $this->db->update('ppdb', $update_data);

        $this->session->set_flashdata('success', 'Status verifikasi berkas berhasil diperbarui.');
        redirect('admin_ppdb/detail/'.$id);
    }

    private function ocr_extract_text($file_path) {
        if (!file_exists($file_path)) {
            return '';
        }

        $ext = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));
        $allowed_images = ['jpg', 'jpeg', 'png', 'gif', 'tiff', 'bmp', 'webp'];
        if (!in_array($ext, $allowed_images)) {
            return '[ERROR_UNSUPPORTED_FORMAT]';
        }

        try {
            $tesseract = new \thiagoalessio\TesseractOCR\TesseractOCR($file_path);
            
            $win_default_path = 'C:\Program Files\Tesseract-OCR\tesseract.exe';
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                if (file_exists($win_default_path)) {
                    $tesseract->executable($win_default_path);
                }
            }
            
            $tesseract->lang('ind', 'eng');
            
            $text = $tesseract->run();
            return $text;
        } catch (\Exception $e) {
            log_message('error', 'Tesseract OCR Error: ' . $e->getMessage());
            return '';
        }
    }

    public function ajax_check_ocr_match() {
        @ob_clean();
        header('Content-Type: application/json');
        if (!$this->session->userdata('logged_in') || $this->session->userdata('role') != 'admin') {
            echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
            return;
        }

        $id = (int)$this->input->post('id');
        $field = $this->input->post('field');

        $siswa = $this->db->where('id', $id)->get('ppdb')->row();

        if (!$siswa) {
            echo json_encode(['status' => 'error', 'message' => 'Pendaftar PPDB tidak ditemukan']);
            return;
        }

        $filename = $siswa->$field;
        if (empty($filename)) {
            echo json_encode(['status' => 'error', 'message' => 'Berkas belum diunggah']);
            return;
        }

        $file_path = './uploads/temp/ppdb/' . $filename;
        if (!file_exists($file_path)) {
            echo json_encode(['status' => 'error', 'message' => 'Berkas fisik tidak ditemukan di server']);
            return;
        }

        $extracted_text = $this->ocr_extract_text($file_path);

        if ($extracted_text === '[ERROR_UNSUPPORTED_FORMAT]') {
            echo json_encode([
                'status' => 'error', 
                'message' => 'OCR Lokal Tesseract hanya mendukung file gambar (JPG, JPEG, PNG). Untuk file PDF, silakan lakukan pemeriksaan secara manual.'
            ]);
            return;
        }

        if (empty($extracted_text)) {
            echo json_encode([
                'status' => 'error', 
                'message' => 'Gagal membaca teks dari berkas. Pastikan software Tesseract OCR sudah terinstal di server dan format file adalah gambar yang jelas.'
            ]);
            return;
        }

        $text_lower = strtolower($extracted_text);

        // 1. Nama Lengkap
        $nama_db = strtolower(trim($siswa->nama_lengkap));
        $nama_words = explode(' ', $nama_db);
        $matched_words = 0;
        $total_words = count($nama_words);
        
        foreach ($nama_words as $word) {
            if (strlen($word) > 2 && strpos($text_lower, $word) !== false) {
                $matched_words++;
            }
        }
        $nama_match_pct = $total_words > 0 ? round(($matched_words / $total_words) * 100) : 0;
        $nama_matched = ($nama_match_pct >= 50);

        // 2. NIK
        $nik_matched = false;
        if (!empty($siswa->nik)) {
            $nik_clean = preg_replace('/[^0-9]/', '', $siswa->nik);
            if (strpos($text_lower, $nik_clean) !== false) {
                $nik_matched = true;
            }
        }

        // 3. NISN
        $nisn_matched = false;
        if (!empty($siswa->nisn)) {
            $nisn_clean = preg_replace('/[^0-9]/', '', $siswa->nisn);
            if (strpos($text_lower, $nisn_clean) !== false) {
                $nisn_matched = true;
            }
        }

        // 4. Tempat Lahir
        $tempat_matched = false;
        if (!empty($siswa->tempat_lahir)) {
            $tempat_db = strtolower(trim($siswa->tempat_lahir));
            if (strpos($text_lower, $tempat_db) !== false) {
                $tempat_matched = true;
            }
        }

        // 5. Tanggal Lahir
        $tgl_matched = false;
        if (!empty($siswa->tanggal_lahir) && $siswa->tanggal_lahir != '0000-00-00') {
            $tgl_parts = explode('-', $siswa->tanggal_lahir);
            if (count($tgl_parts) == 3) {
                $tahun = $tgl_parts[0];
                $hari = (int)$tgl_parts[2];
                if (strpos($text_lower, $tahun) !== false && strpos($text_lower, (string)$hari) !== false) {
                    $tgl_matched = true;
                }
            }
        }

        $results = [
            'nama' => [
                'label' => 'Nama Lengkap',
                'db_value' => $siswa->nama_lengkap,
                'matched' => $nama_matched,
                'detail' => $nama_matched ? 'Cocok (Ditemukan di dokumen)' : 'Tidak ditemukan kecocokan nama'
            ],
            'nik' => [
                'label' => 'NIK',
                'db_value' => $siswa->nik ? $siswa->nik : 'Belum diisi',
                'matched' => $nik_matched,
                'detail' => $nik_matched ? 'Cocok' : 'Tidak ditemukan'
            ],
            'nisn' => [
                'label' => 'NISN',
                'db_value' => $siswa->nisn ? $siswa->nisn : 'Belum diisi',
                'matched' => $nisn_matched,
                'detail' => $nisn_matched ? 'Cocok' : 'Tidak ditemukan'
            ],
            'tempat_lahir' => [
                'label' => 'Tempat Lahir',
                'db_value' => $siswa->tempat_lahir ? $siswa->tempat_lahir : 'Belum diisi',
                'matched' => $tempat_matched,
                'detail' => $tempat_matched ? 'Cocok' : 'Tidak ditemukan'
            ],
            'tanggal_lahir' => [
                'label' => 'Tanggal Lahir',
                'db_value' => $siswa->tanggal_lahir ? date('d-m-Y', strtotime($siswa->tanggal_lahir)) : 'Belum diisi',
                'matched' => $tgl_matched,
                'detail' => $tgl_matched ? 'Cocok' : 'Tidak ditemukan'
            ],
        ];

        @ob_clean();
        echo json_encode([
            'status' => 'success',
            'extracted_text' => $extracted_text,
            'results' => $results
        ]);
    }

    public function ajax_mass_check_ocr() {
        @ob_clean();
        header('Content-Type: application/json');
        if (!$this->session->userdata('logged_in') || $this->session->userdata('role') != 'admin') {
            echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
            return;
        }

        $id = (int)$this->input->post('id');
        $siswa = $this->db->where('id', $id)->get('ppdb')->row();

        if (!$siswa) {
            echo json_encode(['status' => 'error', 'message' => 'Pendaftar PPDB tidak ditemukan']);
            return;
        }

        $docs_to_check = ['ijazah', 'kk', 'akta_lahir'];
        $final_results = [];

        foreach ($docs_to_check as $field) {
            $filename = $siswa->$field;
            if (empty($filename)) {
                $final_results[$field] = ['status' => 'kosong'];
                continue;
            }

            $file_path = './uploads/temp/ppdb/' . $filename;
            if (!file_exists($file_path)) {
                $final_results[$field] = ['status' => 'tidak_ditemukan'];
                continue;
            }

            $extracted_text = $this->ocr_extract_text($file_path);

            if ($extracted_text === '[ERROR_UNSUPPORTED_FORMAT]' || empty($extracted_text)) {
                $final_results[$field] = ['status' => 'gagal_baca'];
                continue;
            }

            $text_lower = strtolower($extracted_text);

            // Nama Lengkap
            $nama_db = strtolower(trim($siswa->nama_lengkap));
            $nama_words = explode(' ', $nama_db);
            $matched_words = 0;
            $total_words = count($nama_words);
            foreach ($nama_words as $word) {
                if (strlen($word) > 2 && strpos($text_lower, $word) !== false) {
                    $matched_words++;
                }
            }
            $nama_match_pct = $total_words > 0 ? round(($matched_words / $total_words) * 100) : 0;
            $nama_matched = ($nama_match_pct >= 50);

            // NIK
            $nik_matched = false;
            if (!empty($siswa->nik)) {
                $nik_clean = preg_replace('/[^0-9]/', '', $siswa->nik);
                if (strpos($text_lower, $nik_clean) !== false) {
                    $nik_matched = true;
                }
            }

            // NISN
            $nisn_matched = false;
            if (!empty($siswa->nisn)) {
                $nisn_clean = preg_replace('/[^0-9]/', '', $siswa->nisn);
                if (strpos($text_lower, $nisn_clean) !== false) {
                    $nisn_matched = true;
                }
            }

            $final_results[$field] = [
                'status' => 'berhasil',
                'nama' => $nama_matched,
                'nik' => $nik_matched,
                'nisn' => $nisn_matched
            ];
        }

        $this->db->where('id', $id);
        $this->db->update('ppdb', [
            'ocr_scanned_at' => date('Y-m-d H:i:s'),
            'ocr_results_json' => json_encode($final_results)
        ]);

        @ob_clean();
        echo json_encode([
            'status' => 'success',
            'results' => $final_results
        ]);
    }

    /**
     * SINKRONISASI PENDAFTAR DARI CLOUD DOMAINESIA KE SERVER LOKAL LABSYS
     */
    public function sync_from_cloud(){
        header('Content-Type: application/json; charset=utf-8');

        $cloud_url = $this->input->post('cloud_url', TRUE);
        $api_key = $this->input->post('api_key', TRUE);

        if(empty($cloud_url)){
            // Default URL endpoint cloud
            $cloud_url = 'https://man3banjar.sch.id/api/ppdb/sync';
        }
        if(empty($api_key)){
            $api_key = 'LABSYS_SYNC_SECRET_KEY_MAN3BANJAR_2026';
        }

        // 1. Request data ke Cloud
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $cloud_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'X-API-KEY: ' . $api_key,
            'Accept: application/json'
        ]);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $err = curl_error($ch);
        curl_close($ch);

        if($err || $http_code !== 200){
            echo json_encode([
                'status' => 'error',
                'message' => 'Gagal terhubung ke Cloud (' . ($err ? $err : 'HTTP Status ' . $http_code) . '). Pastikan URL dan API Key benar.'
            ]);
            return;
        }

        $res_data = json_decode($response, true);
        if(!$res_data || !isset($res_data['data'])){
            echo json_encode([
                'status' => 'error',
                'message' => 'Format respons dari cloud tidak valid.'
            ]);
            return;
        }

        $pendaftar_list = $res_data['data'];
        $inserted_count = 0;
        $downloaded_files = 0;
        $synced_numbers = [];

        $upload_dir = FCPATH . 'uploads/ppdb/';
        if(!is_dir($upload_dir)){
            @mkdir($upload_dir, 0777, true);
        }

        foreach($pendaftar_list as $p){
            $no_pendaftaran = $p['no_pendaftaran'];

            // Cek apakah sudah ada di database lokal
            $cek = $this->db->where('no_pendaftaran', $no_pendaftaran)->get('ppdb')->row();

            $kk_file = NULL;
            $akta_file = NULL;
            $ijazah_file = NULL;
            $foto_file = NULL;

            // Unduh file berkas jika ada
            if(!empty($p['berkas_list'])){
                foreach($p['berkas_list'] as $b){
                    $fname = $b['nama_file'];
                    $furl = $b['full_download_url'];
                    $target_file = $upload_dir . $fname;

                    // Download file jika belum ada di lokal
                    if(!file_exists($target_file) && !empty($furl)){
                        $file_content = @file_get_contents($furl);
                        if($file_content){
                            @file_put_contents($target_file, $file_content);
                            $downloaded_files++;
                        }
                    }

                    if($b['jenis_berkas'] == 'kartu_keluarga') $kk_file = $fname;
                    elseif($b['jenis_berkas'] == 'akta_kelahiran') $akta_file = $fname;
                    elseif($b['jenis_berkas'] == 'ijazah_skhu') $ijazah_file = $fname;
                    elseif($b['jenis_berkas'] == 'pas_foto') $foto_file = $fname;
                }
            }

            $local_data = [
                'no_pendaftaran' => $no_pendaftaran,
                'nama_lengkap' => $p['nama_lengkap'],
                'nisn' => $p['nisn'],
                'jk' => $p['jenis_kelamin'],
                'tempat_lahir' => $p['tempat_lahir'],
                'tanggal_lahir' => $p['tanggal_lahir'],
                'nik' => $p['nik'],
                'agama' => $p['agama'],
                'asal_sekolah' => $p['sekolah_asal'],
                'alamat' => $p['alamat_siswa'],
                'no_hp' => $p['no_hp_siswa'] ? $p['no_hp_siswa'] : $p['no_hp_ortu'],
                'nama_ayah' => $p['nama_ayah'],
                'pekerjaan_ayah' => $p['pekerjaan_ayah'],
                'nama_ibu' => $p['nama_ibu'],
                'pekerjaan_ibu' => $p['pekerjaan_ibu'],
                'status' => 'mendaftar',
                'created_at' => $p['created_at']
            ];

            if($kk_file) $local_data['kk_file'] = $kk_file;
            if($akta_file) $local_data['akta_file'] = $akta_file;
            if($ijazah_file) $local_data['ijazah_file'] = $ijazah_file;
            if($foto_file) $local_data['foto'] = $foto_file;

            if(!$cek){
                $this->db->insert('ppdb', $local_data);
                $inserted_count++;
            } else {
                $this->db->where('no_pendaftaran', $no_pendaftaran)->update('ppdb', $local_data);
            }

            $synced_numbers[] = $no_pendaftaran;
        }

        // 2. Kirim konfirmasi ke Cloud bahwa data berhasil ditarik
        if(!empty($synced_numbers)){
            $confirm_url = str_replace('/sync', '/confirm_sync', $cloud_url);
            $ch_conf = curl_init();
            curl_setopt($ch_conf, CURLOPT_URL, $confirm_url);
            curl_setopt($ch_conf, CURLOPT_POST, true);
            curl_setopt($ch_conf, CURLOPT_POSTFIELDS, json_encode(['no_pendaftaran_list' => $synced_numbers]));
            curl_setopt($ch_conf, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_conf, CURLOPT_HTTPHEADER, [
                'X-API-KEY: ' . $api_key,
                'Content-Type: application/json'
            ]);
            curl_setopt($ch_conf, CURLOPT_TIMEOUT, 15);
            curl_setopt($ch_conf, CURLOPT_SSL_VERIFYPEER, false);
            curl_exec($ch_conf);
            curl_close($ch_conf);
        }

        echo json_encode([
            'status' => 'success',
            'message' => "Sinkronisasi berhasil! {$inserted_count} pendaftar baru disimpan dan {$downloaded_files} berkas digital berhasil diunduh ke server lokal.",
            'total_synced' => count($synced_numbers),
            'inserted_count' => $inserted_count,
            'downloaded_files' => $downloaded_files
        ]);
    }
}