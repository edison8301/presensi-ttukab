<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\iclock\Userinfo */
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

<div class="userinfo-form box box-primary">

    <div class="box-header">
        <h3 class="box-title">Form Userinfo</h3>
    </div>
	<div class="box-body">

        <?= $form->errorSummary($model); ?>

        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'badgenumber')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'AccGroup')->textInput() ?>

        <?php /*
        <?= $form->field($model, 'defaultdeptid')->textInput() ?>

        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'Password')->passwordInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'Card')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'Privilege')->textInput() ?>



        <?= $form->field($model, 'TimeZones')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'Gender')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'Birthday')->textInput() ?>

        <?= $form->field($model, 'street')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'zip')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'ophone')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'FPHONE')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'pager')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'minzu')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'SN')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'SSN')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'UTime')->textInput() ?>

        <?= $form->field($model, 'State')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'City')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'SECURITYFLAGS')->textInput() ?>

        <?= $form->field($model, 'DelTag')->textInput() ?>

        <?= $form->field($model, 'RegisterOT')->textInput() ?>

        <?= $form->field($model, 'AutoSchPlan')->textInput() ?>

        <?= $form->field($model, 'MinAutoSchInterval')->textInput() ?>

        <?= $form->field($model, 'Image_id')->textInput() ?>
        */ ?>

        <?= Html::hiddenInput('referrer',$referrer); ?>

	</div>
    <div class="box-footer">
        <div class="col-sm-offset-2 col-sm-3">
            <?= Html::submitButton('<i class="fa fa-check"></i> Simpan',['class' => 'btn btn-success btn-flat']) ?>
        </div>
    </div>

</div>

<?php ActiveForm::end(); ?>
