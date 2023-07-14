<?php



/* @var $this \yii\web\View */

use app\modules\kinerja\models\KegiatanStatus;
use dmstr\widgets\Menu;

echo Menu::widget([
    'options' => ['class' => 'sidebar-menu', 'data-widget' => 'tree'],
    'items' => [

        ['label' => 'MENU UTAMA', 'options' => ['class' => 'header']],
        ['label' => 'IKI', 'icon' => 'book', 'url' => ['/kinerja/instansi-pegawai/index-iki']],
        ['label' => 'Rekap IKI', 'icon' => 'file', 'url' => ['/kinerja/instansi-pegawai/rekap-iki']],

        ['label' => 'MENU LAIN', 'options' => ['class' => 'header']],
        ['label' => 'Profil', 'icon' => 'user', 'url' => ['/pegawai/profil']],
        ['label' => 'Ganti Password', 'icon' => 'key', 'url' => ['/user/change-password']],
        ['label' => 'Logout', 'url' => ['/site/logout'], 'icon' => 'power-off', 'template' => '<a href="{url}" data-method="post">{icon} {label}</a>', 'visible' => !Yii::$app->user->isGuest],
    ],
]);
