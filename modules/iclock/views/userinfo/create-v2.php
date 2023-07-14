<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\iclock\Userinfo */
/* @var $referrer string */

$this->title = "Edit Userinfo";
$this->params['breadcrumbs'][] = ['label' => 'Userinfos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="userinfo-create-v2">

    <?= $this->render('_form-v2', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
