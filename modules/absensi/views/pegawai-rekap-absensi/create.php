<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\absensi\models\PegawaiRekapAbsensi */
/* @var $referrer string */

$this->title = "Tambah Pegawai Rekap Absensi";
$this->params['breadcrumbs'][] = ['label' => 'Pegawai Rekap Absensis', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pegawai-rekap-absensi-create">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
