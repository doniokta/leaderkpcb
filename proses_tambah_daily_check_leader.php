<?php
// proses_tambah_daily_check_leader.php

// Ambil data dari form
$unit = $_POST['unit'];
$engineer = $_POST['engineer'];
$date = $_POST['date'];
$duration = $_POST['duration'];
$umpc = $_POST['umpc'];
$gps = $_POST['gps'];
$vhms = $_POST['vhms'];
$plm = $_POST['plm'];
$lan = $_POST['lan'];
$issue = $_POST['issue'];
$solved = $_POST['solved'];

// Proses penyimpanan data (misalnya simpan ke database, tapi di sini hanya sebagai contoh)
$data = [
    'unit' => $unit,
    'engineer' => $engineer,
    'date' => $date,
    'duration' => $duration,
    'umpc' => $umpc,
    'gps' => $gps,
    'vhms' => $vhms,
    'plm' => $plm,
    'lan' => $lan,
    'issue' => $issue,
    'solved' => $solved
];

// Anda dapat menyimpan data ke database di sini.

// Redirect kembali ke daily_check_leader.php setelah data disubmit
header("Location: daily_check_leader.php");
exit();
