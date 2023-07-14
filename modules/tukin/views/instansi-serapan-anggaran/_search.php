<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\tukin\models\InstansiSerapanAnggaranSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="instansi-serapan-anggaran-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'id_instansi') ?>

    <?= $form->field($model, 'tahun') ?>

    <?= $form->field($model, 'bulan') ?>

    <?= $form->field($model, 'target') ?>

    <?php // echo $form->field($model, 'realisasi') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
