<?php

use dmstr\widgets\Menu;

/* @var $this \yii\web\View */


echo Menu::widget([
    'options' => ['class' => 'sidebar-menu', 'data-widget' => 'tree'],
    'items' => [

        ['label' => 'MENU UTAMA', 'options' => ['class' => 'header']],
        ['label' => 'Pegawai', 'icon' => 'users', 'url' => ['/absensi/instansi-pegawai/index-pegawai']],
        ['label' => 'Ketidakhadiran Hari Kerja', 'icon' => 'list', 'url' => ['/absensi/ketidakhadiran-panjang/index']],
        ['label' => 'Ketidakhadiran Jam Kerja', 'icon' => 'list', 'url' => ['/absensi/ketidakhadiran-jam-kerja/index']],

        ['label' => 'MENU LAIN', 'options' => ['class' => 'header']],
        ['label' => 'Profil', 'icon' => 'user', 'url' => ['/pegawai/profil']],
        ['label' => 'Ganti Password', 'icon' => 'key', 'url' => ['/user/change-password']],
        ['label' => 'Logout', 'url' => ['/site/logout'], 'icon' => 'power-off', 'template' => '<a href="{url}" data-method="post">{icon} {label}</a>', 'visible' => !Yii::$app->user->isGuest],
    ],
]);
