<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PegawaiTundaBayar */

$this->title = "Sunting Penundaan Pembayaran TPP Pegawai";
$this->params['breadcrumbs'][] = ['label' => 'Pegawai Tunda Bayars', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Sunting';
?>
<div class="pegawai-tunda-bayar-update">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
