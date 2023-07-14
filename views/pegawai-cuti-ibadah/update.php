<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PegawaiCutiIbadah */

$this->title = "Sunting Pegawai Cuti Ibadah";
$this->params['breadcrumbs'][] = ['label' => 'Pegawai Cuti Ibadahs', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Sunting';
?>
<div class="pegawai-cuti-ibadah-update">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
