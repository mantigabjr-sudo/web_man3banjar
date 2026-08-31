/* assets/js/admin_jadwal.js */
$(document).ready(function() {
    // Inisialisasi Select2
    $('.select2').select2({
        width: '100%'
    });

    // Filter jadwal berdasarkan hari
    $('#filter-hari').on('change', function() {
        var hari = $(this).val();
        if (hari === '') {
            $('.jadwal-card').show();
        } else {
            $('.jadwal-card').hide();
            $('.jadwal-card[data-hari="' + hari + '"]').show();
        }
    });

    // Handle export excel frontend
    $('#btn-export-excel').on('click', function() {
        let csv = 'Hari,Jam Ke,Jam Mulai,Jam Selesai,Guru,Mapel,Kelas\n';
        
        $('.jadwal-card').each(function() {
            // Hanya ambil data yang terlihat jika difilter
            if ($(this).is(':visible')) {
                let hari = $(this).find('.data-hari').text();
                let jam_ke = $(this).find('.data-jam-ke').text();
                let jam_mulai = $(this).find('.data-jam-mulai').text();
                let jam_selesai = $(this).find('.data-jam-selesai').text();
                let guru = $(this).find('.data-guru').text();
                let mapel = $(this).find('.data-mapel').text();
                let kelas = $(this).find('.data-kelas').text();

                // Bersihkan koma agar format csv tidak rusak
                guru = guru.replace(/,/g, '');
                mapel = mapel.replace(/,/g, '');
                kelas = kelas.replace(/,/g, '');

                csv += `${hari},${jam_ke},${jam_mulai},${jam_selesai},${guru},${mapel},${kelas}\n`;
            }
        });

        // Download CSV
        const blob = new Blob([csv], { type: 'text/csv' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.setAttribute('hidden', '');
        a.setAttribute('href', url);
        a.setAttribute('download', 'Jadwal_Mengajar.csv');
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
    });
});
