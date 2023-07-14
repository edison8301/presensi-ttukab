<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\kinerja\models\KegiatanRhkSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="kegiatan-rhk-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'tahun') ?>

    <?= $form->field($model, 'id_induk') ?>

    <?= $form->field($model, 'id_pegawai') ?>

    <?= $form->field($model, 'id_instansi_pegawai') ?>

    <?php // echo $form->field($model, 'id_kegiatan_rhk_jenis') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
