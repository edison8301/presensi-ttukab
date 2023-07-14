<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\iclock\Iclock */
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

<div class="iclock-form box box-primary">

    <div class="box-header">
        <h3 class="box-title">Form Iclock</h3>
    </div>
	<div class="box-body">

        <?= $form->errorSummary($model); ?>

        <?= $form->field($model, 'SN')->textInput(['maxlength' => true]) ?>

        <?php /*
        <?= $form->field($model, 'State')->textInput() ?>

        <?= $form->field($model, 'LastActivity')->textInput() ?>

        <?= $form->field($model, 'TransTimes')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'TransInterval')->textInput() ?>

        <?= $form->field($model, 'LogStamp')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'OpLogStamp')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'PhotoStamp')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'Alias')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'DeptID')->textInput() ?>

        <?= $form->field($model, 'UpdateDB')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'Style')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'FWVersion')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'FPCount')->textInput() ?>

        <?= $form->field($model, 'TransactionCount')->textInput() ?>

        <?= $form->field($model, 'UserCount')->textInput() ?>

        <?= $form->field($model, 'MainTime')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'MaxFingerCount')->textInput() ?>

        <?= $form->field($model, 'MaxAttLogCount')->textInput() ?>

        <?= $form->field($model, 'DeviceName')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'AlgVer')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'FlashSize')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'FreeFlashSize')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'Language')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'VOLUME')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'DtFmt')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'IPAddress')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'IsTFT')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'Platform')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'Brightness')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'BackupDev')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'OEMVendor')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'City')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'AccFun')->textInput() ?>

        <?= $form->field($model, 'TZAdj')->textInput() ?>

        <?= $form->field($model, 'DelTag')->textInput() ?>

        <?= $form->field($model, 'FPVersion')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'PushVersion')->textInput(['maxlength' => true]) ?>
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
