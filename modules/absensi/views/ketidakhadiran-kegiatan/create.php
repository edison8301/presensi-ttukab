<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\KetidakhadiranKegiatan */
/* @var $referrer string */

$this->title = "Tambah Ketidakhadiran ".@$model->ketidakhadiranKegiatanJenis->nama;
$this->params['breadcrumbs'][] = ['label' => 'Ketidakhadiran Kegiatans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ketidakhadiran-kegiatan-create">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
