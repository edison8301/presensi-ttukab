<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\UserRoleMenu */

$this->title = "Sunting User Role Menu";
$this->params['breadcrumbs'][] = ['label' => 'User Role Menus', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Sunting';
?>
<div class="user-role-menu-update">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
