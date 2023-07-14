<?php

use app\components\Session;
use app\models\Pegawai;
use app\models\PegawaiPenghargaanStatus;
use app\models\PegawaiPenghargaanTingkat;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PegawaiPenghargaan */
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

<div class="pegawai-penghargaan-form box box-primary">

    <div class="box-header">
        <h3 class="box-title">Form Pegawai Penghargaan</h3>
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

        <?= $form->field($model, 'nama')->textarea(['rows' => 3]) ?>

        <?= $form->field($model, 'tanggal')->widget(DatePicker::className(), [
            'removeButton' => false,
            'options' => ['placeholder' => 'Tanggal'],
            'pluginOptions' => [
                'autoclose' => true,
                'format' => 'yyyy-mm-dd'
            ]
        ]) ?>

        <?= $form->field($model, 'id_pegawai_penghargaan_tingkat')->dropDownList(
            PegawaiPenghargaanTingkat::getList(),
            [
                'prompt' => '- Pilih Tingkat -',
            ]
        )->label('Tingkat') ?>

        <?php if (Session::isAdmin()) { ?>
            <?= $form->field($model, 'id_pegawai_penghargaan_status')->dropDownList(
                PegawaiPenghargaanStatus::getList()
            )->label('Status') ?>
        <?php } ?>

        <?= Html::hiddenInput('referrer', $referrer); ?>

	</div>
    <div class="box-footer">
        <div class="col-sm-offset-2 col-sm-3">
            <?= Html::submitButton('<i class="fa fa-check"></i> Simpan',['class' => 'btn btn-success btn-flat']) ?>
        </div>
    </div>

</div>

<?php ActiveForm::end(); ?>
