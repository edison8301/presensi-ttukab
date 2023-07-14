<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\absensi\models\JamKerjaReguler */
/* @var $referrer string */

$this->title = "Tambah Jam Kerja Reguler";
$this->params['breadcrumbs'][] = ['label' => 'Jam Kerja Regulers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jam-kerja-reguler-create">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
