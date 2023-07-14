<?php

use app\components\Helper;

/* @var $no int */
/* @var $data \app\models\InstansiPegawai */
/* @var $searchModel \app\models\InstansiPegawaiSearch */

?>

<tr>
    <td style="text-align: center;"><?= $no++; ?></td>
    <td><?= @$data->pegawai->nama; ?></td>
    <td><?= @$data->pegawai->nip; ?></td>
    <td><?= $data->getNamaJabatan(); ?></td>
    <td style="text-align: center;"><?= $data->pegawai->getCeklisSkp($searchModel->tahun, $searchModel->bulan); ?></td>
    <td style="text-align: center;">
        <?= Helper::rp($potonganSkp = $data->pegawai->getPotonganSkp($searchModel->tahun, $searchModel->bulan),0,2); ?>
    </td>
    <td style="text-align: center;">
        <?= Helper::rp($data->pegawai->getPersenRealisasiKegiatanBulanan($searchModel->bulan),0,2); ?>
    </td>
    <td style="text-align: center;">
        <?= Helper::rp($potonganRkb = $data->pegawai->getPersenPotonganSkpBulanan($searchModel->bulan),0,2); ?>
    </td>
    <td style="text-align: center">
        <?= Helper::rp($potonganSkp + $potonganRkb,0,2); ?>
    </td>
    <td></td>
</tr>
