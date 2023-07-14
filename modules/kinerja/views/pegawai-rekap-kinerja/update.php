<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\kinerja\models\PegawaiRekapKinerja */
/* @var $referrer string */

$this->title = "Sunting Pegawai Rekap Kinerja";
$this->params['breadcrumbs'][] = ['label' => 'Pegawai Rekap Kinerjas', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Sunting';
?>
<div class="pegawai-rekap-kinerja-update">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
