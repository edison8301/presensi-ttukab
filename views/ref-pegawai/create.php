<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\RefPegawai */
/* @var $referrer string */

$this->title = "Tambah Ref Pegawai";
$this->params['breadcrumbs'][] = ['label' => 'Ref Pegawais', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ref-pegawai-create">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
