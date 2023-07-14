<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CatatanSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="catatan-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'id_kegiatan_tahunan') ?>

    <?= $form->field($model, 'id_induk') ?>

    <?= $form->field($model, 'id_user') ?>

    <?= $form->field($model, 'catatan') ?>

    <?php // echo $form->field($model, 'waktu_buat') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
