<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TunjanganKomponen */
/* @var $referrer string */

$this->title = "Sunting Komponen Tunjangan";
$this->params['breadcrumbs'][] = ['label' => 'Tunjangan Komponens', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Sunting';
?>
<div class="tunjangan-komponen-update">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
