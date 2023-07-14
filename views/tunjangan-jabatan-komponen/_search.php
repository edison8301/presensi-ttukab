<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TunjanganJabatanKomponenSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tunjangan-jabatan-komponen-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'id_jabatan') ?>

    <?= $form->field($model, 'id_tunjangan_komponen') ?>

    <?= $form->field($model, 'jumlah_tunjangan') ?>

    <?= $form->field($model, 'status_aktif') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
