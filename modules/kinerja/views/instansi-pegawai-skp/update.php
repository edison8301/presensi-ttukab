<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\kinerja\models\InstansiPegawaiSkp */
/* @var $referrer string */

$this->title = "Sunting Instansi Pegawai Skp";
$this->params['breadcrumbs'][] = ['label' => 'Instansi Pegawai Skps', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Sunting';
?>
<div class="instansi-pegawai-skp-update">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
