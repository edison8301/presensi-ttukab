<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;

?>

<?php $form = ActiveForm::begin([
    'id' => 'login-form',
    'enableClientValidation' => false,
    'options' => [
        'autocomplete' => 'off'
    ]
]); ?>

<div class="login-box">
    <br>
    <div class="login-logo" style="margin-bottom: 30px">
        <img src="<?php echo Yii::$app->request->baseUrl; ?>/images/<?= Yii::$app->params['logo']; ?>" style="height:100px">
        <p style="color: white; font-size: 24px; margin: 5px 0px; text-transform: uppercase"><?= Yii::$app->params['namaAplikasi']; ?></p>
        <p href="#" style="color: white; font-size: 24px; margin: 5px 0px; text-transform: uppercase"><b><?= Yii::$app->params['kabkota']; ?></b></p>

    </div>

    <div class="login-box-body">
        <p class="login-box-msg">Masukkan Nama Pengguna Dan Kata Sandi</p>

        <?= $form->field($model, 'username',[
            'options' => ['class' => 'form-group has-feedback'],
            'inputTemplate' => "{input}<span class='glyphicon glyphicon-user form-control-feedback'></span>"
        ])->textInput(['autofocus' => true,'placeholder' => 'Username'])->label(false) ?>

        <?= $form->field($model, 'password',[
            'options' => ['class' => 'form-group has-feedback'],
            'inputTemplate' => "{input}<span class='glyphicon glyphicon-lock form-control-feedback'></span>"
        ])->passwordInput(['placeholder' => 'Password'])->label(false) ?>

        <?= $form->field($model, 'tahun',[
            'options' => ['class' => 'form-group has-feedback'],
            'inputTemplate' => "{input}<span class='glyphicon glyphicon-calendar form-control-feedback'></span>"
        ])->textInput(['placeholder' => 'Tahun'])->label(false) ?>

        <div class="row">
            <div class="col-xs-8">
                <?= $form->field($model, 'rememberMe')->checkbox() ?>
            </div>
            <div class="col-xs-4">
                <?= Html::submitButton('Login <i class="fa fa-sign-in"></i>', ['class' => 'btn btn-primary btn-block btn-flat', 'name' => 'login-button']) ?>
            </div>
        </div>
    </div><!-- .login-box-body -->
    <?php /*
    <div style="margin-bottom: 30px">
        <div style="text-align: center; padding-top:10px; padding-bottom: 10px">Powered By</div>
        <div style="text-align: center; padding-bottom: 15px">
            <?= Html::img(Yii::$app->request->baseUrl.'/images/logo-bsre.png',[
                'height' => '60px'
            ]); ?>
        </div>
    </div>
    */ ?>
</div><!-- .login-box -->





<?php ActiveForm::end(); ?>
