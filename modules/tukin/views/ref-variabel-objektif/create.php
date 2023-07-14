<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\tukin\models\RefVariabelObjektif */
/* @var $referrer string */


$this->title = "Tambah Ref Variabel Objektif";
$this->params['breadcrumbs'][] = ['label' => 'Ref Variabel Objektifs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ref-variabel-objektif-create">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
