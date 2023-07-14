<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\absensi\models\UploadPresensiLogSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="upload-presensi-log-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'id_upload_presensi') ?>

    <?= $form->field($model, 'badgenumber') ?>

    <?= $form->field($model, 'checktime') ?>

    <?= $form->field($model, 'checktype') ?>

    <?php // echo $form->field($model, 'SN') ?>

    <?php // echo $form->field($model, 'status_kirim') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
