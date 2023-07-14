<?php

use app\components\Helper;
use app\modules\absensi\models\Absensi;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

$date = $pegawaiSearch->getDate();
$pegawai->platform = 'mobile';

?>

<?=$this->render('_search-view', [
    'pegawaiSearch' => $pegawaiSearch,
    'action' => Url::to(['web-view-kehadiran', 'nip' => $pegawai->nip]),
]);?>

<div class="pegawai-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Data Pegawai</h3>
    </div>

    <div class="box-body">
        <?= DetailView::widget([
            'model' => $pegawai,
            'template' => '<tr><th style="text-align:left">{label}</th><td>{value}</td></tr>',
            'attributes' => [
                [
                    'attribute' => 'nama',
                    'format' => 'raw',
                    'value' => Html::encode($pegawai->nama),
                ],
                [
                    'attribute' => 'nip',
                    'format' => 'raw',
                    'value' => Html::encode($pegawai->nip),
                ],
                [
                    'attribute' => 'id_instansi',
                    'label' => 'Perangkat Daerah',
                    'format' => 'raw',
                    'value' => $pegawai->getNamaInstansi([
                        'bulan'=>$pegawaiSearch->bulan,
                        'tahun'=>$pegawaiSearch->tahun
                    ])
                ],
                [
                    'attribute' => 'nama_jabatan',
                    'format' => 'raw',
                    'value' => $pegawai->getNamaJabatan([
                        'bulan'=>$pegawaiSearch->bulan,
                        'tahun'=>$pegawaiSearch->tahun
                    ])
                ],
            ],
        ]) ?>
    </div>
</div>

<div class="box box-primary">
    <div class="box-header">
        <h3 class="box-title">Rincian Potongan</h3>
    </div>
    <div class="box-body">
        <div style="overflow-y: auto">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th style="text-align: center; width: 60px">No</th>
                    <th colspan="2">Uraian</th>
                    <th>Jumlah</th>
                    <th>Satuan Potongan</th>
                    <th>Total Potongan</th>
                </tr>
                </thead>
                <tr>
                    <td style="text-align: center">1</td>
                    <td colspan="2">Tidak Hadir Tanpa Keterangan</td>
                    <td style="text-align: center">
                        <?= Helper::rp($pegawaiRekapAbsensi->jumlah_tanpa_keterangan,0); ?> Kali
                    </td>
                    <td style="text-align: center">12%</td>
                    <td style="text-align: center">
                        <?= Helper::rp($pegawai->getPersenPotonganTidakHadirTanpaKeterangan(),0); ?>%
                    </td>
                </tr>
                <tr>
                    <td rowspan="4" style="text-align: center; vertical-align: middle">2</td>
                    <td rowspan="4" style="vertical-align: middle">Terlambat Masuk Kerja Pagi</td>
                    <td>1 - 15 Menit</td>
                    <td style="text-align: center">
                        <?= $pegawai->getJumlahTerlambatMasukKerjaInterval(Absensi::INTERVAL_1_SD_15); ?> Kali
                    </td>
                    <td style="text-align: center">1%</td>
                    <td style="text-align: center">
                        <?= $pegawai->getPersenPotonganTerlambatMasukKerjaInterval(Absensi::INTERVAL_1_SD_15); ?>%
                    </td>
                </tr>
                <tr>
                    <td>16 - 30 Menit</td>
                    <td style="text-align: center">
                        <?= $pegawai->getJumlahTerlambatMasukKerjaInterval(Absensi::INTERVAL_16_SD_30); ?> Kali
                    </td>
                    <td style="text-align: center">2%</td>
                    <td style="text-align: center">
                        <?= $pegawai->getPersenPotonganTerlambatMasukKerjaInterval(Absensi::INTERVAL_16_SD_30); ?>%
                    </td>
                </tr>
                <tr>
                    <td>31 Menit ke Atas</td>
                    <td style="text-align: center">
                        <?= $pegawai->getJumlahTerlambatMasukKerjaInterval(Absensi::INTERVAL_31_KE_ATAS); ?> Kali
                    </td>
                    <td style="text-align: center">2.5%</td>
                    <td style="text-align: center">
                        <?= $pegawai->getPersenPotonganTerlambatMasukKerjaInterval(Absensi::INTERVAL_31_KE_ATAS); ?>%
                    </td>
                </tr>
                <tr>
                    <td>Tidak Presensi</td>
                    <td style="text-align: center">
                        <?= $pegawai->getJumlahTerlambatMasukKerjaInterval(Absensi::INTERVAL_TIDAK_PRESENSI); ?> Kali
                    </td>
                    <td style="text-align: center">4%</td>
                    <td style="text-align: center">
                        <?= $pegawai->getPersenPotonganTerlambatMasukKerjaInterval(Absensi::INTERVAL_TIDAK_PRESENSI); ?>%
                    </td>
                </tr>
                <tr>
                    <td rowspan="4" style="text-align: center; vertical-align: middle">3</td>
                    <td rowspan="4" style="vertical-align: middle">Terlambat Masuk Kerja Setelah Istirahat</td>
                    <td>1 - 15 Menit</td>
                    <td style="text-align: center">
                        <?= $pegawai->getJumlahTerlambatMasukIstirahatInterval(Absensi::INTERVAL_1_SD_15); ?> Kali
                    </td>
                    <td style="text-align: center">1%</td>
                    <td style="text-align: center">
                        <?= $pegawai->getPersenPotonganTerlambatMasukIstirahatInterval(Absensi::INTERVAL_1_SD_15); ?>%
                    </td>
                </tr>
                <tr>
                    <td>16 - 30 Menit</td>
                    <td style="text-align: center">
                        <?= $pegawai->getJumlahTerlambatMasukIstirahatInterval(Absensi::INTERVAL_16_SD_30); ?> Kali
                    </td>
                    <td style="text-align: center">2%</td>
                    <td style="text-align: center">0%</td>
                </tr>
                <tr>
                    <td>31 Menit ke Atas</td>
                    <td style="text-align: center">
                        <?= $pegawai->getJumlahTerlambatMasukIstirahatInterval(Absensi::INTERVAL_31_KE_ATAS); ?> Kali
                    </td>
                    <td style="text-align: center">2.5%</td>
                    <td style="text-align: center">
                        <?= $pegawai->getPersenPotonganTerlambatMasukIstirahatInterval(Absensi::INTERVAL_31_KE_ATAS); ?>%
                    </td>
                </tr>
                <tr>
                    <td>Tidak Presensi</td>
                    <td style="text-align: center">
                        <?= $pegawai->getJumlahTerlambatMasukIstirahatInterval(Absensi::INTERVAL_TIDAK_PRESENSI); ?> Kali
                    </td>
                    <td style="text-align: center">4%</td>
                    <td style="text-align: center">
                        <?= $pegawai->getPersenPotonganTerlambatMasukIstirahatInterval(Absensi::INTERVAL_TIDAK_PRESENSI); ?>%
                    </td>
                </tr>
                <tr>
                    <td rowspan="2"style="text-align: center">4</td>
                    <td rowspan="2">Pulang Sebelum Waktunya</td>
                    <td>Lebih Awal</td>
                    <td style="text-align: center">
                        <?= $pegawai->getJumlahPulangAwalKeluarKerjaSemuaInterval(); ?> Kali
                    </td>
                    <td style="text-align: center">1%</td>
                    <td style="text-align: center">
                        <?= $pegawai->getPersenPotonganPulangAwalKeluarKerjaSemuaInterval(); ?>%
                    </td>
                </tr>
                <tr>
                    <td>Tidak Presensi</td>
                    <td style="text-align: center">
                        <?= $pegawai->getJumlahPulangAwalKeluarKerja(Absensi::INTERVAL_TIDAK_PRESENSI); ?> Kali
                    </td>
                    <td style="text-align: center">4%</td>
                    <td style="text-align: center">
                        <?= $pegawai->getPersenPotonganPulangAwalKeluarKerjaInterval(Absensi::INTERVAL_TIDAK_PRESENSI); ?>%
                    </td>
                </tr>

                <tr>
                    <td style="text-align: center">5</td>
                    <td colspan="2">Tidak Melaksanakan Kegiatan Apel Pagi, Apel Sore, atau Olahraga Tanpa Keterangan</td>
                    <td style="text-align: center">
                        <?= $pegawai->getJumlahKetidakhadiranKegiatanTanpaKeteranganSelainSidak(); ?> Kali
                    </td>
                    <td style="text-align: center">4%</td>
                    <td style="text-align: center">
                        <?= $pegawai->getPersenPotonganKetidakhadiranKegiatanTanpaKeteranganSelainSidak(); ?>%
                    </td>
                </tr>

                <tr>
                    <td style="text-align: center">6</td>
                    <td colspan="2">Meninggalkan Tugas Tanpa Izin Atasan Pada Saat Sidak</td>
                    <td style="text-align: center">
                        <?= $pegawai->getJumlahKetidakhadiranKegiatanTanpaKeteranganSidak(); ?> Kali
                    </td>
                    <td style="text-align: center">4%</td>
                    <td style="text-align: center">
                        <?= $pegawai->getPersenPotonganKetidakhadiranKegiatanTanpaKeteranganSidak(); ?>%
                    </td>
                </tr>
                <tr>
                    <th></th>
                    <th colspan="4">TOTAL</th>
                    <th style="text-align: center">
                        <?= Helper::rp($pegawai->getPersenPotonganPresensi(),0,1); ?>%
                    </th>
                </tr>
            </table>
        </div>
    </div>
