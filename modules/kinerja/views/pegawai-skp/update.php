<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\kinerja\models\PegawaiSkp */
/* @var $referrer string */

$this->title = "Sunting Pegawai Skp";
$this->params['breadcrumbs'][] = ['label' => 'Pegawai Skps', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Sunting';
?>
<div class="pegawai-skp-update">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
