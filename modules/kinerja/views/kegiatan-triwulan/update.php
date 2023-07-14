<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\kinerja\models\KegiatanTriwulan */

$this->title = "Sunting Kegiatan Triwulan";
$this->params['breadcrumbs'][] = ['label' => 'Kegiatan Triwulans', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Sunting';
?>
<div class="kegiatan-triwulan-update">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
