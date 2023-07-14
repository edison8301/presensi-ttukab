<?php



/* @var $this \yii\web\View */

use app\components\Helper;
use yii\helpers\Html;

/* @var $date \DateTime */
/* @var $pegawai \app\models\Pegawai|mixed|null */

$presensiCeklis = $pegawai->getPresensiCeklis($date->format('n'));
?>

<table class="table table-hover table-bordered">
    <thead>
    <tr>
        <th style="text-align: center; width:100px">Tanggal</th>
        <th style="text-align: center; width:150px">Hari</th>
        <th style="text-align: center">Waktu Absensi</th>
        <th style="text-align: center">% Potong</th>
        <th style="text-align: center">Keterangan</th>
        <th>&nbsp;</th>
    </tr>
    </thead>
    <?php $tgl = $date->format('t');?>
    <?php foreach ($presensiCeklis->presensiHari as $presensiHari) { ?>
        <tr class="tanggal tanggal-<?=$presensiHari->date->format('Y-m-d') ?> bg-grey bold" data-tanggal="<?=$presensiHari->date->format('Y-m-d') ?>" style="cursor:pointer;" data-tanggal="<?=$presensiHari->tanggal ?>">
            <td style="text-align: center"><?=Helper::getTanggalSingkat($presensiHari->tanggal) ?></td>
            <td style="text-align: center">
                <?= Helper::getHari($presensiHari->tanggal) ?>
            </td>
            <td>
                <?= $presensiHari->getStringCheckinout() ?>
                <?= $presensiHari->getButtons() ?>
            </td>
            <td style="text-align: center"><?= $presensiHari->getPotongan() ?> %</td>
            <td style="text-align: center">
            </td>
            <td style="text-align: center">
            </td>
        </tr>
        <?php foreach ($presensiHari->presensiJamKerja as $presensiJamKerja) { ?>
            <tr>
                <td style="text-align: center"><?= $presensiJamKerja->getRange() ?></td>
                <td style="text-align: center"><?= $presensiJamKerja->getNama() ?></td>
                <td>
                    <?= $presensiJamKerja->getStringCheckinout() ?>
                    <?php if (($ketidakhadiranCeklis = $presensiJamKerja->ketidakhadiranCeklis) === null) {
                        if ($presensiHari->isHariLibur() == false) {
                            echo Html::a(
                                '<i class="fa fa-remove"></i>',
                                [
                                    '/absensi/ketidakhadiran-ceklis/create',
                                    'tanggal' => $presensiJamKerja->tanggal,
                                    'id_jam_kerja' => $presensiJamKerja->jamKerja->id,
                                    'id_pegawai' => $presensiJamKerja->pegawai->id,
                                ],
                                [
                                    'data-toggle' => 'tooltip',
                                    'title' => 'Tandai Tidak Hadir',
                                ]
                            );
                        }
                    } else {
                        echo Html::a(
                            '<i class="fa fa-trash"></i>',
                            [
                                '/absensi/ketidakhadiran-ceklis/delete',
                                'id' => $ketidakhadiranCeklis->id,
                            ],
                            [
                                'data-toggle' => 'tooltip',
                                'data-confirm' => 'Yakin akan menghapus tanda tidak hadir?',
                                'data-method' => 'post',
                                'title' => 'Hapus Tanda Tidak Hadir',
                            ]
                        );
                    } ?>
                    <?= $presensiJamKerja->getButton(); ?>
                </td>
                <td style="text-align: center"><?= $presensiJamKerja->getPotongan() ?> %</td>
                <td style="text-align: center"></td>
                <td style="text-align: center"></td>
            </tr>
        <?php } ?>
        <?php $date->modify('+1 day');?>
    <?php } ?>
</table>
