<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\absensi\models\PegawaiDispensasiSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pegawai-dispensasi-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'id_pegawai') ?>

    <?= $form->field($model, 'tanggal_mulai') ?>

    <?= $form->field($model, 'tanggal_akhir') ?>

    <?= $form->field($model, 'status_hapus') ?>

    <?php // echo $form->field($model, 'user_pembuat') ?>

    <?php // echo $form->field($model, 'waktu_dibuat') ?>

    <?php // echo $form->field($model, 'user_pengubah') ?>

    <?php // echo $form->field($model, 'waktu_diubah') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
