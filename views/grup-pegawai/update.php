<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\GrupPegawai */
/* @var $referrer string */

$this->title = "Sunting Grup Pegawai";
$this->params['breadcrumbs'][] = ['label' => 'Grup Pegawais', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Sunting';
?>
<div class="grup-pegawai-update">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
