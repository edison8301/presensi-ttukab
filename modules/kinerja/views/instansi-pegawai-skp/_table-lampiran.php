<?php

use app\modules\kinerja\models\SkpLampiran;
use app\modules\kinerja\models\SkpLampiranJenis;
use yii\helpers\Html;

/* @var $model \app\modules\kinerja\models\InstansiPegawaiSkp */
/* @var $allSkpLampiranJenis SkpLampiranJenis[] */
/* @var $pdf bool|null */

$allSkpLampiranJenis = SkpLampiranJenis::find()->all();

?>

<table class="table table-bordered">
    <?php foreach ($allSkpLampiranJenis as $skpLampiranJenis) { ?>
        <?php $allSkpLampiran = SkpLampiran::findAll([
            'id_skp' => $model->id,
            'id_skp_lampiran_jenis' => $skpLampiranJenis->id,
        ]) ?>
        <tr>
            <th colspan="2">
                <?= $skpLampiranJenis->nama ?>
                <?php if (@$pdf !== true) { ?>
                    <?= $model->getLinkCreateSkpLampiran([
                        'id_skp_lampiran_jenis' => $skpLampiranJenis->id,
                    ]) ?>
                <?php } ?>
            </th>
        </tr>
        <?php $no=1; foreach ($allSkpLampiran as $skpLampiran) { ?>
            <tr>
                <td style="text-align: center; width: 50px;">
                    <?= $no++ ?>
                </td>
                <td>
                    <?= $skpLampiran->uraian ?>
                    <?php if (@$pdf !== true) { ?>
                        <?= $skpLampiran->getLinkUpdateIcon() ?>
                        <?= $skpLampiran->getLinkDeleteIcon() ?>
                    <?php } ?>
                </td>
            </tr>
        <?php } ?>
    <?php } ?>
</table>
