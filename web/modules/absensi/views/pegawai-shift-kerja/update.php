<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\absensi\models\PegawaiShiftKerja */

$this->title = "Sunting Pegawai Shift Kerja";
$this->params['breadcrumbs'][] = ['label' => 'Pegawai Shift Kerjas', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Sunting';
?>
<div class="pegawai-shift-kerja-update">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
