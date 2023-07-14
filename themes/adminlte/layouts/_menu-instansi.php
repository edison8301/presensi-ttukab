<?php

use app\components\Session;
use app\modules\kinerja\models\KegiatanStatus;
use app\models\User;
use app\modules\tandatangan\models\BerkasStatus;
use dmstr\widgets\Menu;


if(Yii::$app->params['mode']=='tukin') {
    echo Menu::widget([
        'options' => ['class' => 'sidebar-menu', 'data-widget' => 'tree'],
        'items' => [
            ['label' => 'ABSENSI', 'options' => ['class' => 'header']],
            ['label' => 'Pegawai', 'icon' => 'users', 'url' => ['/absensi/instansi-pegawai/index-pegawai']],
            ['label' => 'Ketidakhadiran Hari Kerja', 'icon' => 'calendar', 'url' => ['/absensi/ketidakhadiran-panjang/index']],
            ['label' => 'Ketidakhadiran Jam Kerja', 'icon' => 'clock-o', 'url' => ['/absensi/ketidakhadiran-jam-kerja/index']],
            ['label' => 'Ketidakhadiran Kegiatan', 'icon' => 'pencil-square-o', 'items' => [
                ['label' => 'Upacara', 'url' => ['/absensi/ketidakhadiran-kegiatan/index', 'id_ketidakhadiran_kegiatan_jenis' => 1]],
                ['label' => 'Senam', 'url' => ['/absensi/ketidakhadiran-kegiatan/index', 'id_ketidakhadiran_kegiatan_jenis' => 2]],
                ['label' => 'Apel Pagi', 'url' => ['/absensi/ketidakhadiran-kegiatan/index', 'id_ketidakhadiran_kegiatan_jenis' => 3]],
                ['label' => 'Apel Sore', 'url' => ['/absensi/ketidakhadiran-kegiatan/index', 'id_ketidakhadiran_kegiatan_jenis' => 4]],
                ['label' => 'Sidak', 'url' => ['/absensi/ketidakhadiran-kegiatan/index', 'id_ketidakhadiran_kegiatan_jenis' => 5]],
            ]],
            ['label' => 'Upload Presensi Manual', 'icon' => 'circle-o', 'url' => ['/absensi/upload-presensi/index']],
            ['label' => 'IKI', 'icon' => 'book', 'url' => ['/kinerja/instansi-pegawai/index-iki']],
            ['label' => 'Rekap IKI', 'icon' => 'file', 'url' => ['/kinerja/instansi-pegawai/rekap-iki']],
            ['label' => 'Rekap SKP & RKB', 'icon' => 'file', 'url' => ['/kinerja/instansi-pegawai/index-rekap']],
            ['label' => 'Rekap Absensi Peta', 'icon' => 'file', 'url' => ['/absensi/instansi-pegawai/index-rekap-peta']],
            ['label' => 'Shift Kerja', 'icon' => 'clock-o', 'url' => ['/absensi/pegawai/index-shift-kerja']],
            /*
            ['label' => 'KINERJA', 'options' => ['class' => 'header']],
            ['label' => 'Kinerja Harian', 'icon' => 'folder', 'items' => [
                ['label' => 'Rekap Kinerja Harian', 'url' => ['/kinerja/instansi-pegawai/index-rekap-kegiatan-harian-v4']],
            ]],
            ['label' => 'Matriks Peran Hasil', 'icon' => 'list', 'url' => ['/kinerja/instansi-pegawai/matriks-peran-hasil']],
            ['label' => 'TUKIN', 'options' => ['class' => 'header']],
            ['label' => 'Laporan dan Rekap', 'icon' => 'users', 'url' => ['/tunjangan/instansi-pegawai/index']],
            ['label' => 'Hukuman Disiplin', 'icon'=>'gavel', 'url' => ['/absensi/hukuman-disiplin/index']],
            ['label' => 'Potongan Lain', 'icon' => 'cut', 'url' => ['/tunjangan/tunjangan-potongan-pegawai/index']],
            ['label' => 'Tambahan TPP', 'icon' => 'plus', 'items' => [
                ['label' => 'Jabatan Khusus', 'url' => ['/tunjangan/jabatan-tunjangan-khusus-pegawai/index']],
            ]],
            */
            ['label' => 'MENU AKUN', 'options' => ['class' => 'header']],
            ['label' => 'Pegawai', 'icon' => 'list', 'items' => [
                ['label' => 'Pegawai Penghargaan', 'icon' => 'trophy','url' => ['/pegawai-penghargaan/index']],
            ]],
            ['label' => 'Peta', 'icon' => 'users', 'url' => ['/peta/index']],
            ['label' => 'Profil', 'icon' => 'user', 'url' => ['/instansi/profil']],
            ['label' => 'Profil Jabatan', 'icon' => 'sitemap', 'url' => ['/instansi/profil-jabatan']],
            ['label' => 'Ganti Password', 'icon' => 'key', 'url' => ['/user/change-password']],
            ['label' => 'Logout', 'url' => ['/site/logout'], 'icon' => 'power-off', 'template' => '<a href="{url}" data-method="post">{icon} {label}</a>', 'visible' => !Yii::$app->user->isGuest],
        ],
    ]);
}

