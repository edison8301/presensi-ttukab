<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\tunjangan\models\JabatanTunjanganKhususPegawai */
/* @var $referrer string */

$this->title = "Sunting Jabatan Tunjangan Khusus Pegawai";
$this->params['breadcrumbs'][] = ['label' => 'Jabatan Tunjangan Khusus Pegawais', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Sunting';
?>
<div class="jabatan-tunjangan-khusus-pegawai-update">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
