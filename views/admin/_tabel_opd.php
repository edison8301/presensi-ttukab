<?php

use yii\helpers\Html;
use app\components\Helper;

/* @var $allOpd \app\models\Instansi[] */

?>
<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">
            Data per OPD
        </h3>
    </div>
    <div class="box-body">
        <table class="table table-condensed table-striped">
            <thead>
                <tr>
                    <th style="text-align: center;">No</th>
                    <th style="text-align: center;">OPD</th>
                    <th style="text-align: center;">Realisasi</th>
                    <th style="text-align: center;">Rencana</th>
                    <th style="text-align: center;">Pagu</th>
                    <th style="text-align: center;">Realisasi/Pagu</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1; foreach ($allOpd as $opd) { ?>
                    <tr>
                        <td style="text-align: center"><?= $i++ ?></td>
                        <td><?= Html::a($opd->nama, ['opd/view','id'=>$opd->id]); ?></td>
                        <td style="text-align: right;">Rp <?= Helper::rp($opd->getJumlahRealisasi()) ?></td>
                        <td style="text-align: right;">Rp <?= Helper::rp($opd->getJumlahRencana()) ?></td>
                        <td style="text-align: right;">Rp <?= Helper::rp($opd->getJumlahPagu()) ?></td>
                        <td style="text-align: center;"><?= $opd->getRealisasiPaguPersen().' %' ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
