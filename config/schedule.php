<?php

/**
 * @var \omnilight\scheduling\Schedule $schedule
 */

$schedule->command('hello/schedule')
    ->everyNMinutes(60);

$schedule->command('rekap-instansi-bulan/update-jumlah-pegawai-potongan-ckhp')
    ->dailyAt('01:00');

$schedule->command('rekap-instansi-bulan/update-jumlah-pegawai-potongan-iki')
    ->dailyAt('02:00');

$schedule->command('rekap-pegawai-bulan/update-rekap-pembayaran')
    ->dailyAt('02:00');

$schedule->command('rekap-pegawai-bulan/update-rekap-pembayaran --bulan=mundur')
    ->dailyAt('03:00');

?>
