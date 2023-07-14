<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\kinerja\models\UnitKerja */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="unit-kerja-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'unit_kerja')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
