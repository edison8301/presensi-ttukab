<?php

use app\modules\tunjangan\models\JabatanTunjanganGolongan;
use app\modules\tunjangan\models\JabatanTunjanganKhusus;
use app\modules\tunjangan\models\JabatanTunjanganKhususJenis;
use kartik\date\DatePicker;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $model app\modules\tunjangan\models\JabatanTunjanganKhusus */
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

<div class="jabatan-tunjangan-khusus-form box box-primary">

    <div class="box-header">
        <h3 class="box-title">Form Jabatan Tunjangan Khusus</h3>
    </div>
	<div class="box-body">

        <?= $form->errorSummary($model); ?>

        <?= $form->field($model, 'id_jabatan_tunjangan_khusus_jenis')->dropDownList(
            JabatanTunjanganKhususJenis::getList(),
            ['option' => 'value']
        ); ?>


        <?= $form->field($model, 'id_jabatan_tunjangan_golongan')->dropDownList(
            JabatanTunjanganGolongan::getList(),
            ['prompt' => '- Golongan -']
        ); ?>

        <?= $form->field($model, 'kelas_jabatan')->textInput() ?>

        <?= $form->field($model, 'besaran_tpp')->widget(MaskedInput::class, [
            'clientOptions' => [
                'alias' =>  'decimal',
                'groupSeparator' => ',',
                'autoGroup' => true
            ],
        ]); ?>

        <?= $form->field($model, 'tanggal_mulai')->widget(DatePicker::class, [
            'removeButton' => false,
            'options' => ['placeholder' => 'Tanggal Selesai'],
            'pluginOptions' => [
                'autoclose'=>true,
                'format' => 'yyyy-mm-dd'
            ]
        ])->label('Tanggal Berlaku Mulai') ?>

        <?= $form->field($model, 'tanggal_selesai')->widget(DatePicker::class, [
            'removeButton' => false,
            'options' => ['placeholder' => 'Tanggal Selesai'],
            'pluginOptions' => [
                'autoclose'=>true,
                'format' => 'yyyy-mm-dd'
            ]
        ])->label('Tanggal Berlaku Selesai') ?>

        <?= $form->field($model, 'keterangan')->dropDownList(JabatanTunjanganKhusus::getListKeterangan(), [
            'prompt' => '- Pilih Keterangan -',
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
