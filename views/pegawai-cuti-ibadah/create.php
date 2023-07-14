<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\PegawaiCutiIbadah */

$this->title = "Tambah Pegawai Cuti Ibadah";
$this->params['breadcrumbs'][] = ['label' => 'Pegawai Cuti Ibadahs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pegawai-cuti-ibadah-create">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
