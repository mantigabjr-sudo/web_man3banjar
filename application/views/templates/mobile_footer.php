<?php if($this->uri->segment(1) == 'user_tata_usaha'): ?>
    <?php $this->load->view('user/tu/_style'); ?>
<?php endif; ?>

<?php if($this->uri->segment(1) == 'user_nilai'): ?>
    <?php $this->load->view('user/nilai/_style'); ?>
<?php endif; ?>

</main>
</div>
</div>

<?php
/*
 * Bottom nav dipanggil di footer agar pasti tampil di semua halaman mobile user.
 * Diletakkan setelah style halaman supaya CSS bottom nav tidak kalah oleh CSS user_nilai.
 * Partial mobile_bottom_nav sudah punya guard, jadi aman jika tidak sengaja dipanggil di tempat lain.
 */
$this->load->view('templates/mobile_bottom_nav');
?>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
<!-- Datatable Export Buttons -->
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>

<script>
$(document).ready(function(){
    // Inisiasi DataTable default untuk tampilan mobile PWA
    if($('.datatable').length > 0){
        $('.datatable').DataTable({
            responsive: true,
            pageLength: 10,
            lengthMenu: [5, 10, 25, 50],
            language: {
                search: "Cari:",
                lengthMenu: "Tampil _MENU_ data",
                zeroRecords: "Data tidak ditemukan",
                info: "_START_ - _END_ dari _TOTAL_ data",
                infoEmpty: "Tidak ada data",
                infoFiltered: "(filter dari _MAX_ data)",
                paginate: {
                    first: "Awal",
                    last: "Akhir",
                    next: "Lanjut",
                    previous: "Mundur"
                }
            }
        });
    }

    // Datatable dengan fitur Export Excel
    if($('.datatable-export').length > 0){
        $('.datatable-export').DataTable({
            responsive: true,
            pageLength: 10,
            lengthMenu: [5, 10, 25, 50, 100],
            dom: "<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'B><'col-sm-12 col-md-6'f>>" +
                 "<'row'<'col-sm-12'tr>>" +
                 "<'row mt-3'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            buttons: [
                {
                    extend: 'excelHtml5',
                    text: '<i class="bi bi-file-earmark-excel"></i> Export Excel',
                    className: 'btn btn-success btn-sm rounded-pill px-3 fw-bold shadow-sm',
                    title: 'Data Export'
                }
            ],
            language: {
                search: "Cari:",
                lengthMenu: "Tampil _MENU_ data",
                zeroRecords: "Data tidak ditemukan",
                info: "_START_ - _END_ dari _TOTAL_ data",
                infoEmpty: "Tidak ada data",
                infoFiltered: "(filter dari _MAX_ data)",
                paginate: {
                    first: "Awal",
                    last: "Akhir",
                    next: "Lanjut",
                    previous: "Mundur"
                }
            }
        });
    }

    // Fix bug Datatables Responsive mengkerut saat diletakkan di dalam Bootstrap Tabs
    $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
        $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust().responsive.recalc();
    });
});
</script>

<?php if($this->uri->segment(1) == 'user_tata_usaha'): ?>
<script>
(function(){

    function findTarget(trigger){
        var target =
            trigger.getAttribute('data-tu-target') ||
            trigger.getAttribute('data-bs-target') ||
            trigger.getAttribute('data-target') ||
            trigger.getAttribute('href');

        if(!target || target === '#' || target.charAt(0) !== '#'){
            return null;
        }

        return document.querySelector(target);
    }

    function openModal(modal){
        if(!modal){
            return;
        }

        document.querySelectorAll('.modal.show').forEach(function(m){
            closeModal(m);
        });

        modal.classList.add('show');
        modal.style.display = 'block';
        modal.removeAttribute('aria-hidden');
        modal.setAttribute('aria-modal','true');

        document.body.classList.add('tu-modal-open');
    }

    function closeModal(modal){
        if(!modal){
            return;
        }

        modal.classList.remove('show');
        modal.style.display = 'none';
        modal.setAttribute('aria-hidden','true');
        modal.removeAttribute('aria-modal');

        if(document.querySelectorAll('.modal.show').length === 0){
            document.body.classList.remove('tu-modal-open');
        }
    }

    document.addEventListener('click', function(e){

        var trigger = e.target.closest('[data-bs-toggle="modal"], [data-toggle="modal"], [data-tu-toggle="modal"]');

        if(trigger){
            e.preventDefault();
            e.stopPropagation();

            var modal = findTarget(trigger);

            if(!modal){
                alert('Modal tidak ditemukan. Periksa ID modal dan target tombol.');
                return false;
            }

            openModal(modal);
            return false;
        }

        var closeBtn = e.target.closest('[data-bs-dismiss="modal"], [data-dismiss="modal"], [data-tu-dismiss="modal"], .btn-close');

        if(closeBtn){
            e.preventDefault();
            e.stopPropagation();

            var activeModal = closeBtn.closest('.modal');

            closeModal(activeModal);
            return false;
        }

        if(e.target.classList && e.target.classList.contains('modal')){
            closeModal(e.target);
            return false;
        }

    }, true);

    document.addEventListener('keydown', function(e){
        if(e.key === 'Escape'){
            document.querySelectorAll('.modal.show').forEach(function(modal){
                closeModal(modal);
            });
        }
    });

})();
</script>
<?php endif; ?>

<script>
/*
 * Penyesuaian tinggi bottom nav.
 * Ini mencegah konten terakhir tertutup menu bawah di HP.
 */
(function(){
    function syncBottomNavSpace(){
        var nav = document.querySelector('.mobile-bottom-nav');

        if(!nav){
            return;
        }

        var height = Math.ceil(nav.getBoundingClientRect().height || 86);

        document.documentElement.style.setProperty('--labsys-bottom-nav-height', height + 'px');
    }

    document.addEventListener('DOMContentLoaded', syncBottomNavSpace);
    window.addEventListener('resize', syncBottomNavSpace);
    window.addEventListener('orientationchange', function(){
        setTimeout(syncBottomNavSpace, 250);
    });
})();
</script>

</body>
</html>
