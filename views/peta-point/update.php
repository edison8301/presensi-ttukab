<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PetaPoint */

$this->title = "Sunting Peta Point";
$this->params['breadcrumbs'][] = ['label' => 'Peta Points', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Sunting';
?>
<div class="peta-point-update">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
