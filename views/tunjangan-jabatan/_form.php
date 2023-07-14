<?php

use app\models\Jabatan;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TunjanganJabatan */
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

<div class="tunjangan-jabatan-form box box-primary">

    <div class="box-header">
        <h3 class="box-title">Form Tunjangan Jabatan</h3>
    </div>
	<div class="box-body">

        <?= $form->errorSummary($model); ?>

        <?php if($model->isNewRecord) { ?>
        <?= $form->field($model, 'id_jabatan')->dropDownList(
            Jabatan::getListStruktur($model->id_instansi, $model->status_kepala),
            ['prompt' => '- Pilih Jabatan -']
        ); ?>
        <?php } ?>

        <?php if($model->isNewRecord==false) { ?>
            <?= $form->field($model, 'namaJabatan')->textarea(['readonly'=>'readonly','rows'=>4]); ?>
        <?php } ?>



        <?php /*
        <?= $form->field($model, 'jumlah_tunjangan')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'tanggal_mulai')->textInput() ?>

        <?= $form->field($model, 'tanggal_selesai')->textInput() ?>
        */ ?>

        <?= Html::hiddenInput('referrer',$referrer); ?>

	</div>
    <div class="box-footer">
        <div class="col-sm-offset-2 col-sm-3">
            <?= Html::submitButton('<i class="fa fa-check"></i> Simpan',['class' => 'btn btn-success btn-flat']) ?>
        </div>
    </div>

</div>

<?php ActiveForm::end(); ?>
