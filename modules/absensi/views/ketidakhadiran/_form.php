<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\modules\absensi\models\KetidakhadiranJenis;
use app\modules\absensi\models\KetidakhadiranStatus;

/* @var $this yii\web\View */
/* @var $model app\modules\absensi\models\Ketidakhadiran */
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

<div class="ketidakhadiran-form box box-primary">

    <div class="box-header">
        <h3 class="box-title">Form Ketidakhadiran</h3>
    </div>
	<div class="box-body">

        <?= $form->errorSummary($model); ?>

        <?= $form->field($model, 'tanggal')->textInput() ?>

        <?= $form->field($model, 'id_ketidakhadiran_jenis')->dropDownList(KetidakhadiranJenis::getList(),['prompt'=>'- Pilih Jenis Ketidakhadiran -']) ?>

        <?= $form->field($model, 'keterangan')->textarea(['rows' => 6]) ?>

        <?php if($model->accessIdKetidakhadiranStatus()) { ?>
        <?= $form->field($model, 'id_ketidakhadiran_status')->dropDownList(KetidakhadiranStatus::getList()) ?>
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
