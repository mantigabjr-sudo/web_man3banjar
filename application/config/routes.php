<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'home';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

// Dashboard & Admin Portal
$route['dashboard'] = 'dashboard';
$route['admin']     = 'dashboard';
$route['admin_web'] = 'dashboard';

// Berita Management
$route['berita']                             = 'berita';
$route['berita/add']                         = 'berita/add';
$route['berita/detail/(:num)']               = 'berita/detail/$1';
$route['berita/publish/(:num)']              = 'berita/publish/$1';
$route['berita/draft/(:num)']                = 'berita/draft/$1';
$route['berita/edit/(:num)']                 = 'berita_admin_actions/edit/$1';
$route['berita/update/(:num)']               = 'berita_admin_actions/update/$1';
$route['berita/delete/(:num)']               = 'berita_admin_actions/delete/$1';
$route['berita/delete_gambar/(:num)']        = 'berita_admin_actions/delete_gambar/$1';
$route['berita/regenerate_pamflet/(:num)']   = 'berita_admin_actions/regenerate_pamflet/$1';
$route['berita/download_pamflet/(:num)']     = 'berita_admin_actions/download_pamflet/$1';

// Admin Banner Slider & Organigram
$route['admin_banner']                       = 'admin_banner';
$route['admin_banner/(:any)']                = 'admin_banner/$1';
$route['admin_struktur']                     = 'admin_struktur';
$route['admin_struktur/(:any)']              = 'admin_struktur/$1';

// PMB (Penerimaan Murid Baru) & PPDB Public
$route['pmb'] = 'ppdb';
$route['pmb/(:any)'] = 'ppdb/$1';
$route['ppdb'] = 'ppdb';
$route['ppdb/(:any)'] = 'ppdb/$1';

// Admin PMB & PPDB
$route['admin_pmb'] = 'admin_ppdb';
$route['admin_pmb/(:any)'] = 'admin_ppdb/$1';

// Live Monitoring KBM & Data Siswa
$route['monitoring_kbm']        = 'website/monitoring_kbm';
$route['jadwal_live']           = 'website/monitoring_kbm';
$route['data_siswa']            = 'website/data_siswa';
$route['website/data_siswa']    = 'website/data_siswa';
$route['keadaan_siswa']         = 'website/data_siswa';
$route['alumni']                = 'website/alumni';
$route['website/alumni']        = 'website/alumni';
$route['kontak']                = 'website/kontak';
$route['website/kontak']        = 'website/kontak';
$route['hubungi_kami']          = 'website/kontak';

// REST API Sync ke LabSys Server Lokal (Two-Way Sync)
$route['api/sync/status']          = 'api_sync/status';
$route['api/sync/berita']          = 'api_sync/sync_berita';
$route['api/sync/website']         = 'api_sync/sync_website';
$route['api/sync/ptk']             = 'api_sync/sync_ptk';
$route['api/sync/kbm']             = 'api_sync/sync_kbm';
$route['api/sync/siswa']           = 'api_sync/sync_siswa';
$route['api/sync/ppdb_settings']   = 'api_sync/sync_ppdb_settings';
$route['api/sync/ppdb_status']     = 'api_sync/sync_ppdb_status';
$route['api/sync/pull_website']    = 'api_sync/pull_website';
$route['api/sync/pull_berita']     = 'api_sync/pull_berita';
$route['api/sync/pull_ppdb']       = 'api_sync/pull_ppdb';
$route['api/sync/get_existing_foto_mentah'] = 'api_sync/get_existing_foto_mentah';
$route['api/sync/upload_foto_mentah']       = 'api_sync/upload_foto_mentah';
$route['api/sync/pull_foto_verified']       = 'api_sync/pull_foto_verified';
$route['api/sync/push_foto_verified']       = 'api_sync/push_foto_verified';

$route['api/ppdb/sync'] = 'api_ppdb/sync_pendaftar';
$route['api/ppdb/confirm_sync'] = 'api_ppdb/confirm_sync';
$route['api/ppdb/stats'] = 'api_ppdb/stats';


