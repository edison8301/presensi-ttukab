<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\absensi\models\UploadPresensiLog */
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

<div class="upload-presensi-log-form box box-primary">

    <div class="box-header">
        <h3 class="box-title">Form Upload Presensi Log</h3>
    </div>
	<div class="box-body">

        <?= $form->errorSummary($model); ?>

        <?= $form->field($model, 'id_upload_presensi')->textInput() ?>

        <?= $form->field($model, 'badgenumber')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'checktime')->textInput() ?>

        <?= $form->field($model, 'checktype')->textInput() ?>

        <?= $form->field($model, 'SN')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'status_kirim')->textInput() ?>

        <?= Html::hiddenInput('referrer',$referrer); ?>

	</div>
    <div class="box-footer">
        <div class="col-sm-offset-2 col-sm-3">
            <?= Html::submitButton('<i class="fa fa-check"></i> Simpan',['class' => 'btn btn-success btn-flat']) ?>
        </div>
    </div>

</div>

<?php ActiveForm::end(); ?>
