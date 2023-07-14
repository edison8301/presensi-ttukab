<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ArtikelSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="artikel-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'judul') ?>

    <?= $form->field($model, 'slug') ?>

    <?= $form->field($model, 'konten') ?>

    <?= $form->field($model, 'id_user_buat') ?>

    <?php // echo $form->field($model, 'id_user_ubah') ?>

    <?php // echo $form->field($model, 'id_artikel_kategori') ?>

    <?php // echo $form->field($model, 'waktu_buat') ?>

    <?php // echo $form->field($model, 'waktu_ubah') ?>

    <?php // echo $form->field($model, 'waktu_terbit') ?>

    <?php // echo $form->field($model, 'thumbnail') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
