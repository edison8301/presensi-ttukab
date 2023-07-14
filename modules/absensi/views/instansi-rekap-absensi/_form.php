<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\InstansiRekapAbsensi */
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

<div class="instansi-rekap-absensi-form box box-primary">

    <div class="box-header">
        <h3 class="box-title">Form Instansi Rekap Absensi</h3>
    </div>
	<div class="box-body">

        <?= $form->errorSummary($model); ?>

        <?= $form->field($model, 'id_instansi')->textInput() ?>

        <?= $form->field($model, 'bulan')->textInput() ?>

        <?= $form->field($model, 'tahun')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'persen_potongan_total')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'persen_potongan_fingerprint')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'persen_potongan_kegiatan')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'waktu_diperbarui')->textInput() ?>

        <?= Html::hiddenInput('referrer',$referrer); ?>

	</div>
    <div class="box-footer">
        <div class="col-sm-offset-2 col-sm-3">
            <?= Html::submitButton('<i class="fa fa-check"></i> Simpan',['class' => 'btn btn-success btn-flat']) ?>
        </div>
    </div>

</div>

<?php ActiveForm::end(); ?>
