<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\PegawaiGolongan */
/* @var $referrer string */

$this->title = "Tambah Pegawai Golongan";
$this->params['breadcrumbs'][] = ['label' => 'Pegawai Golongans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pegawai-golongan-create">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
