<?php

use app\components\Session;
use app\models\PegawaiNonTppJenis;
use app\models\UserRole;
use app\modules\kinerja\models\KegiatanStatus;
use dmstr\widgets\Menu;

/* @var $this \yii\web\View */



if(Yii::$app->params['mode']=='tukin') {

    echo Menu::widget([
        'options' => ['class' => 'sidebar-menu', 'data-widget' => 'tree'],
        'items' => [
            ['label' => 'ABSENSI', 'options' => ['class' => 'header']],
            ['label' => 'Kegiatan', 'icon' => 'folder', 'url' => ['/kegiatan/index']],
            ['label' => 'Perangkat Daerah', 'icon' => 'folder', 'url' => ['/instansi/index-kegiatan']],
            ['label' => 'Ketidakhadiran', 'icon' => 'folder', 'url' => ['/kegiatan-ketidakhadiran/index']],
            ['label' => 'MENU SISTEM', 'options' => ['class' => 'header']],
            ['label' => 'Perangkat Daerah', 'icon' => 'building', 'url' => ['/instansi/index']],
            ['label' => 'Peta', 'icon' => 'map-marker', 'items' => [
                ['label' => 'Perangkat Daerah', 'icon' => 'circle-o', 'url' => ['/peta/index', 'mode' => 'instansi']],
                ['label' => 'Pegawai', 'icon' => 'circle-o', 'url' => ['/peta/index', 'mode' => 'pegawai']],
                ['label' => 'Kegiatan', 'icon' => 'circle-o', 'url' => ['/peta/index', 'mode' => 'kegiatan']],
            ]],
            ['label' => 'Pegawai', 'icon' => 'user', 'url' => ['/pegawai/index']],
            ['label' => 'Mutasi dan Promosi', 'icon' => 'refresh', 'url' => ['/instansi-pegawai/index']],
            ['label' => 'User', 'icon' => 'user', 'items' => [
                ['label' => 'Admin', 'url' => ['/user/index', 'id_user_role' => UserRole::ADMIN]],
                ['label' => 'Pegawai', 'url' => ['/user/index', 'id_user_role' => UserRole::PEGAWAI]],
                ['label' => 'Perangkat Daerah', 'url' => ['/user/index', 'id_user_role' => UserRole::INSTANSI]],
            ]],
            ['label' => 'Ganti Password', 'icon' => 'lock', 'url' => ['/user/change-password']],
            ['label' => 'Logout', 'url' => ['/site/logout'], 'icon' => 'power-off', 'template' => '<a href="{url}" data-method="post">{icon} {label}</a>', 'visible' => !Yii::$app->user->isGuest],

        ],
    ]);

}

if(Yii::$app->params['mode']=='skp-ckhp') {

    echo Menu::widget([
        'options' => ['class' => 'sidebar-menu', 'data-widget' => 'tree'],
        'items' => [
            ['label' => 'KINERJA', 'options' => ['class' => 'header']],
            ['label' => 'Dashboard', 'icon' => 'dashboard', 'url' => ['/kinerja/dasbor/index']],
            ['label' => 'Rekap Kinerja', 'icon' => 'file-o', 'url' => ['/kinerja/pegawai-rekap-kinerja/index']],
            ['label' => 'Pegawai', 'icon' => 'user', 'url' => ['/kinerja/pegawai/index']],
            ['label' => 'Keg. Tahunan', 'icon' => 'list', 'items' => [
                ['label' => 'Daftar Kegiatan', 'url' => ['/kinerja/kegiatan-tahunan/index']],
                ['label' => 'Daftar SKP', 'url' => ['/kinerja/instansi-pegawai/view']],
            ]],
            ['label' => 'Keg. Bulanan', 'icon' => 'list', 'url' => ['/kinerja/kegiatan-bulanan/index']],
            ['label' => 'Keg. Harian', 'icon' => 'list', 'url' => ['/kinerja/kegiatan-harian/index']],
            ['label' => 'Rekap SKP & CKHP', 'icon' => 'file', 'url' => ['/kinerja/kegiatan-harian/rekap']],
            /*['label' => 'Tunjangan', 'options' => ['class' => 'header']],
            ['label' => 'Dashboard', 'icon' => 'dashboard', 'url' => ['/tunjangan/dasbor/admin']],*/

            //['label' => 'MENU TUNJANGAN', 'options' => ['class' => 'header']],
            //['label' => 'Dashboard', 'icon' => 'home', 'url' => ['/site/index']],
            //['label' => 'ICLOCK', 'options' => ['class' => 'header']],
            //['label' => 'Checkinout', 'icon' => 'home', 'url' => ['/iclock/checkinout/index']],
            ['label' => 'MENU SISTEM', 'options' => ['class' => 'header']],
            ['label' => 'Pengumuman', 'icon' => 'bullhorn', 'url' => ['/pengumuman/index']],
            ['label' => 'Pengaturan', 'icon' => 'wrench', 'url' => ['/pengaturan/index']],
            ['label' => 'Perangkat Daerah', 'icon' => 'building', 'url' => ['/instansi/index']],
            ['label' => 'Pegawai', 'icon' => 'user', 'url' => ['/pegawai/index']],
            ['label' => 'Grup Pegawai', 'icon' => 'users', 'url' => ['/grup/index']],
            ['label' => 'Jabatan', 'icon' => 'star', 'url' => ['/jabatan/index']],
            ['label' => 'Mutasi', 'icon' => 'refresh', 'url' => ['/pegawai-instansi/index']],
            ['label' => 'User', 'icon' => 'user', 'items' => [
                ['label' => 'Admin', 'url' => ['/user/index', 'id_user_role' => UserRole::ADMIN]],
                ['label' => 'Pegawai', 'url' => ['/user/index', 'id_user_role' => UserRole::PEGAWAI]],
                ['label' => 'Perangkat Daerah', 'url' => ['/user/index', 'id_user_role' => UserRole::INSTANSI]],
                ['label' => 'Verifikator', 'url' => ['/user/index', 'id_user_role' => UserRole::VERIFIKATOR]],
                ['label' => 'Grup', 'url' => ['/user/index', 'id_user_role' => UserRole::GRUP]],
                ['label' => 'Mapping', 'url' => ['/user/index', 'id_user_role' => UserRole::MAPPING]],
            ]],
            ['label' => 'Ganti Password', 'icon' => 'lock', 'url' => ['/user/change-password']],
            ['label' => 'Logout', 'url' => ['/site/logout'], 'icon' => 'power-off', 'template' => '<a href="{url}" data-method="post">{icon} {label}</a>', 'visible' => !Yii::$app->user->isGuest],

        ],
    ]);

}

