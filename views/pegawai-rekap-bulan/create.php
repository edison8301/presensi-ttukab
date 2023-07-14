<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\PegawaiRekapBulan */
/* @var $referrer string */

$this->title = "Tambah Pegawai Rekap Bulan";
$this->params['breadcrumbs'][] = ['label' => 'Pegawai Rekap Bulans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pegawai-rekap-bulan-create">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
