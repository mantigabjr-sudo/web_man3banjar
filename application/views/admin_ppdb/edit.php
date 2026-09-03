<?php $this->load->view('templates/header'); ?>
<?php $this->load->view('templates/sidebar'); ?>

<div class="content">

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h2 class="glow mb-1">Edit Data Calon Siswa PMB</h2>
            <p class="soft-text mb-0">Perbarui data administrasi, jalur pendaftaran, status, dan jadwal seleksi peserta.</p>
        </div>
        <div>
            <a href="<?= base_url('admin_ppdb/detail/'.$p->id) ?>" class="btn btn-outline-secondary px-3 py-2 rounded-pill fw-bold" style="font-size: 13.5px;">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Detail
            </a>
        </div>
    </div>

    <div class="card p-4 border-0 shadow-sm rounded-4" style="background:#ffffff;">

        <form method="post" action="<?= base_url('admin_ppdb/update/'.$p->id) ?>">

            <!-- ═══ 1. DATA POKOK & STATUS PENDAFTARAN ═══ -->
            <div class="p-3 rounded-3 bg-light border-start border-4 border-success mb-3">
                <span class="fw-bold small text-success"><i class="bi bi-shield-check me-1"></i> Data Pokok, Jalur &amp; Status Seleksi</span>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label fw-bold small text-secondary">Nomor Pendaftaran</label>
                    <input type="text" class="form-control bg-light" value="<?= htmlspecialchars($p->no_pendaftaran) ?>" disabled>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold small text-secondary">NISN (Username Login) <span class="text-danger">*</span></label>
                    <input type="text" name="nisn" class="form-control" value="<?= htmlspecialchars($p->nisn) ?>" required maxlength="10">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold small text-secondary">Status Pendaftaran <span class="text-danger">*</span></label>
                    <select name="status" class="form-select fw-bold text-success" required>
                        <option value="Lengkapi Biodata" <?= $p->status=='Lengkapi Biodata'?'selected':'' ?>>Lengkapi Biodata</option>
                        <option value="Upload Berkas" <?= $p->status=='Upload Berkas'?'selected':'' ?>>Upload Berkas</option>
                        <option value="Menunggu Verifikasi Berkas" <?= $p->status=='Menunggu Verifikasi Berkas'?'selected':'' ?>>Menunggu Verifikasi Berkas</option>
                        <option value="Lulus Verifikasi" <?= $p->status=='Lulus Verifikasi'?'selected':'' ?>>Lulus Verifikasi</option>
                        <option value="Perlu Perbaikan" <?= $p->status=='Perlu Perbaikan'?'selected':'' ?>>Perlu Perbaikan</option>
                        <option value="Diterima" <?= $p->status=='Diterima'?'selected':'' ?>>Diterima</option>
                        <option value="Ditolak" <?= $p->status=='Ditolak'?'selected':'' ?>>Ditolak</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold small text-secondary">Jalur Pendaftaran <span class="text-danger">*</span></label>
                    <select name="jalur_pendaftaran" class="form-select" required>
                        <option value="Reguler" <?= ($p->jalur_pendaftaran ?? '')=='Reguler'?'selected':'' ?>>Jalur Reguler / Umum</option>
                        <option value="Prestasi" <?= ($p->jalur_pendaftaran ?? '')=='Prestasi'?'selected':'' ?>>Jalur Prestasi (Akademik / Non-Akademik)</option>
                        <option value="Tahfidz" <?= ($p->jalur_pendaftaran ?? '')=='Tahfidz'?'selected':'' ?>>Jalur Tahfidz Al-Qur'an</option>
                        <option value="Afirmasi" <?= ($p->jalur_pendaftaran ?? '')=='Afirmasi'?'selected':'' ?>>Jalur Afirmasi (KIP / PKH / KKS)</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold small text-secondary">Asal Sekolah (SMP / MTs) <span class="text-danger">*</span></label>
                    <input type="text" name="asal_sekolah" class="form-control" value="<?= htmlspecialchars($p->asal_sekolah) ?>" required>
                </div>
            </div>

            <!-- ═══ 2. JADWAL TES & SELEKSI (KHUSUS ADMIN) ═══ -->
            <div class="p-3 rounded-3 bg-light border-start border-4 border-primary mb-3">
                <span class="fw-bold small text-primary"><i class="bi bi-calendar-check me-1"></i> Data Kartu Peserta &amp; Jadwal Ujian Seleksi</span>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label fw-bold small text-secondary">Nomor Peserta Tes</label>
                    <input type="text" name="no_peserta_tes" class="form-control font-monospace" placeholder="Contoh: TES-2026-0001" value="<?= htmlspecialchars($p->no_peserta_tes ?? '') ?>">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold small text-secondary">Tanggal Tes Seleksi</label>
                    <input type="date" name="tanggal_tes" class="form-control" value="<?= htmlspecialchars($p->tanggal_tes ?? '') ?>">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold small text-secondary">Jam Pelaksanaan Tes</label>
                    <input type="text" name="jam_tes" class="form-control" placeholder="Contoh: 08:00 - 11.30 WITA" value="<?= htmlspecialchars($p->jam_tes ?? '08:00 - 11.30 WITA') ?>">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold small text-secondary">Ruang / Lokasi Tes</label>
                    <input type="text" name="ruang_tes" class="form-control" placeholder="Contoh: Ruang CBT 1 / Kampus MAN 3 Banjar" value="<?= htmlspecialchars($p->ruang_tes ?? 'Kampus MAN 3 Banjar') ?>">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold small text-secondary">Nilai Tes (Opsional)</label>
                    <input type="number" step="0.01" name="nilai_tes" class="form-control" placeholder="Contoh: 85.50" value="<?= htmlspecialchars($p->nilai_tes ?? '') ?>">
                </div>

                <div class="col-12">
                    <label class="form-label fw-bold small text-secondary">Catatan Verifikasi / Catatan Panitia</label>
                    <textarea name="catatan_verifikasi" class="form-control" rows="2" placeholder="Catatan internal panitia atau alasan perbaikan berkas..."><?= htmlspecialchars($p->catatan_verifikasi ?? '') ?></textarea>
                </div>
            </div>

            <!-- ═══ 3. IDENTITAS & BIODATA SISWA ═══ -->
            <div class="p-3 rounded-3 bg-light border-start border-4 border-success mb-3">
                <span class="fw-bold small text-success"><i class="bi bi-person-lines-fill me-1"></i> Identitas &amp; Biodata Siswa</span>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-12">
                    <label class="form-label fw-bold small text-secondary">Nama Lengkap Siswa <span class="text-danger">*</span></label>
                    <input type="text" name="nama_lengkap" class="form-control" value="<?= htmlspecialchars($p->nama_lengkap) ?>" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold small text-secondary">Tempat Lahir <span class="text-danger">*</span></label>
                    <input type="text" name="tempat_lahir" class="form-control" value="<?= htmlspecialchars($p->tempat_lahir) ?>" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold small text-secondary">Tanggal Lahir <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal_lahir" class="form-control" value="<?= htmlspecialchars($p->tanggal_lahir) ?>" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold small text-secondary">Jenis Kelamin <span class="text-danger">*</span></label>
                    <select name="jk" class="form-select" required>
                        <option value="L" <?= $p->jk=='L'?'selected':'' ?>>Laki-laki</option>
                        <option value="P" <?= $p->jk=='P'?'selected':'' ?>>Perempuan</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold small text-secondary">Agama <span class="text-danger">*</span></label>
                    <select name="agama" class="form-select" required>
                        <option value="Islam" <?= ($p->agama ?? 'Islam')=='Islam'?'selected':'' ?>>Islam</option>
                        <option value="Kristen" <?= ($p->agama ?? '')=='Kristen'?'selected':'' ?>>Kristen</option>
                        <option value="Katolik" <?= ($p->agama ?? '')=='Katolik'?'selected':'' ?>>Katolik</option>
                        <option value="Hindu" <?= ($p->agama ?? '')=='Hindu'?'selected':'' ?>>Hindu</option>
                        <option value="Budha" <?= ($p->agama ?? '')=='Budha'?'selected':'' ?>>Budha</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold small text-secondary">NIK (16 Digit)</label>
                    <input type="text" name="nik" class="form-control" value="<?= htmlspecialchars($p->nik ?? '') ?>" maxlength="16">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold small text-secondary">Nomor KK (16 Digit)</label>
                    <input type="text" name="no_kk" class="form-control" value="<?= htmlspecialchars($p->no_kk ?? '') ?>" maxlength="16">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold small text-secondary">Anak Ke-</label>
                    <input type="number" name="anak_ke" class="form-control" value="<?= htmlspecialchars($p->anak_ke ?? '') ?>" min="1">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold small text-secondary">Jumlah Saudara</label>
                    <input type="number" name="jumlah_saudara" class="form-control" value="<?= htmlspecialchars($p->jumlah_saudara ?? '') ?>" min="0">
                </div>
            </div>

            <!-- ═══ 4. KONTAK & ALAMAT ═══ -->
            <div class="p-3 rounded-3 bg-light border-start border-4 border-success mb-3">
                <span class="fw-bold small text-success"><i class="bi bi-telephone me-1"></i> Kontak &amp; Alamat Tempat Tinggal</span>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-bold small text-secondary">Nomor WhatsApp / HP <span class="text-danger">*</span></label>
                    <input type="text" name="no_hp" class="form-control" value="<?= htmlspecialchars($p->no_hp) ?>" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold small text-secondary">Alamat Email</label>
                    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($p->email ?? '') ?>" placeholder="contoh: siswa@gmail.com">
                </div>

                <div class="col-12">
                    <label class="form-label fw-bold small text-secondary">Alamat Lengkap</label>
                    <textarea name="alamat" class="form-control" rows="2"><?= htmlspecialchars($p->alamat ?? '') ?></textarea>
                </div>

                <div class="col-md-3 col-6">
                    <label class="form-label fw-bold small text-secondary">RT</label>
                    <input type="text" name="rt" class="form-control" value="<?= htmlspecialchars($p->rt ?? '') ?>">
                </div>

                <div class="col-md-3 col-6">
                    <label class="form-label fw-bold small text-secondary">RW</label>
                    <input type="text" name="rw" class="form-control" value="<?= htmlspecialchars($p->rw ?? '') ?>">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold small text-secondary">Desa / Kelurahan</label>
                    <input type="text" name="desa" class="form-control" value="<?= htmlspecialchars($p->desa ?? '') ?>">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold small text-secondary">Kecamatan</label>
                    <input type="text" name="kecamatan" class="form-control" value="<?= htmlspecialchars($p->kecamatan ?? '') ?>">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold small text-secondary">Kabupaten / Kota</label>
                    <input type="text" name="kabupaten" class="form-control" value="<?= htmlspecialchars($p->kabupaten ?? '') ?>">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold small text-secondary">Provinsi</label>
                    <input type="text" name="provinsi" class="form-control" value="<?= htmlspecialchars($p->provinsi ?? '') ?>">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold small text-secondary">Kode Pos</label>
                    <input type="text" name="kode_pos" class="form-control" value="<?= htmlspecialchars($p->kode_pos ?? '') ?>">
                </div>
            </div>

            <!-- ═══ 5. DATA ORANG TUA ═══ -->
            <div class="p-3 rounded-3 bg-light border-start border-4 border-success mb-3">
                <span class="fw-bold small text-success"><i class="bi bi-people me-1"></i> Data Orang Tua / Wali</span>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-bold small text-secondary">Nama Ayah / Wali <span class="text-danger">*</span></label>
                    <input type="text" name="nama_ayah" class="form-control" value="<?= htmlspecialchars($p->nama_ayah ?? ($p->nama_ortu ?? '')) ?>" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold small text-secondary">Pekerjaan Ayah</label>
                    <input type="text" name="pekerjaan_ayah" class="form-control" value="<?= htmlspecialchars($p->pekerjaan_ayah ?? '') ?>">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold small text-secondary">Nama Ibu</label>
                    <input type="text" name="nama_ibu" class="form-control" value="<?= htmlspecialchars($p->nama_ibu ?? '') ?>">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold small text-secondary">Pekerjaan Ibu</label>
                    <input type="text" name="pekerjaan_ibu" class="form-control" value="<?= htmlspecialchars($p->pekerjaan_ibu ?? '') ?>">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold small text-secondary">Penghasilan Orang Tua</label>
                    <select name="penghasilan_ortu" class="form-select">
                        <option value="">-- Pilih Range Penghasilan --</option>
                        <option value="< 1 Juta" <?= ($p->penghasilan_ortu ?? '')=='< 1 Juta'?'selected':'' ?>>&lt; Rp 1.000.000</option>
                        <option value="1-3 Juta" <?= ($p->penghasilan_ortu ?? '')=='1-3 Juta'?'selected':'' ?>>Rp 1.000.000 - Rp 3.000.000</option>
                        <option value="3-5 Juta" <?= ($p->penghasilan_ortu ?? '')=='3-5 Juta'?'selected':'' ?>>Rp 3.000.000 - Rp 5.000.000</option>
                        <option value="> 5 Juta" <?= ($p->penghasilan_ortu ?? '')=='> 5 Juta'?'selected':'' ?>>&gt; Rp 5.000.000</option>
                    </select>
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                <a href="<?= base_url('admin_ppdb/detail/'.$p->id) ?>" class="btn btn-outline-secondary px-4 py-2 rounded-pill fw-bold" style="font-size: 14px;">
                    Batal
                </a>
                <button type="submit" class="btn btn-success px-4 py-2 rounded-pill fw-bold shadow-sm" style="background:#059669; border-color:#059669; font-size: 14px;">
                    <i class="bi bi-check2-circle me-1"></i> Simpan Seluruh Perubahan
                </button>
            </div>

        </form>

    </div>

</div>

<?php $this->load->view('templates/footer'); ?>