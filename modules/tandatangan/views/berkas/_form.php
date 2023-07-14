<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\tandatangan\models\Berkas */
/* @var $form yii\widgets\ActiveForm */
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

<div class="berkas-form box box-primary">

    <div class="box-header">
        <h3 class="box-title">Form Berkas</h3>
    </div>
	<div class="box-body">

        <?= $form->errorSummary($model); ?>

        <?= $form->field($model, 'nama')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'uraian')->textarea(['rows' => 6]) ?>

        <?= $form->field($model, 'berkas_mentah')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'berkas_tandatangan')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'id_berkas_status')->textInput() ?>

        <?= $form->field($model, 'nip_tandatangan')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'waktu_tandatangan')->textInput() ?>

        <?= $form->field($model, 'id_aplikasi')->textInput() ?>

        <?= $form->field($model, 'created_at')->textInput() ?>

        <?= $form->field($model, 'updated_at')->textInput() ?>

        <?= $form->field($model, 'deleted_at')->textInput() ?>

        <?= Html::hiddenInput('referrer',$referrer); ?>

	</div>
    <div class="box-footer">
        <div class="col-sm-offset-2 col-sm-3">
            <?= Html::submitButton('<i class="fa fa-check"></i> Simpan',['class' => 'btn btn-success btn-flat']) ?>
        </div>
    </div>

</div>

<?php ActiveForm::end(); ?>
