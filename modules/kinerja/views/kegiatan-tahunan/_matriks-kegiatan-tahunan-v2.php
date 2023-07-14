<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 29/01/2019
 * Time: 22:58
 */

use app\components\Helper;
use app\modules\kinerja\models\KegiatanTahunan;

/* @var $this yii\web\View */
/* @var $allKegiatanTahunanUtamaInduk KegiatanTahunan[] */
/* @var $allKegiatanTahunanTambahanInduk KegiatanTahunan[] */

?>

<div style="overflow: auto;">
    <table class="table table-hover table-bordered">
        <thead>
            <tr>
                <th rowspan="3" style="vertical-align:middle;text-align: center;">No</th>
                <th style="vertical-align:middle;text-align: left;" rowspan="3">
                    <?php for($i=1;$i<=25;$i++) echo "&nbsp;"; ?>Rencana&nbsp;Kinerja<?php for($i=1;$i<=25;$i++) echo "&nbsp;"; ?>
                </th>
                <th rowspan="3" style="vertical-align:middle;text-align: center;">Nomor<br/>SKP</th>
                <th rowspan="3" style="vertical-align:middle;text-align: center;">Status</th>
                <th rowspan="3" style="vertical-align:middle;text-align: center; width:80px">Aspek</th>
                <th rowspan="3" style="vertical-align:middle;text-align: center; width:200px">IKI</th>
                <th rowspan="3" style="vertical-align:middle;text-align: center; width:80px">Target</th>
                <th rowspan="3" style="vertical-align:middle;text-align:center; width:30px">Total<br/>Rencana<br/>Target</th>
                <th rowspan="3" style="vertical-align:middle;text-align: center; width:80px">Total Realisasi</th>
                <th style="vertical-align:middle;text-align: center" colspan="24">Rencana Target / Realisasi Pada Bulan</th>
            </tr>
            <tr>
                <?php for ($i = 1;$i <= 12;$i++) { ?>
                    <th colspan="2" style="text-align:center; width:30px"><?= Helper::getBulanSingkat($i); ?></th>
                <?php } ?>
            </tr>
            <tr>
                <?php for ($i = 1;$i <= 12;$i++) { ?>
                    <th style="text-align:center; width:30px"><span data-toggle="tooltip" title="Rencana Target">&nbsp;TRGT&nbsp;</span></th>
                    <th style="text-align:center; width:30px"><span data-toggle="tooltip" title="Realisasi">&nbsp;REAL&nbsp;</span></th>
                <?php } ?>
            </tr>
        </thead>
        <tr>
            <th colspan="33">KINERJA UTAMA</th>
        </tr>
        <?php foreach($allKegiatanTahunanUtamaInduk as $kegiatanTahunan) {
            echo $this->render('_tr-kegiatan-tahunan-v2', [
                'kegiatanTahunan' => $kegiatanTahunan,
                'level' => 0,
                'no' => 1
            ]);
        } ?>
        <tr>
            <th colspan="33">KINERJA TAMBAHAN</th>
        </tr>
        <?php foreach($allKegiatanTahunanTambahanInduk as $kegiatanTahunan) {
            echo $this->render('_tr-kegiatan-tahunan-v2', [
                'kegiatanTahunan' => $kegiatanTahunan,
                'level' => 0,
                'no' => 1
            ]);
        } ?>
    </table>
</div>
