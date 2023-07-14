<?php

use app\models\Iclock;
use app\models\Instansi;
use app\models\InstansiPegawai;
use kartik\select2\Select2;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\KirimKeMesinForm */
/* @var $form yii\widgets\ActiveForm */
/* @var $query null */

$this->title = "Kirim Pegawai ke Mesin";

?>

<?php $form = ActiveForm::begin([
    'layout' => 'horizontal',
    'enableAjaxValidation' => false,
    'enableClientValidation' => false,
    'fieldConfig' => [
        'horizontalCssClasses' => [
            'label' => 'col-sm-2',
            'wrapper' => 'col-sm-4',
            'error' => '',
            'hint' => '',
        ],
    ]
]); ?>


<div class="pegawai-form box box-primary">

    <div class="box-header">
        <h3 class="box-title">Form Pegawai</h3>
    </div>
    <div class="box-body">

        <?= $form->errorSummary($model); ?>

        <?= $form->field($model, 'nama')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'nip')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'id_instansi')->widget(Select2::className(), [
            'data' => Instansi::getList(),
            'options' => [
                'placeholder' => '- Pilih Perangkat Daerah -',
            ],
            'pluginOptions' => [
                'allowClear' => true,
            ]
        ]); ?>

        <?php /*$form->field($model, 'id_pegawai_jenis')->dropDownList(
            PegawaiJenis::getList(),
            ['prompt' => '- Semua -']
        );*/ ?>

        <?php if ($query != null) { ?>
            <?= $form->field($model, 'SN_id')->widget(Select2::class, [
                'data' => Iclock::getList(),
                'options' => [
                    'placeholder' => '- Pilih Mesin -',
                    'id' => 'sn-id',
                ],
                'pluginOptions' => [
                    'allowClear' => true,
                ]
            ])->label('Mesin Absensi'); ?>

            <?= $form->field($model, 'proses')->dropDownList(
                [0 => 'Tampilkan Pegawai', 1 => 'Kirim Ke Mesin']
            ) ?>
        <?php } ?>


    </div>
    <div class="box-footer">
        <div class="col-sm-offset-2 col-sm-3">
            <?php if ($query == null) { ?>
                <?= Html::submitButton('<i class="fa fa-check"></i> Tampilkan Pegawai', ['class' => 'btn btn-success btn-flat']) ?>
            <?php } ?>

            <?php if ($query != null) { ?>
                <?= Html::submitButton('<i class="fa fa-check"></i> Kirim Pegawai', ['class' => 'btn btn-success btn-flat']) ?>
            <?php } ?>
        </div>
    </div>

</div>

<?php ActiveForm::end(); ?>



<?php if ($query != null) { ?>
    <div class="box box-primary">
        <div class="box-header">
            <h3 class="box-title">Daftar Pegawai : <?= $query->count(); ?></h3>
        </div>
        <div class="box-body">
            <table class="table table-bordered">
                <tr>
                    <th style="text-align: center">No</th>
                    <th style="text-align: center; width: 130px">Kode Presensi</th>
                    <th style="text-align: center">Nama</th>
                    <th style="text-align: center">NIP</th>
                    <th style="text-align: center">Perangkat Daerah</th>
                    <th style="text-align: center">Jumlah<br>Sidik Jari</th>
                </tr>
                <?php $i = 1;
                foreach ($query->all() as $instansiPegawai) { ?>
                    <?php /** @var InstansiPegawai $instansiPegawai */ ?>
                    <tr>
                        <td style="text-align: center"><?= $i; ?></td>
                        <td style="text-align: center"><?= @$instansiPegawai->pegawai->getOneUserInfo()->userid ?? '<i>-</i>'; ?></td>
                        <td><?= $instansiPegawai->pegawai->nama; ?></td>
                        <td style="text-align: center"><?= $instansiPegawai->pegawai->nip; ?></td>
                        <td style="text-align: left"><?= @$instansiPegawai->instansi->nama; ?></td>
                        <td style="text-align: center"><?= @$instansiPegawai->pegawai->countTemplate(); ?> Template</td>
                    </tr>
                    <?php $i++;
                } ?>
            </table>
        </div>
    </div>
<?php } ?>
