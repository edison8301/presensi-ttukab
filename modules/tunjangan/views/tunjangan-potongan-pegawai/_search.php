<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\tunjangan\models\TunjanganPotonganPegawaiSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tunjangan-potongan-pegawai-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'id_tunjangan_potongan') ?>

    <?= $form->field($model, 'id_pegawai') ?>

    <?= $form->field($model, 'tanggal_mulai') ?>

    <?= $form->field($model, 'tanggal_selesai') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
