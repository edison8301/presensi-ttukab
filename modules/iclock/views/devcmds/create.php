<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\iclock\models\Devcmds */
/* @var $referrer string */

$this->title = "Tambah Devcmds";
$this->params['breadcrumbs'][] = ['label' => 'Devcmds', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="devcmds-create">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
