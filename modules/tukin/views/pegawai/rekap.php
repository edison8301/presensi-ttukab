<?php

use app\modules\tukin\models\PegawaiSearch;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\components\Helper;
use kartik\select2\Select2;
use app\models\Instansi;

/* @var $this \yii\web\View */
/* @var $searchModel PegawaiSearch */

$this->title = "Rekap Tunjangan";
?>

<?php $form = ActiveForm::begin([
    'action' => ['pegawai/rekap'],
    'layout'=>'inline',
    'method' => 'get',
]); ?>
<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">Filter Kegiatan Harian</h3>
    </div>
    <div class="box-body">
        <?= $form->field($searchModel, 'bulan')->widget(Select2::class, [
            'data' => Helper::getBulanList(),
            'options' => [
                'placeholder' => '- Pilih Bulan -',
            ],
            'pluginOptions' => [
                'allowClear' => true,
                'width'=>'300px'
            ]
        ]); ?>
        <?= $form->field($searchModel, 'id_instansi')->widget(Select2::class, [
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
    <div class="box-header with-border">
        <?php /* <?= Html::a('<i class="fa fa-file-excel-o"></i> Export Excel', ['pegawai/rekap', 'PegawaiSearch' => ['bulan' => $searchModel->bulan, 'id_instansi' => $searchModel->id_instansi], 'export' => true], ['class' => 'btn btn-success btn-flat']) ?>*/?>
    </div>
    <div class="box-body" style="overflow: auto">
        <table class="table table-bordered table-striped">
            <thead>
            <tr>
                <th style="text-align: center; vertical-align: middle" rowspan="2">NO</th>
                <th style="text-align: center; vertical-align: middle" rowspan="2">NAMA PEGAWAI</th>
                <th style="text-align: center; vertical-align: middle" rowspan="2">NIP</th>
                <th style="text-align: center; vertical-align: middle" rowspan="2">NAMA JABATAN</th>
                <th style="text-align: center; vertical-align: middle" rowspan="2">NILAI JABATAN</th>
                <th style="text-align: center; vertical-align: middle" rowspan="2">KELAS JABATAN</th>
                <th style="text-align: center; vertical-align: middle" rowspan="2">FAKTOR<br>PENYEIMBANG (%)</th>
                <th style="text-align: center; vertical-align: middle" rowspan="2">IDRp</th>
                <th style="text-align: center; vertical-align: middle" colspan="2">REALISASI KINERJA (80%)</th>
                <th style="text-align: center; vertical-align: middle" colspan="2">REALISASI PRESENSI (10%)</th>
                <th style="text-align: center; vertical-align: middle" colspan="2">SERAPAN ANGGARAN (10%)</th>
                <th style="text-align: center; vertical-align: middle" rowspan="2">JUMLAH TUKIN</th>
                <th style="text-align: center; vertical-align: middle" colspan="4">HUKUMAN DISIPLIN</th>
                <th style="text-align: center; vertical-align: middle" rowspan="2">JUMLAH AKHIR</th>
            </tr>
            <tr>
                <th style="text-align: center;">%</th>
                <th style="text-align: center;">(RUPIAH)</th>
                <th style="text-align: center;">%</th>
                <th style="text-align: center;">(RUPIAH)</th>
                <th style="text-align: center;">%</th>
                <th style="text-align: center;">(RUPIAH)</th>
                <th style="text-align: center;">RINGAN</th>
                <th style="text-align: center;">SEDANG</th>
                <th style="text-align: center;">BERAT</th>
                <th style="text-align: center;">TOTAL</th>
            </tr>
            </thead>
            <?php if (!empty($searchModel->id_instansi)) { ?>
                <?php $i = 1; ?>
                <?php foreach ($searchModel->searchPegawaiRekap() as $pegawai) { ?>
                    <?php $rekap = $pegawai->findOrCreatePegawaiRekapTunjangan($searchModel->bulan) ?>
                    <tr>
                        <td style="text-align: center;"><?= $i++; ?></td>
                        <td style="text-align: left;"><?= Html::a($pegawai->nama, ['pegawai/view', 'id' => $pegawai->id]) ?></td>
                        <td style="text-align: left;"><?= $pegawai->nip ?></td>
                        <?php if ($pegawai->jabatan !== null) { ?>
                            <td style="text-align: center;"><?= @$pegawai->jabatan->nama ?></td>
                            <td style="text-align: center;">
                                <?php if (@$pegawai->kelasJabatan !== null): ?>
                                    <?= @$pegawai->kelasJabatan->getNilaiTengah() ?>
                                <?php endif ?>
                            </td>
                            <td style="text-align: center;"><?= @$pegawai->kelasJabatan->kelas_jabatan ?></td>
                            <td style="text-align: center;"><?= @$pegawai->jabatan->penyeimbang ?></td>
                            <td style="text-align: center;"><?= Helper::rp(Yii::$app->params['idrp']) ?></td>
                            <td style="text-align: center;"><?= @$rekap->getPersenKinerja() ?></td>
                            <td style="text-align: center;"><?= Helper::rp(@$pegawai->getRupiahKinerjaPersen($rekap)) ?></td>
                            <td style="text-align: center;"><?= @$rekap->getPersenAbsensi() ?></td>
                            <td style="text-align: center;"><?= Helper::rp(@$pegawai->getRupiahAbsensiPersen($rekap)) ?></td>
                            <td style="text-align: center;"><?= Helper::rp(@$rekap->getPersenSerapanAnggaran(), 0) ?></td>
                            <td style="text-align: center;"><?= Helper::rp(@$pegawai->getRupiahSerapanAnggaranPersen($rekap), 0) ?></td>
                            <td style="text-align: center;"><?= Helper::rp(@$pegawai->getRupiahTukinPersen($searchModel->bulan)) ?></td>
                            <td style="text-align: center"><?= $pegawai->getPersenPotonganHukumanDisiplinRingan($searchModel->bulan) ?></td>
                            <td style="text-align: center"><?= $pegawai->getPersenPotonganHukumanDisiplinSedang($searchModel->bulan) ?></td>
                            <td style="text-align: center"><?= $pegawai->getPersenPotonganHukumanDisiplinBerat($searchModel->bulan) ?></td>
                            <td style="text-align: center"><?= $pegawai->getPotonganHukumanDisiplin($searchModel->bulan) ?></td>
                            <td style="text-align: center;"><?= Helper::rp(@$pegawai->getRupiahAkhirPersen($searchModel->bulan)) ?></td>
                        <?php } else { ?>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        <?php } ?>

                    </tr>
                <?php } ?>
            <?php } else { ?>
                <tr>
                    <td colspan="17" style="font-style: italic">
                        Silahkan pilih instansi terlebih dahulu
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>
</div>
