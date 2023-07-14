<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\absensi\models\Ketidakhadiran */
/* @var $referrer string */

$this->title = "Sunting Ketidakhadiran";
$this->params['breadcrumbs'][] = ['label' => 'Ketidakhadirans', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Sunting';
?>
<div class="ketidakhadiran-update">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
