<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\KegiatanKetidakhadiran */
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

<div class="kegiatan-ketidakhadiran-form box box-primary">

    <div class="box-header">
        <h3 class="box-title">Form Kegiatan Ketidakhadiran</h3>
    </div>
	<div class="box-body">

        <?= $form->errorSummary($model); ?>

        <?= $form->field($model, 'nip')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'id_kegiatan_ketidakhadiran_jenis')->textInput() ?>

        <?= $form->field($model, 'penjelasan')->textarea(['rows' => 6]) ?>

        <?= $form->field($model, 'checktime')->textInput() ?>

        <?= $form->field($model, 'foto_pendukung')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'created_at')->textInput() ?>

        <?= $form->field($model, 'updated_at')->textInput() ?>

        <?= $form->field($model, 'deleted_at')->textInput() ?>

        <?= Html::hiddenInput('referrer',$referrer); ?>

	</div>
    <div class="box-footer">
        <div class="col-sm-offset-2 col-sm-3">
            <?= Html::submitButton('<i class="fa fa-check"></i> Simpan',['class' => 'btn btn-success btn-flat']) ?>
        </div>
    </div>

</div>

<?php ActiveForm::end(); ?>
