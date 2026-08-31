<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'home';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['api/ppdb/sync'] = 'api_ppdb/sync_pendaftar';
$route['api/ppdb/confirm_sync'] = 'api_ppdb/confirm_sync';
$route['api/ppdb/stats'] = 'api_ppdb/stats';
