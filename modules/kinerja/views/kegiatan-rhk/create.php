<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\kinerja\models\KegiatanRhk */

$this->title = "Tambah RHK";
$this->params['breadcrumbs'][] = ['label' => 'RHK', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kegiatan-rhk-create">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
