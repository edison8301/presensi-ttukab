<?php

use app\models\InstansiLokasi;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\Instansi */
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

<div class="instansi-form box box-primary">

    <div class="box-header">
        <h3 class="box-title">Form Instansi</h3>
    </div>
	<div class="box-body">

        <?= $form->errorSummary($model); ?>

        <?= $form->field($model, 'nama')->textarea(['rows' => 4]) ?>

        <?= $form->field($model, 'id_induk')->widget(Select2::className(), [
            'data' => $model->getListInstansiInduk(),
            'options' => [
                'placeholder' => '- Pilih Induk -',
                'id' => 'id-instansi',
            ],
            'pluginOptions' => [
                'allowClear' => true,
            ]
        ]); ?>

        <?= $form->field($model, 'singkatan')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'id_instansi_jenis')->dropDownList(
            \app\models\InstansiJenis::getList()
        ); ?>

        <?= $form->field($model, 'alamat')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'telepon')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

        <?php /*
        <?= $form->field($model, 'id_instansi_lokasi')->dropDownList(
            InstansiLokasi::findArrayDropDownList()
        ) ?>
        */ ?>

        <?= $form->field($model, 'status_aktif')->dropDownList([
            1 => 'Aktif',
            0 => 'Tidak Aktif',
        ])->label('Status') ?>

        <?= Html::hiddenInput('referrer',$referrer); ?>

	</div>
    <div class="box-footer">
        <div class="col-sm-offset-2 col-sm-3">
            <?= Html::submitButton('<i class="fa fa-check"></i> Simpan',['class' => 'btn btn-success btn-flat']) ?>
        </div>
    </div>

</div>

<?php ActiveForm::end(); ?>
