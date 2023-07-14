<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\kinerja\modelsKegiatanTriwulanSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="kegiatan-triwulan-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'id_kegiatan_tahunan') ?>

    <?= $form->field($model, 'id_kegiatan_bulanan') ?>

    <?= $form->field($model, 'tahun') ?>

    <?= $form->field($model, 'bulan') ?>

    <?php // echo $form->field($model, 'target') ?>

    <?php // echo $form->field($model, 'realisasi') ?>

    <?php // echo $form->field($model, 'persen_capaian') ?>

    <?php // echo $form->field($model, 'deskripsi_capaian') ?>

    <?php // echo $form->field($model, 'kendala') ?>

    <?php // echo $form->field($model, 'tindak_lanjut') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
