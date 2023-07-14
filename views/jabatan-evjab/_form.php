<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\JabatanEvjab */
/* @var $form yii\widgets\ActiveForm */
/* @var $referrer string */

?>

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

<div class="jabatan-evjab-form box box-primary">

    <div class="box-header">
        <h3 class="box-title">Form Jabatan Evjab</h3>
    </div>
	<div class="box-body">

        <?= $form->errorSummary($model); ?>

        <?= $form->field($model, 'id_jenis_jabatan')->textInput() ?>

        <?= $form->field($model, 'id_instansi')->textInput() ?>

        <?= $form->field($model, 'id_instansi_bidang')->textInput() ?>

        <?= $form->field($model, 'nama')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'nilai_jabatan')->textInput() ?>

        <?= $form->field($model, 'kelas_jabatan')->textInput() ?>

        <?= $form->field($model, 'persediaan_pegawai')->textInput() ?>

        <?= $form->field($model, 'id_induk')->textInput() ?>

        <?= $form->field($model, 'status_hapus')->textInput() ?>

        <?= $form->field($model, 'waktu_hapus')->textInput() ?>

        <?= $form->field($model, 'id_user_hapus')->textInput() ?>

        <?= $form->field($model, 'nomor')->textInput() ?>

        <?= Html::hiddenInput('referrer',$referrer); ?>

	</div>
    <div class="box-footer">
        <div class="col-sm-offset-2 col-sm-3">
            <?= Html::submitButton('<i class="fa fa-check"></i> Simpan',['class' => 'btn btn-success btn-flat']) ?>
        </div>
    </div>

</div>

<?php ActiveForm::end(); ?>
