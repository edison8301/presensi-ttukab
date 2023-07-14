<?php

use yii\helpers\Html;
use app\models\User;
use app\components\Helper;

/* @var $dasbor \app\modules\absensi\models\Dasbor */

?>

<div class="pegawai-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Daftar Absensi</h3>
    </div>

    <div class="box-body">

        <table class="table table-hover table-bordered">
        <thead>
        <tr>
            <th style="text-align: center; width:100px">Tanggal</th>
            <th style="text-align: center; width:80px">Hari</th>
            <th style="text-align: center">Total Absensi</th>
            <th style="text-align: center">Telat/Kurang</th>
            <th style="text-align: center">DL</th>
            <th style="text-align: center">S</th>
            <th style="text-align: center">I</th>
            <th style="text-align: center">C</th>
            <th style="text-align: center">TK</th>
            <th style="text-align: center">% Potong</th>
            <th>&nbsp;</th>
        </tr>
        </thead>
        <?php /*
        <?php
            $date = date_create(date('Y-m-01'));
            $jumlahHari = $date->format('t');
        ?>
        <?php for($j=1;$j<=$jumlahHari;$j++) { ?>
        <?php
            $class = "";
            $libur = false;
            $tanggal = $date->format('Y-m-d');

            if($date->format('N')==6 OR $date->format('N')==7)
            {
                $class = 'danger';
                $libur = true;
            }
        ?>
        <tr class="<?= $class; ?>" style="cursor:pointer" data-tanggal="<?= $tanggal; ?>">
            <td style="text-align: center"><?= Helper::getTanggalSingkat($tanggal); ?></td>
            <td style="text-align: center"><?= Helper::getHari($tanggal); ?></td>
            <td style="text-align: center"><?= $rekap[$tanggal]['jumlahAbsensi']; ?> Absensi</td>
            <td style="text-align: center"><?= $rekap[$tanggal]['jumlahMenitKurangTelat']; ?> Menit</td>
            <td style="text-align: center"><?php // Helper::getIconRemove($dl,"Dinas Luar"); ?></td>
            <td style="text-align: center"><?php // Helper::getIconRemove($s,"Sakit"); ?></td>
            <td style="text-align: center"><?php // Helper::getIconRemove($i,"Izin"); ?></td>
            <td style="text-align: center"><?php // Helper::getIconRemove($c,"Cuti"); ?></td>
            <td style="text-align: center"><?php // Helper::getIconRemove($tk,"Tanpa Keterangan"); ?></td>
            <td style="text-align: center"><?php // $totalPersenPotongan; ?> %</td>
            <td style="text-align: center">&nbsp;</td>
        </tr>
        <?php $i=1; foreach($rekap[$tanggal]['jamKerja'] as $jamKerja) { ?>
        <tr>
            <td>&nbsp;</td>
            <td style="text-align:center"><?= $jamKerja['nama']; ?></td>
            <td><?= $jamKerja['checktime']; ?></td>
            <td style="text-align: center"><?= $jamKerja['jumlahMenitKurangTelat']; ?> Menit</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <?php $i++; } ?>
        <?php $date->modify('+1 day'); } ?>
        <tr>
            <th colspan="2" style="text-align: center">Total</th>
            <th style="text-align: center">
                <span data-toggle="tooltip" title="Jumlah Absensi"><?php // $sumJumlahAbsensi; ?> Absensi</span>
            </th>
            <th style="text-align: center">
                <span data-toggle="tooltip" title="Menit Telat"><?php // $sumTotalMenitTelat; ?> Menit</span>
            </th>
            <th style="text-align:center">
                <span data-toggle="tooltip" title="Dinas Luar"><?php // $sumDinasLuar; ?></span>
            </th>
            <th style="text-align:center">
                <span data-toggle="tooltip" title="Sakit"><?php // $sumSakit; ?></span>
            </th>
            <th style="text-align:center">
                <span data-toggle="tooltip" title="Izin"><?php // $sumIzin; ?></span>
            </th>
            <th style="text-align:center">
                <span data-toggle="tooltip" title="Cuti"><?php // $sumCuti; ?></span>
            </th>
            <th style="text-align:center">
                <span data-toggle="tooltip" title="Tanpa Keterangan"><?php // $sumTanpaKeterangan; ?></span>
            </th>
            <th style="text-align: center">
                <span data-toggle="tooltip" title="% Potongan"><?php // $sumTotalPersenPotongan; ?> %</span>
            </th>
            <th>&nbsp;</th>
        </tr>
        */ ?>
        </table>

        <script type="text/javascript">
            $("tr").click(function() {
                var tanggal = $(this).data("tanggal");
                $(".jam-"+tanggal).toggle("fast");
            });
        </script>

    </div>
</div>
