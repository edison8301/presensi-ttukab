<div style="text-align: center; text-transform: uppercase;">Daftar Rekapitulasi SKP Dan CKHP</div>

<div>&nbsp;</div>

<table>
<tr>
    <td>OPD</td>
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
<?php $date = $searchModel->getDate(); ?>
<?php $jumlahHari = $date->format('t'); ?>
<table class="table table-bordered rekap">
    <thead>
    <tr>
        <th style="text-align: center; vertical-align: middle;" rowspan="2">NO</th>
        <th style="text-align: center; vertical-align: middle;" rowspan="2">NAMA<br>PEGAWAI</th>
        <th style="text-align: center; vertical-align: middle;" rowspan="2">NIP</th>
        <th style="text-align: center; vertical-align: middle;" rowspan="2">JABATAN</th>
        <th style="text-align: center; vertical-align: middle;" colspan="4">PERHITUNGAN SKP DAN RKB</th>
        <th style="text-align: center; vertical-align: middle;" rowspan="2">TOT.<br>POT%</th>
        <th style="text-align: center; vertical-align: middle;" rowspan="2">KET</th>
    </tr>
    <tr>
        <th style="text-align: center; vertical-align: middle;">SKP</th>
        <th style="text-align: center; vertical-align: middle;">POTONGAN<br>SKP<br>(%)</th>
        <th style="text-align: center; vertical-align: middle;">REALISASI<br/>SKB<br/>(%)</th>
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
                </td>
                <td>&nbsp;</td>
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
