<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\absensi\models\PegawaiRekapAbsensi */
/* @var $form yii\widgets\ActiveForm */
/* @var $referrer string */

?>

<?php $form = ActiveForm::begin([
    'layout'=>'horizontal',
    'enableAjaxValidation'=>false,
    'enableClientValidation'=>false,
    'fieldConfig' => [
        'horizontalCssClasses' => [
            'label' => 'col-sm-2',
            'wrapper' => 'col-sm-4',
            'error' => '',
            'hint' => '',
        ],
    ]
]); ?>

<div class="pegawai-rekap-absensi-form box box-primary">

    <div class="box-header">
        <h3 class="box-title">Form Pegawai Rekap Absensi</h3>
    </div>
	<div class="box-body">

        <?= $form->errorSummary($model); ?>

        <?= $form->field($model, 'id_pegawai')->textInput() ?>

        <?= $form->field($model, 'bulan')->textInput() ?>

        <?= $form->field($model, 'tahun')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'id_instansi')->textInput() ?>

        <?= $form->field($model, 'id_golongan')->textInput() ?>

        <?= $form->field($model, 'jumlah_hari_kerja')->textInput() ?>

        <?= $form->field($model, 'jumlah_hadir')->textInput() ?>

        <?= $form->field($model, 'jumlah_tidak_hadir')->textInput() ?>

        <?= $form->field($model, 'jumlah_izin')->textInput() ?>

        <?= $form->field($model, 'jumlah_sakit')->textInput() ?>

        <?= $form->field($model, 'jumlah_cuti')->textInput() ?>

        <?= $form->field($model, 'jumlah_tugas_belajar')->textInput() ?>

        <?= $form->field($model, 'jumlah_tugas_kedinasan')->textInput() ?>

        <?= $form->field($model, 'jumlah_dinas_luar')->textInput() ?>

        <?= $form->field($model, 'jumlah_tanpa_keterangan')->textInput() ?>

        <?= $form->field($model, 'jumlah_tidak_hadir_apel_pagi')->textInput() ?>

        <?= $form->field($model, 'jumlah_tidak_hadir_apel_sore')->textInput() ?>

        <?= $form->field($model, 'jumlah_tidak_hadir_upacara')->textInput() ?>

        <?= $form->field($model, 'jumlah_tidak_hadir_senam')->textInput() ?>

        <?= $form->field($model, 'jumlah_tidak_hadir_sidak')->textInput() ?>

        <?= $form->field($model, 'persen_potongan_fingerprint')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'persen_potongan_kegiatan')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'persen_potongan_total')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'status_kunci')->textInput() ?>

        <?= $form->field($model, 'waktu_diperbarui')->textInput() ?>

        <?= Html::hiddenInput('referrer',$referrer); ?>

	</div>
    <div class="box-footer">
        <div class="col-sm-offset-2 col-sm-3">
            <?= Html::submitButton('<i class="fa fa-check"></i> Simpan',['class' => 'btn btn-success btn-flat']) ?>
        </div>
    </div>

</div>

<?php ActiveForm::end(); ?>
