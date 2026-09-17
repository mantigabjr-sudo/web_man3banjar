<?php
$active_profil_tab = $active_profil_tab ?? '';
?>

<div class="web-subnav-wrapper">
    <div class="container">
        <ul class="web-subnav-pills">
            <li>
                <a href="<?= base_url('website/sejarah') ?>" class="nav-link <?= $active_profil_tab == 'sejarah' ? 'active' : '' ?>">
                    <i class="bi bi-clock-history"></i> Sejarah
                </a>
            </li>
            <li>
                <a href="<?= base_url('website/visi_misi') ?>" class="nav-link <?= $active_profil_tab == 'visi_misi' ? 'active' : '' ?>">
                    <i class="bi bi-compass"></i> Visi &amp; Misi
                </a>
            </li>
            <li>
                <a href="<?= base_url('website/fasilitas') ?>" class="nav-link <?= $active_profil_tab == 'fasilitas' ? 'active' : '' ?>">
                    <i class="bi bi-building"></i> Fasilitas
                </a>
            </li>
            <li>
                <a href="<?= base_url('website/struktur/tenaga-pendidik') ?>" class="nav-link <?= $active_profil_tab == 'struktur' ? 'active' : '' ?>">
                    <i class="bi bi-diagram-3"></i> Struktur Organisasi
                </a>
            </li>
            <li>
                <a href="<?= base_url('website/ptk') ?>" class="nav-link <?= $active_profil_tab == 'ptk' ? 'active' : '' ?>">
                    <i class="bi bi-people-fill"></i> Direktori PTK
                </a>
            </li>
        </ul>
    </div>
</div>
