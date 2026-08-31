<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>

<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

<script>
$(document).ready(function(){

    $('.datatable').DataTable({
        responsive: true,
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50, 100],
        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data",
            zeroRecords: "Data tidak ditemukan",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            infoEmpty: "Tidak ada data",
            infoFiltered: "(difilter dari _MAX_ total data)",
            paginate: {
                first: "Awal",
                last: "Akhir",
                next: "Berikutnya",
                previous: "Sebelumnya"
            }
        }
    });

});
</script>

<?php
$footer_role = $this->session->userdata('role');
$footer_current = uri_string();
$footer_can_bk_admin = in_array($footer_role, ['admin', 'admin_master', 'admin_kesiswaan']);
$footer_can_website = function_exists('can_admin_menu') ? can_admin_menu('website') : in_array($footer_role, ['admin','admin_master','admin_humas','wakil_humas','operator_humas']);
?>

<?php if($footer_can_website): ?>
<script>
document.addEventListener('DOMContentLoaded', function(){
    var menuWebsite = document.getElementById('menuWebsite');
    if(!menuWebsite){ return; }

    if(!menuWebsite.querySelector('a[href$="/admin_banner"], a[href*="admin_banner"]')){
        var beritaLink = menuWebsite.querySelector('a[href*="/berita"]');
        var link = document.createElement('a');
        link.href = '<?= base_url('admin_banner') ?>';
        link.className = '<?= strpos($footer_current, 'admin_banner') === 0 ? 'active-menu' : '' ?>';
        link.innerHTML = '<span class="sub-dot"></span>Banner Slider';

        if(beritaLink && beritaLink.nextSibling){
            menuWebsite.insertBefore(link, beritaLink.nextSibling);
        } else {
            menuWebsite.appendChild(link);
        }
    }

    <?php if(strpos($footer_current, 'admin_banner') === 0): ?>
    menuWebsite.classList.add('show');
    var toggleWebsite = document.querySelector('[data-bs-target="#menuWebsite"]');
    if(toggleWebsite){
        toggleWebsite.classList.add('active-toggle');
        toggleWebsite.setAttribute('aria-expanded', 'true');
    }
    <?php endif; ?>
});
</script>
<?php endif; ?>

<?php if($footer_can_bk_admin): ?>
<script>
document.addEventListener('DOMContentLoaded', function(){
    var menuAkademik = document.getElementById('menuAkademik');

    if(!menuAkademik){
        return;
    }

    if(!menuAkademik.querySelector('a[href$="/admin_bk_bp"], a[href*="admin_bk_bp"]')){
        var link = document.createElement('a');
        link.href = '<?= base_url('admin_bk_bp') ?>';
        link.className = '<?= strpos($footer_current, 'admin_bk_bp') === 0 ? 'active-menu' : '' ?>';
        link.innerHTML = '<span class="sub-dot"></span>Monitoring BK/BP';
        menuAkademik.appendChild(link);
    }

    <?php if(strpos($footer_current, 'admin_bk_bp') === 0): ?>
    menuAkademik.classList.add('show');

    var toggle = document.querySelector('[data-bs-target="#menuAkademik"]');
    if(toggle){
        toggle.classList.add('active-toggle');
        toggle.setAttribute('aria-expanded', 'true');
    }
    <?php endif; ?>
});
</script>
<?php endif; ?>

<?php if($footer_current == 'berita'): ?>
<script>
document.addEventListener('DOMContentLoaded', function(){
    var form = document.querySelector('#modalTambahBerita form');

    if(form){
        form.action = '<?= base_url('berita_admin_actions/add') ?>';

        if(!form.querySelector('[name="kategori"]')){
            var kategoriField = document.createElement('div');
            kategoriField.className = 'news-field';
            kategoriField.innerHTML = '<label>Kategori Berita</label>'+
                '<select name="kategori" class="news-input news-select" required>'+
                '<option value="Prestasi">Prestasi</option>'+
                '<option value="Kegiatan" selected>Kegiatan</option>'+
                '<option value="Pengumuman">Pengumuman</option>'+
                '<option value="PPDB">PPDB</option>'+
                '<option value="Akademik">Akademik</option>'+
                '<option value="Keagamaan">Keagamaan</option>'+
                '<option value="Ekstrakurikuler">Ekstrakurikuler</option>'+
                '</select>'+
                '<small class="text-muted fw-bold d-block mt-2">Kategori membantu pengunjung menemukan berita sesuai jenis informasi.</small>';

            var judulField = form.querySelector('[name="judul"]');
            var firstField = judulField ? judulField.closest('.news-field') : null;

            if(firstField && firstField.parentNode){
                firstField.parentNode.insertBefore(kategoriField, firstField.nextSibling);
            }
        }

        if(!form.querySelector('[name="is_featured"]')){
            var featuredField = document.createElement('div');
            featuredField.className = 'news-field';
            featuredField.innerHTML = '<label>Berita Utama / Featured</label>'+
                '<div class="news-featured-box">'+
                '<label class="news-featured-check"><input type="checkbox" name="is_featured" value="1"> <span>Jadikan berita ini sebagai Berita Utama</span></label>'+
                '<input type="number" name="featured_order" class="news-input mt-2" value="0" min="0" placeholder="Urutan tampil, 0 paling atas">'+
                '<small class="text-muted fw-bold d-block mt-2">Berita Utama diprioritaskan tampil besar di homepage setelah dipublish.</small>'+
                '</div>';

            var isiField = form.querySelector('[name="isi"]');
            var isiWrapper = isiField ? isiField.closest('.news-field') : null;

            if(isiWrapper && isiWrapper.parentNode){
                isiWrapper.parentNode.insertBefore(featuredField, isiWrapper.nextSibling);
            }
        }
    }

    document.querySelectorAll('.news-actions').forEach(function(actions){
        var edit = actions.querySelector('a[href*="berita/edit/"]');

        if(!edit || actions.querySelector('.news-action-featured')){
            return;
        }

        var match = edit.getAttribute('href').match(/berita\/edit\/(\d+)/);

        if(!match){
            return;
        }

        var id = match[1];

        var feature = document.createElement('a');
        feature.href = '<?= base_url('berita_admin_actions/featured/') ?>' + id;
        feature.className = 'news-action news-action-featured';
        feature.onclick = function(){ return confirm('Jadikan berita ini sebagai Berita Utama?'); };
        feature.textContent = 'Jadikan Utama';

        var unfeature = document.createElement('a');
        unfeature.href = '<?= base_url('berita_admin_actions/unfeatured/') ?>' + id;
        unfeature.className = 'news-action news-action-unfeatured';
        unfeature.onclick = function(){ return confirm('Batalkan berita utama untuk berita ini?'); };
        unfeature.textContent = 'Batal Utama';

        actions.insertBefore(feature, edit.nextSibling);
        actions.insertBefore(unfeature, feature.nextSibling);
    });
});
</script>
<?php endif; ?>

<script>
if ('serviceWorker' in navigator) {
  window.addEventListener('load', function() {
    navigator.serviceWorker.register('<?= base_url('sw.js') ?>').then(function(registration) {
      console.log('ServiceWorker registration successful in dashboard');
    }, function(err) {
      console.log('ServiceWorker registration failed in dashboard: ', err);
    });
  });
}
</script>

</body>
</html>