<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\kinerja\models\SkpPerilakuJenis */

$this->title = "Tambah Skp Perilaku Jenis";
$this->params['breadcrumbs'][] = ['label' => 'Skp Perilaku Jenis', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="skp-perilaku-jenis-create">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
