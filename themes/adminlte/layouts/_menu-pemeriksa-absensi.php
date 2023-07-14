<?php

use app\models\PegawaiNonTppJenis;
use app\models\UserMenu;
use app\models\UserRole;

?>

<?= \dmstr\widgets\Menu::widget([
        'options' => ['class' => 'sidebar-menu', 'data-widget' => 'tree'],
        'items' => [
            ['label' => 'ABSENSI', 'options' => ['class' => 'header']],
            ['label' => 'Perangkat Daerah', 'icon' => 'bank', 'items' => [
                ['label' => 'Rekap Absensi', 'url' => ['/absensi/instansi-rekap-absensi/index'], 'visible'=>UserMenu::findStatusAktif(['path'=>'/absensi/instansi-rekap-absensi/index'])],
            ]],
            ['label' => 'Pegawai', 'icon' => 'user', 'items' => [
                ['label' => 'Pegawai', 'url' => ['/absensi/instansi-pegawai/index-pegawai'], 'visible' => UserMenu::findStatusAktif(['path'=>'/absensi/instansi-pegawai/index-pegawai'])],
                ['label' => 'Rekap Absensi', 'url' => ['/absensi/pegawai-rekap-absensi/index'], 'visible' => UserMenu::findStatusAktif(['path'=>'/absensi/pegawai-rekap-absensi/index'])],
                ['label' => 'Rekap Absensi Peta', 'url' => ['/absensi/instansi-pegawai/index-rekap-peta']],
            ]],
            ['label' => 'Hukuman Disiplin', 'icon' => 'gavel', 'url' => ['/absensi/hukuman-disiplin/index'], 'visible'=>UserMenu::findStatusAktif(['path'=>'/absensi/hukuman-disiplin/index'])],
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
