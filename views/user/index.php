<?php

use app\models\Instansi;
use app\models\User;
use yii\helpers\Html;
use app\components\Helper;
use kartik\grid\GridView;
use app\models\UserRole;
use app\models\Grup;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $id_user_role int */

$this->title = 'Daftar User '.($searchModel->userRole ? $searchModel->userRole->nama : " ");
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-primary user-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?php /*
    <div class="box-header with-border">
        <?= Html::a('<i class="fa fa-plus"></i> Tambah User', ['create','id_user_role'=>$searchModel->id_role], ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    */ ?>
    <div class="box-body">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'hover'=>true,
        'striped'=>false,
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'header' => 'No',
                'headerOptions'=>['style'=>'text-align:center;width:20px;'],
                'contentOptions'=>['style'=>'text-align:center;width:20px;']
            ],
            'username',
            [
                'attribute'=>'id_instansi',
                'value'=>function(User $data) {
                    return @$data->instansi->nama;
                },
                'visible'=>$searchModel->visibleIdInstansi(),
                'filter'=>Instansi::getList(),
                'headerOptions'=>['style'=>'text-align:center;width:200px'],
                'contentOptions'=>['style'=>'text-align:center']
            ],
            [
                'attribute'=>'id_grup',
                'value'=>function($data) {
                    return @$data->grup->nama;
                },
                'visible'=>$id_user_role == UserRole::GRUP ? true : false,
                'filter'=>Grup::getList(),
                'headerOptions'=>['style'=>'text-align:center;width:200px'],
                'contentOptions'=>['style'=>'text-align:center']
            ],
            [
                'attribute'=>'nama_pegawai',
                'value'=>function(User $data) {
                    return @$data->pegawai->nama;
                },
                'visible' =>($id_user_role == UserRole::PEGAWAI),
                'headerOptions'=>['style'=>'text-align:center;width:300px'],
                'contentOptions'=>['style'=>'text-align:center']
            ],
            [
                'attribute'=>'nip_pegawai',
                'value'=>function(User $data) {
                    return @$data->pegawai->nip;
                },
                'visible' =>($id_user_role == UserRole::PEGAWAI),
                'headerOptions'=>['style'=>'text-align:center;width:150px'],
                'contentOptions'=>['style'=>'text-align:center']
            ],
            [
                'attribute'=>'id_role',
                'value'=>function($data) {
                    return @$data->userRole->nama;
                },
                'filter'=>UserRole::getList(),
                'headerOptions'=>['style'=>'text-align:center;width:150px'],
                'contentOptions'=>['style'=>'text-align:center']
            ],
            [
                'format'=>'raw',
                'headerOptions' => ['style'=>'text-align:center;width:20px;'],
                'visible'=>$id_user_role == UserRole::PEGAWAI ? true : false,
                'value'=>function($data) {
                    return Html::a('<i class="glyphicon glyphicon-refresh"></i>',['user/reset-imei','id'=>$data->id],['data-toggle'=>'tooltip','title'=>'Reset IMEI']);
                }
            ],
            [
                'format'=>'raw',
                'headerOptions' => ['style'=>'text-align:center;width:20px;'],
                'value'=>function($data) {
                    return Html::a('<i class="glyphicon glyphicon-lock"></i>',['user/set-password','id'=>$data->id],['data-toggle'=>'tooltip','title'=>'Set Password']);
                }
            ],
            /*
            [
                'class' => 'app\components\ToggleActionColumn',
                'headerOptions'=>['style'=>'text-align:center;width:80px'],
                'contentOptions'=>['style'=>'text-align:center']
            ],
            */
        ],
    ]); ?>
    </div>
</div>
