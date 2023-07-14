<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\tukin\models\RefVariabelObjektifSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ref-variabel-objektif-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'kode') ?>

    <?= $form->field($model, 'kelompok') ?>

    <?= $form->field($model, 'uraian') ?>

    <?= $form->field($model, 'satuan') ?>

    <?php // echo $form->field($model, 'tarif') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
