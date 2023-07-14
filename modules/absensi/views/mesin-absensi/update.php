<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\absensi\models\MesinAbsensi */
/* @var $referrer string */

$this->title = "Sunting Mesin Absensi";
$this->params['breadcrumbs'][] = ['label' => 'Mesin Absensis', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Sunting';
?>
<div class="mesin-absensi-update">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
