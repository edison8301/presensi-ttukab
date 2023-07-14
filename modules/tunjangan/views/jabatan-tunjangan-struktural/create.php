<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\tunjangan\models\JabatanTunjanganStruktural */
/* @var $referrer string */

$this->title = "Tambah Jabatan Tunjangan Struktural";
$this->params['breadcrumbs'][] = ['label' => 'Jabatan Tunjangan Strukturals', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jabatan-tunjangan-struktural-create">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
