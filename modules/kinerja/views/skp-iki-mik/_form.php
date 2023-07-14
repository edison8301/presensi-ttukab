<?php

use app\modules\kinerja\models\SkpIkiMik;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\kinerja\models\SkpIkiMik */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = ActiveForm::begin([
    //'layout'=>'horizontal',
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

<div class="skp-iki-mik-form box box-primary">

    <div class="box-header with-border">
        <h3 class="box-title">Form Manual Indikator Kinerja</h3>
    </div>
	<div class="box-body">

        <?= $form->errorSummary($model); ?>

        <?= $form->field($model, 'tujuan')->textarea(['rows' => 1]) ?>

        <?= $form->field($model, 'definisi')->textarea(['rows' => 3]) ?>

        <?= $form->field($model, 'formula')->textarea(['rows' => 3])
            ->label('Formula (Opsional bagi pendekatan hasil kerja kualitatif)')?>

        <?= $form->field($model, 'satuan_pengukuran')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'kualitas_tingkat_kendali')
            ->inline()
            ->radioList(SkpIkiMik::getListKualitasTingkatKendali()) ?>

        <?= $form->field($model, 'sumber_data')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'periode_pelaporan')
            ->inline()
            ->radioList(SkpIkiMik::getListPeriodePelaporan()) ?>

        <?= Html::hiddenInput('referrer',$referrer); ?>

	</div>
    <div class="box-footer">
        <div class="col-sm-3">
            <?= Html::submitButton('<i class="fa fa-check"></i> Simpan',['class' => 'btn btn-success btn-flat']) ?>
        </div>
    </div>

</div>

<?php ActiveForm::end(); ?>
