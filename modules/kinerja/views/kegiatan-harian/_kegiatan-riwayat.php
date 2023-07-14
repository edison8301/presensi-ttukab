<?php

use app\components\Helper;
?>
<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title">Riwayat</h3>
    </div>
    <div class="box-body">
        <table class="table table-condensed table-striped">
            <thead>
                <tr>
                    <th style="text-align: center;" width="120px">No</th>
                    <th style="text-align: center;">Keterangan</th>
                    <th style="text-align: center;">Waktu</th>
                </tr>
            </thead>
            <?php $i = 1; ?>
            <?php foreach ($model->allKegiatanRiwayat as $riwayat): ?>
                <tr>
                    <td style="text-align: center;"><?= $i++; ?></td>
                    <td><?= $riwayat->keterangan; ?></td>
                    <td style="text-align: center;"><?= Helper::getWaktuWIB($riwayat->waktu_dibuat); ?></td>
                </tr>
            <?php endforeach ?>
        </table>
    </div>
</div>
