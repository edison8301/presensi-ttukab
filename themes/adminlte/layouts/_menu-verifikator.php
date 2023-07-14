<?php

use app\models\UserRole;

?>

<?= \dmstr\widgets\Menu::widget([
        'options' => ['class' => 'sidebar-menu', 'data-widget' => 'tree'],
        'items' => [
            ['label' => 'ABSENSI', 'options' => ['class' => 'header']],
            ['label' => 'Dashboard', 'icon' => 'dashboard', 'url' => ['/absensi/dasbor/index']],
            ['label' => 'Rekap Aktivitas SKPD', 'icon' => 'bank', 'url' => ['/absensi/instansi/index']],
            ['label' => 'Rekap Absensi Pegawai', 'icon' => 'user', 'url' => ['/absensi/pegawai/index']],

            ['label' => 'Ketidakhadiran Kerja', 'icon' => 'list', 'url' => ['/absensi/ketidakhadiran/index']],
            ['label' => 'Ketidakhadiran Kegiatan', 'icon' => 'list', 'items'=> [
                ['label'=>'Upacara', 'url' => ['/absensi/ketidakhadiran-kegiatan/index','id_ketidakhadiran_kegiatan_jenis'=>1]],
                ['label'=>'Senam', 'url' => ['/absensi/ketidakhadiran-kegiatan/index','id_ketidakhadiran_kegiatan_jenis'=>2]],
                ['label'=>'Apel Pagi', 'url' => ['/absensi/ketidakhadiran-kegiatan/index','id_ketidakhadiran_kegiatan_jenis'=>3]],
                ['label'=>'Apel Sore', 'url' => ['/absensi/ketidakhadiran-kegiatan/index','id_ketidakhadiran_kegiatan_jenis'=>4]],
            ]],
            ['label' => 'Ketidakhadiran Panjang', 'icon' => 'list', 'url' => ['/absensi/ketidakhadiran-panjang/index']],

            ['label' => 'KINERJA', 'options' => ['class' => 'header']],
            ['label' => 'Dashboard', 'icon' => 'dashboard', 'url' => ['/kinerja/dasbor/index']],
            ['label' => 'Pegawai', 'icon' => 'user', 'url' => ['/kinerja/pegawai/index']],
            ['label' => 'Keg. Tahunan', 'icon' => 'list', 'items'=> [
                ['label'=>'Daftar Kegiatan', 'url' => ['/kinerja/kegiatan-tahunan/index']],
                ['label'=>'Daftar SKP', 'url' => ['/kinerja/kegiatan-tahunan/skp']],
            ]],
            ['label' => 'Keg. Bulanan', 'icon' => 'list', 'url' => ['/kinerja/kegiatan-bulanan/index']],
            ['label' => 'Keg. Harian', 'icon' => 'list', 'url' => ['/kinerja/kegiatan-harian/index']],
            ['label' => 'Rekap SKP & CKHP', 'icon' => 'file', 'url' => ['/kinerja/kegiatan-harian/rekap']],
            ['label' => 'Tunjangan', 'options' => ['class' => 'header']],
            ['label' => 'Dashboard', 'icon' => 'dashboard', 'url' => ['/tunjangan/dasbor/admin']],

            //['label' => 'MENU TUNJANGAN', 'options' => ['class' => 'header']],
            //['label' => 'Dashboard', 'icon' => 'home', 'url' => ['/site/index']],
            //['label' => 'ICLOCK', 'options' => ['class' => 'header']],
            //['label' => 'Checkinout', 'icon' => 'home', 'url' => ['/iclock/checkinout/index']],
            ['label' => 'MENU SISTEM', 'options' => ['class' => 'header']],
            ['label' => 'Perangkat Daerah', 'icon' => 'building', 'url' => ['/instansi/index']],
            ['label' => 'Pegawai', 'icon' => 'users', 'url' => ['/pegawai/index']],
            ['label' => 'Ganti Password', 'icon' => 'lock', 'url' => ['/user/change-password']],
            ['label' => 'Logout', 'url' => ['/site/logout'], 'icon' => 'power-off', 'template' => '<a href="{url}" data-method="post">{icon} {label}</a>' , 'visible' => !Yii::$app->user->isGuest],

        ],
]) ?>
