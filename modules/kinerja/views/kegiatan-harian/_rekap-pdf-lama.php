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

        <tr>
            <th style="text-align: center; vertical-align: middle;" rowspan="3">No</th>
            <th style="text-align: center; vertical-align: middle;" rowspan="3">Nama <br> Pegawai</th>
            <th style="text-align: center; vertical-align: middle;" rowspan="3">NIP</th>
            <th style="text-align: center; vertical-align: middle;" rowspan="3">Jabatan</th>
            <th style="text-align: center; vertical-align: middle;" colspan="<?= $jumlahHari + 3; ?>">Perhitungan SKP dan CKHP</th>
            <th style="text-align: center; vertical-align: middle;" rowspan="3">TOT.<br>POT%</th>
            <th style="text-align: center; vertical-align: middle;" rowspan="3">KET</th>
        </tr>
        <tr>
            <th style="text-align: center; vertical-align: middle;" rowspan="2">SKP</th>
            <th style="text-align: center; vertical-align: middle;" rowspan="2">POT<br>SKP<br>(%)</th>
            <th style="text-align: center; vertical-align: middle;" colspan="<?= $jumlahHari; ?>">CKHP</th>
            <th style="text-align: center; vertical-align: middle;" rowspan="2">POT<br>CKHP<br>(%)</th>
        </tr>
        <tr>
            <?php for ($i = 1; $i <= $date->format('t'); $i++) { ?>
                <th style="text-align: center; vertical-align: middle;"><?= $i < 10 ? "&nbsp;" . $i : $i; ?></th>
            <?php } ?>
        </tr>

    <?php $no = 1; ?>
    <?php foreach ($searchModel->searchPegawaiRekap() as $pegawai): ?>
        <tr>
            <td style="text-align: center;"><?= $no++; ?></td>
            <td><?= $pegawai->nama; ?></td>
            <td><?= $pegawai->nip; ?></td>
            <td><?= $pegawai->nama_jabatan; ?></td>
            <td style="text-align: center;"><?= $pegawai->getCeklisSkp($date->format('Y')); ?></td>
            <td style="text-align: center;"><?= $pegawai->getPotonganSkp($date->format('Y')); ?></td>
            <?php for ($i = 1; $i <= $jumlahHari; $i++) { ?>
            <?php $tanggal = $date->format("Y-m-" . sprintf("%02d", $i)); ?>
                <td <?= $pegawai->getClassCeklis($tanggal) === "bg-gray" ? 'style="text-align: center; background-color: #d2d6de;"' : 'style="text-align: center;"'; ?>><?= $pegawai->getCeklis($tanggal); ?></td>
            <?php } ?>
            <td style="text-align: center;"><?= $pegawai->getPotonganCkhpTotal($searchModel->getDate()->format("m"), $searchModel->getDate()->format('Y')); ?></td>
            <td style="text-align: center;"><?= $pegawai->getPotonganKegiatanTotal($searchModel->getDate()->format("m")); ?></td>
            <td>&nbsp;</td>
        </tr>
    <?php endforeach ?>
</table>
