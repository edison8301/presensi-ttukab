<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Template */
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

<div class="template-form box box-primary">

    <div class="box-header">
        <h3 class="box-title">Form Template</h3>
    </div>
	<div class="box-body">

        <?= $form->errorSummary($model); ?>

        <?= $form->field($model, 'userid')->textInput() ?>

        <?= $form->field($model, 'Template')->textarea(['rows' => 6]) ?>

        <?= $form->field($model, 'FingerID')->textInput() ?>

        <?= $form->field($model, 'Valid')->textInput() ?>

        <?= $form->field($model, 'DelTag')->textInput() ?>

        <?= $form->field($model, 'SN')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'UTime')->textInput() ?>

        <?= $form->field($model, 'BITMAPPICTURE')->textarea(['rows' => 6]) ?>

        <?= $form->field($model, 'BITMAPPICTURE2')->textarea(['rows' => 6]) ?>

        <?= $form->field($model, 'BITMAPPICTURE3')->textarea(['rows' => 6]) ?>

        <?= $form->field($model, 'BITMAPPICTURE4')->textarea(['rows' => 6]) ?>

        <?= $form->field($model, 'USETYPE')->textInput() ?>

        <?= $form->field($model, 'Template2')->textarea(['rows' => 6]) ?>

        <?= $form->field($model, 'Template3')->textarea(['rows' => 6]) ?>

        <?= Html::hiddenInput('referrer',$referrer); ?>

	</div>
    <div class="box-footer">
        <div class="col-sm-offset-2 col-sm-3">
            <?= Html::submitButton('<i class="fa fa-check"></i> Simpan',['class' => 'btn btn-success btn-flat']) ?>
        </div>
    </div>

</div>

<?php ActiveForm::end(); ?>
