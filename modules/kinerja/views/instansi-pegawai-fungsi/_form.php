<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\kinerja\models\InstansiPegawaiFungsi */
/* @var $form yii\widgets\ActiveForm */
/* @var $referrer  */
?>

<?php $form = ActiveForm::begin([
    'layout' => 'horizontal',
    'enableAjaxValidation' => false,
    'enableClientValidation' => false,
    'fieldConfig' => [
        'horizontalCssClasses' => [
            'label' => 'col-sm-2',
            'wrapper' => 'col-sm-4',
            'error' => '',
            'hint' => '',
        ],
    ]
]); ?>

<div class="instansi-pegawai-fungsi-form box box-primary">

    <div class="box-header">
        <h3 class="box-title">Form Instansi Pegawai Fungsi</h3>
    </div>
    <div class="box-body">

        <?= $form->errorSummary($model); ?>

        <?= $form->field($model, 'nama')->textarea(['rows' => 6]) ?>

        <?= $form->field($model, 'urutan')->textInput() ?>

        <?= Html::hiddenInput('referrer', $referrer); ?>

    </div>
    <div class="box-footer">
        <div class="col-sm-offset-2 col-sm-3">
            <?= Html::submitButton('<i class="fa fa-check"></i> Simpan', ['class' => 'btn btn-success btn-flat']) ?>
        </div>
    </div>

</div>

<?php ActiveForm::end(); ?>
