<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\tukin\models\Instansi */
/* @var $referrer string */

$this->title = "Sunting Instansi";
$this->params['breadcrumbs'][] = ['label' => 'Instansis', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Sunting';
?>
<div class="instansi-update">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
