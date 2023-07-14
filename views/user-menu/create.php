<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\UserMenu */

$this->title = "Tambah User Menu";
$this->params['breadcrumbs'][] = ['label' => 'User Menus', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-menu-create">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
