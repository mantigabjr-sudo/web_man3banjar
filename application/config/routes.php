<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'home';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

// PMB (Penerimaan Murid Baru) & PPDB Public
$route['pmb'] = 'ppdb';
$route['pmb/(:any)'] = 'ppdb/$1';
$route['ppdb'] = 'ppdb';
$route['ppdb/(:any)'] = 'ppdb/$1';

// Admin PMB & PPDB
$route['admin_pmb'] = 'admin_ppdb';
$route['admin_pmb/(:any)'] = 'admin_ppdb/$1';

// Live Monitoring KBM
$route['monitoring_kbm'] = 'website/monitoring_kbm';
$route['jadwal_live'] = 'website/monitoring_kbm';

// REST API Sync ke LabSys Server Lokal (Two-Way Sync)
$route['api/sync/status']       = 'api_sync/status';
$route['api/sync/berita']       = 'api_sync/sync_berita';
$route['api/sync/website']      = 'api_sync/sync_website';
$route['api/sync/ptk']          = 'api_sync/sync_ptk';
$route['api/sync/kbm']          = 'api_sync/sync_kbm';
$route['api/sync/pull_website'] = 'api_sync/pull_website';
$route['api/sync/pull_berita']  = 'api_sync/pull_berita';

$route['api/ppdb/sync'] = 'api_ppdb/sync_pendaftar';
$route['api/ppdb/confirm_sync'] = 'api_ppdb/confirm_sync';
$route['api/ppdb/stats'] = 'api_ppdb/stats';
