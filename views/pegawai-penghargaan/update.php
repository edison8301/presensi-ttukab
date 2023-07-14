<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PegawaiPenghargaan */

$this->title = "Sunting Pegawai Penghargaan";
$this->params['breadcrumbs'][] = ['label' => 'Pegawai Penghargaans', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Sunting';
?>
<div class="pegawai-penghargaan-update">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
