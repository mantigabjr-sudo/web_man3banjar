<style>
.admin-ppdb-detail{
    max-width:1400px;
    margin:0 auto;
}

.admin-detail-hero{
    position:relative;
    overflow:hidden;
    background:
        radial-gradient(circle at top right, rgba(250,204,21,.20), transparent 30%),
        radial-gradient(circle at bottom left, rgba(34,197,94,.18), transparent 34%),
        linear-gradient(135deg,#064e3b,#15803d 56%,#22c55e);
    border-radius:30px;
    padding:26px;
    color:white;
    box-shadow:0 22px 60px rgba(22,163,74,.22);
    margin-bottom:20px;
}

.admin-detail-hero:after{
    content:"";
    position:absolute;
    right:-80px;
    top:-80px;
    width:230px;
    height:230px;
    border-radius:50%;
    background:rgba(255,255,255,.10);
}

.admin-detail-hero-inner{
    position:relative;
    z-index:2;
    display:grid;
    grid-template-columns:150px minmax(0,1fr) auto;
    gap:22px;
    align-items:center;
}

.admin-profile-photo,
.admin-profile-empty{
    width:145px;
    height:185px;
    border-radius:28px;
    border:5px solid rgba(255,255,255,.28);
    box-shadow:0 18px 45px rgba(15,23,42,.22);
}

.admin-profile-photo{
    object-fit:cover;
    background:#ecfdf5;
}

.admin-profile-empty{
    background:rgba(255,255,255,.14);
    color:rgba(255,255,255,.82);
    display:flex;
    align-items:center;
    justify-content:center;
    text-align:center;
    font-size:13px;
    font-weight:850;
    padding:14px;
}

.admin-hero-info h2{
    font-weight:950;
    letter-spacing:-.5px;
    margin:0 0 8px;
}

.admin-hero-meta{
    display:flex;
    flex-wrap:wrap;
    gap:9px;
    color:rgba(255,255,255,.78);
    font-size:14px;
    font-weight:750;
    margin-bottom:12px;
}

.admin-status-row{
    display:flex;
    gap:8px;
    flex-wrap:wrap;
}

.admin-pill{
    display:inline-flex;
    align-items:center;
    padding:8px 12px;
    border-radius:999px;
    font-size:12px;
    font-weight:950;
}

.pill-green{
    background:#dcfce7;
    color:#166534;
}

.pill-red{
    background:#fee2e2;
    color:#991b1b;
}

.pill-orange{
    background:#ffedd5;
    color:#9a3412;
}

.pill-blue{
    background:#dbeafe;
    color:#1d4ed8;
}

.pill-gray{
    background:#f1f5f9;
    color:#475569;
}

.admin-hero-mini{
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:10px;
    margin-top:16px;
    max-width:740px;
}

.admin-mini-item{
    padding:12px;
    border-radius:18px;
    background:rgba(255,255,255,.12);
    border:1px solid rgba(255,255,255,.18);
}

.admin-mini-item small{
    display:block;
    color:rgba(255,255,255,.70);
    font-size:11px;
    font-weight:850;
    margin-bottom:4px;
}

.admin-mini-item strong{
    color:white;
    font-weight:950;
}

.admin-action-panel{
    display:grid;
    grid-template-columns:1fr;
    gap:8px;
    min-width:190px;
}

.admin-action-btn{
    min-height:40px;
    padding:0 13px;
    border-radius:14px;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:12px;
    font-weight:950;
    text-decoration:none;
    border:0;
    white-space:nowrap;
}

.action-white{
    background:white;
    color:#166534;
}

.action-yellow{
    background:#facc15;
    color:#422006;
}

.action-blue{
    background:#dbeafe;
    color:#1d4ed8;
}

.action-red{
    background:#fee2e2;
    color:#991b1b;
}

.action-soft{
    background:rgba(255,255,255,.14);
    color:white;
    border:1px solid rgba(255,255,255,.23);
}

.action-white:hover{color:#166534;}
.action-yellow:hover{color:#422006;}
.action-blue:hover{color:#1d4ed8;}
.action-red:hover{color:#991b1b;}
.action-soft:hover{color:white;background:rgba(255,255,255,.18);}

.admin-detail-layout{
    display:grid;
    grid-template-columns:minmax(0,1fr) 380px;
    gap:20px;
    align-items:start;
}

.admin-card{
    background:white;
    border:1px solid #e2e8f0;
    border-radius:26px;
    box-shadow:0 16px 42px rgba(15,23,42,.07);
    overflow:hidden;
    margin-bottom:18px;
}

.admin-card-head{
    padding:18px 20px;
    border-bottom:1px solid #e2e8f0;
    background:
        radial-gradient(circle at top right, rgba(34,197,94,.08), transparent 34%),
        #ffffff;
}

.admin-card-head h5{
    color:#14532d;
    font-size:18px;
    font-weight:950;
    margin:0;
}

.admin-card-head small{
    color:#64748b;
    font-size:12px;
    font-weight:750;
}

.admin-card-body{
    padding:20px;
}

.admin-info-grid{
    display:grid;
    grid-template-columns:repeat(2,1fr);
    gap:12px;
}

.admin-info-item{
    background:#f8fafc;
    border:1px solid #e2e8f0;
    border-radius:18px;
    padding:14px;
}

.admin-info-item.full{
    grid-column:1 / -1;
}

.admin-info-item small{
    display:block;
    color:#64748b;
    font-size:12px;
    font-weight:850;
    margin-bottom:5px;
}

.admin-info-item strong{
    display:block;
    color:#0f172a;
    font-weight:950;
    line-height:1.45;
}

.admin-file-grid{
    display:grid;
    grid-template-columns:repeat(2,1fr);
    gap:13px;
}

.admin-file-card{
    border:1px solid #e2e8f0;
    border-radius:20px;
    background:#f8fafc;
    padding:14px;
    min-height:120px;
    display:flex;
    flex-direction:column;
    justify-content:space-between;
    gap:12px;
}

.admin-file-top{
    display:flex;
    gap:12px;
    align-items:flex-start;
}

.admin-file-icon{
    width:42px;
    height:42px;
    border-radius:16px;
    background:#ecfdf5;
    color:#166534;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:12px;
    font-weight:950;
    flex-shrink:0;
}

.admin-file-card strong{
    display:block;
    color:#0f172a;
    font-weight:950;
    margin-bottom:4px;
}

.admin-file-card small{
    color:#64748b;
    font-size:12px;
    font-weight:750;
}

.admin-file-action{
    min-height:36px;
    border-radius:13px;
    display:flex;
    align-items:center;
    justify-content:center;
    padding:0 12px;
    font-size:12px;
    font-weight:950;
    text-decoration:none;
}

.file-open{
    background:#dcfce7;
    color:#166534;
}

.file-missing{
    background:#f1f5f9;
    color:#64748b;
}

.file-open:hover{
    color:#166534;
    background:#bbf7d0;
}

.sidebar-stack{
    position:sticky;
    top:90px;
}

.admin-progress-label{
    display:flex;
    justify-content:space-between;
    gap:10px;
    color:#334155;
    font-size:13px;
    font-weight:900;
    margin-bottom:8px;
}

.admin-progress-track{
    height:10px;
    border-radius:999px;
    background:#f1f5f9;
    overflow:hidden;
    margin-bottom:16px;
}

.admin-progress-track span{
    display:block;
    height:100%;
    border-radius:999px;
    background:linear-gradient(135deg,#16a34a,#22c55e);
}

.admin-side-list{
    display:grid;
    gap:11px;
}

.admin-side-item{
    background:#f8fafc;
    border:1px solid #e2e8f0;
    border-radius:18px;
    padding:14px;
}

.admin-side-item small{
    display:block;
    color:#64748b;
    font-size:12px;
    font-weight:850;
    margin-bottom:5px;
}

.admin-side-item strong{
    color:#0f172a;
    font-weight:950;
}

.admin-zone-danger{
    background:#fff7ed;
    border:1px solid #fed7aa;
    border-radius:22px;
    padding:16px;
}

.admin-zone-danger h6{
    color:#9a3412;
    font-weight:950;
}

.admin-zone-danger p{
    color:#9a3412;
    font-size:13px;
    font-weight:700;
    line-height:1.55;
}

.admin-delete-btn{
    min-height:42px;
    border-radius:14px;
    background:#fee2e2;
    color:#991b1b;
    display:flex;
    align-items:center;
    justify-content:center;
    font-weight:950;
    text-decoration:none;
}

.admin-delete-btn:hover{
    color:#991b1b;
    background:#fecaca;
}

.admin-password-box{
    background:#fffbeb;
    border:1px solid #fde68a;
    color:#92400e;
    border-radius:18px;
    padding:14px;
    margin-bottom:16px;
    font-weight:800;
}

.admin-password-box small{
    display:block;
    margin-top:8px;
    color:#92400e;
    font-weight:700;
}

.admin-copy-group{
    display:flex;
    gap:8px;
    margin-top:10px;
}

.admin-copy-group input{
    border-radius:13px;
    border:1px solid #fcd34d;
    font-weight:900;
}

.admin-copy-group button{
    border-radius:13px;
    font-weight:950;
}

.admin-alert{
    border:0;
    border-radius:18px;
    font-weight:800;
}

@media(max-width:1200px){
    .admin-detail-hero-inner{
        grid-template-columns:150px minmax(0,1fr);
    }

    .admin-action-panel{
        grid-column:1 / -1;
        grid-template-columns:repeat(3,1fr);
    }

    .admin-detail-layout{
        grid-template-columns:1fr;
    }

    .sidebar-stack{
        position:static;
    }
}

@media(max-width:768px){
    .admin-detail-hero,
    .admin-card{
        border-radius:22px;
    }

    .admin-detail-hero{
        padding:22px;
    }

    .admin-detail-hero-inner{
        grid-template-columns:1fr;
        text-align:center;
    }

    .admin-profile-photo,
    .admin-profile-empty{
        margin:0 auto;
        width:130px;
        height:165px;
        border-radius:24px;
    }

    .admin-hero-meta,
    .admin-status-row{
        justify-content:center;
    }

    .admin-hero-mini,
    .admin-action-panel,
    .admin-info-grid,
    .admin-file-grid{
        grid-template-columns:1fr;
    }

    .admin-card-body{
        padding:16px;
    }

    .admin-copy-group{
        display:grid;
        grid-template-columns:1fr;
    }
}
</style>