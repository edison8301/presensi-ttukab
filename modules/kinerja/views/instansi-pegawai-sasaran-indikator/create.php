<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\kinerja\models\InstansiPegawaiSasaranIndikator */
/* @var $referrer string */

$this->title = "Tambah Instansi Pegawai Sasaran Indikator";
$this->params['breadcrumbs'][] = ['label' => 'Instansi Pegawai Sasaran Indikators', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="instansi-pegawai-sasaran-indikator-create">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
