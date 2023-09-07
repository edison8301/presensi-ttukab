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
            ['label' => 'MENU AKUN', 'options' => ['class' => 'header']],
            ['label' => 'Ganti Password', 'icon' => 'key', 'url' => ['/user/change-password']],
            ['label' => 'Logout', 'url' => ['/site/logout'], 'icon' => 'power-off', 'template' => '<a href="{url}" data-method="post">{icon} {label}</a>', 'visible' => !Yii::$app->user->isGuest],
        ],
    ]);
}

?>
