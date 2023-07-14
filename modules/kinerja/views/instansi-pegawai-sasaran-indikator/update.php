<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\kinerja\models\InstansiPegawaiSasaranIndikator */
/* @var $referrer string */

$this->title = "Sunting Instansi Pegawai Sasaran Indikator";
$this->params['breadcrumbs'][] = ['label' => 'Instansi Pegawai Sasaran Indikators', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Sunting';
?>
<div class="instansi-pegawai-sasaran-indikator-update">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
