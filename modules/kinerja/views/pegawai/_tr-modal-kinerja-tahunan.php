<?php

use yii\helpers\Html;

?>
<tr>
    <td style="text-align:center;">
        <?php if($model->id_induk === null) {
            echo $no;
        } ?>
    </td>
    <td style="text-align:left;padding-left:<?=$padding?>px"><?= $model->nama ?></td>
    <td style="text-align:center;">
        <?= Html::a('<i class="fa fa-check"></i> Pilih',[
            '/kinerja/kegiatan-harian/create-v2',
            'id_kegiatan_harian_jenis' => @$id_kegiatan_harian_jenis,
            'id_kegiatan_tahunan' => $model->id,
            'tanggal' => $tanggal,
        ],['class'=>'btn btn-xs btn-success btn-flat']); ?>
    </td>
</tr>

<?php foreach($model->findAllSub() as $sub) {
    echo $this->render('_tr-modal-kinerja-tahunan', [
        'model' => $sub,
        'tanggal' => $tanggal,
        'id_kegiatan_harian_jenis' => $id_kegiatan_harian_jenis,
        'padding' => $padding+=20,
    ]);
} ?>