<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Helper Data & Matriks Instrumen LKE PMPZI Kemenag RI 2026
 * Satuan Kerja: MADRASAH ALIYAH NEGERI 3 BANJAR
 * Sesuai Permenpan RB No. 90 Tahun 2021
 */
if (!function_exists('get_pmpzi_lke_summary')) {
    function get_pmpzi_lke_summary($zi_stats = []) {
        $json_file = FCPATH . 'scratch/pmpzi_lke_data.json';
        $detail_data = [];
        if (file_exists($json_file)) {
            $json_content = file_get_contents($json_file);
            $detail_data = json_decode($json_content, true);
        }

        $matrix = [
            'tahun'         => '2026',
            'regulasi'      => 'Permenpan 90 Tahun 2021',
            'satker'        => 'MADRASAH ALIYAH NEGERI 3 BANJAR',
            'kode_satker'   => '309442',
            'indeks_satker' => 68.67,
            'predikat'      => 'Memenuhi Syarat Pengusulan WBK (Ambang Batas ≥ 60.00)',
            'pengungkit'    => [
                'bobot' => 60.0,
                'nilai' => 38.67,
                'persen' => 64.45,
                'pemenuhan' => [
                    'bobot' => 30.0,
                    'nilai' => 21.59,
                    'persen' => 71.97,
                    'items' => [
                        [
                            'no' => 1,
                            'area_key' => 'area1',
                            'kode_ev' => 'A.1.1',
                            'nama' => 'Manajemen Perubahan',
                            'bobot' => 4.0,
                            'nilai' => 2.59,
                            'persen' => 64.70,
                            'berkas_count' => $zi_stats['area1'] ?? 0,
                            'keterangan' => 'Penyusunan Tim Kerja, Rencana Pembangunan ZI, Pola Pikir & Budaya Kerja'
                        ],
                        [
                            'no' => 2,
                            'area_key' => 'area2',
                            'kode_ev' => 'A.1.2',
                            'nama' => 'Penataan Tatalaksana',
                            'bobot' => 3.5,
                            'nilai' => 2.83,
                            'persen' => 80.95,
                            'berkas_count' => $zi_stats['area2'] ?? 0,
                            'keterangan' => 'SOP Layanan & Administrasi, SPBE / e-Office, Keterbukaan Informasi Publik (PPID)'
                        ],
                        [
                            'no' => 3,
                            'area_key' => 'area3',
                            'kode_ev' => 'A.1.3',
                            'nama' => 'Penataan Sistem Manajemen SDM Aparatur',
                            'bobot' => 5.0,
                            'nilai' => 3.72,
                            'persen' => 74.48,
                            'berkas_count' => $zi_stats['area3'] ?? 0,
                            'keterangan' => 'Perencanaan Anjab/ABK, Pola Mutasi, Diklat/TNA, SKP & Penegakan Disiplin GTK'
                        ],
                        [
                            'no' => 4,
                            'area_key' => 'area4',
                            'kode_ev' => 'A.1.4',
                            'nama' => 'Penguatan Akuntabilitas',
                            'bobot' => 5.0,
                            'nilai' => 4.58,
                            'persen' => 91.69,
                            'berkas_count' => $zi_stats['area4'] ?? 0,
                            'keterangan' => 'Keterlibatan Kamad, Perjanjian Kinerja (PK), Laporan LKjIP & Capaian IKU'
                        ],
                        [
                            'no' => 5,
                            'area_key' => 'area5',
                            'kode_ev' => 'A.1.5',
                            'nama' => 'Penguatan Pengawasan',
                            'bobot' => 7.5,
                            'nilai' => 4.65,
                            'persen' => 62.04,
                            'berkas_count' => $zi_stats['area5'] ?? 0,
                            'keterangan' => 'Pengendalian Gratifikasi, SPIP Risk Register, Dumas, WBS, Bebas Benturan Kepentingan'
                        ],
                        [
                            'no' => 6,
                            'area_key' => 'area6',
                            'kode_ev' => 'A.1.6',
                            'nama' => 'Peningkatan Kualitas Pelayanan Publik',
                            'bobot' => 5.0,
                            'nilai' => 3.21,
                            'persen' => 64.15,
                            'berkas_count' => $zi_stats['area6'] ?? 0,
                            'keterangan' => 'Standar Pelayanan, Maklumat Pelayanan di PTSP, Fasilitas Disabilitas, SKM/IKM'
                        ]
                    ]
                ],
                'reform' => [
                    'bobot' => 30.0,
                    'nilai' => 17.08,
                    'persen' => 56.93,
                    'items' => [
                        [
                            'no' => 1,
                            'area_key' => 'area1',
                            'kode_ev' => 'A.2.1',
                            'nama' => 'Manajemen Perubahan',
                            'bobot' => 4.0,
                            'nilai' => 3.42,
                            'persen' => 85.50,
                            'berkas_count' => $zi_stats['area1'] ?? 0,
                            'keterangan' => 'Monev Berkala Pembangunan ZI oleh Pimpinan'
                        ],
                        [
                            'no' => 2,
                            'area_key' => 'area2',
                            'kode_ev' => 'A.2.2',
                            'nama' => 'Penataan Tatalaksana',
                            'bobot' => 3.5,
                            'nilai' => 2.34,
                            'persen' => 66.71,
                            'berkas_count' => $zi_stats['area2'] ?? 0,
                            'keterangan' => 'Transformasi Digital dan Efisiensi Tata Kelola Madrasah'
                        ],
                        [
                            'no' => 3,
                            'area_key' => 'area3',
                            'kode_ev' => 'A.2.3',
                            'nama' => 'Penataan Sistem Manajemen SDM Aparatur',
                            'bobot' => 5.0,
                            'nilai' => 1.50,
                            'persen' => 30.00,
                            'berkas_count' => $zi_stats['area3'] ?? 0,
                            'keterangan' => 'Peningkatan Indeks Profesionalitas ASN (IP ASN) dan Prestasi GTK'
                        ],
                        [
                            'no' => 4,
                            'area_key' => 'area4',
                            'kode_ev' => 'A.2.4',
                            'nama' => 'Penguatan Akuntabilitas',
                            'bobot' => 5.0,
                            'nilai' => 3.81,
                            'persen' => 76.20,
                            'berkas_count' => $zi_stats['area4'] ?? 0,
                            'keterangan' => 'Efektivitas Penggunaan Anggaran & Realisasi Capaian IKU Output'
                        ],
                        [
                            'no' => 5,
                            'area_key' => 'area5',
                            'kode_ev' => 'A.2.5',
                            'nama' => 'Penguatan Pengawasan',
                            'bobot' => 7.5,
                            'nilai' => 2.88,
                            'persen' => 38.33,
                            'berkas_count' => $zi_stats['area5'] ?? 0,
                            'keterangan' => 'Tindak Lanjut Hasil Pengawasan (TLHP) & Kepatuhan Rekomendasi Itjen'
                        ],
                        [
                            'no' => 6,
                            'area_key' => 'area6',
                            'kode_ev' => 'A.2.6',
                            'nama' => 'Peningkatan Kualitas Pelayanan Publik',
                            'bobot' => 5.0,
                            'nilai' => 3.14,
                            'persen' => 62.75,
                            'berkas_count' => $zi_stats['area6'] ?? 0,
                            'keterangan' => 'Inovasi Pelayanan Publik Madrasah & Keberlanjutan Pemanfaatan'
                        ]
                    ]
                ]
            ],
            'hasil' => [
                'bobot' => 40.0,
                'nilai' => 30.00,
                'persen' => 75.00,
                'items' => [
                    [
                        'no' => 1,
                        'kode_ev' => 'B.1',
                        'nama' => 'Birokrasi Bersih dan Akuntabel',
                        'bobot' => 22.5,
                        'nilai' => 16.88,
                        'persen' => 75.00,
                        'keterangan' => 'Hasil survei persepsi anti korupsi (SPAK) dan akuntabilitas kinerja instansi'
                    ],
                    [
                        'no' => 2,
                        'kode_ev' => 'B.2',
                        'nama' => 'Pelayanan Publik Prima',
                        'bobot' => 17.5,
                        'nilai' => 13.13,
                        'persen' => 75.00,
                        'keterangan' => 'Hasil survei persepsi kualitas pelayanan (SPKP) dan indeks kepuasan masyarakat'
                    ]
                ]
            ],
            'detail_lke' => $detail_data
        ];

        return $matrix;
    }
}
