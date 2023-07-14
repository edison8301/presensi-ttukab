<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\RefPegawai */
/* @var $referrer string */

$this->title = "Sunting Ref Pegawai";
$this->params['breadcrumbs'][] = ['label' => 'Ref Pegawais', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Sunting';
?>
<div class="ref-pegawai-update">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
