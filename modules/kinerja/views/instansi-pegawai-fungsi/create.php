<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\kinerja\models\InstansiPegawaiFungsi */
/* @var $referrer string */

$this->title = "Tambah Instansi Pegawai Fungsi";
$this->params['breadcrumbs'][] = ['label' => 'Instansi Pegawai Fungsis', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="instansi-pegawai-fungsi-create">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
