<?php

use app\models\InstansiPegawai;
use app\modules\kinerja\models\SkpForm;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\components\Helper;
use kartik\select2\Select2;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\InstansiPegawaiSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/** @var SkpForm $model */

$this->title = 'Daftar Instansi Pegawai';
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('//filter/_filter-tahun'); ?>

<?php $form = ActiveForm::begin([
    'action' => ['instansi-pegawai/view-v2'],
    'method' => 'get',
]); ?>
<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">Filter Kinerja Tahunan</h3>
    </div>

    <div class="box-body">
        <div class="row">
            <div class="col-sm-4">
                <?= $form->field($model, 'id_pegawai')->widget(Select2::className(), [
                    'data' => $model->getListPegawai(),
                    'options' => [
                        'placeholder' => '- Pilih Pegawai -',
                        'id' => 'id-pegawai',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ]
                ])->label(false); ?>
            </div>
            <div class="col-sm-4">
                <?= $form->field($model, 'id_instansi_pegawai')->widget(DepDrop::className(), [
                    'type' => DepDrop::TYPE_SELECT2,
                    'data' => InstansiPegawai::getListInstansi($model->id_pegawai, true),
                    'pluginOptions' => [
                        'depends' => ['id-pegawai'],
                        'placeholder' => '- Pilih Jabatan -',
                        'url' => Url::to(['/instansi-pegawai/get-list']),
                    ],
                    'select2Options' => [
                        'pluginOptions' => [
                            'allowClear' => true,
                        ]
                    ]
                ])->label(false); ?>
            </div>
            <div class="col-sm-2">
                <?= Html::submitButton('<i class="fa fa-search"></i> Filter',['class' => 'btn btn-primary btn-flat']) ?>
            </div>
        </div>

    </div>

</div>
<?php ActiveForm::end(); ?>

