<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\PegawaiNonTpp */

$this->title = "Tambah Pegawai Non Tpp";
$this->params['breadcrumbs'][] = ['label' => 'Pegawai Non Tpps', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pegawai-non-tpp-create">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
