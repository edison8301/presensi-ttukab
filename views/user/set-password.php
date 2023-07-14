<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \app\models\SetPasswordForm */
/* @var $referrer string */

$this->title = 'Set Password User';

$this->params['breadcrumbs'][] = $this->title;

?>

<?php $form = ActiveForm::begin([
        'layout'=>'horizontal',
        'fieldConfig' => [
            'horizontalCssClasses' => [
                    'label' => 'col-sm-2',
                    'wrapper' => 'col-sm-4',
                    'error' => '',
                    'hint' => '',
            ],
        ]
]); ?>

<div class="box box-primary">
    <div class="box-header">
        <h3 class="box-title">Form Set Password User</h3>
    </div>
    <div class="box-body">


            <?= $form->errorSummary($model); ?>

            <?= $form->errorSummary($model); ?>

            <?php /*
            <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>
            */ ?>

            <?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'password_konfirmasi')->passwordInput(['maxlength' => true]) ?>

            <?= Html::hiddenInput('referrer',$referrer); ?>

            <div class="col-sm-offset-2 col-sm-3 ">
                <?= Html::submitButton('<i class="fa fa-check"></i> Set Password', ['class' => 'btn btn-success btn-flat', 'name' => 'register-button']) ?>
            </div>

    </div>
</div>

<?php ActiveForm::end(); ?>
