<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\absensi\models\JamKerjaSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="jam-kerja-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'id_shift_kerja') ?>

    <?= $form->field($model, 'hari') ?>

    <?= $form->field($model, 'jenis') ?>

    <?= $form->field($model, 'nama') ?>

    <?php // echo $form->field($model, 'jam_mulai_pindai') ?>

    <?php // echo $form->field($model, 'jam_selesai_pindai') ?>

    <?php // echo $form->field($model, 'jam_mulai_normal') ?>

    <?php // echo $form->field($model, 'jam_selesai_normal') ?>

    <?php // echo $form->field($model, 'status_wajib') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
