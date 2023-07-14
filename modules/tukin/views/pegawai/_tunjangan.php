<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\Helper;

/* @var $this yii\web\View */
/* @var $model app\modules\tukin\models\Pegawai */
/* @var $filter \app\modules\tukin\models\FilterTunjanganForm */
/* @var $rekap \app\modules\tukin\models\PegawaiRekapTunjangan */
?>


<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">
            Tunjangan
        </h3>
    </div>
    <div class="box-header">
        <?php /* <?= Html::a('<i class="fa fa-file-excel-o"></i> Export Excel', Url::current(['export' => 1]), ['class' => 'btn btn-success btn-flat']); ?> */ ?>
    </div>
    <div class="box-body">
        <table class="table table-bordered table-striped">


            <tr>
                <th style="">POTONGAN DISIPLIN</th>
                <th style="text-align: center;">%</th>
                <td style="text-align: left;"><?= @$rekap->getPersenKinerja() ?></td>
            </tr>
            <tr>
                <th style="">POTONGAN KINERJA</th>
                <th style="text-align: center;">%</th>
                <td style="text-align: left;"><?= @$rekap->getPersenAbsensi() ?></td>
            </tr>
            <tr>
                <th rowspan="3">POTONGAN HUKUMAN DISIPLIN</th>
                <th style="text-align: center">RINGAN</th>
                <td style="text-align: left"><?= @$model->getPersenPotonganHukumanDisiplinRingan($filter->bulan) ?></td>
            </tr>
            <tr>
                <th style="text-align: center">SEDANG</th>
                <td style="text-align: left"><?= @$model->getPersenPotonganHukumanDisiplinSedang($filter->bulan) ?></td>
            </tr>
            <tr>
                <th style="text-align: center">BERAT</th>
                <td style="text-align: left"><?= @$model->getPersenPotonganHukumanDisiplinBerat($filter->bulan) ?></td>
            </tr>
            <tr>
                <th colspan="2">POTONGAN TPP AKHIR</th>
                <th style="text-align: left">
                </th>
            </tr>
        </table>
        <?php /*echo DetailView::widget([
            'model' => ($rekap = $model->findOrCreatePegawaiRekapTunjangan($filter->bulan)),
            'template' => '<tr><th width="180px" style="text-align:right">{label}</th><td>{value}</td></tr>',
            'attributes' => [
                [
                    'label' => 'Kelas Jabatan',
                    'value' => @$rekap->jabatan->kelas_jabatan
                ],
                [
                    'label' => 'Nilai Jabatan',
                    'value' => Helper::rp(@$rekap->kelasJabatan->nilai_minimal) . ' - ' . Helper::rp(@$rekap->kelasJabatan->nilai_maksimal),
                ],
                [
                    'label' => 'Asumsi Idrp',
                    'value' => Helper::rp(Yii::$app->params['idrp']),
                ],
                [
                    'label' => 'Nilai Minimal',
                    'value' => Helper::rp(@$rekap->kelasJabatan->getBesaranMinimal())
                ],
                [
                    'label' => 'Nilai Maksimal',
                    'value' => Helper::rp(@$rekap->kelasJabatan->getBesaranMaksimal())
                ]
            ],
        ]);*/ ?>
    </div>
</div>


