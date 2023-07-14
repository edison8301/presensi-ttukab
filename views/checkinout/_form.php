<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\iclock\Checkinout */
/* @var $form yii\widgets\ActiveForm */
/* @var $referrer string */

?>

<?php $form = ActiveForm::begin([
    'layout'=>'horizontal',
    'enableAjaxValidation'=>false,
    'enableClientValidation'=>false,
    'fieldConfig' => [
        'horizontalCssClasses' => [
            'label' => 'col-sm-2',
            'wrapper' => 'col-sm-4',
            'error' => '',
            'hint' => '',
        ],
    ]
]); ?>

<div class="checkinout-form box box-primary">

    <div class="box-header">
        <h3 class="box-title">Form Checkinout</h3>
    </div>
	<div class="box-body">

        <?= $form->errorSummary($model); ?>

        <?= $form->field($model, 'userid')->textInput() ?>

        <?= $form->field($model, 'checktime')->textInput() ?>

        <?= $form->field($model, 'checktype')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'verifycode')->textInput() ?>

        <?= $form->field($model, 'SN')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'sensorid')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'WorkCode')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'Reserved')->textInput(['maxlength' => true]) ?>

        <?= Html::hiddenInput('referrer',$referrer); ?>

	</div>
    <div class="box-footer">
        <div class="col-sm-offset-2 col-sm-3">
            <?= Html::submitButton('<i class="fa fa-check"></i> Simpan',['class' => 'btn btn-success btn-flat']) ?>
        </div>
    </div>

</div>

<?php ActiveForm::end(); ?>
