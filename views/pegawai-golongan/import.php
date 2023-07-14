<?php

use app\models\ImportPegawaiGolonganForm;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model ImportPegawaiGolonganForm */

$this->title = 'Form Import Golongan Pegawai';

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

<div class="box box-primary">
    <div class="box-header">
        <h3 class="box-title">Kolom pada file Excel</h3>
    </div>
    <div class="box-body">
        <?= $form->field($model, 'kolom_nip')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'kolom_golongan')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'kolom_tmt')->textInput(['maxlength' => true])
            ->hint('Format tanggal dalam excel: dd-mm-yyyy <br>Contoh: 01-01-2022')?>
    </div>
</div>

<div class="box box-primary">
    <div class="box-header">
        <h3 class="box-title">Baris pada file Excel Pada data yang akan di import</h3>
    </div>
    <div class="box-body">
        <?= $form->field($model, 'baris_awal')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'baris_akhir')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'berkas')->fileInput() ?>
    </div>
    <div class="box-footer">
        <div class="col-sm-offset-2 col-sm-3">
            <?= Html::submitButton('<i class="fa fa-check"></i> Simpan',['class' => 'btn btn-success btn-flat']) ?>
        </div>
    </div>
</div>

<?php ActiveForm::end() ?>
