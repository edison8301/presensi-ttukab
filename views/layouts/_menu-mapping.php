<?php

/**
 * Created by PhpStorm.
 * User: iqbal
 * Date: 1/4/2019
 * Time: 10:16 AM
 */

/* @var $this \yii\web\View */
?>

<?= \dmstr\widgets\Menu::widget([
    'options' => ['class' => 'sidebar-menu', 'data-widget' => 'tree'],
    'items' => [
        ['label' => 'TUKIN', 'options' => ['class' => 'header']],
        ['label' => 'Home', 'icon' => 'circle', 'url' => ['/tukin/admin/index']],
        ['label' => 'Pegawai', 'icon' => 'users', 'url' => ['/tukin/instansi-pegawai/index-pegawai']],
        ['label' => 'Rekap Tunjangan', 'icon' => 'list', 'url' => ['/tukin/pegawai/rekap']],
        ['label' => 'Instansi Kordinatif', 'icon' => 'building-o', 'url' => ['/tukin/instansi-kordinatif/index']],
        ['label' => 'Serapan Anggaran', 'icon' => 'building', 'url' => ['/tukin/instansi-serapan-anggaran/index']],
        ['label' => 'Referensi', 'icon' => 'list', 'items' => [
            ['label' => 'Variabel Objektif', 'icon' => 'circle', 'url' => ['/tukin/ref-variabel-objektif/index']]
        ]],
        ['label' => 'SISTEM', 'options' => ['class' => 'header']],
        ['label' => 'Instansi', 'icon' => 'building', 'url' => ['/instansi/index']],
        ['label' => 'Pegawai', 'icon' => 'users', 'url' => ['/pegawai/index']],
        ['label' => 'Jabatan', 'icon' => 'star', 'url' => ['/jabatan/index']],
        ['label' => 'Mutasi dan Promosi', 'icon' => 'refresh', 'url' => ['/instansi-pegawai/index']],
        ['label' => 'Ganti Password', 'icon' => 'lock', 'url' => ['/user/change-password']],
        ['label' => 'Logout', 'url' => ['/site/logout'], 'icon' => 'power-off', 'template' => '<a href="{url}" data-method="post">{icon} {label}</a>' , 'visible' => !Yii::$app->user->isGuest],

    ],
]) ?>
