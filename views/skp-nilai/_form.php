<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\SkpNilai */
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

<div class="skp-nilai-form box box-primary">

    <div class="box-header">
        <h3 class="box-title">Form Skp Nilai</h3>
    </div>
	<div class="box-body">

        <?= $form->errorSummary($model); ?>

        <?= $form->field($model, 'namaSkpPeriode')->textInput([
            'readonly' => 'readonly'
        ]) ?>

        <?= $form->field($model, 'periode')->textInput([
            'readonly' => 'readonly'
        ]) ?>

        <?= $form->field($model, 'feedback_hasil_kerja')->textarea(['rows'=>7]) ?>

        <?= $form->field($model, 'nilai_hasil_kerja')->dropDownList([
            '1' => 'Di Bawah Ekspekstasi',
            '2' => 'Sesuai Ekspektasi',
            '3' => 'Di Atas Ekspektasi'
        ],[
            'prompt' => '- Pilih Nilai -'
        ]) ?>

        <?= $form->field($model, 'feedback_perilaku_kerja')->textarea(['rows'=>7])?>

        <?= $form->field($model, 'nilai_perilaku_kerja')->dropDownList([
            '1' => 'Di Bawah Ekspekstasi',
            '2' => 'Sesuai Ekspektasi',
            '3' => 'Di Atas Ekspektasi'
        ],[
            'prompt' => '- Pilih Nilai -'
        ]) ?>

       

        <?= Html::hiddenInput('referrer',$referrer); ?>

	</div>
    <div class="box-footer">
        <div class="col-sm-offset-2 col-sm-3">
            <?= Html::submitButton('<i class="fa fa-check"></i> Simpan',['class' => 'btn btn-success btn-flat']) ?>
        </div>
    </div>

</div>

<?php ActiveForm::end(); ?>
