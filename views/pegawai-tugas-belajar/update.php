<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PegawaiTugasBelajar */

$this->title = "Sunting Pegawai Tugas Belajar";
$this->params['breadcrumbs'][] = ['label' => 'Pegawai Tugas Belajars', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Sunting';
?>
<div class="pegawai-tugas-belajar-update">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
