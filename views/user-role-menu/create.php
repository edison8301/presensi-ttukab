<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\UserRoleMenu */

$this->title = "Tambah User Role Menu";
$this->params['breadcrumbs'][] = ['label' => 'User Role Menus', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-role-menu-create">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
