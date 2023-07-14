<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\UserRole */

$this->title = "Sunting User Role";
$this->params['breadcrumbs'][] = ['label' => 'User Roles', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Sunting';
?>
<div class="user-role-update">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
