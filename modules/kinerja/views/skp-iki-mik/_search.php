<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\kinerja\models\SkpIkiMikSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="skp-iki-mik-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'id_skp') ?>

    <?= $form->field($model, 'id_skp_iki') ?>

    <?= $form->field($model, 'tujuan') ?>

    <?= $form->field($model, 'definisi') ?>

    <?php // echo $form->field($model, 'formula') ?>

    <?php // echo $form->field($model, 'satuan_pengukuran') ?>

    <?php // echo $form->field($model, 'kualitas_tingkat_kendali') ?>

    <?php // echo $form->field($model, 'sumber_data') ?>

    <?php // echo $form->field($model, 'periode_pelaporan') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