<?php if ($model->pegawai !== null) { ?>
    <?php $instansiPegawai = $model->getInstansiPegawai(); ?>
    <?php $pegawai = $model->pegawai; ?>
    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">SKP : <?= Html::encode($pegawai->nama); ?></h3>
        </div>
        <div class="box-header with-border">
            <?= Html::a('<i class="fa fa-print"></i> Ekspor PDF', Url::current(['export' => 'pdf']), ['class' => 'btn btn-primary btn-flat','target' => '_blank']) ?>
            <?php /* Html::a('<i class="fa fa-print"></i> Ekspor Excel', Url::current(['export' => 'excel']), ['class' => 'btn btn-success btn-flat']) */ ?>
        </div>
        <div class="box-body">
            <table class="table table-bordered table-hover">
                <tr>
                    <th colspan="2">PEGAWAI YANG DINILAI</th>
                    <th colspan="2">PEJABAT PENILAI KINERJA</th>
                </tr>
                <tr>
                    <th>NAMA</th>
                    <td style="width: 350px;">
                        <?= @$model->pegawai->nama ?>
                    </td>
                    <th>NAMA</th>
                    <td style="width: 350px;">
                        <?= @$model->instansiPegawai->atasan->nama ?>
                    </td>
                </tr>
                <tr>
                    <th>NIP</th>
                    <td>
                        <?= @$model->pegawai->nip ?>
                    </td>
                    <th>NIP</th>
                    <td>
                        <?= @$model->instansiPegawai->atasan->nip ?>
                    </td>
                </tr>
                <tr>
                    <th>PANGKAT/GOL RUANG</th>
                    <td>
                        <?= @$model->pegawai->golongan->golongan ?>
                    </td>
                    <th>PANGKAT/GOL RUANG</th>
                    <td>
                        <?= @$model->instansiPegawai->atasan->golongan->golongan ?>
                    </td>
                </tr>
                <tr>
                    <th>JABATAN</th>
                    <td>
                        <?= $model->instansiPegawai->namaJabatan ?>
                    </td>
                    <th>JABATAN</th>
                    <td>
                        <?= $model->instansiPegawai->atasan ? $model->instansiPegawai->atasan->nama : '' ?>
                    </td>
                </tr>
                <tr>
                    <th>INSTANSI</th>
                    <td><?=strtoupper(@$model->instansiPegawai->instansi->nama) ?></td>
                    <th>INSTANSI</th>
                    <td><?=strtoupper(@$model->instansiPegawai->instansi->nama) ?></td>
                </tr>
            </table>
            <table class="table table-bordered table-hover">
                <tr>
                    <th style="text-align: center">NO</th>
                    <th style="text-align: center">RENCANA KINERJA</th>
                    <th style="text-align: center" colspan="2">INDIKATOR INDIVIDU</th>
                    <th style="text-align: center">TARGET</th>
                </tr>
                <tr>
                    <th style="text-align: center">(1)</th>
                    <th style="text-align: center">(2)</th>
                    <th style="text-align: center" colspan="2">(3)</th>
                    <th style="text-align: center">(4)</th>
                </tr>
                <!-- A. KINERJA UTAMA -->
                <tr>
                    <th colspan="5">A. KINERJA UTAMA</th>
                </tr>
                <?php $allKegiatanTahunanUtama = $model->findAllKegiatanTahunan([
                    'id_kegiatan_tahunan_versi' => 2,
                    'id_kegiatan_tahunan_jenis' => 1,
                ]) ?>
                <?php $i=1; foreach ($allKegiatanTahunanUtama as $kegiatanTahunan) { ?>
                    <tr>
                        <td style="vertical-align: middle; text-align: center;" rowspan="4"><?= $i++ ?></td>
                        <td style="vertical-align: middle;" rowspan="4">
                            <?= $kegiatanTahunan->nama ?>
                        </td>
                        <td style="vertical-align: middle; text-align: center; width: 100px;">Kuantitas</td>
                        <td style="vertical-align: middle; width: 400px">
                            <?= $kegiatanTahunan->indikator_kuantitas ?>
                        </td>
                        <td style="vertical-align: middle; text-align: right;">
                            <?= $kegiatanTahunan->target_kuantitas ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="vertical-align: middle; text-align: center;">Kualitas</td>
                        <td style="vertical-align: middle;"><?= $kegiatanTahunan->indikator_kualitas ?></td>
                        <td style="vertical-align: middle; text-align: right;">
                            <?= $kegiatanTahunan->target_kualitas ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="vertical-align: middle; text-align: center;">Waktu</td>
                        <td style="vertical-align: middle;"><?= $kegiatanTahunan->indikator_waktu ?></td>
                        <td style="vertical-align: middle; text-align: right;">
                            <?= $kegiatanTahunan->target_waktu ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="vertical-align: middle; text-align: center;">Biaya</td>
                        <td style="vertical-align: middle;"><?= $kegiatanTahunan->indikator_biaya ?></td>
                        <td style="vertical-align: middle; text-align: right;">
                            <?= $kegiatanTahunan->target_biaya ?>
                        </td>
                    </tr>
                <?php } ?>
                <!-- B. KINERJA TAMBAHAN -->
                <tr>
                    <th colspan="5">B. KINERJA TAMBAHAN</th>
                </tr>
                <?php $allKegiatanTahunanTambahan = $model->findAllKegiatanTahunan([
                    'id_kegiatan_tahunan_versi' => 2,
                    'id_kegiatan_tahunan_jenis' => 2,
                ]) ?>
                <?php $i=1; foreach ($allKegiatanTahunanTambahan as $kegiatanTambahan) { ?>
                    <tr>
                        <td style="vertical-align: middle; text-align: center;" rowspan="4"><?= $i++ ?></td>
                        <td style="vertical-align: middle;" rowspan="4">
                            <?= $kegiatanTambahan->nama ?>
                        </td>
                        <td style="vertical-align: middle; text-align: center; width: 100px;">Kuantitas</td>
                        <td style="vertical-align: middle; width: 400px">
                            <?= $kegiatanTambahan->indikator_kuantitas ?>
                        </td>
                        <td style="vertical-align: middle; text-align: right;">
                            <?= $kegiatanTambahan->target_kuantitas ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="vertical-align: middle; text-align: center;">Kualitas</td>
                        <td style="vertical-align: middle;"><?= $kegiatanTambahan->indikator_kualitas ?></td>
                        <td style="vertical-align: middle; text-align: right;">
                            <?= $kegiatanTambahan->target_kualitas ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="vertical-align: middle; text-align: center;">Waktu</td>
                        <td style="vertical-align: middle;"><?= $kegiatanTambahan->indikator_waktu ?></td>
                        <td style="vertical-align: middle; text-align: right;">
                            <?= $kegiatanTambahan->target_waktu ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="vertical-align: middle; text-align: center;">Biaya</td>
                        <td style="vertical-align: middle;"><?= $kegiatanTambahan->indikator_biaya ?></td>
                        <td style="vertical-align: middle; text-align: right;">
                            <?= $kegiatanTambahan->target_biaya ?>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    </div>
<?php } else { ?>
    <div class="box box-primary">
        <div class="box-body">
            Silahkan pilih pegawai pada filter untuk menampilkan SKP
        </div>
    </div>
<?php } ?>
