<?php

function tanggal_indo($tanggal){

    if(empty($tanggal) || $tanggal == '0000-00-00'){
        return '-';
    }

    $bulan = [
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember'
    ];

    $pecah = explode('-', $tanggal);

    return intval($pecah[2]).' '.$bulan[intval($pecah[1])].' '.$pecah[0];
}

function tanggal_jam_indo($datetime){

    if(empty($datetime)){
        return '-';
    }

    $tanggal = date('Y-m-d', strtotime($datetime));
    $jam = date('H:i', strtotime($datetime));

    return tanggal_indo($tanggal).' '.$jam;
}