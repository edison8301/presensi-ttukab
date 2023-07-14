<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PegawaiAbsensiManual */
/* @var $referrer string */

$this->title = "Sunting Pegawai Absensi Manual";
$this->params['breadcrumbs'][] = ['label' => 'Pegawai Absensi Manuals', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Sunting';
?>
<div class="pegawai-absensi-manual-update">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
