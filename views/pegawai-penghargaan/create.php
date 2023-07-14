<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\PegawaiPenghargaan */

$this->title = "Tambah Pegawai Penghargaan";
$this->params['breadcrumbs'][] = ['label' => 'Pegawai Penghargaans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pegawai-penghargaan-create">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
