<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\kinerja\models\PegawaiSkp */
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

<div class="pegawai-skp-form box box-primary">

    <div class="box-header">
        <h3 class="box-title">Form Pegawai Skp</h3>
    </div>
	<div class="box-body">

        <?= $form->errorSummary($model); ?>

        <?= $form->field($model, 'id_instansi_pegawai')->textInput() ?>

        <?= $form->field($model, 'id_pegawai')->textInput() ?>

        <?= $form->field($model, 'id_instansi')->textInput() ?>

        <?= $form->field($model, 'id_jabatan')->textInput() ?>

        <?= $form->field($model, 'id_golongan')->textInput() ?>

        <?= $form->field($model, 'id_eselon')->textInput() ?>

        <?= $form->field($model, 'nomor')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'urutan')->textInput() ?>

        <?= $form->field($model, 'tahun')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'id_atasan')->textInput() ?>

        <?= $form->field($model, 'tanggal_berlaku')->textInput() ?>

        <?= $form->field($model, 'status_hapus')->textInput() ?>

        <?= Html::hiddenInput('referrer',$referrer); ?>

	</div>
    <div class="box-footer">
        <div class="col-sm-offset-2 col-sm-3">
            <?= Html::submitButton('<i class="fa fa-check"></i> Simpan',['class' => 'btn btn-success btn-flat']) ?>
        </div>
    </div>

</div>

<?php ActiveForm::end(); ?>
