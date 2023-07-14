<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\kinerja\models\InstansiPegawaiSasaran */
/* @var $referrer string */

$this->title = "Tambah Instansi Pegawai Sasaran";
$this->params['breadcrumbs'][] = ['label' => 'Instansi Pegawai Sasarans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="instansi-pegawai-sasaran-create">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
