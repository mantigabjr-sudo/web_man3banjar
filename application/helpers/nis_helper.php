<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if(!function_exists('madrasah_profile')){
    function madrasah_profile(){

        $CI =& get_instance();

        if($CI->db->table_exists('website_profil')){
            return $CI->db->get('website_profil')->row();
        }

        return null;
    }
}

if(!function_exists('madrasah_nsm')){
    function madrasah_nsm(){

        $profile = madrasah_profile();

        if($profile && !empty($profile->nsm)){
            return preg_replace('/\D/', '', $profile->nsm);
        }

        return '131163030003';
    }
}

if(!function_exists('madrasah_npsn')){
    function madrasah_npsn(){

        $profile = madrasah_profile();

        if($profile && !empty($profile->npsn)){
            return $profile->npsn;
        }

        return '-';
    }
}

if(!function_exists('nis_pendek')){
    function nis_pendek($nis){

        $nis = preg_replace('/\D/', '', (string)$nis);

        if($nis === ''){
            return '';
        }

        if(strlen($nis) > 4){
            return substr($nis, -4);
        }

        return str_pad($nis, 4, '0', STR_PAD_LEFT);
    }
}

if(!function_exists('tahun_nis_dua_digit')){
    function tahun_nis_dua_digit($tanggal_masuk = null, $tahun_ajaran = null){

        if(!empty($tanggal_masuk) && $tanggal_masuk != '0000-00-00'){
            $time = strtotime($tanggal_masuk);

            if($time !== false){
                return date('y', $time);
            }
        }

        if(!empty($tahun_ajaran)){
            if(preg_match('/20[0-9]{2}/', $tahun_ajaran, $match)){
                return substr($match[0], -2);
            }
        }

        return date('y');
    }
}

if(!function_exists('nis_lengkap')){
    function nis_lengkap($nis, $tanggal_masuk = null, $tahun_ajaran = null){

        $nis_pendek = nis_pendek($nis);

        if($nis_pendek === ''){
            return '-';
        }

        return madrasah_nsm()
            .tahun_nis_dua_digit($tanggal_masuk, $tahun_ajaran)
            .$nis_pendek;
    }
}

if(!function_exists('nis_lengkap_siswa')){
    function nis_lengkap_siswa($siswa){

        if(!$siswa){
            return '-';
        }

        return nis_lengkap(
            $siswa->nis ?? '',
            $siswa->tanggal_masuk ?? null,
            $siswa->tahun_ajaran_masuk ?? null
        );
    }
}

if(!function_exists('generate_nis_baru')){
    function generate_nis_baru(){

        $CI =& get_instance();

        $row = $CI->db
            ->select('MAX(CAST(RIGHT(nis, 4) AS UNSIGNED)) AS nis_terakhir', false)
            ->where('nis IS NOT NULL', null, false)
            ->where('nis !=', '')
            ->get('siswa')
            ->row();

        $terakhir = 0;

        if($row && !empty($row->nis_terakhir)){
            $terakhir = (int)$row->nis_terakhir;
        }

        $baru = $terakhir + 1;

        while(true){

            $nis_baru = str_pad($baru, 4, '0', STR_PAD_LEFT);

            $cek = $CI->db
                ->group_start()
                    ->where('nis', $nis_baru)
                    ->or_where('RIGHT(nis, 4) = '.$CI->db->escape($nis_baru), null, false)
                ->group_end()
                ->get('siswa')
                ->row();

            if(!$cek){
                return $nis_baru;
            }

            $baru++;
        }
    }
}