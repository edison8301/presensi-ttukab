<?php

use app\components\Helper;
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $date \DateTime */
/* @var $pegawai \app\models\Pegawai|mixed|null */
?>

<table class="table table-hover table-bordered">
    <thead>
    <tr>
        <th style="text-align: center; width:100px">Tanggal</th>
        <th style="text-align: center; width:150px">Hari</th>
        <th style="text-align: center">Waktu Kehadiran</th>
        <th style="text-align: center">% Potong</th>
        <th style="text-align: center">Keterangan</th>
        <th>&nbsp;</th>
    </tr>
    </thead>
    <?php $tgl = $date->format('t');?>
    <?php for ($j = 1; $j <= $tgl; $j++) { ?>
        <?php $tanggal = $date->format('Y-m-d'); ?>
        <?php $shiftKerja = $pegawai->findShiftKerja(['tanggal' => $tanggal]) ?>
        <?php //$pegawai->getPotonganHari($date, $shiftKerja) ?>
        <?php $pegawai->_potongan_hari = $pegawai->_hari[$tanggal]['_potongan_hari'];?>
        <tr class="tanggal tanggal-<?=$date->format('Y-m-d');?> bg-grey bold" data-tanggal="<?=$date->format('Y-m-d');?>" style="cursor:pointer;" data-tanggal="<?=$tanggal;?>">
            <td style="text-align: center"><?=Helper::getTanggalSingkat($tanggal);?></td>
            <td style="text-align: center"><?=Helper::getHari($tanggal) . ($shiftKerja->isDoubleShift ? "<br> (Double Shift)" : null);?></td>
            <td>
                <?php
                $output = "";
                //$output .= $pegawai->_hari[$tanggal]['uraian'] != "" ?  $pegawai->_hari[$tanggal]['uraian']." " : $pegawai->getStringChecktime($date)." ";
                $output .= ($stringChecktime = $pegawai->getStringChecktime($date)) . " ";
                $output .= $pegawai->getLabelKetidakhadiran($date) . ' ';
                $output .= $pegawai->getLinkKetidakhadiran($date, null, $stringChecktime) . ' ';
                print trim($output);
                ?>
            </td>
            <td style="text-align: center">
                <?=$pegawai->_potongan_hari == null ? "" : "$pegawai->_potongan_hari %";?>
            </td>
            <td style="text-align: center">&nbsp;</td>
            <td style="text-align: center">
                <?=Html::a('<i class="fa fa-list"></i>', '#', ['class' => 'link-tanggal', 'data-tanggal' => $tanggal, 'data-toggle' => 'tooltip', 'title' => 'Tampilkan / Sembunyikan Rincian']);?>
            </td>
        </tr>
        <?php foreach ($shiftKerja->findAllJamKerja($date->format('N')) as $jamKerja) {?>
            <?php $pegawai->_potongan_jam_kerja = $pegawai->_jam_kerja[$tanggal][$jamKerja->id]['_potongan_jam_kerja'];?>
            <tr class="jam-kerja jam-kerja-<?=$date->format('Y-m-d');?>">
                <td style="text-align: center"><?php print $jamKerja->getRangeAbsensi();?></td>
                <td style="text-align: center"><?php print $jamKerja->nama;?></td>
                <td style="text-align: left">
                    <?php $pegawai->getPotonganJamKerja($date, $jamKerja);?>
                    <?php //print $pegawai->_jam_kerja[$tanggal][$jamKerja->id]['uraian']; ?>
                    <?php print ($stringChecktime = $pegawai->getStringChecktime($date, $jamKerja));?>
                    <?php print $pegawai->getLabelKetidakhadiranJamKerja($date, $jamKerja);?>
                    <?php print $pegawai->getLinkKetidakhadiranJamKerja($date, $jamKerja, $stringChecktime);?>
                </td>
                <td style="text-align: center;"><?=$pegawai->_potongan_jam_kerja !== 0 ? "$pegawai->_potongan_jam_kerja %" : null;?></td>
                <td style="text-align: center;">&nbsp;</td>
                <td style="text-align: center;">

                </td>
            </tr>
        <?php }?>
        <?php $date->modify('+1 day');?>
    <?php } //END FOR ?>
</table>
