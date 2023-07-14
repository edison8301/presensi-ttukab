<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\KegiatanBulanan */
/* @var $referrer string */

$this->title = "Sunting Kegiatan Bulanan";
$this->params['breadcrumbs'][] = ['label' => 'Kegiatan Bulanans', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Sunting';
?>
<div class="kegiatan-bulanan-update">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
