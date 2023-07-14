<?php

use app\components\Session;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\models\PetaJenis;
use kartik\select2\Select2;
use app\models\Instansi;
use app\models\Pegawai;

/* @var $this yii\web\View */
/* @var $model app\models\Peta */
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

<div class="peta-form box box-primary">

    <div class="box-header">
        <h3 class="box-title">Form Peta</h3>
    </div>
	<div class="box-body">

        <?= $form->errorSummary($model); ?>

        <?php if (Session::isAdmin()) { ?>
            <?= $form->field($model, 'nama')->textInput(['maxlength' => true]) ?>

            <?php if($mode == 'pegawai' OR $mode == 'pegawai-wfh') { ?>
                <?= $form->field($model, 'id_pegawai')->widget(Select2::className(),[
                    'data' => Pegawai::getList(),
                    'options' => [
                        'placeholder' => '- Pilih Pegawai -'
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ]
                ])->label('Pegawai') ?>
            <?php } ?>

            <?php if($mode == 'instansi') { ?>
                <?= $form->field($model, 'id_instansi')->widget(Select2::className(),[
                    'data' => Instansi::getList(),
                    'options' => [
                        'placeholder' => '- Pilih Instansi -'
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ]
                ])->label('Instansi') ?>
            <?php } ?>

            <?= $form->field($model, 'id_peta_jenis')->dropDownList(PetaJenis::getList())->label('Jenis Peta'); ?>
        
        <?php } ?>
    
        <?= $form->field($model, 'latlong')->textInput(['maxlength' => true]) ?>        
        
        <?php if (Session::isAdmin()) { ?>
            <?= $form->field($model, 'jarak')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'keterangan')->textarea(['rows' => 6]) ?>

            <?= $form->field($model, 'id_induk')->hiddenInput()->label(false) ?>
        <?php } ?>

        <?= Html::hiddenInput('referrer',$referrer); ?>

	</div>
    <div class="box-footer">
        <div class="col-sm-offset-2 col-sm-3">
            <?= Html::submitButton('<i class="fa fa-check"></i> Simpan',['class' => 'btn btn-success btn-flat']) ?>
        </div>
    </div>

</div>

<?php ActiveForm::end(); ?>
