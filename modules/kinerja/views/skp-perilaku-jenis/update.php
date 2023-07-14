<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\kinerja\models\SkpPerilakuJenis */

$this->title = "Sunting Skp Perilaku Jenis";
$this->params['breadcrumbs'][] = ['label' => 'Skp Perilaku Jenis', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Sunting';
?>
<div class="skp-perilaku-jenis-update">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
