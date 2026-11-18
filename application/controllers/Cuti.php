<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cuti extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper(['url', 'form']);
        date_default_timezone_set('Asia/Makassar');
    }

    /**
     * Hitung masa kerja dari NIP TMT atau profil kepegawaian
     */
    private function hitung_masa_kerja($ptk) {
        $masa_kerja = '';
        if (!empty($ptk->nip)) {
            $nip_clean = preg_replace('/[^0-9]/', '', $ptk->nip);
            if (strlen($nip_clean) >= 14) {
                $tmt_year = (int)substr($nip_clean, 8, 4);
                $tmt_month = (int)substr($nip_clean, 12, 2);
                
                $cur_year = (int)date('Y');
                $cur_month = (int)date('m');
                
                $diff_years = $cur_year - $tmt_year;
                $diff_months = $cur_month - $tmt_month;
                if ($diff_months < 0) {
                    $diff_years--;
                    $diff_months += 12;
                }

                if (isset($ptk->status_kepegawaian) && stripos($ptk->status_kepegawaian, 'PPPK') !== false) {
                    $masa_kerja = max(0, $diff_years) . ' Tahun';
                } else {
                    $masa_kerja = max(0, $diff_years) . ' Tahun ' . max(0, $diff_months) . ' Bulan';
                }
            }
        }

        if (empty($masa_kerja)) {
            $thn = !empty($ptk->masa_kerja_tahun) ? $ptk->masa_kerja_tahun : 0;
            $bln = !empty($ptk->masa_kerja_bulan) ? $ptk->masa_kerja_bulan : 0;
            $masa_kerja = $thn . ' Tahun ' . $bln . ' Bulan';
        }

        return $masa_kerja;
    }

    /**
     * Hitung saldo cuti tahunan (N-2, N-1, N) & riwayat cuti lainnya
     */
    private function hitung_saldo_cuti($ptk_id) {
        $tahun_ini   = (int)date('Y');
        $tahun_lalu  = $tahun_ini - 1;
        $tahun_lalu2 = $tahun_ini - 2;

        // Ambil kuota dari tu_cuti_kuota jika ada custom setting, default 12 hari
        $kuota_db = $this->db
            ->where('ptk_id', $ptk_id)
            ->where_in('tahun', [$tahun_ini, $tahun_lalu, $tahun_lalu2])
            ->get('tu_cuti_kuota')
            ->result();
        
        $kuota_map = [];
        foreach ($kuota_db as $k) {
            $kuota_map[$k->tahun] = (int)$k->jatah;
        }

        $jatah_n   = $kuota_map[$tahun_ini] ?? 12;
        $jatah_n1  = $kuota_map[$tahun_lalu] ?? 0;
        $jatah_n2  = $kuota_map[$tahun_lalu2] ?? 0;

        // Hitung cuti yang sudah diambil di tu_cuti_asn yang statusnya Aktif atau Disetujui
        $cuti_taken = $this->db
            ->where('ptk_id', $ptk_id)
            ->where_in('status', ['Aktif', 'Disetujui Kepala'])
            ->get('tu_cuti_asn')
            ->result();

        $taken_n   = 0;
        $taken_n1  = 0;
        $taken_n2  = 0;
        $taken_besar   = 0;
        $taken_sakit   = 0;
        $taken_lahir   = 0;
        $taken_penting = 0;
        $taken_cltn    = 0;

        foreach ($cuti_taken as $c) {
            $thn_cuti = (int)date('Y', strtotime($c->tanggal_mulai ?: $c->tanggal_surat));
            $lama = (int)$c->lama_cuti_jumlah;

            if (in_array($c->jenis_cuti, ['Cuti Tahunan', 'Cuti Tahunan N-1', 'Cuti Tahunan N-2', 'Cuti Tahun N-1', 'Cuti Tahun N-2'])) {
                if ($c->jenis_cuti == 'Cuti Tahunan N-1' || $c->jenis_cuti == 'Cuti Tahun N-1') {
                    $taken_n1 += $lama;
                } elseif ($c->jenis_cuti == 'Cuti Tahunan N-2' || $c->jenis_cuti == 'Cuti Tahun N-2') {
                    $taken_n2 += $lama;
                } else {
                    if ($thn_cuti == $tahun_ini) {
                        $taken_n += $lama;
                    } elseif ($thn_cuti == $tahun_lalu) {
                        $taken_n1 += $lama;
                    } elseif ($thn_cuti == $tahun_lalu2) {
                        $taken_n2 += $lama;
                    }
                }
            } elseif ($c->jenis_cuti == 'Cuti Besar') {
                $taken_besar += $lama;
            } elseif ($c->jenis_cuti == 'Cuti Sakit') {
                $taken_sakit += $lama;
            } elseif ($c->jenis_cuti == 'Cuti Melahirkan') {
                $taken_lahir += $lama;
            } elseif (in_array($c->jenis_cuti, ['Cuti Karena Alasan Penting', 'Cuti Alasan Penting'])) {
                $taken_penting += $lama;
            } elseif (in_array($c->jenis_cuti, ['Cuti di Luar Tanggungan Negara', 'Cuti diluar Tanggungan Negara'])) {
                $taken_cltn += $lama;
            }
        }

        // Rumus BKN (Perka BKN No 24/2017 & Perka BKN No 7/2021):
        // Sisa N-2 = jatah N-2 - terpakai N-2
        // Sisa N-1 = jatah N-1 - terpakai N-1 (maksimal 6 hari akumulasi)
        // Sisa N = jatah N - terpakai N
        $sisa_n2 = max(0, $jatah_n2 - $taken_n2);
        $sisa_n1 = max(0, $jatah_n1 - $taken_n1);
        $sisa_n  = max(0, $jatah_n - $taken_n);

        $total_hak_cuti = $sisa_n2 + $sisa_n1 + $sisa_n;

        return [
            'tahun_n'   => $tahun_ini,
            'tahun_n1'  => $tahun_lalu,
            'tahun_n2'  => $tahun_lalu2,
            'jatah_n'   => $jatah_n,
            'jatah_n1'  => $jatah_n1,
            'jatah_n2'  => $jatah_n2,
            'taken_n'   => $taken_n,
            'taken_n1'  => $taken_n1,
            'taken_n2'  => $taken_n2,
            'sisa_n'    => $sisa_n,
            'sisa_n1'   => $sisa_n1,
            'sisa_n2'   => $sisa_n2,
            'total_hak' => $total_hak_cuti,
            'taken_besar'   => $taken_besar,
            'taken_sakit'   => $taken_sakit,
            'taken_lahir'   => $taken_lahir,
            'taken_penting' => $taken_penting,
            'taken_cltn'    => $taken_cltn
        ];
    }

    /**
     * Halaman Utama: Layanan Mandiri Pengajuan Cuti ASN
     */
    public function index() {
        // Data dasar profil website untuk header/footer resmi madrasah
        $data['page_title']    = 'Layanan Mandiri Cuti ASN';
        $data['nama_madrasah'] = 'MAN 3 Banjar';
        $data['profil_website'] = null;
        if ($this->db->table_exists('website_profil')) {
            $data['profil_website'] = $this->db->limit(1)->get('website_profil')->row();
        }

        // Ambil daftar seluruh pegawai ASN (PNS & PPPK) untuk dropdown autocomplete
        $data['pegawai_list'] = $this->db
            ->select('id, nama_lengkap, nip, status_kepegawaian, jabatan')
            ->where('(nip IS NOT NULL AND LENGTH(nip) > 10) OR status_kepegawaian IN ("PNS", "PPPK")', NULL, FALSE)
            ->order_by('nama_lengkap', 'ASC')
            ->get('ptk')
            ->result();

        // Ambil riwayat seluruh permohonan cuti untuk langsung ditampilkan di monitoring tanpa harus input NIP
        $data['riwayat_cuti'] = $this->db
            ->select('tu_cuti_asn.*, ptk.nama_lengkap, ptk.nip, ptk.jabatan as ptk_jabatan, ptk.foto as ptk_foto')
            ->from('tu_cuti_asn')
            ->join('ptk', 'ptk.id = tu_cuti_asn.ptk_id', 'left')
            ->order_by('tu_cuti_asn.tanggal_mulai', 'DESC')
            ->order_by('tu_cuti_asn.id', 'DESC')
            ->limit(100)
            ->get()
            ->result();

        // Hitung statistik ringkasan untuk 4 kartu KPI
        $today        = date('Y-m-d');
        $stat_total   = count($data['riwayat_cuti']);
        $stat_aktif   = 0;
        $stat_draft   = 0;
        $stat_sedang  = 0;
        foreach ($data['riwayat_cuti'] as $rc) {
            if ($rc->status == 'Aktif') {
                $stat_aktif++;
                if (!empty($rc->tanggal_mulai) && !empty($rc->tanggal_selesai) && $today >= $rc->tanggal_mulai && $today <= $rc->tanggal_selesai) {
                    $stat_sedang++;
                }
            } elseif (in_array($rc->status, ['Draft', 'Disetujui Kepala'])) {
                $stat_draft++;
            }
        }
        $data['stat_total']   = $stat_total;
        $data['stat_aktif']   = $stat_aktif;
        $data['stat_draft']   = $stat_draft;
        $data['stat_sedang']  = $stat_sedang;
        $data['stat_pegawai'] = count($data['pegawai_list']);

        // Cek status sesi login (apakah admin atau user login)
        $is_logged_in = $this->session->userdata('logged_in');
        $data['is_logged_in'] = $is_logged_in;
        $data['is_admin']     = ($is_logged_in && in_array($this->session->userdata('role'), ['admin', 'tata_usaha']));
        $data['session_ptk']  = $this->session->userdata('ptk_id');

        // Ambil atasan langsung (Kepala Madrasah)
        $kamad = $this->db
            ->like('jabatan', 'Kepala Madrasah')
            ->or_where('id', 4)
            ->get('ptk')
            ->row();
        
        $data['kamad_nama'] = $kamad ? $kamad->nama_lengkap : 'Nor Ifansyah, S.Pd., M.Sc';
        $data['kamad_nip']  = $kamad ? $kamad->nip : '197103152001121001';

        // Pejabat berwenang Kemenag Banjar
        $data['pejabat_nama']    = 'Dr. H. Ahmad Kamal, S.H.I., M.Ag.';
        $data['pejabat_nip']     = '198107032005011007';
        $data['pejabat_jabatan'] = 'Kepala Kantor Kementerian Agama Kab. Banjar';

        $this->load->view('cuti/index', $data);
    }

    /**
     * AJAX: Ambil detail data pegawai & saldo cuti berdasarkan NIP
     */
    public function ajax_get_ptk() {
        header('Content-Type: application/json');
        $nip = trim($this->input->get_post('nip'));

        if (empty($nip)) {
            echo json_encode(['status' => 'error', 'message' => 'NIP tidak boleh kosong']);
            return;
        }

        $nip_clean = preg_replace('/[^0-9]/', '', $nip);

        $ptk = $this->db
            ->where('nip', $nip)
            ->or_where('REPLACE(nip, " ", "") =', $nip_clean)
            ->or_where('id', (int)$nip)
            ->get('ptk')
            ->row();

        if (!$ptk) {
            echo json_encode(['status' => 'error', 'message' => 'Pegawai dengan NIP tersebut tidak ditemukan di database.']);
            return;
        }

        $masa_kerja = $this->hitung_masa_kerja($ptk);
        $saldo      = $this->hitung_saldo_cuti($ptk->id);

        // Ambil riwayat pengajuan cuti terakhir
        $riwayat = $this->db
            ->where('ptk_id', $ptk->id)
            ->order_by('id', 'DESC')
            ->limit(5)
            ->get('tu_cuti_asn')
            ->result();

        echo json_encode([
            'status' => 'success',
            'data'   => [
                'id'                 => $ptk->id,
                'nama_lengkap'       => $ptk->nama_lengkap,
                'nip'                => $ptk->nip,
                'jabatan'            => !empty($ptk->jabatan) ? $ptk->jabatan : 'Guru / Pegawai',
                'pangkat_golongan'   => !empty($ptk->pangkat_golongan) ? $ptk->pangkat_golongan : '-',
                'status_kepegawaian' => $ptk->status_kepegawaian ?: 'PNS',
                'masa_kerja'         => $masa_kerja,
                'alamat'             => $ptk->alamat ?: '',
                'no_hp'              => $ptk->no_hp ?: '',
                'saldo'              => $saldo,
                'riwayat'            => $riwayat
            ]
        ]);
    }

    /**
     * AJAX: Hitung jumlah hari kerja antara 2 tanggal (mengabaikan akhir pekan dan libur nasional)
     */
    public function ajax_hitung_hari() {
        header('Content-Type: application/json');
        $start_date = $this->input->get_post('tanggal_mulai');
        $end_date   = $this->input->get_post('tanggal_selesai');
        $jenis_cuti = $this->input->get_post('jenis_cuti');

        if (empty($start_date) || empty($end_date)) {
            echo json_encode(['status' => 'error', 'message' => 'Tanggal mulai dan selesai wajib diisi']);
            return;
        }

        $start_ts = strtotime($start_date);
        $end_ts   = strtotime($end_date);

        if ($end_ts < $start_ts) {
            echo json_encode(['status' => 'error', 'message' => 'Tanggal selesai tidak boleh sebelum tanggal mulai']);
            return;
        }

        // Ambil daftar hari libur nasional dari presensi_hari_libur
        $holidays = $this->db
            ->where('tanggal >=', $start_date)
            ->where('tanggal <=', $end_date)
            ->get('presensi_hari_libur')
            ->result();

        $holiday_map = [];
        foreach ($holidays as $h) {
            $holiday_map[$h->tanggal] = $h->keterangan;
        }

        $work_days = 0;
        $calendar_days = 0;
        $cur = $start_ts;
        $holiday_details = [];

        while ($cur <= $end_ts) {
            $cur_d = date('Y-m-d', $cur);
            $day_of_week = (int)date('N', $cur); // 1 = Senin ... 7 = Minggu
            $calendar_days++;

            $is_weekend = ($day_of_week == 6 || $day_of_week == 7); // Sabtu & Minggu libur kerja ASN Kemenag (5 Hari Kerja)
            $is_holiday = isset($holiday_map[$cur_d]);

            if ($is_holiday) {
                $holiday_details[] = $cur_d . ': ' . $holiday_map[$cur_d];
            }

            // Sesuai BKN, Cuti Tahunan dihitung HARI KERJA
            // Cuti Besar & Cuti Sakit tertentu dihitung HARI KALENDER
            if (!$is_weekend && !$is_holiday) {
                $work_days++;
            }

            $cur = strtotime('+1 day', $cur);
        }

        $jumlah = ($jenis_cuti == 'Cuti Tahunan') ? $work_days : $calendar_days;
        $satuan = 'Hari';

        // Cek jika Cuti Besar / Melahirkan / CLTN biasanya dalam Bulan
        if (in_array($jenis_cuti, ['Cuti Melahirkan', 'Cuti Besar', 'Cuti diluar Tanggungan Negara']) && $calendar_days >= 28) {
            $bulan = round($calendar_days / 30, 1);
            if ($bulan >= 1) {
                $satuan_rekomendasi = 'Bulan';
            }
        }

        echo json_encode([
            'status'           => 'success',
            'work_days'        => $work_days,
            'calendar_days'    => $calendar_days,
            'hari_rekomendasi' => $jumlah,
            'holidays'         => $holiday_details
        ]);
    }

    /**
     * Submit Formulir Cuti Pegawai (Public Form)
     */
    public function submit() {
        $ptk_id = (int)$this->input->post('ptk_id');
        $ptk    = $this->db->where('id', $ptk_id)->get('ptk')->row();

        if (!$ptk) {
            $this->session->set_flashdata('error', 'Data Pegawai tidak valid. Silakan pilih kembali NIP/Nama Anda.');
            redirect('cuti');
            return;
        }

        $jenis_cuti      = trim($this->input->post('jenis_cuti'));
        $alasan_cuti     = trim($this->input->post('alasan_cuti'));
        $lama_cuti       = (int)$this->input->post('lama_cuti_jumlah');
        $lama_satuan     = trim($this->input->post('lama_cuti_satuan')) ?: 'Hari';
        $tanggal_mulai   = $this->input->post('tanggal_mulai');
        $tanggal_selesai = $this->input->post('tanggal_selesai');
        $alamat_cuti     = trim($this->input->post('alamat_cuti'));
        $telepon_cuti    = trim($this->input->post('telepon_cuti'));

        if (empty($jenis_cuti) || empty($alasan_cuti) || empty($tanggal_mulai) || empty($tanggal_selesai)) {
            $this->session->set_flashdata('error', 'Semua kolom wajib diisi dengan benar.');
            redirect('cuti');
            return;
        }

        // Upload lampiran pendukung jika ada
        $file_lampiran = null;
        if (!empty($_FILES['file_lampiran']['name'])) {
            $upload_path = FCPATH . 'uploads/tu_cuti/';
            if (!is_dir($upload_path)) {
                mkdir($upload_path, 0777, true);
            }

            $config['upload_path']   = $upload_path;
            $config['allowed_types'] = 'pdf|doc|docx|jpg|jpeg|png';
            $config['max_size']      = 5120; // 5MB
            $config['encrypt_name']  = true;

            $this->load->library('upload', $config);
            if ($this->upload->do_upload('file_lampiran')) {
                $up_data = $this->upload->data();
                $file_lampiran = 'uploads/tu_cuti/' . $up_data['file_name'];
            } else {
                $this->session->set_flashdata('error', 'Gagal upload dokumen: ' . $this->upload->display_errors('', ''));
                redirect('cuti');
                return;
            }
        }

        $masa_kerja = $this->hitung_masa_kerja($ptk);
        $saldo      = $this->hitung_saldo_cuti($ptk->id);

        // Pejabat TTD
        $kamad = $this->db->like('jabatan', 'Kepala Madrasah')->or_where('id', 4)->get('ptk')->row();
        $kamad_nama = $kamad ? $kamad->nama_lengkap : 'Nor Ifansyah, S.Pd., M.Sc';
        $kamad_nip  = $kamad ? $kamad->nip : '197103152001121001';

        $pejabat_nama    = 'Dr. H. Ahmad Kamal, S.H.I., M.Ag.';
        $pejabat_nip     = '198107032005011007';
        $pejabat_jabatan = 'Kepala Kantor Kementerian Agama Kab. Banjar';

        $insert_data = [
            'ptk_id'           => $ptk->id,
            'tanggal_surat'    => date('Y-m-d'),
            'yth_kepada'       => 'Kepala Kantor Kemenag Kab. Banjar',
            'di_tempat'        => 'Martapura',
            'masa_kerja'       => $masa_kerja,
            'jenis_cuti'       => $jenis_cuti,
            'alasan_cuti'      => $alasan_cuti,
            'file_lampiran'    => $file_lampiran,
            'lama_cuti_jumlah' => $lama_cuti,
            'lama_cuti_satuan' => $lama_satuan,
            'tanggal_mulai'    => $tanggal_mulai,
            'tanggal_selesai'  => $tanggal_selesai,

            // Catatan Saldo Cuti Sesuai Ketentuan BKN
            'catatan_cuti_tahunan_n2_sisa' => (string)$saldo['sisa_n2'],
            'catatan_cuti_tahunan_n2_ket'  => '',
            'catatan_cuti_tahunan_n1_sisa' => (string)$saldo['sisa_n1'],
            'catatan_cuti_tahunan_n1_ket'  => '',
            'catatan_cuti_tahunan_n_sisa'  => (string)$saldo['sisa_n'],
            'catatan_cuti_tahunan_n_ket'   => '',

            'catatan_cuti_besar'             => $saldo['taken_besar'] > 0 ? $saldo['taken_besar'] . ' Hari' : '-',
            'catatan_cuti_sakit'             => $saldo['taken_sakit'] > 0 ? $saldo['taken_sakit'] . ' Hari' : '-',
            'catatan_cuti_melahirkan'        => $saldo['taken_lahir'] > 0 ? $saldo['taken_lahir'] . ' Hari' : '-',
            'catatan_cuti_alasan_penting'    => $saldo['taken_penting'] > 0 ? $saldo['taken_penting'] . ' Hari' : '-',
            'catatan_cuti_diluar_tanggungan' => $saldo['taken_cltn'] > 0 ? $saldo['taken_cltn'] . ' Hari' : '-',

            'alamat_cuti'  => $alamat_cuti,
            'telepon_cuti' => $telepon_cuti,

            'atasan_langsung_nama'        => $kamad_nama,
            'atasan_langsung_nip'         => $kamad_nip,
            'atasan_langsung_keputusan'   => '',

            'pejabat_berwenang_nama'      => $pejabat_nama,
            'pejabat_berwenang_nip'       => $pejabat_nip,
            'pejabat_berwenang_jabatan'   => $pejabat_jabatan,
            'pejabat_berwenang_keputusan' => '',

            'status'            => 'Draft', // Menunggu persetujuan Kepala Madrasah
            'created_by_ptk_id' => $ptk->id,
            'created_at'        => date('Y-m-d H:i:s')
        ];

        $this->db->insert('tu_cuti_asn', $insert_data);
        $insert_id = $this->db->insert_id();

        redirect('cuti/sukses/' . $insert_id);
    }

    /**
     * Halaman Sukses Pengajuan Cuti dengan Tombol Cetak Langsung
     */
    public function sukses($id) {
        $data['cuti'] = $this->db
            ->select('tu_cuti_asn.*, ptk.nama_lengkap, ptk.nip, ptk.jabatan, ptk.status_kepegawaian')
            ->from('tu_cuti_asn')
            ->join('ptk', 'ptk.id = tu_cuti_asn.ptk_id', 'left')
            ->where('tu_cuti_asn.id', (int)$id)
            ->get()
            ->row();

        if (!$data['cuti']) {
            show_404();
            return;
        }

        $this->load->view('cuti/sukses', $data);
    }

    /**
     * Cetak Formulir Cuti Resmi BKN (A4 Siap Cetak / Unduh PDF)
     * Proteksi: Hanya dapat diakses oleh Admin Tata Usaha atau Pegawai Pemohon yang Login
     */
    public function cetak($id) {
        $data['cuti'] = $this->db
            ->select('tu_cuti_asn.*, ptk.nama_lengkap, ptk.nip, ptk.jabatan as ptk_jabatan')
            ->from('tu_cuti_asn')
            ->join('ptk', 'ptk.id = tu_cuti_asn.ptk_id', 'left')
            ->where('tu_cuti_asn.id', (int)$id)
            ->get()
            ->row();

        if (!$data['cuti']) {
            show_404();
            return;
        }

        // Cek otorisasi cetak:
        // Sesuai ketentuan: Cetak resmi HANYA untuk Admin atau akun masing-masing pegawai yang login
        $is_logged_in = $this->session->userdata('logged_in');
        $user_role    = $this->session->userdata('role');
        $session_ptk  = $this->session->userdata('ptk_id');

        $is_admin = ($is_logged_in && in_array($user_role, ['admin', 'tata_usaha']));
        $is_owner = ($is_logged_in && !empty($session_ptk) && (int)$session_ptk === (int)$data['cuti']->ptk_id);

        if (!$is_admin && !$is_owner) {
            $this->load->view('cuti/cetak_terkunci', $data);
            return;
        }

        // Tampilkan view cetak resmi
        $this->load->view('admin_tata_usaha/cetak_cuti_asn', $data);
    }

    /**
     * AJAX: Cek Riwayat & Status Pengajuan Cuti Berdasarkan NIP
     */
    public function ajax_cek_status() {
        header('Content-Type: application/json');
        $nip = trim($this->input->get_post('nip'));

        if (empty($nip)) {
            echo json_encode(['status' => 'error', 'message' => 'NIP tidak boleh kosong']);
            return;
        }

        $nip_clean = preg_replace('/[^0-9]/', '', $nip);
        $ptk = $this->db
            ->where('nip', $nip)
            ->or_where('REPLACE(nip, " ", "") =', $nip_clean)
            ->get('ptk')
            ->row();

        if (!$ptk) {
            echo json_encode(['status' => 'error', 'message' => 'Pegawai tidak ditemukan.']);
            return;
        }

        $riwayat = $this->db
            ->where('ptk_id', $ptk->id)
            ->order_by('id', 'DESC')
            ->get('tu_cuti_asn')
            ->result();

        $saldo = $this->hitung_saldo_cuti($ptk->id);

        echo json_encode([
            'status'  => 'success',
            'pegawai' => [
                'nama' => $ptk->nama_lengkap,
                'nip'  => $ptk->nip,
                'jabatan' => $ptk->jabatan
            ],
            'saldo'   => $saldo,
            'riwayat' => $riwayat
        ]);
    }

    /**
     * Sinkronisasi Kuota Cuti Pegawai dari Google Spreadsheet (Sheet C)
     * Proteksi: Khusus Admin Tata Usaha atau request dengan API Key yang valid
     */
    public function sync_kuota_sheet() {
        header('Content-Type: application/json');

        // Validasi hak akses Admin
        $is_admin = ($this->session->userdata('logged_in') && in_array($this->session->userdata('role'), ['admin', 'tata_usaha']));
        $api_key = $this->input->get_post('api_key') ?: $this->input->get_request_header('X-API-KEY');
        if (!$is_admin && $api_key !== 'MAN3BANJAR_SECRET_SYNC_KEY_2026') {
            echo json_encode([
                'status'  => 'error',
                'message' => 'Akses ditolak: Fitur sinkronisasi spreadsheet merupakan fitur khusus Administrator / Tata Usaha.'
            ]);
            return;
        }

        $sheet_name = $this->input->get_post('sheet') ?: 'C';
        $sheet_url = "https://docs.google.com/spreadsheets/d/1u58r2gQ3dn9AiLx7QSDle_fB1QykOjq3lHWgyFkCf0o/gviz/tq?tqx=out:csv&sheet=" . urlencode($sheet_name);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $sheet_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $csv_data = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if (!$csv_data || $http_code != 200 || strpos($csv_data, '<!DOCTYPE html>') !== false || strpos($csv_data, 'accounts.google.com') !== false) {
            echo json_encode([
                'status'  => 'error',
                'message' => 'Gagal mengambil data dari Google Spreadsheet. Pastikan link spreadsheet dapat diakses publik.'
            ]);
            return;
        }

        $lines = explode("\n", trim($csv_data));
        $synced = 0;
        $tahun_n = (int)date('Y'); // 2026
        $tahun_n1 = $tahun_n - 1;  // 2025
        $tahun_n2 = $tahun_n - 2;  // 2024

        // Ambil daftar seluruh PTK untuk matching cepat
        $all_ptk = $this->db->select('id, nip, nama_lengkap')->get('ptk')->result();
        $ptk_nip_map = [];
        $ptk_name_map = [];
        foreach ($all_ptk as $p) {
            $cNip = preg_replace('/\D/', '', $p->nip);
            if ($cNip) $ptk_nip_map[$cNip] = $p;
            $cName = strtolower(preg_replace('/[^a-zA-Z]/', '', $p->nama_lengkap));
            $ptk_name_map[$cName] = $p;
        }

        $details = [];

        foreach ($lines as $i => $line) {
            if ($i < 2) continue; // Skip 2 baris judul & header
            $cols = str_getcsv($line);
            if (empty($cols) || count($cols) < 3) continue;

            $raw_nip = trim($cols[1] ?? '');
            $raw_nama = trim($cols[2] ?? '');
            $clean_nip = preg_replace('/\D/', '', $raw_nip);

            if (empty($clean_nip) && empty($raw_nama)) continue;

            $ptk = $ptk_nip_map[$clean_nip] ?? null;
            if (!$ptk && !empty($raw_nama)) {
                $cName = strtolower(preg_replace('/[^a-zA-Z]/', '', $raw_nama));
                foreach ($ptk_name_map as $kn => $pn) {
                    if (strpos($kn, substr($cName, 0, 8)) !== false || strpos($cName, substr($kn, 0, 8)) !== false) {
                        $ptk = $pn;
                        break;
                    }
                }
            }

            if ($ptk) {
                // Kolom T: Saldo N (index 19)
                // Kolom U: Saldo N-1 (index 20)
                // Kolom V: Saldo N-2 (index 21)
                $val_n  = isset($cols[19]) && is_numeric(trim($cols[19])) ? (int)trim($cols[19]) : (trim($cols[19] ?? '') === '' ? 0 : 12);
                $val_n1 = isset($cols[20]) && is_numeric(trim($cols[20])) ? (int)trim($cols[20]) : 0;
                $val_n2 = isset($cols[21]) && is_numeric(trim($cols[21])) ? (int)trim($cols[21]) : 0;

                // Upsert N, N-1, N-2
                $targets = [
                    $tahun_n  => $val_n,
                    $tahun_n1 => $val_n1,
                    $tahun_n2 => $val_n2
                ];

                foreach ($targets as $th => $jt) {
                    $exist = $this->db->where('ptk_id', $ptk->id)->where('tahun', $th)->get('tu_cuti_kuota')->row();
                    if ($exist) {
                        $this->db->where('id', $exist->id)->update('tu_cuti_kuota', ['jatah' => $jt]);
                    } else {
                        $this->db->insert('tu_cuti_kuota', ['ptk_id' => $ptk->id, 'tahun' => $th, 'jatah' => $jt]);
                    }
                }

                $synced++;
                $details[] = "{$ptk->nama_lengkap} (N: $val_n, N-1: $val_n1, N-2: $val_n2)";
            }
        }

        echo json_encode([
            'status'  => 'success',
            'message' => "Berhasil menyinkronkan kuota cuti pegawai dari Google Spreadsheet Sheet '$sheet_name'. Total $synced pegawai diperbarui.",
            'total'   => $synced,
            'details' => array_slice($details, 0, 5)
        ]);
    }
}
