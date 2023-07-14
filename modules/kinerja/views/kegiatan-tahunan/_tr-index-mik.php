<?php

use yii\helpers\Html;

/* @var $model \app\modules\kinerja\models\KegiatanTahunan */
/* @var $no int|null */

?>

<tr>
    <td style="text-align: center;">
        <?= $no++ ?>
    </td>
    <td>
        <?= Html::a($model->nama, [
            '/kinerja/kegiatan-tahunan/view-mik',
            'id' => $model->id,
        ]); ?>
    </td>
    <td style="text-align: center;">
        <?= Html::a('<i class="fa fa-eye"></i>', [
            '/kinerja/kegiatan-tahunan/view-mik',
            'id' => $model->id,
        ]); ?>
    </td>
</tr>

<?php $i=1; foreach ($model->findAllSubFromKegiatanRhk() as $sub) { ?>
    <?= $this->render('_tr-index-mik', [
        'model' => $sub,
        'no' => $no . '.' .$i++,
    ]); ?>
<?php } ?>
