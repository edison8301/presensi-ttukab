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
    'action' => ['instansi-pegawai/view'],
    'method' => 'get',
]); ?>
<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">Filter Kegiatan Tahunan</h3>
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
            <?= Html::a('<i class="fa fa-print"></i> Ekspor Excel', Url::current(['export' => 'excel']), ['class' => 'btn btn-success btn-flat']) ?>
        </div>
        <div class="box-body">
            <table class="table table-hover table-bordered">
                <thead>
                <tr>
                    <th style="text-align: center">No</th>
                    <th colspan="3">I. Pejabat Penilai</th>
                    <th style="text-align: center">No</th>
                    <th colspan="7">II. Pegawai Negeri Sipil yang Dinilai</th>
                </tr>
                </thead>
                <tr>
                    <td style="text-align:center">1</td>
                    <td style="width:160px">Nama</td>
                    <td colspan="2"><?= $instansiPegawai->atasan ? $instansiPegawai->atasan->nama : ""; ?></td>
                    <td style="text-align: center">1</td>
                    <td colspan="2">Nama</td>
                    <td colspan="5"><?= Html::encode($pegawai->nama); ?></td>
                </tr>
                <tr>
                    <td style="text-align:center">2</td>
                    <td style="width:160px">NIP</td>
                    <td colspan="2"><?= $instansiPegawai->atasan ? $instansiPegawai->atasan->nip : ""; ?></td>
                    <td style="text-align: center">2</td>
                    <td colspan="2">NIP</td>
                    <td colspan="5"><?= $pegawai->nip; ?></td>
                </tr>
                <tr>
                    <td style="text-align:center">3</td>
                    <td style="width:160px">Pangkat/Gol.Ruang</td>
                    <td colspan="2">&nbsp;</td>
                    <td style="text-align: center">3</td>
                    <td colspan="2">Pangkat/Gol.Ruang</td>
                    <td colspan="5">&nbsp;</td>
                </tr>
                <tr>
                    <td style="text-align:center">4</td>
                    <td style="width:160px">Jabatan</td>
                    <td colspan="2"><?= $instansiPegawai->atasan ? $instansiPegawai->atasan->nama_jabatan : ""; ?></td>
                    <td style="text-align: center">4</td>
                    <td colspan="2">Jabatan</td>
                    <td colspan="5"><?= Html::encode($instansiPegawai->nama_jabatan); ?></td>
                </tr>
                <tr>
                    <td style="text-align:center">5</td>
                    <td style="width:160px">Unit Kerja</td>
                    <td colspan="2"><?= @$instansiPegawai->atasan ? @$instansiPegawai->atasan->instansi->nama : ""; ?></td>
                    <td style="text-align: center">5</td>
                    <td colspan="2">Unit Kerja</td>
                    <td colspan="5"><?= $instansiPegawai->instansi->nama; ?></td>
                </tr>
                <thead>
                <tr>
                    <th rowspan="2" style="text-align: center; vertical-align: middle;">No</th>
                    <th style="vertical-align:middle;text-align: center" rowspan="2" colspan="3">III. Kegiatan Tugas Jabatan</th>
                    <th style="vertical-align:middle;text-align: center" rowspan="2">AK</th>
                    <th style="vertical-align:middle;text-align: center;" colspan="6">Target</th>
                    <th style="vertical-align:middle;text-align: center" rowspan="2">Status</th>
                </tr>
                <tr>
                    <th colspan="2" style="text-align: center;">Kuant/Output</th>
                    <th style="text-align: center;">Kual/Mutu</th>
                    <th colspan="2" style="text-align: center;">Waktu</th>
                    <th style="text-align: center;">Biaya</th>
                </tr>
                </thead>
                <?php $total_ak = 0; ?>
                <?php $allKegiatanTahunan = $model->getManyKegiatanTahunan(['id_kegiatan_tahunan_versi' => 1])->all(); ?>
                <?php $i=1; foreach($allKegiatanTahunan as $kegiatanTahunan) { ?>
                    <?php $total_ak += $kegiatanTahunan->target_angka_kredit; ?>
                    <tr>
                        <td style="text-align:center; width: 50px"><?= $i; ?></td>
                        <td colspan="2"><?= Html::encode($kegiatanTahunan->nama); ?></td>
                        <td style="width: 25px">&nbsp;</td>
                        <td style="width: 50px"><?= $kegiatanTahunan->target_angka_kredit; ?></td>
                        <td style="text-align: center; width: 50px"><?= $kegiatanTahunan->target_kuantitas; ?></td>
                        <td style="text-align: center; width: 50px"><?= $kegiatanTahunan->satuan_kuantitas; ?></td>
                        <td style="text-align: center; width: 50px">100</td>
                        <td style="text-align: center; width: 50px"><?= $kegiatanTahunan->target_waktu; ?></td>
                        <td style="text-align: center; width: 50px">Bulan</td>
                        <td style="text-align: right; width: 50px"><?= Helper::rp($kegiatanTahunan->target_biaya); ?></td>
                        <td style="text-align: center; width: 50px"><?= $kegiatanTahunan->getLabelIdKegiatanStatus(); ?></td>
                    </tr>
                <?php $i++; } ?>
                <tr>
                    <td>&nbsp;</td>
                    <td colspan="2" style="text-align: center;">Jumlah Angka Kredit</td>
                    <td>&nbsp;</td>
                    <td><?= $total_ak; ?></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
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
