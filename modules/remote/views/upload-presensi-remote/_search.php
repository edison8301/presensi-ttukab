<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\remote\models\UploadPresensiRemoteSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="upload-presensi-remote-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'id_queue') ?>

    <?= $form->field($model, 'SN') ?>

    <?= $form->field($model, 'file') ?>

    <?= $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'user_pengupload') ?>

    <?php // echo $form->field($model, 'waktu_diupload') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
