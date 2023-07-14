<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\KegiatanBulanan */
/* @var $referrer string */

$this->title = "Tambah Kegiatan Bulanan";
$this->params['breadcrumbs'][] = ['label' => 'Kegiatan Bulanans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kegiatan-bulanan-create">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
