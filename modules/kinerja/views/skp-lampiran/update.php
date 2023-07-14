<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\kinerja\models\SkpLampiran */

$this->title = "Sunting SKP Lampiran";
$this->params['breadcrumbs'][] = ['label' => 'Skp Lampirans', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Sunting';
?>
<div class="skp-lampiran-update">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
