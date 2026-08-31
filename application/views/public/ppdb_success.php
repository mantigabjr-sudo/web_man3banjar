<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Pendaftaran Berhasil</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body style="background:#f0fdf4;">

<div class="container py-5">

<div class="card p-5 text-center" id="buktiCard">

<h2 class="text-success">
PMB MAN 3 Banjar
</h2>
<h2 class="text-success">
Pendaftaran Berhasil
</h2>

<p>No Pendaftaran:</p>
<h4><?= $no_pendaftaran ?></h4>

<p>Username Login (NISN):</p>
<h4><?= $username ?></h4>

<p>Password:</p>
<h4><?= $password ?></h4>

<p class="text-muted">
Simpan data ini untuk melengkapi biodata.
</p>

<a href="<?= base_url() ?>" class="btn btn-success">
Kembali
</a>
<button onclick="downloadBukti()" class="btn btn-primary mt-3">
Download Bukti Pendaftaran
</button>
</div>

</div>

</body>
<script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
<script>
function downloadBukti(){

    html2canvas(document.querySelector("#buktiCard")).then(canvas => {

        let link = document.createElement('a');
        link.download = 'bukti-pendaftaran.png';
        link.href = canvas.toDataURL();
        link.click();

    });

}
</script>
</html>