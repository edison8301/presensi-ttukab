<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\absensi\models\KetidakhadiranCeklis */
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

<div class="ketidakhadiran-ceklis-form box box-primary">

    <div class="box-header">
        <h3 class="box-title">Form Ketidakhadiran Ceklis</h3>
    </div>
	<div class="box-body">

        <?= $form->errorSummary($model); ?>

        <?= $form->field($model->pegawai, 'nama')->textInput(['disabled' => true])->label('Pegawai') ?>

        <?= $form->field($model, 'tanggal')->textInput(['disabled' => true]) ?>

        <?= $form->field($model->jamKerja->jamKerjaJenis, 'nama')->textInput(['disabled' => true])->label('Jam Kerja') ?>

        <?= $form->field($model, 'keterangan')->textarea(['rows' => 6]) ?>

        <?= Html::hiddenInput('referrer',$referrer); ?>

	</div>
    <div class="box-footer">
        <div class="col-sm-offset-2 col-sm-3">
            <?= Html::submitButton('<i class="fa fa-check"></i> Simpan',['class' => 'btn btn-success btn-flat']) ?>
        </div>
    </div>

</div>

<?php ActiveForm::end(); ?>
