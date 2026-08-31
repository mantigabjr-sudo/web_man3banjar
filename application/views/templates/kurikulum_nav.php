<div class="kurikulum-nav mb-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-3">
        <div>
            <h5 class="mb-1 text-success">Navigasi Kurikulum</h5>
            <p class="text-muted mb-0">Kelola tugas mengajar, jadwal, dan pengaturan KBM.</p>
        </div>
    </div>

    <div class="row g-3">

        <div class="col-md-3">
            <a href="<?= base_url('admin_tugas_mengajar') ?>" class="nav-card">
                <div class="nav-title">Dashboard Tugas</div>
                <div class="nav-desc">Ringkasan beban guru</div>
            </a>
        </div>

        <div class="col-md-3">
            <a href="<?= base_url('admin_tugas_mengajar/bulk') ?>" class="nav-card">
                <div class="nav-title">Input Cepat</div>
                <div class="nav-desc">Isi jam banyak kelas</div>
            </a>
        </div>

        <div class="col-md-3">
            <a href="<?= base_url('admin_tugas_mengajar/rekap') ?>" class="nav-card">
                <div class="nav-title">Rekap Matriks</div>
                <div class="nav-desc">Format SK mengajar</div>
            </a>
        </div>

        <div class="col-md-3">
            <a href="<?= base_url('admin_jadwal_mengajar/builder') ?>" class="nav-card">
                <div class="nav-title">Jadwal Builder</div>
                <div class="nav-desc">Grid input jadwal</div>
            </a>
        </div>

        <div class="col-md-3">
            <a href="<?= base_url('admin_jadwal_mengajar') ?>" class="nav-card">
                <div class="nav-title">Dashboard Jadwal</div>
                <div class="nav-desc">Pantau jadwal aktif</div>
            </a>
        </div>

        <div class="col-md-3">
            <a href="<?= base_url('admin_jadwal_mengajar/rekap') ?>" class="nav-card">
                <div class="nav-title">Rekap Jadwal</div>
                <div class="nav-desc">Cetak jadwal global</div>
            </a>
        </div>

        <div class="col-md-3">
            <a href="<?= base_url('admin_jadwal_mengajar/pengaturan') ?>" class="nav-card">
                <div class="nav-title">Hari & Jam</div>
                <div class="nav-desc">Atur slot jadwal</div>
            </a>
        </div>

        <div class="col-md-3">
            <a href="<?= base_url('admin_mapel') ?>" class="nav-card">
                <div class="nav-title">Data Mapel</div>
                <div class="nav-desc">Master pelajaran</div>
            </a>
        </div>

        <div class="col-md-3">
            <a href="<?= base_url('admin_ptk/akg') ?>" class="nav-card" style="border-color: #86efac; background: #f0fdf4;">
                <div class="nav-title text-success">Kebutuhan Guru (AKG)</div>
                <div class="nav-desc">Analisis formasi mapel</div>
            </a>
        </div>

    </div>

</div>