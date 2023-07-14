<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\InstansiPegawai */
/* @var $referrer string */

$this->title = "Ubah Promosi dan Mutasi Pegawai";
$this->params['breadcrumbs'][] = ['label' => 'Instansi Pegawai', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Sunting';
?>
<div class="instansi-pegawai-update">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
