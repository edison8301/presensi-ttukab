<?php

use app\modules\absensi\models\JamKerjaJenis;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\absensi\models\JamKerja */
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

<div class="jam-kerja-form box box-primary">

    <div class="box-header">
        <h3 class="box-title">Form Jam Kerja</h3>
    </div>
    <div class="box-body">

        <?= $form->field($model, 'hari')->dropDownList([
                '1'=>'Senin',
                '2'=>'Selasa',
                '3'=>'Rabu',
                '4'=>'Kamis',
                '5'=>'Jumat',
                '6'=>'Sabtu',
                '7'=>'Minggu',
                '8'=>'Tanggal Merah'
        ]) ?>

        <?= $form->field($model, 'id_jam_kerja_jenis')->dropDownList(JamKerjaJenis::getList()) ?>

        <?= $form->field($model, 'nama')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'jam_mulai_hitung',[
            'horizontalCssClasses' => [
                'label' => 'col-sm-2',
                'wrapper' => 'col-sm-2',
                'error' => '',
                'hint' => '',
            ],
        ])->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'jam_minimal_absensi',[
            'horizontalCssClasses' => [
                'label' => 'col-sm-2',
                'wrapper' => 'col-sm-2',
                'error' => '',
                'hint' => '',
            ],
        ])->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'jam_maksimal_absensi',[
            'horizontalCssClasses' => [
                'label' => 'col-sm-2',
                'wrapper' => 'col-sm-2',
                'error' => '',
                'hint' => '',
            ],
        ])->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'jam_selesai_hitung',[
            'horizontalCssClasses' => [
                'label' => 'col-sm-2',
                'wrapper' => 'col-sm-2',
                'error' => '',
                'hint' => '',
            ],
        ])->textInput(['maxlength' => true]) ?>

    </div>

    <div class="box-footer">
        <div class="col-sm-offset-2 col-sm-3">
            <?= Html::submitButton('<i class="fa fa-check"></i> Simpan',['class' => 'btn btn-success btn-flat']) ?>
        </div>
    </div>

</div>

<?php ActiveForm::end(); ?>
