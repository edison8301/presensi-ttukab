<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\kinerja\models\InstansiPegawaiTugas */
/* @var $referrer string */

$this->title = "Sunting Instansi Pegawai Tugas";
$this->params['breadcrumbs'][] = ['label' => 'Instansi Pegawai Tugas', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Sunting';
?>
<div class="instansi-pegawai-tugas-update">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
