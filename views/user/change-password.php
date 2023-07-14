<?php

use kartik\password\PasswordInput;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \app\models\LoginForm */
/* @var $referrer string */

$this->title = 'Ganti Password';

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

    <?php if($is_password_default !== null) { ?>
        <div class="alert alert-danger">Anda masih menggunakan password default/NIP. Silahkan ganti password anda terlebih dahulu</div>
    <?php } ?>

    <div class="box box-primary">
        <div class="box-header">
            <h3 class="box-title">Form Ganti Password</h3>
        </div>
        <div class="box-body">
            <div class="row">

                <?= $form->field($model, 'password_lama')->passwordInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'password_baru')->widget(PasswordInput::class, [
                    'pluginOptions' => [
                        'showMeter' => true,
                        'toggleMask' => false
                    ]
                ]) ?>

                <?= $form->field($model, 'password_baru_konfirmasi')->widget(PasswordInput::class, [
                    'pluginOptions' => [
                        'showMeter' => true,
                        'toggleMask' => false
                    ]
                ]) ?>

                <?= Html::hiddenInput('referrer',$referrer); ?>

            </div>
        </div>
        <div class="box-footer">
            <div class="row">
                <div class="col-sm-offset-2 col-sm-3">
                    <?= Html::submitButton('<i class="fa fa-check"></i> Ganti Password', ['class' => 'btn btn-success btn-flat', 'name' => 'register-button']) ?>
                </div>
            </div>
        </div>
    </div>

<?php ActiveForm::end(); ?>
