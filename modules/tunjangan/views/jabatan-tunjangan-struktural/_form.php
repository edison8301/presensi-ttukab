<?php

use app\models\Golongan;
use app\modules\tukin\models\Eselon;
use app\modules\tukin\models\Instansi;
use app\modules\tunjangan\models\JabatanGolongan;
use app\modules\tunjangan\models\JabatanTunjanganGolongan;
use kartik\date\DatePicker;
use kartik\money\MaskMoney;
use kartik\select2\Select2;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $model app\modules\tunjangan\models\JabatanTunjanganStruktural */
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

<div class="jabatan-tunjangan-struktural-form box box-primary">

    <div class="box-header">
        <h3 class="box-title">Form Jabatan Tunjangan Struktural</h3>
    </div>
	<div class="box-body">

        <?= $form->errorSummary($model); ?>

        <?= $form->field($model, 'id_eselon')->dropDownList(Eselon::getList()); ?>

        <?= $form->field($model, 'id_instansi')->widget(Select2::className(), [
            'data' => Instansi::getList(),
            'options' => [
                'placeholder' => '- Pilih Instansi -',
            ],
            'pluginOptions' => [
                'allowClear' => true,
            ]
        ]); ?>

        <?= $form->field($model, 'id_jabatan_tunjangan_golongan')->dropDownList(
            JabatanTunjanganGolongan::getList(),
            ['prompt'=>'- Pilih Golongan -']
        ); ?>

        <?= $form->field($model, 'kelas_jabatan')->textInput(); ?>

        <?= $form->field($model, 'besaran_tpp')->widget(MaskedInput::classname(), [
            'clientOptions' => [
                'alias' =>  'decimal',
                'groupSeparator' => ',',
                'autoGroup' => true
            ],
        ]); ?>

        <?= $form->field($model, 'tanggal_mulai')->widget(DatePicker::className(), [
            'removeButton' => false,
            'options' => ['placeholder' => 'Tanggal Selesai'],
            'pluginOptions' => [
                'autoclose'=>true,
                'format' => 'yyyy-mm-dd'
            ]
        ])->label('Tanggal Berlaku Mulai') ?>

        <?= $form->field($model, 'tanggal_selesai')->widget(DatePicker::className(), [
            'removeButton' => false,
            'options' => ['placeholder' => 'Tanggal Selesai'],
            'pluginOptions' => [
                'autoclose'=>true,
                'format' => 'yyyy-mm-dd'
            ]
        ])->label('Tanggal Berlaku Selesai') ?>



        <?= Html::hiddenInput('referrer',$referrer); ?>

	</div>
    <div class="box-footer">
        <div class="col-sm-offset-2 col-sm-3">
            <?= Html::submitButton('<i class="fa fa-check"></i> Simpan',['class' => 'btn btn-success btn-flat']) ?>
        </div>
    </div>

</div>

<?php ActiveForm::end(); ?>
