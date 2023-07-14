<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\PegawaiRb */

$this->title = "Tambah Pegawai " . @$model->pegawaiRbJenis->nama;
$this->params['breadcrumbs'][] = ['label' => 'Pegawai RB', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pegawai-rb-create">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
