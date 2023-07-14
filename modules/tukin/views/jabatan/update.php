<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\tukin\models\Jabatan */
/* @var $referrer string */

$this->title = "Sunting Jabatan";
$this->params['breadcrumbs'][] = ['label' => 'Jabatans', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Sunting';
?>
<div class="jabatan-update">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
