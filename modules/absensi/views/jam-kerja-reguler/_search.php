<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\absensi\models\JamKerjaRegulerSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="jam-kerja-reguler-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'id_shift_kerja_reguler') ?>

    <?= $form->field($model, 'id_jam_kerja_jenis') ?>

    <?= $form->field($model, 'hari') ?>

    <?= $form->field($model, 'nama') ?>

    <?php // echo $form->field($model, 'jam_mulai_hitung') ?>

    <?php // echo $form->field($model, 'jam_selesai_hitung') ?>

    <?php // echo $form->field($model, 'jam_minimal_absensi') ?>

    <?php // echo $form->field($model, 'jam_maksimal_absensi') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
