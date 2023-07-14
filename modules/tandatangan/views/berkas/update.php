<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\tandatangan\models\Berkas */

$this->title = "Sunting Berkas";
$this->params['breadcrumbs'][] = ['label' => 'Berkas', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Sunting';
?>
<div class="berkas-update">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
