<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PegawaiAtribut */

$this->title = "Sunting Pegawai Seragam Dinas";
$this->params['breadcrumbs'][] = ['label' => 'Pegawai Atributs', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Sunting';
?>
<div class="pegawai-atribut-update">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
