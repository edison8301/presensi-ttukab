<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\TunjanganInstansiJenisJabatanKelas */

$this->title = "Tambah Tunjangan Unit Jenis Jabatan Kelas";
$this->params['breadcrumbs'][] = ['label' => 'Tunjangan Unit Jenis Jabatan Kelas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tunjangan-unit-jenis-jabatan-kelas-create">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
