<?php
function tgl_indo($tanggal){
    if(empty($tanggal) || $tanggal == '0000-00-00'){
        return '-';
    }

    $bulan = [
        1=>'Januari','Februari','Maret','April','Mei','Juni',
        'Juli','Agustus','September','Oktober','November','Desember'
    ];

    $split = explode('-', $tanggal);

    if(count($split) < 3 || (int)$split[1] == 0){
        return '-';
    }

    // 🔥 FIX: pakai str_pad biar selalu 2 digit
    $hari = str_pad($split[2], 2, '0', STR_PAD_LEFT);

    return $hari.' '.$bulan[(int)$split[1]].' '.$split[0];
}