<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\absensi\models\PegawaiRekapAbsensi */
/* @var $referrer string */

$this->title = "Sunting Pegawai Rekap Absensi";
$this->params['breadcrumbs'][] = ['label' => 'Pegawai Rekap Absensis', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Sunting';
?>
<div class="pegawai-rekap-absensi-update">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
