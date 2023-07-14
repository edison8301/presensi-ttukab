<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\kinerja\models\PegawaiUbah */
/* @var $referrer string */

$this->title = "Tambah Pegawai Ubah";
$this->params['breadcrumbs'][] = ['label' => 'Pegawai Ubahs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pegawai-ubah-create">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
