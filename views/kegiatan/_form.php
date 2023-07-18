<?php

use kartik\date\DatePicker;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Kegiatan */
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

<div class="kegiatan-form box box-primary">

    <div class="box-header">
        <h3 class="box-title">Form Kegiatan</h3>
    </div>
	<div class="box-body">

        <?= $form->errorSummary($model); ?>

        <?= $form->field($model, 'nama')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'tanggal')->widget(DatePicker::className(), [
                'removeButton' => false,
                'value' => date('Y-m-d'),
                'options' => ['placeholder' => 'Tanggal'],
                'pluginOptions' => [
                    'autoclose'=>true,
                    'format' => 'yyyy-mm-dd'
                ]
        ]) ?>

        <?= $form->field($model, 'jam_mulai_absen',[
            'horizontalCssClasses' => [
                'label' => 'col-sm-2',
                'wrapper' => 'col-sm-2',
                'error' => '',
                'hint' => '',
            ],
        ])->textInput(['maxlength' => true, 'placeholder' => '00:00:00']) ?>

        <?= $form->field($model, 'jam_selesai_absen',[
            'horizontalCssClasses' => [
                'label' => 'col-sm-2',
                'wrapper' => 'col-sm-2',
                'error' => '',
                'hint' => '',
            ],
        ])->textInput(['maxlength' => true, 'placeholder' => '00:00:00']) ?>

        <?= Html::hiddenInput('referrer',$referrer); ?>

	</div>
    <div class="box-footer">
        <div class="col-sm-offset-2 col-sm-3">
            <?= Html::submitButton('<i class="fa fa-check"></i> Simpan',['class' => 'btn btn-success btn-flat']) ?>
        </div>
    </div>

</div>

<?php ActiveForm::end(); ?>
