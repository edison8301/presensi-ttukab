<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\SkpNilaiSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="skp-nilai-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'id_instansi_pegawai_skp') ?>

    <?= $form->field($model, 'id_skp_periode') ?>

    <?= $form->field($model, 'periode') ?>

    <?= $form->field($model, 'feedback_hasil_kerja') ?>

    <?= $form->field($model, 'nilai_hasil_kerja') ?>

    <?= $form->field($model, 'feedback_perilaku_kerja') ?>

    <?php // echo $form->field($model, 'nilai_perilaku_kerja') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
