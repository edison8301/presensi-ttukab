<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<div  class="login-pages">
    <div class="login-logo">
        <img src="<?php echo Yii::$app->request->baseUrl; ?>/images/logo.png">
        <p>Sistem Absensi</p>
        <p class="txt-l">Kabupaten <?= Yii::$app->params['kabkota'] ?></p>
    </div>

<div class="login-box">
    <div class="login-box-body">
    <p class="login-box-msg">Silahkan login terlebih dahulu</p>
        <?php $form = ActiveForm::begin([
                'id' => 'login-form',
                'options' => ['class' => 'form-signin'],
        ]); ?>

        <?= $form->field($model, 'username')->textInput(['placeholder'=>'Email / Username'])->label(false); ?>

        <?= $form->field($model, 'password')->passwordInput(['placeholder'=>'Password'])->label(false); ?>

        <?= $form->field($model, 'tahun')->textInput(['placeholder'=>'Tahun'])->label(false); ?>

        <?php /*
        <div class="row">
            <div class="col-xs-8">
                <?= $form->field($model, 'rememberMe')->checkbox() ?>
            </div>
        </div>
        */ ?>
        <div class="row">
            <div class="col-xs-4 col-xs-offset-8">
                <?= Html::submitButton('<i class="fa fa-lock"></i> Login',
                    [
                        'id' => 'btnLogin',
                        'class' => 'btn btn-flat btn-primary btn-block btn-signin',
                        'name' => 'login-button'
                    ]) ?>
            </div>
        </div>

    <?php ActiveForm::end(); ?>
    </div><!-- /.login-box-body -->
</div><!-- /.login-box -->
