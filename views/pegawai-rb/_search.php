<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PegawaiRbSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pegawai-rb-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'tahun') ?>

    <?= $form->field($model, 'tanggal') ?>

    <?= $form->field($model, 'id_pegawai') ?>

    <?= $form->field($model, 'id_pegawai_rb_jenis') ?>

    <?php // echo $form->field($model, 'status_realisasi') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