<?php /*
<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">
            Tunjangan
        </h3>
    </div>
    <div class="box-header">
        <?php <?= Html::a('<i class="fa fa-file-excel-o"></i> Export Excel', Url::current(['export' => 1]), ['class' => 'btn btn-success btn-flat']); ?> ?>
    </div>
    <div class="box-body">
        <table class="table table-bordered table-striped">
            <tr>
                <th style="" colspan="2">NILAI JABATAN</th>
                <td style="text-align: left;"><?= @$model->kelasJabatan->getNilaiTengah() ?></td>
            </tr>
            <tr>
                <th style="" colspan="2">KELAS JABATAN</th>
                <td style="text-align: left;"><?= @$model->kelasJabatan->kelas_jabatan ?></td>
            </tr>
            <tr>
                <th style="" colspan="2">FAKTOR<br>PENYEIMBANG (%)</th>
                <td style="text-align: left;"><?= @$model->jabatan->penyeimbang ?></td>
            </tr>
            <tr>
                <th style="" colspan="2">IDRp</th>
                <td style="text-align: left;">
                    Proses Penyesuaian
                    <?php // Helper::rp(Yii::$app->params['idrp']) ?>
                </td>
            </tr>
            <tr>
                <th style="" rowspan="2">REALISASI KINERJA (80%)</th>
                <th style="text-align: center;">%</th>
                <td style="text-align: left;"><?= @$rekap->getPersenKinerja() ?></td>
            </tr>
            <tr>
                <th style="text-align: center;">(RUPIAH)</th>
                <td style="text-align: left;">
                    Proses Penyesuaian
                    <?php //Helper::rp(@$model->getRupiahKinerjaPersen($rekap), 0) ?>
                </td>
            </tr>
            <tr>
                <th style="" rowspan="2">REALISASI PRESENSI (10%)</th>
                <th style="text-align: center;">%</th>
                <td style="text-align: left;"><?= @$rekap->getPersenAbsensi() ?></td>
            </tr>
            <tr>
                <th style="text-align: center;">(RUPIAH)</th>
                <td style="text-align: left;">
                    Proses Penyesuaian
                    <?php // Helper::rp(@$model->getRupiahAbsensiPersen($rekap), 0) ?>
                </td>
            </tr>

            <tr>
                <th style="" rowspan="2">REALISASI SERAPAN ANGGARAN INSTANSI (10%)</th>
                <th style="text-align: center;">%</th>
                <td style="text-align: left;"><?= @$rekap->getPersenSerapanAnggaran() ?></td>
            </tr>

            <tr>
                <th style="text-align: center;">(RUPIAH)</th>
                <td style="text-align: left;"><?= Helper::rp(@$model->getRupiahSerapanAnggaranPersen($rekap), 0) ?></td>
            </tr>

            <tr>
                <th colspan="2">VARIABEL OBJEKTIF TAMBAHAN</th>
                <td style="text-align: left;">
                    <ul>
                        <?php foreach ($model->getManyVariabelObjektifBulan($filter->bulan) as $variabelObjektif) { ?>
                            <li><?= $variabelObjektif->refVariabelObjektif->uraian ?> : <?= Helper::rp($variabelObjektif->tarif, 0) ?></li>
                        <?php } ?>
                    </ul>
                    Jumlah : <span style="font-weight: bold"><?= Helper::rp($model->getTarifVariabelObjektifBulan($filter->bulan)) ?></span>
                </td>
            </tr>
            <tr>
                <th colspan="2">JUMLAH TUKIN (100%)</th>
                <td style="text-align: left">
                    Proses Penyesuaian
                    <?php // Helper::rp($model->getRupiahTukin(), 0) ?>
                </td>
            </tr>
            <tr>
                <th style="" colspan="2">JUMLAH TUKIN</th>
                <td style="text-align: left;">
                    Proses Penyesuaian
                    <?php // Helper::rp(@$model->getRupiahTukinPersen($filter->bulan), 0) ?>
                </td>
            </tr>
            <tr>
                <th rowspan="4">HUKUMAN DISIPLIN</th>
                <th style="text-align: center">RINGAN</th>
                <td style="text-align: left"><?= @$model->getPotonganHukumanDisiplinRingan($filter->bulan) ?></td>
            </tr>
            <tr>
                <th style="text-align: center">SEDANG</th>
                <td style="text-align: left"><?= @$model->getPotonganHukumanDisiplinSedang($filter->bulan) ?></td>
            </tr>
            <tr>
                <th style="text-align: center">BERAT</th>
                <td style="text-align: left"><?= @$model->getPotonganHukumanDisiplinBerat($filter->bulan) ?></td>
            </tr>
            <tr>
                <th style="text-align: center">TOTAL</th>
                <td style="text-align: left"><?= @$model->getPotonganHukumanDisiplin($filter->bulan) ?></td>
            </tr>
            <tr>
                <th colspan="2">JUMLAH AKHIR</th>
                <th style="text-align: left">
                    Proses Penyesuaian
                    <?php // Helper::rp(@$model->getRupiahAkhirPersen($filter->bulan), 0) ?>
                </th>
            </tr>
        </table>
        <?php echo DetailView::widget([
            'model' => ($rekap = $model->findOrCreatePegawaiRekapTunjangan($filter->bulan)),
            'template' => '<tr><th width="180px" style="text-align:right">{label}</th><td>{value}</td></tr>',
            'attributes' => [
                [
                    'label' => 'Kelas Jabatan',
                    'value' => @$rekap->jabatan->kelas_jabatan
                ],
                [
                    'label' => 'Nilai Jabatan',
                    'value' => Helper::rp(@$rekap->kelasJabatan->nilai_minimal) . ' - ' . Helper::rp(@$rekap->kelasJabatan->nilai_maksimal),
                ],
                [
                    'label' => 'Asumsi Idrp',
                    'value' => Helper::rp(Yii::$app->params['idrp']),
                ],
                [
                    'label' => 'Nilai Minimal',
                    'value' => Helper::rp(@$rekap->kelasJabatan->getBesaranMinimal())
                ],
                [
                    'label' => 'Nilai Maksimal',
                    'value' => Helper::rp(@$rekap->kelasJabatan->getBesaranMaksimal())
                ]
            ],
        ]); ?>
    </div>
</div>
 */ ?>
