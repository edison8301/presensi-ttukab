<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\KegiatanKetidakhadiran */

$this->title = "Tambah Kegiatan Ketidakhadiran";
$this->params['breadcrumbs'][] = ['label' => 'Kegiatan Ketidakhadirans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kegiatan-ketidakhadiran-create">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
