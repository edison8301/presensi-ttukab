<?php

/* @var $searchModel \app\models\InstansiPegawaiSearch */
/* @var $query \app\models\InstansiPegawaiQuery */
/* @var $this \yii\web\View */
/* @var $modelExportPdf \app\modules\absensi\models\ExportPdf */

use app\components\Helper;
use app\models\Jabatan;
use app\models\Pegawai;
use app\models\User;

?>

<div style="text-align: center;">DAFTAR REKAPITULASI PERHITUNGAN TAMBAHAN PENGHASILAN BERDASASRKAN BEBAN KERJA</div>
<div style="text-align: center;">TAHUN ANGGARAN <?= User::getTahun(); ?></div>

<div>&nbsp;</div>

<table>
    <tr>
        <td>PD / UNIT KERJA</td>
        <td>:</td>
        <td><?= @$searchModel->instansi->nama; ?></td>
    </tr>
    <tr>
        <td>BULAN</td>
        <td>:</td>
        <td style="text-transform:uppercase"><?= $searchModel->getBulanLengkapTahun(); ?></td>
    </tr>
</table>

<div>&nbsp;</div>

<table cellpadding="11" border="1" style="margin-bottom: 70px; background: #ffffff">
    <thead>
    <tr>
        <th rowspan="4" style="vertical-align: middle; text-align: center; width:30px; vertical-align: middle; border-bottom: 0px">
            NO
        </th>
        <th rowspan="4" style="vertical-align: middle; text-align: center; vertical-align: middle; border-bottom: 0px">
            NAMA/NIP
        </th>
        <th rowspan="4" style="vertical-align: middle; text-align: center; vertical-align: middle; border-bottom: 0px">
            GOL
        </th>
        <th rowspan="4" style="vertical-align: middle; text-align: center; vertical-align: middle; border-bottom: 0px">
            JABATAN/ESELON
        </th>
        <th rowspan="4" style="vertical-align: middle; text-align: center; vertical-align: middle; border-bottom: 0px">
            BESARAN TPP<br/>(RP)
        </th>
        <th colspan="21">PERHITUNGAN TPP</th>

    </tr>
    <tr>
        <th colspan="6">FAKTOR KINERJA</th>
        <th rowspan="3" style="vertical-align: middle;">JUMLAH POT.<br/>(RP)</th>
        <th colspan="11">FAKTOR LAINNNYA</th>
        <th rowspan="3" style="vertical-align: middle;" >JUMLAH TOTAL POT. (RP)</th>
        <th rowspan="3" style="vertical-align: middle;" >JUMLAH TPP SEBELUM PAJAK (RP)</th>
    </tr>
    <tr>
        <th colspan="3">UNSUR PRODUKTIVITAS KERJA</th>
        <th colspan="3">UNSUR DISIPLIN KERJA</th>
        <th colspan="7">HUKUMAN DISIPLIN</th>
        <th colspan="4">PELANGGARAN KETENTUAN JF</th>
    </tr>
    <tr>
        <th>70% X BESARAN TPP</th>
        <th>POT. SKP<br/>BULANAN<br/>(%)</th>
        <th>JUMLAH<br/>POT.(RP)</th>
        <th>30% BESARAN TPP</th>
        <th>POT.<br/>PRESENSI<br/>(%)</th>
        <th>JUMLAH POT. (RP)</th>

        <th>RINGAN<br/>(10%)</th>
        <th>SEDANG<br/>(20%)</th>
        <th>BERAT<br/>(30%)</th>
        <th>LHKPN/<br>LHKASN <br>(50%)</th>
        <th>TPTGR<br/>(50%)</th>
        <th>JUMLAH POT.<br/>(%)</th>
        <th>JUMLAH POT.<br/>(RP)</th>

        <th>TIDAK ADA DUPAK (25%)</th>
        <th>BLM DIANGKAT JF SELAMA 7 TH (10%)</th>
        <th>JUMLAH POT. (%)</th>
        <th>JUMLAH POT. (RP)</th>
    </tr>
    <tr>
        <th>1</th>
        <th>2</th>
        <th>3</th>
        <th>4</th>
        <th>5</th>
        <th>6</th>
        <th>7</th>
        <th>8=6X7</th>
        <th>9</th>
        <th>10</th>
        <th>11=9X10</th>
        <th>12=8+1</th>
        <th>13</th>
        <th>14</th>
        <th>15</th>
        <th>16</th>
        <th>17</th>
        <th>18=13+14+<br/>15+16+17</th>
        <th>19=5X18</th>
        <th>20</th>
        <th>21</th>
        <th>22=20+21</th>
        <th>23=5X22</th>
        <th>24=12+19+23</th>
        <th>25=5-24</th>

    </tr>
    </thead>


    <?php $i=1; foreach($query->all() as $instansiPegawai) { ?>
        <?php $pegawai = \app\modules\tukin\models\Pegawai::findOne($instansiPegawai->id_pegawai); ?>
        <tr>
            <td class="1" style="text-align:center"><?= $i; ?></td>
            <td class="2">
                <?= $pegawai->nama; ?><br>
                <?= 'NIP.'.$pegawai->nip; ?>
            </td>
            <td class="3" style="text-align: center">
                <?= @$pegawai->getNamaPegawaiGolonganBerlaku(['bulan'=>$searchModel->bulan]); ?>
            </td>
            <td class="4">
                <?= @$instansiPegawai->jabatan->nama; ?>
            </td>
            <td class="5" style="text-align: right"><?= Helper::rp($pegawai->getTppAwal($searchModel->bulan)) ?></td>
            <td class="6" style="text-align: right"><?= Helper::rp($pegawai->getTpp70($searchModel->bulan)) ?></td>
            <td class="7" style="text-align: center"><?= number_format($pegawai->getPersenPotonganSkpBulanan($searchModel->bulan),2); ?> %</td>
            <td class="8" style="text-align: right"><?= Helper::rp($pegawai->getRupiahPotonganProduktivitas($searchModel->bulan),0); ?></td>
            <td class="9" style="text-align: right"><?= Helper::rp($pegawai->getTpp30($searchModel->bulan)); ?></td>
            <td class="10" style="text-align: center">
                <?= Helper::rp($pegawai->getPersenPotonganPresensi($searchModel->bulan,['tukin'=>true]),0,2); ?> %
            </td>
            <td class="11" style="text-align: right"><?= Helper::rp($pegawai->getRupiahPotonganDisiplinKerja($searchModel->bulan,['tukin'=>true]),0) ?></td>
            <td class="12" style="text-align: right"><?= Helper::rp($pegawai->getRupiahPotonganFaktorKinerja($searchModel->bulan),0); ?></td>
            <td class="13" style="text-align: center"><?= $pegawai->getPersenPotonganHukumanDisiplinRingan($searchModel->bulan); ?></td>
            <td class="14" style="text-align: center"><?= $pegawai->getPersenPotonganHukumanDisiplinSedang($searchModel->bulan); ?></td>
            <td class="15" style="text-align: center"><?= $pegawai->getPersenPotonganHukumanDisiplinBerat($searchModel->bulan); ?></td>
            <td class="16" style="text-align: center"><?= $pegawai->getHukumanLHKPN($searchModel->bulan); ?></td>
            <td class="17" style="text-align: center"><?= $pegawai->getHukumanTPTGR($searchModel->bulan); ?></td>
            <td class="18" style="text-align: center"><?= Helper::rp($pegawai->getPersenPotonganHukumanDisiplinTotal($searchModel->bulan),0,2); ?> % </td>
            <td class="19" style="text-align: right"><?= Helper::rp($pegawai->getRupiahPotonganHukumanDisiplinTotal($searchModel->bulan),0); ?></td>
            <td class="20" style="text-align: center"><?= $pegawai->getPersenPotonganDupak($searchModel->bulan); ?></td>
            <td class="21" style="text-align: center"><?= $pegawai->getPersenPotonganJfBelumDiangkat($searchModel->bulan); ?>%</td>
            <td class="22" style="text-align: center"><?= Helper::rp($pegawai->getPersenPotonganPelanggaranKetentuanJf($searchModel->bulan),0); ?> %</td>
            <td class="23" style="text-align: right"><?= Helper::rp($pegawai->getRupiahPotonganPelanggaranKetentuanJf($searchModel->bulan),0); ?></td>
            <td class="24" style="text-align: right"><?= Helper::rp($pegawai->getRupiahPotonganKeseluruhan($searchModel->bulan),0); ?></td>
            <td class="25" style="text-align: right"><?= Helper::rp($pegawai->getRupiahTPPSebelumPajak($searchModel->bulan),0); ?></td>
        </tr>
        <?php $i++; } ?>
