<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\iclock\IclockSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="iclock-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'SN') ?>

    <?= $form->field($model, 'State') ?>

    <?= $form->field($model, 'LastActivity') ?>

    <?= $form->field($model, 'TransTimes') ?>

    <?= $form->field($model, 'TransInterval') ?>

    <?php // echo $form->field($model, 'LogStamp') ?>

    <?php // echo $form->field($model, 'OpLogStamp') ?>

    <?php // echo $form->field($model, 'PhotoStamp') ?>

    <?php // echo $form->field($model, 'Alias') ?>

    <?php // echo $form->field($model, 'DeptID') ?>

    <?php // echo $form->field($model, 'UpdateDB') ?>

    <?php // echo $form->field($model, 'Style') ?>

    <?php // echo $form->field($model, 'FWVersion') ?>

    <?php // echo $form->field($model, 'FPCount') ?>

    <?php // echo $form->field($model, 'TransactionCount') ?>

    <?php // echo $form->field($model, 'UserCount') ?>

    <?php // echo $form->field($model, 'MainTime') ?>

    <?php // echo $form->field($model, 'MaxFingerCount') ?>

    <?php // echo $form->field($model, 'MaxAttLogCount') ?>

    <?php // echo $form->field($model, 'DeviceName') ?>

    <?php // echo $form->field($model, 'AlgVer') ?>

    <?php // echo $form->field($model, 'FlashSize') ?>

    <?php // echo $form->field($model, 'FreeFlashSize') ?>

    <?php // echo $form->field($model, 'Language') ?>

    <?php // echo $form->field($model, 'VOLUME') ?>

    <?php // echo $form->field($model, 'DtFmt') ?>

    <?php // echo $form->field($model, 'IPAddress') ?>

    <?php // echo $form->field($model, 'IsTFT') ?>

    <?php // echo $form->field($model, 'Platform') ?>

    <?php // echo $form->field($model, 'Brightness') ?>

    <?php // echo $form->field($model, 'BackupDev') ?>

    <?php // echo $form->field($model, 'OEMVendor') ?>

    <?php // echo $form->field($model, 'City') ?>

    <?php // echo $form->field($model, 'AccFun') ?>

    <?php // echo $form->field($model, 'TZAdj') ?>

    <?php // echo $form->field($model, 'DelTag') ?>

    <?php // echo $form->field($model, 'FPVersion') ?>

    <?php // echo $form->field($model, 'PushVersion') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
