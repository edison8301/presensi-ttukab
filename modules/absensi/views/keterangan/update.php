<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\absensi\models\Keterangan */
/* @var $referrer string */

$this->title = "Sunting Keterangan";
$this->params['breadcrumbs'][] = ['label' => 'Keterangans', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Sunting';
?>
<div class="keterangan-update">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
