<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\UserMenu */

$this->title = "Sunting User Menu";
$this->params['breadcrumbs'][] = ['label' => 'User Menus', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Sunting';
?>
<div class="user-menu-update">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
