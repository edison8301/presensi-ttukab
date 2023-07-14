<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\kinerja\models\PegawaiRekapKinerja */
/* @var $referrer string */

$this->title = "Tambah Pegawai Rekap Kinerja";
$this->params['breadcrumbs'][] = ['label' => 'Pegawai Rekap Kinerjas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pegawai-rekap-kinerja-create">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
