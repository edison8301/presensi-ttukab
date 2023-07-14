<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\PegawaiAtribut */

$this->title = "Tambah Pegawai Seragam Dinas";
$this->params['breadcrumbs'][] = ['label' => 'Pegawai Atributs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pegawai-atribut-create">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
