<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\models\Pegawai;
use kartik\select2\Select2;
use app\modules\absensi\models\KetidakhadiranKegiatanKeterangan;
use app\modules\absensi\models\KetidakhadiranKegiatanStatus;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\KetidakhadiranKegiatan */
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

<div class="ketidakhadiran-kegiatan-form box box-primary">

    <div class="box-header">
        <h3 class="box-title">Form Ketidakhadiran Kegiatan</h3>
    </div>
	<div class="box-body">

        <?= $form->errorSummary($model); ?>

        <?= $form->field($model, 'id_pegawai')->widget(Select2::className(), [
            'data' => Pegawai::getList(),
            'options' => [
                'placeholder' => '- Pilih Pegawai -',
            ],
            'pluginOptions' => [
                'allowClear' => true,
            ]
        ]); ?>

        <?= $form->field($model, 'tanggal')->widget(DatePicker::className(), [
                'removeButton' => false,
                'value' => date('Y-m-d'),
                'options' => ['placeholder' => 'Tanggal'],
                'pluginOptions' => [
                    'autoclose'=>true,
                    'format' => 'yyyy-mm-dd'
                ]
        ]) ?>

        <?= $form->field($model, 'id_ketidakhadiran_kegiatan_keterangan')->dropDownList(KetidakhadiranKegiatanKeterangan::getList()) ?>

        <?= $form->field($model, 'keterangan')->textarea(['rows' => 6]) ?>

        <?php if($model->accessIdKetidakhadiranKegiatanStatus()) { ?>
        <?= $form->field($model, 'id_ketidakhadiran_kegiatan_status')->dropDownList(KetidakhadiranKegiatanStatus::getList()) ?>
        <?php } ?>

        <?= Html::hiddenInput('referrer',$referrer); ?>

	</div>
    <div class="box-footer">
        <div class="col-sm-offset-2 col-sm-3">
            <?= Html::submitButton('<i class="fa fa-check"></i> Simpan',['class' => 'btn btn-success btn-flat']) ?>
        </div>
    </div>

</div>

<?php ActiveForm::end(); ?>
