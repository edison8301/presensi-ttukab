<?php

use app\components\Session;
use app\modules\kinerja\models\KegiatanTahunan;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\kinerja\models\KegiatanTriwulan */
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

<div class="kegiatan-triwulan-form box box-primary">

    <div class="box-header">
        <h3 class="box-title">Form Kegiatan Triwulan</h3>
    </div>
	<div class="box-body">

        <?= $form->errorSummary($model); ?>

        <?= $form->field($model, 'id_kegiatan_tahunan')->widget(Select2::className(), [
            'data' => KegiatanTahunan::getList([
                'id_pegawai' => Session::getIdPegawai(),
                'tahun' => Session::getTahun()
            ]),
            'options' => [
                'placeholder' => '- Pilih Kegiatan Tahunan -',
            ],
            'pluginOptions' => [
                'allowClear' => true,
            ]
        ]); ?>

        <?php /* $form->field($model, 'id_kegiatan_bulanan')->textInput() */ ?>

        <?php /* $form->field($model, 'tahun')->textInput(['maxlength' => true]) */?>

        <?= $form->field($model, 'periode')->textInput() ?>

        <?= $form->field($model, 'target')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'realisasi')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'persen_capaian')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'deskripsi_capaian')->textarea(['rows' => 4]) ?>

        <?= $form->field($model, 'kendala')->textarea(['rows' => 4]) ?>

        <?= $form->field($model, 'tindak_lanjut')->textarea(['rows' => 4]) ?>

        <?= Html::hiddenInput('referrer',$referrer); ?>

	</div>
    <div class="box-footer">
        <div class="col-sm-offset-2 col-sm-3">
            <?= Html::submitButton('<i class="fa fa-check"></i> Simpan',['class' => 'btn btn-success btn-flat']) ?>
        </div>
    </div>

</div>

<?php ActiveForm::end(); ?>
