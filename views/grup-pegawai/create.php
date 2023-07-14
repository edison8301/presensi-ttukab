<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\GrupPegawai */
/* @var $referrer string */

$this->title = "Tambah Grup Pegawai";
$this->params['breadcrumbs'][] = ['label' => 'Grup Pegawais', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="grup-pegawai-create">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
