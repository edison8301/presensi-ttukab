<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TunjanganInstansiJenisJabatanKelasSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tunjangan-unit-jenis-jabatan-kelas-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'id_unit') ?>

    <?= $form->field($model, 'id_jenis_jabatan') ?>

    <?= $form->field($model, 'kelas_jabatan') ?>

    <?= $form->field($model, 'nilai_tpp') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
