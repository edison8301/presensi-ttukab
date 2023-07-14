<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\absensi\models\PegawaiShiftKerja */
/* @var $referrer string */

$this->title = "Tambah Pegawai Shift Kerja";
$this->params['breadcrumbs'][] = ['label' => 'Pegawai Shift Kerjas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pegawai-shift-kerja-create">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
