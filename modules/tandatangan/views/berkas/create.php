<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\tandatangan\models\Berkas */

$this->title = "Tambah Berkas";
$this->params['breadcrumbs'][] = ['label' => 'Berkas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="berkas-create">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
