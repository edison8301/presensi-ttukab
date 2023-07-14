<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\JabatanEvjabSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="jabatan-evjab-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'id_jenis_jabatan') ?>

    <?= $form->field($model, 'id_instansi') ?>

    <?= $form->field($model, 'id_instansi_bidang') ?>

    <?= $form->field($model, 'nama') ?>

    <?php // echo $form->field($model, 'nilai_jabatan') ?>

    <?php // echo $form->field($model, 'kelas_jabatan') ?>

    <?php // echo $form->field($model, 'persediaan_pegawai') ?>

    <?php // echo $form->field($model, 'id_induk') ?>

    <?php // echo $form->field($model, 'status_hapus') ?>

    <?php // echo $form->field($model, 'waktu_hapus') ?>

    <?php // echo $form->field($model, 'id_user_hapus') ?>

    <?php // echo $form->field($model, 'nomor') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
