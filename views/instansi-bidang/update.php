<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\InstansiBidang */
/* @var $referrer string */

$this->title = "Sunting Instansi Bidang";
$this->params['breadcrumbs'][] = ['label' => 'Instansi Bidangs', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Sunting';
?>
<div class="instansi-bidang-update">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
