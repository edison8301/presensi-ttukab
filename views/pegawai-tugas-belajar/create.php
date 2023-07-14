<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\PegawaiTugasBelajar */

$this->title = "Tambah Pegawai Tugas Belajar";
$this->params['breadcrumbs'][] = ['label' => 'Pegawai Tugas Belajars', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pegawai-tugas-belajar-create">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
