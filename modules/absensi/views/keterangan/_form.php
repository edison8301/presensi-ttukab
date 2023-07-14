<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\modules\absensi\models\KeteranganJenis;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\modules\absensi\models\Keterangan */
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

<div class="keterangan-form box box-primary">

    <div class="box-header">
        <h3 class="box-title">Form Keterangan</h3>
    </div>
    <div class="box-body">

        <?= $form->errorSummary($model); ?>

        <?= $form->field($model, 'nip')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tanggal')->textInput() ?>

    <?= $form->field($model, 'id_keterangan_jenis')->dropDownList(KeteranganJenis::getList()) ?>

    <?= $form->field($model, 'keterangan')->textarea(['rows' => 6]) ?>

    <?php if(User::isAdmin()) { ?>
    <?= $form->field($model, 'status')->textInput() ?>
    <?php } ?>

    <?= Html::hiddenInput('referrer',$referrer); ?>

    </div>
    <div class="box-footer">
        <div class="col-sm-offset-2">
            <?= Html::submitButton('<i class="fa fa-check"></i> Simpan',['class' => 'btn btn-success btn-flat']) ?>
        </div>
    </div>

</div>

<?php ActiveForm::end(); ?>
