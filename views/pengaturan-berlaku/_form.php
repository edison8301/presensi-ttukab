<?php

use kartik\date\DatePicker;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PengaturanBerlaku */
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

<div class="pengaturan-berlaku-form box box-primary">

    <div class="box-header">
        <h3 class="box-title">Form Pengaturan Berlaku</h3>
    </div>
	<div class="box-body">

        <?= $form->errorSummary($model); ?>

        <?php if($model->id_pengaturan == null) { ?>
            <?= $form->field($model, 'id_pengaturan')->textInput() ?>
        <?php } ?>

        <?= $model->getFormFieldNilai($form); ?>

        <?= $form->field($model, 'tanggal_mulai')->widget(DatePicker::class, [
            'removeButton' => false,
            'options' => ['placeholder' => 'Kosongkan untuk pengisian otomatis'],
            'pluginOptions' => [
                'autoclose' => true,
                'format' => 'yyyy-mm-dd'
            ]
        ]) ?>

        <?= $form->field($model, 'tanggal_selesai')->widget(DatePicker::class, [
            'removeButton' => false,
            'options' => ['placeholder' => 'Kosongkan untuk pengisian otomatis'],
            'pluginOptions' => [
                'autoclose' => true,
                'format' => 'yyyy-mm-dd'
            ]
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
