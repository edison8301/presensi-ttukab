<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\tandatangan\models\BerkasSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="berkas-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'nama') ?>

    <?= $form->field($model, 'uraian') ?>

    <?= $form->field($model, 'berkas_mentah') ?>

    <?= $form->field($model, 'berkas_tandatangan') ?>

    <?php // echo $form->field($model, 'id_berkas_status') ?>

    <?php // echo $form->field($model, 'nip_tandatangan') ?>

    <?php // echo $form->field($model, 'waktu_tandatangan') ?>

    <?php // echo $form->field($model, 'id_aplikasi') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'deleted_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
