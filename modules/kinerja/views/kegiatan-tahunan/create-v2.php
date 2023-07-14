<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\KegiatanTahunan */
/* @var $referrer string */

$this->title = "Tambah Kinerja Tahunan";
$this->params['breadcrumbs'][] = ['label' => 'Kegiatan Tahunans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kegiatan-tahunan-create">

    <?= $this->render('_form-v2', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
