<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\PegawaiTundaBayar */

$this->title = "Tambah Penundaan Pembayaran TPP Pegawai";
$this->params['breadcrumbs'][] = ['label' => 'Pegawai Tunda Bayars', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pegawai-tunda-bayar-create">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