if(Yii::$app->params['mode']=='absensi') {
    echo Menu::widget([
        'options' => ['class' => 'sidebar-menu', 'data-widget' => 'tree'],
        'items' => [
            ['label' => 'ABSENSI', 'options' => ['class' => 'header']],
            ['label' => 'Pegawai', 'icon' => 'users', 'url' => ['/absensi/instansi-pegawai/index-pegawai']],
            ['label' => 'Rekap Absensi', 'icon' => 'list', 'url' => ['/absensi/pegawai-rekap-absensi/index']],
            ['label' => 'Ketidakhadiran Hari Kerja', 'icon' => 'calendar', 'url' => ['/absensi/ketidakhadiran/index']],
            ['label' => 'Ketidakhadiran Jam Kerja', 'icon' => 'clock-o', 'url' => ['/absensi/ketidakhadiran-jam-kerja/index']],
            ['label' => 'Ketidakhadiran Kegiatan', 'icon' => 'pencil-square-o', 'items' => [
                ['label' => 'Upacara', 'url' => ['/absensi/ketidakhadiran-kegiatan/index', 'id_ketidakhadiran_kegiatan_jenis' => 1]],
                ['label' => 'Senam', 'url' => ['/absensi/ketidakhadiran-kegiatan/index', 'id_ketidakhadiran_kegiatan_jenis' => 2]],
                ['label' => 'Apel Pagi', 'url' => ['/absensi/ketidakhadiran-kegiatan/index', 'id_ketidakhadiran_kegiatan_jenis' => 3]],
                ['label' => 'Apel Sore', 'url' => ['/absensi/ketidakhadiran-kegiatan/index', 'id_ketidakhadiran_kegiatan_jenis' => 4]],
                ['label' => 'Sidak', 'url' => ['/absensi/ketidakhadiran-kegiatan/index', 'id_ketidakhadiran_kegiatan_jenis' => 5]],
            ]],
            ['label' => 'Rekap SKP & CKHP', 'icon' => 'file', 'url' => ['/kinerja/kegiatan-harian/rekap']],
            ['label' => 'Ketidakhadiran Panjang', 'icon' => 'clock-o', 'url' => ['/absensi/ketidakhadiran-panjang/index']],
            ['label' => 'Shift Kerja', 'icon' => 'clock-o', 'url' => ['/absensi/pegawai/index-shift-kerja'], 'visible' => in_array(User::getIdInstansi(), [42, 43, 44, 1, 213])],
            ['label' => 'MENU AKUN', 'options' => ['class' => 'header']],
            ['label' => 'Profil', 'icon' => 'user', 'url' => ['/instansi/profil']],
            ['label' => 'Profil Jabatan', 'icon' => 'sitemap', 'url' => ['/instansi/profil-jabatan']],
            ['label' => 'Ganti Password', 'icon' => 'key', 'url' => ['/user/change-password']],
            ['label' => 'Logout', 'url' => ['/site/logout'], 'icon' => 'power-off', 'template' => '<a href="{url}" data-method="post">{icon} {label}</a>', 'visible' => !Yii::$app->user->isGuest],
        ],
    ]);
}

?>
