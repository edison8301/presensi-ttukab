<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\iclock\Userinfo */
/* @var $referrer string */

$this->title = "Sunting Userinfo";
$this->params['breadcrumbs'][] = ['label' => 'Userinfos', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Sunting';
?>
<div class="userinfo-update">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
