<?php
$setting = $this->db->get('settings')->row();

$tahunAktif = $setting && !empty($setting->tahun_ajaran)
    ? $setting->tahun_ajaran
    : date('Y').'-'.(date('Y') + 1);

$semesterAktif = $setting && !empty($setting->semester_aktif)
    ? $setting->semester_aktif
    : 'Ganjil';
?>

<div class="alert alert-success d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div>
        <strong>Periode Aktif:</strong>
        Tahun Ajaran <?= $tahunAktif ?> • Semester <?= $semesterAktif ?>
    </div>

    <a href="<?= base_url('admin_settings/periode_akademik') ?>" class="btn btn-sm btn-outline-success">
        Ubah Periode
    </a>
</div>