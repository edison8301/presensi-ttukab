<?php

use app\models\UserRole;

?>

<?= dmstr\widgets\Menu::widget([
        'options' => ['class' => 'sidebar-menu', 'data-widget' => 'tree'],
        'items' => [
            ['label' => 'ABSENSI', 'options' => ['class' => 'header']],
            ['label' => 'Shift Kerja', 'icon' => 'refresh', 'url' => ['/absensi/pegawai/index-shift-kerja']],
            ['label' => 'Daftar Pegawai', 'icon' => 'users', 'url' => ['/grup/view']],
            ['label' => 'Ganti Password', 'icon' => 'lock', 'url' => ['/user/change-password']],
            ['label' => 'Logout', 'url' => ['/site/logout'], 'icon' => 'power-off', 'template' => '<a href="{url}" data-method="post">{icon} {label}</a>' , 'visible' => !Yii::$app->user->isGuest],

        ],
]) ?>
