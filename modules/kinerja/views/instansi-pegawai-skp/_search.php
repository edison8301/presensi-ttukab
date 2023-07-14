<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\kinerja\models\InstansiPegawaiSkpSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="instansi-pegawai-skp-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'id_instansi_pegawai') ?>

    <?= $form->field($model, 'tahun') ?>

    <?= $form->field($model, 'urutan') ?>

    <?= $form->field($model, 'nomor') ?>

    <?php // echo $form->field($model, 'status_hapus') ?>

    <?php // echo $form->field($model, 'waktu_hapus') ?>

    <?php // echo $form->field($model, 'id_user_hapus') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
