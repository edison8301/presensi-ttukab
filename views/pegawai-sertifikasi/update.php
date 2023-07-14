<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PegawaiSertifikasi */

$this->title = "Sunting Pegawai Sertifikasi";
$this->params['breadcrumbs'][] = ['label' => 'Pegawai Sertifikasis', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Sunting';
?>
<div class="pegawai-sertifikasi-update">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
