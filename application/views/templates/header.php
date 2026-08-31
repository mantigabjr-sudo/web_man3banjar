<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LabSys</title>
	<meta name="theme-color" content="#16a34a">
	<link rel="manifest" href="<?= base_url('manifest.json') ?>">
	<link rel="icon" type="image/png" href="<?= base_url('assets/img/favicon.png') ?>">
	<link rel="shortcut icon" type="image/png" href="<?= base_url('assets/img/favicon.png') ?>">
	<link rel="apple-touch-icon" href="<?= base_url('assets/img/favicon.png') ?>">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
	<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
	<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/berita-admin-polish.css?v=1') ?>">
	
   <style>

body{
    background: linear-gradient(135deg,#f8fffb,#eefcf3,#ffffff);
    font-family:'Segoe UI',sans-serif;
    color:#1e293b;
}

.sidebar{
    width:270px;
    height:100vh;
    position:fixed;
    background:rgba(255,255,255,.92);
    backdrop-filter:blur(18px);
    border-right:1px solid rgba(34,197,94,.14);
    box-shadow:0 10px 35px rgba(15,23,42,.08);
    padding:24px 18px;
    overflow-y:auto;
}

.brand{
    font-size:28px;
    font-weight:800;
    text-align:center;
    color:#16a34a;
    margin-bottom:28px;
    letter-spacing:.5px;
}

.menu-section{
    font-size:11px;
    text-transform:uppercase;
    color:#94a3b8;
    font-weight:800;
    margin:18px 12px 8px;
    letter-spacing:.8px;
}

.menu-link,
.menu-toggle{
    width:100%;
    display:flex;
    align-items:center;
    justify-content:space-between;
    padding:13px 16px;
    margin-bottom:7px;
    border-radius:16px;
    color:#334155;
    text-decoration:none;
    border:none;
    background:transparent;
    font-weight:600;
    transition:.25s;
}

.menu-link:hover,
.menu-toggle:hover{
    background:#f0fdf4;
    color:#15803d;
    transform:translateX(4px);
}

.submenu{
    margin:4px 0 10px 12px;
    padding-left:10px;
    border-left:2px solid #dcfce7;
}

.submenu a{
    display:block;
    padding:10px 14px;
    margin-bottom:5px;
    border-radius:14px;
    color:#64748b;
    text-decoration:none;
    font-size:14px;
    font-weight:500;
    transition:.25s;
}

.submenu a:hover{
    background:#ecfdf5;
    color:#15803d;
}

.logout-link{
    background:#fef2f2;
    color:#dc2626;
}

.logout-link:hover{
    background:#fee2e2;
    color:#b91c1c;
}

.content{
    margin-left:270px;
    padding:35px;
}

@media(max-width:768px){
    .sidebar{
        width:100%;
        height:auto;
        position:relative;
        border-right:none;
        border-bottom:1px solid #dcfce7;
        border-radius:0 0 24px 24px;
    }

    .content{
        margin-left:0;
        padding:20px;
    }
}
.active-menu{
    background:#dcfce7 !important;
    color:#15803d !important;
    font-weight:700 !important;
}
.content{
    margin-left:260px;
    padding:35px;
}

.card{
    background:rgba(255,255,255,.72);
    backdrop-filter:blur(14px);
    border:none;
    border-radius:24px;
    box-shadow:0 12px 35px rgba(0,0,0,.06);
    transition:.3s;
}

.card:hover{
    transform:translateY(-5px);
    box-shadow:0 18px 45px rgba(34,197,94,.12);
}

.table{
    color:#334155;
}

.table-success{
    background:#dcfce7 !important;
    color:#166534 !important;
}

.btn-success{
    background:linear-gradient(135deg,#22c55e,#16a34a);
    border:none;
    border-radius:12px;
    box-shadow:0 8px 18px rgba(34,197,94,.18);
}

.btn-success:hover{
    transform:translateY(-2px);
}

.btn-primary{
    border-radius:12px;
}

input,select{
    border-radius:14px !important;
    border:1px solid #d1fae5 !important;
    padding:12px !important;
    background:white !important;
}

input:focus,select:focus{
    border-color:#22c55e !important;
    box-shadow:0 0 0 4px rgba(34,197,94,.12) !important;
}

.glow{
    color:#15803d;
    font-weight:700;
}

.stat-number{
    font-size:42px;
    font-weight:700;
    color:#16a34a;
}

.soft-text{
    color:#64748b;
}

.kurikulum-nav{
    background:linear-gradient(135deg,#ecfdf5,#ffffff);
    border:1px solid #dcfce7;
    border-radius:28px;
    padding:24px;
    box-shadow:0 14px 35px rgba(15,23,42,.06);
}

.nav-card{
    display:block;
    text-decoration:none;
    background:#ffffff;
    border:1px solid #e2e8f0;
    border-radius:20px;
    padding:18px;
    height:100%;
    transition:.25s;
    color:#334155;
}

.nav-card:hover{
    transform:translateY(-4px);
    border-color:#86efac;
    box-shadow:0 16px 35px rgba(34,197,94,.12);
    color:#15803d;
}

.nav-title{
    font-weight:800;
    font-size:16px;
    margin-bottom:4px;
}

.nav-desc{
    font-size:13px;
    color:#64748b;
}
</style>

</head>
<body>