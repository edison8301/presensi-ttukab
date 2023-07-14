<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PegawaiRb */

$this->title = "Sunting Pegawai " . @$model->pegawaiRbJenis->nama;
$this->params['breadcrumbs'][] = ['label' => 'Pegawai Rbs', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Sunting';
?>
<div class="pegawai-rb-update">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
