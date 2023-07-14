<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\kinerja\models\KegiatanTriwulan */

$this->title = "Tambah Kegiatan Triwulan";
$this->params['breadcrumbs'][] = ['label' => 'Kegiatan Triwulans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kegiatan-triwulan-create">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
