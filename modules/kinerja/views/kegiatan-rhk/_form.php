<?php

use app\modules\kinerja\models\KegiatanRhkJenis;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\kinerja\models\KegiatanRhk */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = ActiveForm::begin([
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

<div class="kegiatan-rhk-form box box-primary">

    <div class="box-header with-border">
        <h3 class="box-title">Form RHK</h3>
    </div>
	<div class="box-body">

        <?= $form->errorSummary($model); ?>

        <?= $form->field($model, 'nomorSkpLengkap',[
            'horizontalCssClasses' => [
                'label' => 'col-sm-2',
                'wrapper' => 'col-sm-4',
                'error' => '',
                'hint' => '',
            ]
        ])->textarea(['rows'=>3,'readonly'=>'readonly'])->label("Nomor SKP") ?>

        <?php if ($model->id_induk != null) { ?>
            <?= $form->field($model, 'namaInduk')->textInput([
                'readonly' => 'readonly'
            ])->label('Rencana Hasil Kerja Induk') ?>
        <?php } ?>

        <?php if ($model->isJpt() == false) { ?>
            <?= $form->field($model, 'id_kegiatan_rhk_atasan')->dropDownList(
                $model->getListKegiatanRhkAtasan([
                    'id_kegiatan_rhk_jenis' => KegiatanRhkJenis::UTAMA,
                ]), [
                'prompt' => '- Pilih Rencana Hasil kerja Atasan Yang Diintervensi -',
            ]) ?>
        <?php } ?>

        <?= $form->field($model, 'id_kegiatan_rhk_jenis')->dropDownList(KegiatanRhkJenis::getList(), [
            'prompt' => '- Pilih Jenis RHK -',
        ]) ?>

        <?= $form->field($model, 'nama')->textarea(['rows' => 5]) ?>

        <?= Html::hiddenInput('referrer',$referrer); ?>

	</div>
    <div class="box-footer">
        <div class="col-sm-3">
            <?= Html::submitButton('<i class="fa fa-check"></i> Simpan',['class' => 'btn btn-success btn-flat']) ?>
        </div>
    </div>

</div>

<?php ActiveForm::end(); ?>
