<?php

use app\modules\kinerja\models\KegiatanHarianSearch;
use yii\helpers\Html;
use app\components\Helper;
use app\models\Instansi;
use yii\bootstrap\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $searchModel KegiatanHarianSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Daftar Kegiatan Harian';
if($searchModel->mode == 'bawahan')
    $this->title .= ' Bawahan';

$this->title .= ' : '.$searchModel->getBulanLengkapTahun();


$this->params['breadcrumbs'][] = $this->title;
?>

<?php $form = ActiveForm::begin([
    'action' => ['kegiatan-harian/rekap'],
    'layout'=>'inline',
    'method' => 'get',
]); ?>
<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">Filter Kegiatan Harian</h3>
    </div>
    <div class="box-body">
        <?= $form->field($searchModel, 'bulan')->widget(Select2::className(), [
            'data' => Helper::getListBulan(),
            'options' => [
                'placeholder' => '- Pilih Bulan -',
            ],
            'pluginOptions' => [
                'allowClear' => true,
                'width'=>'300px'
            ]
        ]); ?>

        <?= $form->field($searchModel, 'tahun')->textInput() ?>

        <?= $form->field($searchModel, 'id_instansi')->widget(Select2::className(), [
            'data' => Instansi::getList(),
            'options' => [
                'placeholder' => '- Pilih Instansi -',
                'id' => 'id-eselon',
            ],
            'pluginOptions' => [
                'allowClear' => true,
                'width'=>'450px'
            ]
        ]); ?>
        <?= Html::submitButton('<i class="fa fa-search"></i> Filter',['class' => 'btn btn-primary btn-flat']) ?>
    </div>
</div>
<?php ActiveForm::end(); ?>

<div class="box box-primary">
    <div class="box-header">
        <?= Html::a('<i class="fa fa-file-pdf-o"></i> Export PDF Rekap', Yii::$app->request->url.'&export=1', ['target' => '_blank', 'class' => 'btn btn-danger btn-flat']) ?>
    </div>
    <div class="box-body" style="overflow: auto;">
        <?php $date = $searchModel->getDate(); ?>
        <?php $jumlahHari = $date->format('t'); ?>
        <table class="table table-bordered rekap">
            <thead>
                <tr>
                    <th style="text-align: center; vertical-align: middle;" rowspan="2">No</th>
                    <th style="text-align: center; vertical-align: middle;" rowspan="2">Nama <br> Pegawai</th>
                    <th style="text-align: center; vertical-align: middle;" rowspan="2">NIP</th>
                    <th style="text-align: center; vertical-align: middle;" rowspan="2">Jabatan</th>
                    <th style="text-align: center; vertical-align: middle;" colspan="4">Perhitungan SKP dan RKB</th>
                    <th style="text-align: center; vertical-align: middle;" rowspan="2">TOT.<br>POT%</th>
                    <th style="text-align: center; vertical-align: middle;" rowspan="2">KET</th>
                </tr>
                <tr>
                    <th style="text-align: center; vertical-align: middle;">SKP</th>
                    <th style="text-align: center; vertical-align: middle;">POTONGAN<br>SKP<br>(%)</th>
                    <th style="text-align: center; vertical-align: middle;">Realisasi<br/>SKB<br/>(%)</th>
                    <th style="text-align: center; vertical-align: middle;">POTONGAN<br>RKB<br>(%)</th>
                </tr>
            </thead>
            <?php if (!empty($searchModel->id_instansi)): ?>
                <?php $no = 1; ?>
                <?php foreach ($searchModel->searchPegawaiRekap() as $pegawai): ?>
                <?php /* @var app\models\Pegawai $pegawai */ ?>
                    <tr>
                        <td style="text-align: center;"><?= $no++; ?></td>
                        <td><?= $pegawai->nama; ?></td>
                        <td><?= $pegawai->nip; ?></td>
                        <td><?= $pegawai->nama_jabatan; ?></td>
                        <td style="text-align: center;"><?= $pegawai->getCeklisSkp($date->format('Y')); ?></td>
                        <td style="text-align: center;">
                            <?php // $pegawai->getPotonganSkp($date->format('Y')); ?>
                        </td>
                        <td style="text-align: center;">
                            <?php // $pegawai->getPotonganCkhpTotal($searchModel->getDate()->format("m"), $searchModel->getDate()->format('Y')); ?>
                        </td>
                        <td style="text-align: center;">
                            <?php // $pegawai->getPotonganKegiatanTotal($searchModel->getDate()->format("m"), $searchModel->getDate()->format('Y')); ?>
                        </td><td>&nbsp;</td>
                        <td></td>
                    </tr>
                    <?php // $pegawai->updatePegawaiRekapKinerja($searchModel->getDate()->format('m')); ?>
                <?php endforeach ?>
            <?php else: ?>
                <tr>
                    <td colspan="<?= $jumlahHari + 9; ?>" style="font-style: italic;">
                        Silahkan Pilih Instansi Terlebih dahulu
                    </td>
                </tr>
            <?php endif ?>
        </table>
    </div>
</div>