if(Yii::$app->params['mode']=='absensi') {

    echo Menu::widget([
        'options' => ['class' => 'sidebar-menu', 'data-widget' => 'tree'],
        'items' => [
            ['label' => 'ABSENSI', 'options' => ['class' => 'header']],
            ['label' => 'Dashboard', 'icon' => 'dashboard', 'url' => ['/absensi/dasbor/index']],
            ['label' => 'Perangkat Daerah', 'icon' => 'bank', 'items' => [
                ['label' => 'Aktivitas Absensi', 'url' => ['/absensi/instansi/index']],
                ['label' => 'Rekap Absensi', 'url' => ['/absensi/instansi-rekap-absensi/index']],
                ['label' => 'Mesin Absensi', 'url' => ['/absensi/instansi/index-mesin-absensi']],
            ]],
            ['label' => 'Pegawai', 'icon' => 'user', 'items' => [
                ['label' => 'Pegawai', 'url' => ['/absensi/pegawai/index']],
                ['label' => 'Rekap Absensi', 'url' => ['/absensi/pegawai-rekap-absensi/index']],
                ['label' => 'Shift Kerja', 'url' => ['/absensi/pegawai/index-shift-kerja']],
                ['label' => 'Absensi Manual', 'url' => ['/absensi/pegawai/index-absensi-manual']],
                ['label' => 'Batas Pengajuan', 'url' => ['/absensi/pegawai/index-batas-pengajuan']],
                ['label' => 'Userinfo', 'url' => ['/absensi/pegawai/index-userinfo']],
                ['label' => 'Fingerprint', 'url' => ['/absensi/pegawai/index-template']],
                ['label' => 'Pegawai Dispensasi', 'url' => ['/absensi/pegawai-dispensasi/index']],
            ]],
            ['label' => 'Monitoring', 'icon' => 'desktop', 'items' => [
                ['label' => 'Izin', 'url' => ['/absensi/pegawai-rekap-absensi/izin']],
                ['label' => 'Sakit', 'url' => ['/absensi/pegawai-rekap-absensi/sakit']],
                ['label' => 'Cuti', 'url' => ['/absensi/pegawai-rekap-absensi/cuti']],
                ['label' => 'Dinas Luar', 'url' => ['/absensi/pegawai-rekap-absensi/dinas-luar']],
                ['label' => 'Tugas Belajar', 'url' => ['/absensi/pegawai-rekap-absensi/tugas-belajar']],
                ['label' => 'Tugas Kedinasan', 'url' => ['/absensi/pegawai-rekap-absensi/tugas-kedinasan']],
                ['label' => 'Alasan Teknis', 'url' => ['/absensi/pegawai-rekap-absensi/alasan-teknis']],
                ['label' => 'Tanpa Keterangan', 'url' => ['/absensi/pegawai-rekap-absensi/tanpa-keterangan']],
            ]],
            ['label' => 'Ketidakhadiran Hari Kerja', 'icon' => 'list', 'url' => ['/absensi/ketidakhadiran/index']],
            ['label' => 'Ketidakhadiran Jam Kerja', 'icon' => 'list', 'url' => ['/absensi/ketidakhadiran-jam-kerja/index']],
            ['label' => 'Ketidakhadiran Kegiatan', 'icon' => 'list', 'items' => [
                ['label' => 'Upacara', 'url' => ['/absensi/ketidakhadiran-kegiatan/index', 'id_ketidakhadiran_kegiatan_jenis' => 1]],
                ['label' => 'Senam', 'url' => ['/absensi/ketidakhadiran-kegiatan/index', 'id_ketidakhadiran_kegiatan_jenis' => 2]],
                ['label' => 'Apel Pagi', 'url' => ['/absensi/ketidakhadiran-kegiatan/index', 'id_ketidakhadiran_kegiatan_jenis' => 3]],
                ['label' => 'Apel Sore', 'url' => ['/absensi/ketidakhadiran-kegiatan/index', 'id_ketidakhadiran_kegiatan_jenis' => 4]],
                ['label' => 'Sidak', 'url' => ['/absensi/ketidakhadiran-kegiatan/index', 'id_ketidakhadiran_kegiatan_jenis' => 5]],
            ]],
            ['label' => 'Ketidakhadiran Panjang', 'icon' => 'list', 'url' => ['/absensi/ketidakhadiran-panjang/index']],
            ['label' => 'Hukuman Disiplin', 'icon' => 'gavel', 'url' => ['/absensi/hukuman-disiplin/index']],
            ['label' => 'Shift Kerja', 'icon' => 'refresh', 'url' => ['/absensi/shift-kerja/index']],
            ['label' => 'Shift Kerja Reguler', 'icon' => 'clock-o', 'url' => ['/absensi/shift-kerja-reguler/index']],
            ['label' => 'Hari Libur', 'icon' => 'calendar', 'url' => ['/absensi/hari-libur/index']],
            ['label' => 'IClock', 'icon' => 'clock-o', 'items' => [
                ['label' => 'Checkinout', 'icon' => 'circle-o', 'url' => ['/iclock/checkinout/index']],
                ['label' => 'Userinfo', 'icon' => 'circle-o', 'url' => ['/iclock/userinfo/index']],
                ['label' => 'Template', 'icon' => 'circle-o', 'url' => ['/iclock/template/index']],
                ['label' => 'IClock', 'icon' => 'circle-o', 'url' => ['/iclock/iclock/index']],
            ]],
            ['label' => 'Upload Presensi', 'icon' => 'wrench', 'items' => [
                ['label' => 'Upload Presensi Manual', 'icon' => 'circle-o', 'url' => ['/absensi/upload-presensi/index']],
                ['label' => 'Daftar Mesin Absen', 'icon' => 'circle-o', 'url' => ['/absensi/mesin-absensi/index']],
            ]],
            ['label' => 'Perawatan', 'icon' => 'wrench', 'items' => [
                ['label' => 'Pegawai Tanpa Userinfo', 'icon' => 'circle-o', 'url' => ['/absensi/perawatan/pegawai-tanpa-userinfo']],
                ['label' => 'Pegawai Tanpa Checkinout', 'icon' => 'circle-o', 'url' => ['/absensi/perawatan/pegawai-tanpa-checkinout']],
                ['label' => 'Pegawai Tanpa Template', 'icon' => 'circle-o', 'url' => ['/absensi/perawatan/pegawai-tanpa-template']],
            ]],
            ['label' => 'KINERJA', 'options' => ['class' => 'header']],
            ['label' => 'Dashboard', 'icon' => 'dashboard', 'url' => ['/kinerja/dasbor/index']],
            ['label' => 'Rekap Kinerja', 'icon' => 'file-o', 'url' => ['/kinerja/pegawai-rekap-kinerja/index']],
            ['label' => 'Pegawai', 'icon' => 'user', 'url' => ['/kinerja/pegawai/index']],
            ['label' => 'Keg. Tahunan', 'icon' => 'list', 'items' => [
                ['label' => 'Daftar Kegiatan', 'url' => ['/kinerja/kegiatan-tahunan/index']],
                ['label' => 'Daftar SKP', 'url' => ['/kinerja/instansi-pegawai/view']],
            ]],
            ['label' => 'Keg. Bulanan', 'icon' => 'list', 'url' => ['/kinerja/kegiatan-bulanan/index']],
            ['label' => 'Keg. Harian', 'icon' => 'list', 'url' => ['/kinerja/kegiatan-harian/index']],
            ['label' => 'Rekap SKP & CKHP', 'icon' => 'file', 'url' => ['/kinerja/kegiatan-harian/rekap']],
            /*['label' => 'Tunjangan', 'options' => ['class' => 'header']],
            ['label' => 'Dashboard', 'icon' => 'dashboard', 'url' => ['/tunjangan/dasbor/admin']],*/

            //['label' => 'MENU TUNJANGAN', 'options' => ['class' => 'header']],
            //['label' => 'Dashboard', 'icon' => 'home', 'url' => ['/site/index']],
            //['label' => 'ICLOCK', 'options' => ['class' => 'header']],
            //['label' => 'Checkinout', 'icon' => 'home', 'url' => ['/iclock/checkinout/index']],
            ['label' => 'MENU SISTEM', 'options' => ['class' => 'header']],
            ['label' => 'Pengumuman', 'icon' => 'bullhorn', 'url' => ['/pengumuman/index']],
            ['label' => 'Pengaturan', 'icon' => 'wrench', 'url' => ['/pengaturan/index']],
            ['label' => 'Perangkat Daerah', 'icon' => 'building', 'url' => ['/instansi/index']],
            ['label' => 'Pegawai', 'icon' => 'user', 'url' => ['/pegawai/index']],
            ['label' => 'Grup Pegawai', 'icon' => 'users', 'url' => ['/grup/index']],
            ['label' => 'Jabatan', 'icon' => 'star', 'url' => ['/jabatan/index']],
            ['label' => 'Mutasi', 'icon' => 'refresh', 'url' => ['/pegawai-instansi/index']],
            ['label' => 'User', 'icon' => 'user', 'items' => [
                ['label' => 'Admin', 'url' => ['/user/index', 'id_user_role' => UserRole::ADMIN]],
                ['label' => 'Pegawai', 'url' => ['/user/index', 'id_user_role' => UserRole::PEGAWAI]],
                ['label' => 'Perangkat Daerah', 'url' => ['/user/index', 'id_user_role' => UserRole::INSTANSI]],
                ['label' => 'Verifikator', 'url' => ['/user/index', 'id_user_role' => UserRole::VERIFIKATOR]],
                ['label' => 'Grup', 'url' => ['/user/index', 'id_user_role' => UserRole::GRUP]],
                ['label' => 'Mapping', 'url' => ['/user/index', 'id_user_role' => UserRole::MAPPING]],
            ]],
            ['label' => 'Ganti Password', 'icon' => 'lock', 'url' => ['/user/change-password']],
            ['label' => 'Logout', 'url' => ['/site/logout'], 'icon' => 'power-off', 'template' => '<a href="{url}" data-method="post">{icon} {label}</a>', 'visible' => !Yii::$app->user->isGuest],

        ],
    ]);

}

