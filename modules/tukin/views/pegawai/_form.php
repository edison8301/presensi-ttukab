<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\tukin\models\Pegawai */
/* @var $form yii\widgets\ActiveForm */
/* @var $referrer  */
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

<div class="pegawai-form box box-primary">

    <div class="box-header">
        <h3 class="box-title">Form Pegawai</h3>
    </div>
	<div class="box-body">

        <?= $form->errorSummary($model); ?>

        <?= $form->field($model, 'nama')->textInput(['maxlength' => true,'readonly'=>'readonly']) ?>

        <?= $form->field($model, 'id_jabatan')->widget(\kartik\select2\Select2::class, [
            'data' => \app\modules\tukin\models\Jabatan::getList(),
            'options' => [
                'placeholder' => '- Pilih Jabatan -',
            ],
        ]) ?>



        <?= Html::hiddenInput('referrer',$referrer); ?>

	</div>
    <div class="box-footer">
        <div class="col-sm-offset-2 col-sm-3">
            <?= Html::submitButton('<i class="fa fa-check"></i> Simpan',['class' => 'btn btn-success btn-flat']) ?>
        </div>
    </div>

</div>

<?php ActiveForm::end(); ?>
