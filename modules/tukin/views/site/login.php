<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\modules\tukin\models\LoginForm */

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<div  class="login-pages">
            <div class="login-logo">
              <img src="<?php echo Yii::$app->request->baseUrl; ?>/images/makarti.png">
              <p>Kembang Desa</p>
              <p class="txt-l"></p>

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

        <div class="row">
            <div class="col-xs-8">
                <?= $form->field($model, 'rememberMe')->checkbox() ?>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-4 col-xs-offset-4">
                <?= Html::submitButton('Login', 
                    [
                        'id' => 'btnLogin',
                        'class' => 'btn btn-flat btn-primary btn-block btn-signin', 
                        'name' => 'login-button'
                    ]) ?>
                    &nbsp;
            </div>
            <div class="col-xs-4">
                <?= Html::a('<i class="fa fa-home"></i> Beranda', 'http://sireva.l', ['class' => 'btn btn-success']); ?>
                <?= Html::a('<i class="fa fa-sign-up"></i> Register', ['site/register'], ['class' => 'btn btn-success']); ?>
            </div>
        </div>

    <?php ActiveForm::end(); ?>
    </div><!-- /.login-box-body -->
</div><!-- /.login-box -->
