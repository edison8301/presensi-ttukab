<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\kinerja\models\InstansiPegawaiFungsi */
/* @var $referrer string */

$this->title = "Sunting Instansi Pegawai Fungsi";
$this->params['breadcrumbs'][] = ['label' => 'Instansi Pegawai Fungsis', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Sunting';
?>
<div class="instansi-pegawai-fungsi-update">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
