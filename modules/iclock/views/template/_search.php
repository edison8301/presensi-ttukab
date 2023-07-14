<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TemplateSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="template-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'templateid') ?>

    <?= $form->field($model, 'userid') ?>

    <?= $form->field($model, 'Template') ?>

    <?= $form->field($model, 'FingerID') ?>

    <?= $form->field($model, 'Valid') ?>

    <?php // echo $form->field($model, 'DelTag') ?>

    <?php // echo $form->field($model, 'SN') ?>

    <?php // echo $form->field($model, 'UTime') ?>

    <?php // echo $form->field($model, 'BITMAPPICTURE') ?>

    <?php // echo $form->field($model, 'BITMAPPICTURE2') ?>

    <?php // echo $form->field($model, 'BITMAPPICTURE3') ?>

    <?php // echo $form->field($model, 'BITMAPPICTURE4') ?>

    <?php // echo $form->field($model, 'USETYPE') ?>

    <?php // echo $form->field($model, 'Template2') ?>

    <?php // echo $form->field($model, 'Template3') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
