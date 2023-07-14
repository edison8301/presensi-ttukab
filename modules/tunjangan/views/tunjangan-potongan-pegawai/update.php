<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\tunjangan\models\TunjanganPotonganPegawai */
/* @var $referrer string */

$this->title = "Sunting Tunjangan Potongan Pegawai";
$this->params['breadcrumbs'][] = ['label' => 'Tunjangan Potongan Pegawais', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Sunting';
?>
<div class="tunjangan-potongan-pegawai-update">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
