<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\iclock\models\DevcmdsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="devcmds-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'SN_id') ?>

    <?= $form->field($model, 'CmdContent') ?>

    <?= $form->field($model, 'CmdCommitTime') ?>

    <?= $form->field($model, 'CmdTransTime') ?>

    <?php // echo $form->field($model, 'CmdOverTime') ?>

    <?php // echo $form->field($model, 'CmdReturn') ?>

    <?php // echo $form->field($model, 'User_id') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
