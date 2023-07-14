<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\kinerja\models\PegawaiRekapKinerjaSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pegawai-rekap-kinerja-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'id_pegawai') ?>

    <?= $form->field($model, 'id_instansi') ?>

    <?= $form->field($model, 'bulan') ?>

    <?= $form->field($model, 'tahun') ?>

    <?php // echo $form->field($model, 'potongan_skp') ?>

    <?php // echo $form->field($model, 'potongan_ckhp') ?>

    <?php // echo $form->field($model, 'potongan_total') ?>

    <?php // echo $form->field($model, 'waktu_buat') ?>

    <?php // echo $form->field($model, 'waktu_update') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
