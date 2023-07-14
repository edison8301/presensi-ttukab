<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\PegawaiWfh */

$this->title = "Tambah Pegawai Wfh";
$this->params['breadcrumbs'][] = ['label' => 'Pegawai Wfhs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pegawai-wfh-create">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
