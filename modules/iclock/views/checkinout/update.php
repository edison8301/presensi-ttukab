<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Checkinout */
/* @var $referrer string */

$this->title = "Sunting Checkinout";
$this->params['breadcrumbs'][] = ['label' => 'Checkinouts', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Sunting';
?>
<div class="checkinout-update">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
