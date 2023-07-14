<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\tukin\models\PegawaiVariabelObjektif */
/* @var $referrer string */

$this->title = "Sunting Pegawai Variabel Objektif";
$this->params['breadcrumbs'][] = ['label' => 'Pegawai Variabel Objektifs', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Sunting';
?>
<div class="pegawai-variabel-objektif-update">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
