<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 17/02/2019
 * Time: 11:22
 */

use app\models\UserRole;
use dmstr\widgets\Menu;

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

        //['label' => 'MENU TUNJANGAN', 'options' => ['class' => 'header']],
        //['label' => 'Dashboard', 'icon' => 'home', 'url' => ['/site/index']],
        //['label' => 'ICLOCK', 'options' => ['class' => 'header']],
        //['label' => 'Checkinout', 'icon' => 'home', 'url' => ['/iclock/checkinout/index']],
        ['label' => 'MENU SISTEM', 'options' => ['class' => 'header']],
        ['label' => 'Pengumuman', 'icon' => 'bullhorn', 'url' => ['/pengumuman/index']],
        ['label' => 'Pengaturan', 'icon' => 'wrench', 'url' => ['/pengaturan/index']],
        ['label' => 'Unit Kerja', 'icon' => 'building', 'url' => ['/instansi/index']],
        ['label' => 'Pegawai', 'icon' => 'user', 'url' => ['/pegawai/index']],
        ['label' => 'Grup Pegawai', 'icon' => 'users', 'url' => ['/grup/index']],
        ['label' => 'Jabatan', 'icon' => 'star', 'url' => ['/jabatan/index']],
        ['label' => 'Mutasi dan Promosi', 'icon' => 'refresh', 'url' => ['/instansi-pegawai/index']],
        ['label' => 'User', 'icon' => 'user', 'items' => [
            ['label' => 'Admin', 'url' => ['/user/index', 'id_user_role' => UserRole::ADMIN]],
            ['label' => 'Pegawai', 'url' => ['/user/index', 'id_user_role' => UserRole::PEGAWAI]],
            ['label' => 'Operator PD', 'url' => ['/user/index', 'id_user_role' => UserRole::INSTANSI]],
            ['label' => 'Verifikator', 'url' => ['/user/index', 'id_user_role' => UserRole::VERIFIKATOR]],
            ['label' => 'Grup', 'url' => ['/user/index', 'id_user_role' => UserRole::GRUP]],
            ['label' => 'Mapping', 'url' => ['/user/index', 'id_user_role' => UserRole::MAPPING]],
            ['label' => 'Admin PD', 'url' => ['/user/index', 'id_user_role' => UserRole::ADMIN_INSTANSI]],
        ]],
        ['label' => 'Ganti Password', 'icon' => 'lock', 'url' => ['/user/change-password']],
        ['label' => 'Logout', 'url' => ['/site/logout'], 'icon' => 'power-off', 'template' => '<a href="{url}" data-method="post">{icon} {label}</a>', 'visible' => !Yii::$app->user->isGuest],

    ],
]);

?>