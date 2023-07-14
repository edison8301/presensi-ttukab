<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\kinerja\models\PegawaiUbah */
/* @var $referrer string */

$this->title = "Sunting Pegawai Ubah";
$this->params['breadcrumbs'][] = ['label' => 'Pegawai Ubahs', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Sunting';
?>
<div class="pegawai-ubah-update">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
