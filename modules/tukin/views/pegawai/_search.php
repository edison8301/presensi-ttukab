<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\tukin\models\PegawaiSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pegawai-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'nama') ?>

    <?= $form->field($model, 'nip') ?>

    <?= $form->field($model, 'id_instansi') ?>

    <?= $form->field($model, 'id_jabatan') ?>

    <?php // echo $form->field($model, 'id_atasan') ?>

    <?php // echo $form->field($model, 'id_golongan') ?>

    <?php // echo $form->field($model, 'id_instansi_pegawai_bak') ?>

    <?php // echo $form->field($model, 'nama_jabatan') ?>

    <?php // echo $form->field($model, 'status_batas_pengajuan') ?>

    <?php // echo $form->field($model, 'gender') ?>

    <?php // echo $form->field($model, 'tempat_lahir') ?>

    <?php // echo $form->field($model, 'tanggal_lahir') ?>

    <?php // echo $form->field($model, 'alamat') ?>

    <?php // echo $form->field($model, 'telepon') ?>

    <?php // echo $form->field($model, 'email') ?>

    <?php // echo $form->field($model, 'foto') ?>

    <?php // echo $form->field($model, 'grade') ?>

    <?php // echo $form->field($model, 'gelar_depan') ?>

    <?php // echo $form->field($model, 'gelar_belakang') ?>

    <?php // echo $form->field($model, 'id_eselon') ?>

    <?php // echo $form->field($model, 'eselon_bak') ?>

    <?php // echo $form->field($model, 'id_pegawai_status') ?>

    <?php // echo $form->field($model, 'status_hapus') ?>

    <?php // echo $form->field($model, 'jumlah_userinfo') ?>

    <?php // echo $form->field($model, 'jumlah_checkinout') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
