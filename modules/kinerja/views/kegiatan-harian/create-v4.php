<?php

use app\components\Helper;

/* @var $this yii\web\View */
/* @var $model \app\modules\kinerja\models\KegiatanHarian */
/* @var $referrer string */

$this->title = "Tambah Kinerja Harian : ".Helper::getHari($model->tanggal).', '.Helper::getTanggalSingkat($model->tanggal);
$this->params['breadcrumbs'][] = ['label' => 'Kegiatan Harians', 'url' => ['index-v4']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kegiatan-harian-create">

    <?= $this->render('_form-v4', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
