<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'home';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

// PMB (Penerimaan Murid Baru) & PPDB
$route['pmb'] = 'ppdb';
$route['pmb/(:any)'] = 'ppdb/$1';
$route['ppdb'] = 'ppdb';
$route['ppdb/(:any)'] = 'ppdb/$1';

// REST API Sync ke LabSys Server Lokal
$route['api/ppdb/sync'] = 'api_ppdb/sync_pendaftar';
$route['api/ppdb/confirm_sync'] = 'api_ppdb/confirm_sync';
$route['api/ppdb/stats'] = 'api_ppdb/stats';
