<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\absensi\models\ShiftKerja */
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



<div class="instansi-form box box-primary">

    <div class="box-header">
        <h3 class="box-title">Form Shift Kerja</h3>
    </div>
	<div class="box-body">
        <?= $form->errorSummary($model); ?>

	    <?= $form->field($model, 'nama')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'status_libur_nasional')->dropDownList([
            0 => 'Tidak',
            1 => 'Ya',
        ]); ?>

        <?= $form->field($model, 'hari_kerja')->textInput(['maxlength' => true]); ?>
	</div>
    <div class="box-footer">
        <div class="col-sm-offset-2 col-sm-3">
            <?= Html::submitButton('<i class="fa fa-check"></i> Simpan',['class' => 'btn btn-success btn-flat']) ?>
        </div>
    </div>

</div>

<?php ActiveForm::end(); ?>
