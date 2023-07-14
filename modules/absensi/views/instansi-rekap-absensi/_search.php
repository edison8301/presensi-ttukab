<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\InstansiRekapAbsensiSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="instansi-rekap-absensi-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'id_instansi') ?>

    <?= $form->field($model, 'bulan') ?>

    <?= $form->field($model, 'tahun') ?>

    <?= $form->field($model, 'persen_potongan_total') ?>

    <?php // echo $form->field($model, 'persen_potongan_fingerprint') ?>

    <?php // echo $form->field($model, 'persen_potongan_kegiatan') ?>

    <?php // echo $form->field($model, 'waktu_diperbarui') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
