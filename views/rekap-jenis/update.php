<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\RekapJenis */

$this->title = "Sunting Rekap Jenis";
$this->params['breadcrumbs'][] = ['label' => 'Rekap Jenis', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Sunting';
?>
<div class="rekap-jenis-update">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
