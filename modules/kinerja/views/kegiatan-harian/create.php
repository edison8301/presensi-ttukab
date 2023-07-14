<?php

use yii\helpers\Html;
use app\components\Helper;

/* @var $this yii\web\View */
/* @var $model app\models\KegiatanHarian */
/* @var $referrer string */

$this->title = "Tambah Kegiatan Harian : ".Helper::getHari($model->tanggal).', '.Helper::getTanggalSingkat($model->tanggal);
$this->params['breadcrumbs'][] = ['label' => 'Kegiatan Harians', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kegiatan-harian-create">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