</table>

<?php
/** @var Jabatan $jabatanKepala */
$jabatanKepala = $modelExportPdf->instansi->getManyJabatanKepala()->one();
$nama = null;
$nip = null;
if ($jabatanKepala) {
    /** @var Pegawai $instansiPegawaiKepala */
    $instansiPegawaiKepala = $jabatanKepala->getManyInstansiPegawai()
        ->orderBy(['tanggal_mulai'=>SORT_DESC])
        ->one();

    if ($instansiPegawaiKepala) {
        $nama = @$instansiPegawaiKepala->pegawai->nama;
        $nip = @$instansiPegawaiKepala->pegawai->nip;
    }
}
?>
<?php if($tandatangan == false) { ?>
    <table class="table" style="page-break-inside: avoid">
        <tr>
            <td style="width: 78%"></td>
            <td>Pangkalpinang,</td>
        </tr>
        <tr>
            <td></td>
            <td style="text-align: center">Menyetujui</td>
        </tr>
        <tr>
            <td></td>
            <td style="text-align: center"><?= $jabatanKepala->nama; ?></td>
        </tr>
        <tr>
            <td></td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td></td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td></td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td></td>
            <td style="text-align: center"><?= $nama ?></td>
        </tr>
        <tr>
            <td></td>
            <td style="text-align: center">NIP. <?= $nip ?></td>
        </tr>
    </table>
<?php } ?>

<?php if($tandatangan == true) { ?>
    <?= $this->render('_tandatangan-elektronik', [
        'nama' => $nama,
        'jabatan' => @$jabatanKepala->nama,
        'nip' => $nip,
    ]); ?>
<?php } ?>
