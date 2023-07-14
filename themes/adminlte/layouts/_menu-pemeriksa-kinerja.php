<?php

use app\models\UserMenu;
use app\models\UserRole;

?>

<?= \dmstr\widgets\Menu::widget([
        'options' => ['class' => 'sidebar-menu', 'data-widget' => 'tree'],
        'items' => [
            ['label' => 'ABSENSI', 'options' => ['class' => 'header']],
            ['label' => 'Hukuman Disiplin', 'icon' => 'gavel', 'url' => ['/absensi/hukuman-disiplin/index'], 'visible'=>UserMenu::findStatusAktif(['path'=>'/absensi/hukuman-disiplin/index'])],
            ['label' => 'Rekap Absensi Peta', 'icon' => 'file', 'url' => ['/absensi/instansi-pegawai/index-rekap-peta'], 'visible'=>UserMenu::findStatusAktif(['path'=>'/absensi/instansi-pegawai/index-rekap-peta'])],
            ['label' => 'KINERJA', 'options' => ['class' => 'header']],
            ['label' => 'SKP', 'icon' => 'book', 'url' => ['/kinerja/instansi-pegawai-skp/index'], 'visible'=>UserMenu::findStatusAktif(['path'=>'/kinerja/instansi-pegawai-skp/index'])],
            ['label' => 'Keg. Tahunan', 'icon' => 'list', 'items' => [
                ['label' => 'Daftar Kegiatan', 'url' => ['/kinerja/kegiatan-tahunan/index'], 'visible'=>UserMenu::findStatusAktif(['path'=>'/kinerja/kegiatan-tahunan/index'])],
                ['label' => 'Daftar SKP', 'url' => ['/kinerja/instansi-pegawai/view'], 'visible'=>UserMenu::findStatusAktif(['path'=>'/kinerja/instansi-pegawai/view'])],
            ]],
            ['label' => 'Keg. Bulanan', 'icon' => 'list', 'url' => ['/kinerja/kegiatan-bulanan/index'], 'visible'=>UserMenu::findStatusAktif(['path'=>'/kinerja/kegiatan-bulanan/index'])],
            ['label' => 'Keg. Harian', 'icon' => 'list', 'url' => ['/kinerja/kegiatan-harian/index'], 'visible'=>UserMenu::findStatusAktif(['path'=>'/kinerja/kegiatan-harian/index'])],
            ['label' => 'Pegawai', 'icon' => 'user', 'url' => ['/kinerja/instansi-pegawai/index-v3'], 'visible'=>UserMenu::findStatusAktif(['path'=>'/kinerja/instansi-pegawai/index-v3'])],
            ['label' => 'Rekap Kinerja', 'icon' => 'book', 'url' => ['/instansi/index-rekap-kinerja'], 'visible'=>UserMenu::findStatusAktif(['path'=>'/instansi/index-rekap-kinerja'])],
            ['label' => 'SKP', 'icon' => 'book', 'url' => ['/kinerja/instansi-pegawai-skp/index-v3'], 'visible'=>UserMenu::findStatusAktif(['path'=>'/kinerja/instansi-pegawai-skp/index-v3'])],
            [
                'label' => 'Kinerja Tahunan',
                'icon' => 'folder',
                'items' => [
                    ['label' => 'Kinerja Tahunan', 'icon' => 'circle-o', 'url' => ['/kinerja/kegiatan-rhk/index'], 'visible'=>UserMenu::findStatusAktif(['path'=>'/kinerja/kegiatan-rhk/index'])],
                    ['label' => 'Matriks Target/Realisasi', 'icon' => 'circle-o', 'url' => ['/kinerja/kegiatan-rhk/matriks'], 'visible'=>UserMenu::findStatusAktif(['path'=>'/kinerja/kegiatan-rhk/matriks'])],
                ]
            ],
            ['label' => 'Kinerja Bulanan', 'icon' => 'folder', 'url' => ['/kinerja/kegiatan-bulanan/index-v3'], 'visible'=>UserMenu::findStatusAktif(['path'=>'/kinerja/kegiatan-bulanan/index-v3'])],
            ['label' => 'Kinerja Harian', 'icon' => 'folder', 'items' => [
                ['label' => 'Kinerja Harian', 'icon' => 'circle-o', 'url' => ['/kinerja/kegiatan-harian/index-v4'], 'visible'=>UserMenu::findStatusAktif(['path'=>'/kinerja/kegiatan-harian/index-v4'])],
                ['label' => 'Rekap Kinerja Harian', 'icon' => 'circle-o', 'url' => ['/kinerja/instansi-pegawai/index-rekap-kegiatan-harian-v4'], 'visible'=>UserMenu::findStatusAktif(['path'=>'/kinerja/instansi-pegawai/index-rekap-kegiatan-harian-v4'])],
            ]],
            ['label' => 'Matriks Peran Hasil', 'icon' => 'list', 'url' => ['/kinerja/instansi-pegawai/matriks-peran-hasil'], 'visible'=>UserMenu::findStatusAktif(['path'=>'/kinerja/instansi-pegawai/matriks-peran-hasil'])],
            ['label' => 'TUKIN', 'options' => ['class' => 'header']],
            ['label' => 'Pegawai', 'icon' => 'user', 'url' => ['/tunjangan/instansi-pegawai/index', 'jenis' => 'tpp'], 'visible'=>UserMenu::findStatusAktif(['path'=>'/tunjangan/instansi-pegawai/index'])],
            ['label' => 'MENU SISTEM', 'options' => ['class' => 'header']],
            ['label' => 'Ganti Password', 'icon' => 'lock', 'url' => ['/user/change-password']],
            ['label' => 'Pegawai', 'icon' => 'list', 'items' => [
                ['label' => 'Pegawai Penghargaan', 'icon' => 'trophy','url' => ['/pegawai-penghargaan/index'], 'visible'=>UserMenu::findStatusAktif(['path'=>'/pegawai-penghargaan/index'])],
                ['label' => 'Pegawai Cuti Ibadah', 'icon' => 'calendar','url' => ['/pegawai-cuti-ibadah/index'], 'visible'=>UserMenu::findStatusAktif(['path'=>'/pegawai-cuti-ibadah/index'])],
                ['label' => 'Pegawai Tugas Belajar', 'icon' => 'book','url' => ['/pegawai-tugas-belajar/index'], 'visible'=>UserMenu::findStatusAktif(['path'=>'/pegawai-tugas-belajar/index'])],
                ['label' => 'Pegawai Penundaan TPP', 'icon' => 'clock-o','url' => ['/pegawai-tunda-bayar/index'], 'visible'=>UserMenu::findStatusAktif(['path'=>'/pegawai-tunda-bayar/index'])],
                ['label' => 'Pegawai Seragam Dinas', 'icon' => 'gavel','url' => ['/pegawai-atribut/index'], 'visible'=>UserMenu::findStatusAktif(['path'=>'/pegawai-atribut/index'])],
                ['label' => 'Pegawai Cuti Besar Non TPP', 'icon' => 'users','url' => [
                    '/pegawai-non-tpp/index',
                    'id_pegawai_non_tpp_jenis' => PegawaiNonTppJenis::CUTI_BESAR,
                ], 'visible'=>UserMenu::findStatusAktif(['path' => '/pegawai-non-tpp/index-cuti-besar'])],
                ['label' => 'Pegawai Tubel Non TPP', 'icon' => 'users','url' => [
                    '/pegawai-non-tpp/index',
                    'id_pegawai_non_tpp_jenis' => PegawaiNonTppJenis::TUGAS_BELAJAR,
                ], 'visible'=>UserMenu::findStatusAktif(['path' => '/pegawai-non-tpp/index-tugas-belajar'])],
            ]],
            ['label' => 'Logout', 'url' => ['/site/logout'], 'icon' => 'power-off', 'template' => '<a href="{url}" data-method="post">{icon} {label}</a>' , 'visible' => !Yii::$app->user->isGuest],

        ],
]) ?>
