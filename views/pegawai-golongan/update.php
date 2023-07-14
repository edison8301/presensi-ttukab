<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PegawaiGolongan */
/* @var $referrer string */

$this->title = "Sunting Pegawai Golongan";
$this->params['breadcrumbs'][] = ['label' => 'Pegawai Golongans', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Sunting';
?>
<div class="pegawai-golongan-update">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
