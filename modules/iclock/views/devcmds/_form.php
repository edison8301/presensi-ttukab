<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\iclock\models\Devcmds */
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

<div class="devcmds-form box box-primary">

    <div class="box-header">
        <h3 class="box-title">Form Devcmds</h3>
    </div>
	<div class="box-body">

        <?= $form->errorSummary($model); ?>

        <?= $form->field($model, 'SN_id')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'CmdContent')->textarea(['rows' => 6]) ?>

        <?= $form->field($model, 'CmdCommitTime')->textInput() ?>

        <?= $form->field($model, 'CmdTransTime')->textInput() ?>

        <?= $form->field($model, 'CmdOverTime')->textInput() ?>

        <?= $form->field($model, 'CmdReturn')->textInput() ?>

        <?= $form->field($model, 'User_id')->textInput() ?>

        <?= Html::hiddenInput('referrer',$referrer); ?>

	</div>
    <div class="box-footer">
        <div class="col-sm-offset-2 col-sm-3">
            <?= Html::submitButton('<i class="fa fa-check"></i> Simpan',['class' => 'btn btn-success btn-flat']) ?>
        </div>
    </div>

</div>

<?php ActiveForm::end(); ?>
