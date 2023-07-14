<?php

use app\modules\absensi\models\KetidakhadiranPanjang;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\select2\Select2;
use app\models\Pegawai;
use kartik\date\DatePicker;
use app\modules\absensi\models\KetidakhadiranPanjangStatus;
use app\modules\absensi\models\KetidakhadiranPanjangJenis;

/* @var $this yii\web\View */
/* @var $model KetidakhadiranPanjang */
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

<div class="ketidakhadiran-panjang-form box box-primary">

    <div class="box-header">
        <h3 class="box-title">Form Ketidakhadiran Hari Kerja</h3>
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

        <?= $form->field($model, 'tanggal_mulai')->widget(DatePicker::className(), [
                'removeButton' => false,
                'value' => date('Y-m-d'),
                'options' => ['placeholder' => 'Tanggal Mulai'],
                'pluginOptions' => [
                    'autoclose'=>true,
                    'format' => 'yyyy-mm-dd'
                ]
        ]) ?>

        <?= $form->field($model, 'tanggal_selesai')->widget(DatePicker::className(), [
                'removeButton' => false,
                'value' => date('Y-m-d'),
                'options' => ['placeholder' => 'Tanggal Selesai'],
                'pluginOptions' => [
                    'autoclose'=>true,
                    'format' => 'yyyy-mm-dd'
                ]
        ]) ?>

        <?= $form->field($model, 'id_ketidakhadiran_panjang_jenis')->dropDownList(KetidakhadiranPanjangJenis::getList([
            'id_is_not' => KetidakhadiranPanjangJenis::KETIDAKHADIRAN_ALASAN_KHUSUS,
        ]),['prompt'=>'- Pilih Jenis Ketidakhadiran -']) ?>

        <?php if($model->accessIdKetidakhadiranPanjangStatus()) { ?>
        <?= $form->field($model, 'id_ketidakhadiran_panjang_status')->dropDownList(KetidakhadiranPanjangStatus::getList()) ?>
        <?php } ?>

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
