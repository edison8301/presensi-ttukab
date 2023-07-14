<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PegawaiRekapBulan */
/* @var $referrer string */

$this->title = "Sunting Pegawai Rekap Bulan";
$this->params['breadcrumbs'][] = ['label' => 'Pegawai Rekap Bulans', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Sunting';
?>
<div class="pegawai-rekap-bulan-update">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
