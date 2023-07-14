<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\kinerja\models\InstansiPegawaiSkp */
/* @var $referrer string */

$this->title = "Tambah Instansi Pegawai Skp";
$this->params['breadcrumbs'][] = ['label' => 'Instansi Pegawai Skps', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="instansi-pegawai-skp-create">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
