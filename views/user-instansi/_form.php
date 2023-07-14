<?php

use kartik\select2\Select2;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\models\Instansi;

/* @var $this yii\web\View */
/* @var $model app\models\UserInstansi */
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

<div class="user-instansi-form box box-primary">

    <div class="box-header">
        <h3 class="box-title">Form User Instansi</h3>
    </div>
	<div class="box-body">

        <?= $form->errorSummary($model); ?>

        <?= $form->field($model, 'id_instansi')->widget(Select2::class, [
            'data' => Instansi::getList(),
            'options' => [
                'prompt' => '- Pilih Perangkat Daerah -'
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