if(Yii::$app->params['mode']=='tukin-only') {

    echo Menu::widget([
        'options' => ['class' => 'sidebar-menu', 'data-widget' => 'tree'],
        'items' => [
            ['label' => 'TUKIN', 'options' => ['class' => 'header']],
            ['label' => 'Home', 'icon' => 'circle', 'url' => ['/tukin/admin/index']],
            ['label' => 'Pegawai', 'icon' => 'users', 'url' => ['/tukin/pegawai/index']],
            ['label' => 'Rekap Tunjangan', 'icon' => 'list', 'url' => ['/tukin/pegawai/rekap']],
            ['label' => 'Jabatan', 'icon' => 'sitemap', 'url' => ['/tukin/jabatan/index']],
            ['label' => 'Instansi', 'icon' => 'building', 'url' => ['/tukin/instansi/index']],
            ['label' => 'Instansi Kordinatif', 'icon' => 'building-o', 'url' => ['/tukin/instansi-kordinatif/index']],
            ['label' => 'Serapan Anggaran', 'icon' => 'building', 'url' => ['/tukin/instansi-serapan-anggaran/index']],
            ['label' => 'Referensi', 'icon' => 'list', 'items' => [
                ['label' => 'Variabel Objektif', 'icon' => 'circle', 'url' => ['/tukin/ref-variabel-objektif/index']]
            ]],
            ['label' => 'Ganti Password', 'icon' => 'lock', 'url' => ['/user/change-password']],
            ['label' => 'Logout', 'url' => ['/site/logout'], 'icon' => 'power-off', 'template' => '<a href="{url}" data-method="post">{icon} {label}</a>', 'visible' => !Yii::$app->user->isGuest],

        ],
    ]);

}

if(Yii::$app->params['mode']=='dev') {

    echo $this->render('//layouts/_menu-admin-dev');

}
