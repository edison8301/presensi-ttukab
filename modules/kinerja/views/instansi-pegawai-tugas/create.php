<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\kinerja\models\InstansiPegawaiTugas */
/* @var $referrer string */

$this->title = "Tambah Instansi Pegawai Tugas";
$this->params['breadcrumbs'][] = ['label' => 'Instansi Pegawai Tugas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="instansi-pegawai-tugas-create">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
