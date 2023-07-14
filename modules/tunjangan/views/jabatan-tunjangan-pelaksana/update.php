<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\tunjangan\models\JabatanTunjanganPelaksana */
/* @var $referrer string */

$this->title = "Sunting Jabatan Tunjangan Pelaksana";
$this->params['breadcrumbs'][] = ['label' => 'Jabatan Tunjangan Pelaksanas', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Sunting';
?>
<div class="jabatan-tunjangan-pelaksana-update">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
