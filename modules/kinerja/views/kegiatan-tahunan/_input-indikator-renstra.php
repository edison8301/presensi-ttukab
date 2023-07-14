<?php

use app\models\RpjmdIndikatorFungsional;
use app\models\RpjmdKegiatanIndikator;
use app\models\RpjmdProgramIndikator;
use app\models\RpjmdSasaranIndikator;
use app\models\RpjmdSubkegiatanIndikator;
use kartik\select2\Select2;

/* @var $form \kartik\form\ActiveForm */
/* @var $model \app\modules\kinerja\models\KegiatanTahunan */

?>

<?php if ($model->isVisibleRpjmdSasaranIndikator()) { ?>
    <div id="id-rpjmd-sasaran-indikator">
        <?= $form->field($model, 'id_rpjmd_sasaran_indikator')->widget(Select2::className(), [
            'data' => RpjmdSasaranIndikator::getList([
                'id_instansi' => $model->getIdInstansiRpjmd(),
                'tahun' => $model->tahun,
            ]),
            'options' => [
                'placeholder' => '- Pilih Indikator Sasaran Renstra -',
            ],
            'pluginOptions' => [
                'allowClear' => true,
            ]
        ]); ?>
    </div>
<?php } ?>

<?php if ($model->isVisibleRpjmdProgramIndikator()) { ?>
    <div id="id-rpjmd-program-indikator">
        <?= $form->field($model, 'id_rpjmd_program_indikator')->widget(Select2::className(), [
            'data' => RpjmdProgramIndikator::getList([
                'id_instansi' => $model->getIdInstansiRpjmd(),
                'tahun' => $model->tahun,
            ]),
            'options' => [
                'placeholder' => '- Pilih Indikator Program Renstra -',
            ],
            'pluginOptions' => [
                'allowClear' => true,
            ]
        ]); ?>
    </div>
<?php } ?>

<?php if ($model->isVisibleRpjmdKegiatanIndikator()) { ?>
    <div id="id-rpjmd-kegiatan-indikator">
        <?= $form->field($model, 'id_rpjmd_kegiatan_indikator')->widget(Select2::className(), [
            'data' => RpjmdKegiatanIndikator::getList([
                'id_instansi' => $model->getIdInstansiRpjmd(),
                'tahun' => $model->tahun,
            ]),
            'options' => [
                'placeholder' => '- Pilih Indikator Kegiatan Renstra -',
            ],
            'pluginOptions' => [
                'allowClear' => true,
            ]
        ]); ?>
    </div>
<?php } ?>

<?php if ($model->isVisibleRpjmdSubKegiatanIndikator()) { ?>
    <div id="id-rpjmd-sub-kegiatan-indikator">
        <?= $form->field($model, 'id_rpjmd_subkegiatan_indikator')->widget(Select2::className(), [
            'data' => RpjmdSubkegiatanIndikator::getList([
                'id_instansi' => $model->getIdInstansiRpjmd(),
                'tahun' => $model->tahun,
            ]),
            'options' => [
                'placeholder' => '- Pilih Indikator Sub Kegiatan Renstra -',
            ],
            'pluginOptions' => [
                'allowClear' => true,
            ]
        ]); ?>
    </div>
<?php } ?>

<?php if ($model->isVisibleRpjmdIndikatorFungsional()) { ?>
    <div id="id-rpjmd-indikator-fungsional">
        <?= $form->field($model, 'id_rpjmd_indikator_fungsional')->widget(Select2::className(), [
            'data' => RpjmdIndikatorFungsional::getList([
                'id_instansi' => $model->getIdInstansiRpjmd(),
                'tahun' => $model->tahun,
            ]),
            'options' => [
                'placeholder' => '- Pilih Indikator Fungsional Renstra -',
            ],
            'pluginOptions' => [
                'allowClear' => true,
            ]
        ]); ?>
    </div>
<?php } ?>
