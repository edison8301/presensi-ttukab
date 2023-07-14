<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\KegiatanHarianSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="kegiatan-harian-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'id_kegiatan_tahunan') ?>

    <?= $form->field($model, 'tanggal') ?>

    <?= $form->field($model, 'uraian') ?>

    <?= $form->field($model, 'kuantitas') ?>

    <?php // echo $form->field($model, 'jam_mulai') ?>

    <?php // echo $form->field($model, 'jam_selesai') ?>

    <?php // echo $form->field($model, 'berkas') ?>

    <?php // echo $form->field($model, 'kode_kegiatan_status') ?>

    <?php // echo $form->field($model, 'id_pegawai_penyetuju') ?>

    <?php // echo $form->field($model, 'waktu_disetujui') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
