<?php

use app\models\Pegawai;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PegawaiRb */
/* @var $form yii\widgets\ActiveForm */
/* @var $referrer bool */
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

<div class="pegawai-rb-form box box-primary">

    <div class="box-header">
        <h3 class="box-title">Form Pegawai <?= @$model->pegawaiRbJenis->nama ?></h3>
    </div>
	<div class="box-body">

        <?= $form->errorSummary($model); ?>

        <?= $form->field($model, 'id_pegawai')->widget(Select2::class, [
            'data' => Pegawai::getList(),
            'options' => [
                'placeholder' => '- Pilih Pegawai -',
                'id' => 'id-instansi',
            ],
            'pluginOptions' => [
                'allowClear' => true,
            ]
        ]); ?>

        <?= $form->field($model, 'tanggal')->widget(DatePicker::className(), [
            'removeButton' => false,
            'options' => ['placeholder' => 'Tanggal', 'autocomplete' => 'off'],
            'pluginOptions' => [
                'autoclose' => true,
                'format' => 'yyyy-mm-dd'
            ]
        ]) ?>

        <?php if ($model->isNewRecord) { ?>
            <?= $form->field($model, 'id_pegawai_rb_jenis')->dropDownList(
                \app\models\PegawaiRbJenis::getList(),
                [
                    'prompt' => '- Pilih Jenis -',
                ]
            )->label('Jenis') ?>
        <?php } ?>

        <?= $form->field($model, 'status_realisasi')->dropDownList([
            0 => 'Belum',
            1 => 'Sudah',
        ])->label('Status') ?>

        <?= Html::hiddenInput('referrer',$referrer); ?>

	</div>
    <div class="box-footer">
        <div class="col-sm-offset-2 col-sm-3">
            <?= Html::submitButton('<i class="fa fa-check"></i> Simpan',['class' => 'btn btn-success btn-flat']) ?>
        </div>
    </div>

</div>

<?php ActiveForm::end(); ?>
