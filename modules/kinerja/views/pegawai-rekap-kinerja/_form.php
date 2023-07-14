<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\kinerja\models\PegawaiRekapKinerja */
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

<div class="pegawai-rekap-kinerja-form box box-primary">

    <div class="box-header">
        <h3 class="box-title">Form Pegawai Rekap Kinerja</h3>
    </div>
	<div class="box-body">

        <?= $form->errorSummary($model); ?>

        <?= $form->field($model, 'id_pegawai')->textInput() ?>

        <?= $form->field($model, 'id_instansi')->textInput() ?>

        <?= $form->field($model, 'bulan')->textInput() ?>

        <?= $form->field($model, 'tahun')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'potongan_skp')->textInput() ?>

        <?= $form->field($model, 'potongan_ckhp')->textInput() ?>

        <?= $form->field($model, 'potongan_total')->textInput() ?>

        <?= $form->field($model, 'waktu_buat')->textInput() ?>

        <?= $form->field($model, 'waktu_update')->textInput() ?>

        <?= Html::hiddenInput('referrer',$referrer); ?>

	</div>
    <div class="box-footer">
        <div class="col-sm-offset-2 col-sm-3">
            <?= Html::submitButton('<i class="fa fa-check"></i> Simpan',['class' => 'btn btn-success btn-flat']) ?>
        </div>
    </div>

</div>

<?php ActiveForm::end(); ?>
