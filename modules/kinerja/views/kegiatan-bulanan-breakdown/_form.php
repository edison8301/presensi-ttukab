<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\absensi\models\KegiatanBulananBreakdown */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="kegiatan-bulanan-breakdown-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_kegiatan_tahunan_detil')->textInput() ?>

    <?= $form->field($model, 'kegiatan')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'kuantitas')->textInput() ?>

    <?= $form->field($model, 'id_satuan_kuantitas')->textInput() ?>

    <?= $form->field($model, 'penilaian_kualitas')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
