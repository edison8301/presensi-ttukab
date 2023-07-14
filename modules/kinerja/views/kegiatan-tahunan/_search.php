<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\KegiatanTahunanSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="kegiatan-tahunan-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'id_pegawai') ?>

    <?= $form->field($model, 'nama_kegiatan') ?>

    <?= $form->field($model, 'satuan_kuantitas') ?>

    <?= $form->field($model, 'target_kuantitas') ?>

    <?php // echo $form->field($model, 'target_waktu') ?>

    <?php // echo $form->field($model, 'id_pegawai_penyetuju') ?>

    <?php // echo $form->field($model, 'kode_kegiatan_status') ?>

    <?php // echo $form->field($model, 'waktu_dibuat') ?>

    <?php // echo $form->field($model, 'waktu_disetujui') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
