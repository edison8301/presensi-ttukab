<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\KegiatanTahunan */
/* @var $referrer string */

$this->title = "Sunting Kegiatan Tahunan";
$this->params['breadcrumbs'][] = ['label' => 'Kegiatan Tahunans', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Sunting';
?>
<div class="kegiatan-tahunan-update">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
