<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\models\Instansi;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\modules\absensi\models\MesinAbsensi */
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

<div class="mesin-absensi-form box box-primary">

    <div class="box-header">
        <h3 class="box-title">Form Mesin Absensi</h3>
    </div>
	<div class="box-body">

        <?= $form->errorSummary($model); ?>

        <?= $form->field($model, 'id_instansi',[
            'horizontalCssClasses' => [
                'wrapper' => 'col-sm-4',
            ]
        ])->widget(select2::className(), [
            'data' => Instansi::getList(),
            'options' => [
                'placeholder' => 'Pilih Instansi',
            ],
            'pluginOptions' => ['allowClear' => true],
        ]) ?>

        <?= $form->field($model, 'serialnumber')->textInput(['maxlength' => true]) ?>

        <?= Html::hiddenInput('referrer',$referrer); ?>

	</div>
    <div class="box-footer">
        <div class="col-sm-offset-2 col-sm-3">
            <?= Html::submitButton('<i class="fa fa-check"></i> Simpan',['class' => 'btn btn-success btn-flat']) ?>
        </div>
    </div>

</div>

<?php ActiveForm::end(); ?>
