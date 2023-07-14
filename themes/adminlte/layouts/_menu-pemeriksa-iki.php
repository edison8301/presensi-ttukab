<?php

use app\models\UserMenu;
use app\models\UserRole;

?>

<?= \dmstr\widgets\Menu::widget([
        'options' => ['class' => 'sidebar-menu', 'data-widget' => 'tree'],
        'items' => [
            ['label' => 'ABSENSI', 'options' => ['class' => 'header']],
            ['label' => 'IKI', 'icon' => 'book', 'url' => ['/kinerja/instansi-pegawai/index-iki'], 'visible'=>UserMenu::findStatusAktif(['path'=>'/kinerja/instansi-pegawai/index-iki'])],
            ['label' => 'MENU SISTEM', 'options' => ['class' => 'header']],
            ['label' => 'Ganti Password', 'icon' => 'lock', 'url' => ['/user/change-password']],
            ['label' => 'Logout', 'url' => ['/site/logout'], 'icon' => 'power-off', 'template' => '<a href="{url}" data-method="post">{icon} {label}</a>' , 'visible' => !Yii::$app->user->isGuest],

        ],
]) ?>
