<?php

use app\models\Instansi;
use app\models\Jabatan;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\select2\Select2;
use kartik\date\DatePicker;
use app\models\Pegawai;
use app\models\Golongan;
use app\models\Eselon;

/* @var $this yii\web\View */
/* @var $model app\models\InstansiPegawai */
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

<div class="instansi-pegawai-form box box-primary">

    <div class="box-header">
        <h3 class="box-title">Form Instansi Pegawai</h3>
    </div>
	<div class="box-body">

        <?= $form->errorSummary($model); ?>

        <?= $form->field($model, 'namaInstansi')->textarea(['rows'=>3,'readonly'=>'readonly'])->label("Unit Kerja") ?>

        <?php if($model->id_pegawai==null) { ?>
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
        <?php } ?>

        <?php if($model->id_pegawai!=null) { ?>
            <?= $form->field($model, 'namaNipPegawai')->textarea(['rows'=>2,'readonly'=>'readonly'])->label('Pegawai') ?>
        <?php } ?>

        <?= $form->field($model, 'id_jabatan')->widget(
            Select2::class,
            [
                'data' => Jabatan::getListStruktur($model->id_instansi),
                'options' => [
                    'prompt' => '- Pilih Jabatan -'
                ]
            ]
        ); ?>

        <?= $form->field($model, 'status_plt')->dropDownList(
            ['0'=>'Bukan Plt','1'=>'Plt']
        ); ?>

        <?= $form->field($model, 'tanggal_berlaku')->widget(DatePicker::className(), [
            'removeButton' => false,
            'options' => ['placeholder' => 'Tanggal'],
            'pluginOptions' => [
                'autoclose' => true,
                'format' => 'yyyy-mm-dd'
            ]
        ]) ?>

        <hr/>

        <?= $form->field($model, 'tanggal_mulai')->widget(DatePicker::className(), [
            'removeButton' => false,
            'options' => ['placeholder' => 'Kosongkan untuk pengisian otomatis'],
            'pluginOptions' => [
                'autoclose' => true,
                'format' => 'yyyy-mm-dd'
            ]
        ]) ?>

        <?= $form->field($model, 'tanggal_selesai')->widget(DatePicker::className(), [
            'removeButton' => false,
            'options' => ['placeholder' => 'Kosongkan untuk pengisian otomatis'],
            'pluginOptions' => [
                'autoclose' => true,
                'format' => 'yyyy-mm-dd'
            ]
        ]) ?>

        <?php if (!$model->isNewRecord) { ?>
            <hr>
            <?= $form->field($model, 'nama_jabatan')->textarea(['rows'=>3])->label('Jabatan<br/>(Isian Bebas)') ?>

            <?= $form->field($model, 'nama_jabatan_atasan')->textarea(['rows'=>3])->label('Jabatan Atasan<br/>(Isian Bebas)') ?>
            
            <?= $form->field($model, 'nama_instansi')->textarea(['rows'=>3])->label('Instansi<br/>(Isian Bebas)') ?>
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
