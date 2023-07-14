<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\kinerja\models\InstansiPegawaiSasaran */
/* @var $referrer string */

$this->title = "Sunting Instansi Pegawai Sasaran";
$this->params['breadcrumbs'][] = ['label' => 'Instansi Pegawai Sasarans', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Sunting';
?>
<div class="instansi-pegawai-sasaran-update">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
