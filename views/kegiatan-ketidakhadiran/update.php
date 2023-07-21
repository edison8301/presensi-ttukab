<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\KegiatanKetidakhadiran */

$this->title = "Sunting Kegiatan Ketidakhadiran";
$this->params['breadcrumbs'][] = ['label' => 'Kegiatan Ketidakhadirans', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Sunting';
?>
<div class="kegiatan-ketidakhadiran-update">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
