<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\absensi\models\KegiatanBulananBreakdownSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="kegiatan-bulanan-breakdown-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'id_kegiatan_tahunan_detil') ?>

    <?= $form->field($model, 'kegiatan') ?>

    <?= $form->field($model, 'kuantitas') ?>

    <?= $form->field($model, 'id_satuan_kuantitas') ?>

    <?php // echo $form->field($model, 'penilaian_kualitas') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
