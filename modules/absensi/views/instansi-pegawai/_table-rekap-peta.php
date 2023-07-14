<?php 

/* @var $this yii\web\View */
/* @var $pegawaiPetaAbsensiForm app\models\PegawaiPetaAbsensiForm; */
/* @var $allPegawaiPetaAbsensiReport app\models\PegawaiPetaAbsensiReport[]; */

$colspan = $pegawaiPetaAbsensiForm->date->format('t');

?>

<table class="table table-bordered table-condensed table-hover" style="table-layout: fixed;">
    <thead>
        <tr>
            <th style="text-align: center; width: 50px;" rowspan="2">No</th>
            <th style="text-align: center; width: 200px;" rowspan="2">Nama</th>
            <th style="text-align: center; width: <?= 70*$colspan ?>px" colspan="<?=$colspan ?>">Tanggal</th>
        </tr>
        <tr>
            <?php for($i=1; $i<=$colspan; $i++) { ?>
                <th style="text-align: center; width: 70px;"><?= $i ?></th>
            <?php } ?>
        </tr>
    </thead>
    <?php $no=1; foreach ($allPegawaiPetaAbsensiReport as $pegawaiPetaAbsensiReport) { ?>
        <tr>
            <td style="text-align: center;"><?= $no++; ?></td>
            <td style="text-align: left;"><?= $pegawaiPetaAbsensiReport->pegawai->nama ?></td>
            <?php for($i=1; $i<=$colspan; $i++) { ?>
                <?php $datetimeFor = \DateTime::createFromFormat('Y-n-j', $pegawaiPetaAbsensiReport->tahun . '-' . $pegawaiPetaAbsensiReport->bulan . '-' . $i) ?>
                <td style="text-align: center;">
                    <?= $pegawaiPetaAbsensiReport->getStringCheckinout([
                        'tanggal' => $datetimeFor->format('Y-m-d'),
                        'link' => @$pdf !== true,
                    ]) ?>
                </td>
            <?php } ?>
        </tr>
    <?php } ?>
    <?php if ($pegawaiPetaAbsensiForm->id_instansi == null) { ?>
        <tr>
            <td colspan="<?= $colspan + 2 ?>"><i>Silahkan pilih perangkat daerah terlebih dahulu</i></td>
        </tr>
    <?php } ?>
</table>