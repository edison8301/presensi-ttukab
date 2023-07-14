<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\iclock\Userinfo */
/* @var $referrer string */

$this->title = "Tambah Userinfo";
$this->params['breadcrumbs'][] = ['label' => 'Userinfos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="userinfo-create">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
