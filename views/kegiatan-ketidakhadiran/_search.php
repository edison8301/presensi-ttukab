<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\KegiatanKetidakhadiranSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="kegiatan-ketidakhadiran-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'nip') ?>

    <?= $form->field($model, 'id_kegiatan_ketidakhadiran_jenis') ?>

    <?= $form->field($model, 'penjelasan') ?>

    <?= $form->field($model, 'checktime') ?>

    <?php // echo $form->field($model, 'foto_pendukung') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'deleted_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
