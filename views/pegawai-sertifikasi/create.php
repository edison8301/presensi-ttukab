<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\PegawaiSertifikasi */

$this->title = "Tambah Pegawai Sertifikasi";
$this->params['breadcrumbs'][] = ['label' => 'Pegawai Sertifikasis', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pegawai-sertifikasi-create">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
