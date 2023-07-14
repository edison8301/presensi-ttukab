<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\UserInstansi */
/* @var $referrer string */

$this->title = "Sunting User Instansi";
$this->params['breadcrumbs'][] = ['label' => 'User Instansis', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Sunting';
?>
<div class="user-instansi-update">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
