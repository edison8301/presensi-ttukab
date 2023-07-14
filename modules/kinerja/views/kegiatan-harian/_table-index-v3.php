<?php

use app\components\Helper;
use app\models\User;
use app\modules\kinerja\models\KegiatanHarian;
use yii\helpers\Html;

/* @var $searchModel \app\modules\kinerja\models\KegiatanHarianSearch */
/* @var $allKegiatanHarianUtama KegiatanHarian[] */
/* @var $allKegiatanHarianTambahan KegiatanHarian[] */
/* @var $pagination \yii\data\Pagination|null */

?>

<div class="table-responsive">
    <table class="table table-bordered">
        <tr>
            <th style="text-align:center;width:10px;">
                <?= Html::checkbox('pilih', false, [
                    'onClick' => 'toggleCheckbox(this)',
                ]); ?>
            </th>
            <th style="text-align:center;width:10px;">No</th>
            <th style="text-align:center; width: 120px">Tanggal</th>
            <th style="text-align:center;">Uraian</th>
            <th style="text-align:center;width:50px;">Aspek</th>
            <th style="text-align:center;width:300px;">Indikator Kinerja Individu</th>
            <th style="text-align:center;width:100px;">Realisasi</th>
            <th style="width: 100px;"></th>
        </tr>
        <tr>
            <th colspan="8">
                Utama
            </th>
        </tr>
        <?php
            $no = 1;
            if (@$pagination != null) {
                $no = $pagination->offset + 1;
            }
        ?>
        <?php foreach($allKegiatanHarianUtama as $data)  { ?>
            <?= $this->render('_tr-index-v3', [
                'searchModel' => $searchModel,
                'kegiatanHarian' => $data,
                'no' => $no++,
            ]) ?>
        <?php } ?>
        <tr>
            <th colspan="8">
                Tambahan
            </th>
        </tr>
        <?php
            $no = 1;
            if (@$pagination != null) {
                $no = $pagination->offset + 1;
            }
        ?>
        <?php foreach($allKegiatanHarianTambahan as $data)  { ?>
            <?= $this->render('_tr-index-v3', [
                'searchModel' => $searchModel,
                'kegiatanHarian' => $data,
                'no' => $no++,
            ]) ?>
        <?php } ?>
    </table>
</div>
