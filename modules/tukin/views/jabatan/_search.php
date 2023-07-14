<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\tukin\models\JabatanSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="jabatan-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'id_jenis_jabatan') ?>

    <?= $form->field($model, 'id_instansi') ?>

    <?= $form->field($model, 'bidang') ?>

    <?= $form->field($model, 'subbidang') ?>

    <?php // echo $form->field($model, 'nama') ?>

    <?php // echo $form->field($model, 'kelas_jabatan') ?>

    <?php // echo $form->field($model, 'persediaan_pegawai') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
