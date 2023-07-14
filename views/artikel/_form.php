<?php

use kartik\date\DatePicker;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Artikel */
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

<div class="artikel-form box box-primary">

    <div class="box-header">
        <h3 class="box-title">Form Artikel</h3>
    </div>
	<div class="box-body">

        <?= $form->errorSummary($model); ?>

        <?= $form->field($model, 'judul')->textarea(['maxlength' => true]) ?>

        <?= $form->field($model, 'slug')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'konten',[
            'horizontalCssClasses' => [
                'label' => 'col-sm-2',
                'wrapper' => 'col-sm-8',
            ],
        ])->widget(\dosamigos\ckeditor\CKEditor::className(), [
            'options' => ['rows' => 6,'placeholder' => 'Isi Konten Thread Disini'],
            'preset' => 'advanced'
        ]) ?>

        <?= $form->field($model, 'waktu_terbit')->widget(\kartik\datetime\DateTimePicker::classname(), [
            'options' => ['placeholder' => 'Masukan Tanggal dan Jam', 'autocomplete' => 'off'],
            'pluginOptions' => [
                'autoclose' => true
            ]
        ]) ?>

         <?= $form->field($model, 'thumbnail')->widget(\kartik\file\FileInput::classname(), [
            'options' => ['accept' => 'image/*'],
            'pluginOptions' => [
                /*'showCaption' => false,*/
                'showRemove' => false,
                'showUpload' => false,
                'browseClass' => 'btn btn-primary btn-block',
                'browseIcon' => '<i class="glyphicon glyphicon-camera"></i> ',
                'browseLabel' =>  'Pilih Photo'
            ]
        ])->label('Upload Cover Artikel'); ?>

        <?= Html::hiddenInput('referrer', $referrer) ?>


	</div>
    <div class="box-footer">
        <div class="col-sm-offset-2 col-sm-3">
            <?= Html::submitButton('<i class="fa fa-check"></i> Simpan',['class' => 'btn btn-success btn-flat']) ?>
        </div>
    </div>

</div>

<?php ActiveForm::end(); ?>
