<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\kinerja\models\User */

$this->title = "Detail User";
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="user-view box box-primary">

    <div class="box-header">
        <h3 class="box-title"><?= $model->nama; ?></h3>
    </div>

    <div class="box-body">

        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'nama',
                'jabatan_id',
                'gender',
                'tempat_lahir',
                'tanggal_lahir',
                'alamat:ntext',
                'no_telp',
                'email:email',
                'foto',
                'nip',
                'atasan_id',
                'unit_kerja',
                'jabatan_struktural',
                'rekan_id',
                'grade',
                'created_date',
                'super_user',
            ],
        ]) ?>
    </div>

</div>
