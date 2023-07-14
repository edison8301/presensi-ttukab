<?php

use app\modules\absensi\models\PegawaiAbsensiManual;
use kartik\date\DatePicker;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PegawaiAbsensiManual */
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

<div class="pegawai-absensi-manual-form box box-primary">

    <div class="box-header">
        <h3 class="box-title">Form Pegawai Absensi Manual</h3>
    </div>
	<div class="box-body">

        <?= $form->errorSummary($model); ?>

        <?= $form->field($model, 'namaPegawai')->textInput(['readonly'=>'readonly']) ?>

        <?= $form->field($model, 'tanggal_mulai')->widget(DatePicker::class, [
            'removeButton' => false,
            'options' => ['placeholder' => 'Tanggal Mulai', 'autocomplete' => 'off'],
            'pluginOptions' => [
                'autoclose' => true,
                'format' => 'yyyy-mm-dd'
            ]
        ]) ?>

        <?= $form->field($model, 'tanggal_selesai')->widget(DatePicker::class, [
            'removeButton' => false,
            'options' => ['placeholder' => 'Tanggal Selesai', 'autocomplete' => 'off'],
            'pluginOptions' => [
                'autoclose' => true,
                'format' => 'yyyy-mm-dd'
            ]
        ]) ?>

        <?= $form->field($model, 'status')->dropDownList(PegawaiAbsensiManual::getListStatus())
            ->label('Absensi Manual')
            ->hint('1. Aktif: merubah absensi menjadi absensi manual <br>2. Tidak Aktif: menonaktifkan absensi manual') ?>

        <?= Html::hiddenInput('referrer',$referrer); ?>

	</div>
    <div class="box-footer">
        <div class="col-sm-offset-2 col-sm-3">
            <?= Html::submitButton('<i class="fa fa-check"></i> Simpan',['class' => 'btn btn-success btn-flat']) ?>
        </div>
    </div>

</div>

<?php ActiveForm::end(); ?>
