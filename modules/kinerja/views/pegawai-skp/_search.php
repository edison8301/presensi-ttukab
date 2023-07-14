<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\kinerja\models\PegawaiSkpSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pegawai-skp-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'id_instansi_pegawai') ?>

    <?= $form->field($model, 'id_pegawai') ?>

    <?= $form->field($model, 'id_instansi') ?>

    <?= $form->field($model, 'id_jabatan') ?>

    <?php // echo $form->field($model, 'id_golongan') ?>

    <?php // echo $form->field($model, 'id_eselon') ?>

    <?php // echo $form->field($model, 'nomor') ?>

    <?php // echo $form->field($model, 'urutan') ?>

    <?php // echo $form->field($model, 'tahun') ?>

    <?php // echo $form->field($model, 'id_atasan') ?>

    <?php // echo $form->field($model, 'tanggal_berlaku') ?>

    <?php // echo $form->field($model, 'status_hapus') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