</div>

<?= $this->render('_view-rekap-statis', [
    'pegawaiRekapAbsensi' => $pegawaiRekapAbsensi
]); ?>

<div class="pegawai-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Daftar Kehadiran</h3>
    </div>
    <div class="box-header">
        <?=Html::a('<i class="fa fa-exchange"></i> Tampilkan/Sembunyikan Rincian', '#', ['id' => 'btn-jam-kerja', 'class' => 'btn btn-primary btn-flat']);?>
    </div>

    <div class="box-body table-responsive">
        <?php if ($pegawai->getIsAbsensiManual()==true) {
            echo $this->render('_tabel-absensi-ceklis', ['date' => $date, 'pegawai' => $pegawai]);
        } else {
            echo $this->render('_tabel-absensi', ['date' => $date, 'pegawai' => $pegawai]);
        } ?>
    </div>
</div>

<?php

$script = <<<'JS'

        $("#btn-jam-kerja").click(function(event) {
            event.preventDefault();
            event.stopPropagation();
            $("tr.tanggal").toggleClass("bg-grey");
            $("tr.tanggal").toggleClass("bold");
            $("tr.jam-kerja").toggle();
        });

        $(".link-tanggal").click(function(event) {
            var tanggal = $(this).data("tanggal");
            $("tr.tanggal-"+tanggal).toggleClass("bg-grey").toggleClass("bold");
            $("tr.jam-kerja-"+tanggal).toggle("fast");
            event.preventDefault();
            event.stopPropagation();
        });

JS;
$this->registerJs($script);

?>

<script type="text/javascript">
    document.body.style.zoom = "70%" 
</script>