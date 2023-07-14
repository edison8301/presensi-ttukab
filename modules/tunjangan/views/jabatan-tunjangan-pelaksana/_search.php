<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\tunjangan\models\JabatanTunjanganPelaksanaSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="jabatan-tunjangan-pelaksana-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'id_instansi') ?>

    <?= $form->field($model, 'id_jabatan_golongan') ?>

    <?= $form->field($model, 'besaran_tpp') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
