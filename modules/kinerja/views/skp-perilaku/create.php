<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\kinerja\models\SkpPerilaku */

$this->title = "Tambah Skp Perilaku";
$this->params['breadcrumbs'][] = ['label' => 'Skp Perilakus', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="skp-perilaku-create">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
