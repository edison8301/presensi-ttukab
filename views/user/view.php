<?php

use app\models\User;
use app\models\UserRole;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = 'Detail User';
$this->params['breadcrumbs'][] = ['label' => 'User', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-primary user-view">
    <div class="box-header with-border">
        <h3 class="box-title">Detail User <?= Html::encode($this->title) ?></h3>
    </div>
    <div class="box-body">

        <?= DetailView::widget([
            'model' => $model,
            'template' => '<tr><th width="180px" style="text-align:right">{label}</th><td>{value}</td></tr>',
            'attributes' => [
                'username',
                [
                    'label' => 'Role',
                    'value' => @$model->userRole->nama
                ],
                [
                    'label' => 'Perangkat Daerah',
                    'value' => @$model->instansi->nama,
                    'visible' => $model->visibleIdInstansi()
                ],
                [
                    'label' => 'Grup',
                    'value' => @$model->grup->nama,
                    'visible' => $model->id_user_role === UserRole::GRUP
                ]

            ],
        ]) ?>

    </div>
    <div class="box-footer with-border">
        <?= Html::a('<i class="fa fa-list"></i> Daftar User', ['user/index', 'id_user_role' => $model->id_user_role], ['class' => 'btn btn-primary btn-flat']); ?>
    </div>

</div>
<?php if ($model->id_user_role == UserRole::ADMIN_INSTANSI) { ?>
    <div class="box box-primary user-view">
        <div class="box-header with-border">
            <h3 class="box-title">Daftar Perangkat Daerah</h3>
        </div>

        <div class="box-body">
            <table class="table table-bordered">
                <tr>
                    <th style="text-align: center; width: 60px">No</th>
                    <th>Perangkat Daerah</th>
                </tr>
                <?php $i = 1;
                foreach (array_merge([$model->instansi], $model->instansi->manySub) as $userInstansi) { ?>
                <tr>
                    <td style="text-align: center;"><?= $i; ?></td>
                    <td><?= $userInstansi->nama; ?></td>
                    <?php $i++;
                    } ?>
            </table>
        </div>
    </div>
<?php } ?>
<?php if ($model->id_user_role == UserRole::VERIFIKATOR || $model->id_user_role == UserRole::MAPPING) { ?>
    <div class="box box-primary user-view">
        <div class="box-header with-border">
            <h3 class="box-title">Daftar Perangkat Daerah</h3>
        </div>
        <div class="box-header with-border">
            <?= Html::a('<i class="fa fa-plus"></i> Tambah Perangkat Daerah', ['user-instansi/create', 'id_user' => $model->id], ['class' => 'btn btn-primary btn-flat']); ?>
        </div>

        <div class="box-body">
            <table class="table table-bordered">
                <tr>
                    <th style="text-align: center; width: 60px">No</th>
                    <th>Perangkat Daerah</th>
                    <th>&nbsp;</th>
                </tr>
                <?php $i = 1;
                foreach ($model->getManyUserInstansi()->all() as $userInstansi) { ?>
                <tr>
                    <td style="text-align: center;"><?= $i; ?></td>
                    <td><?= $userInstansi->instansi ? $userInstansi->instansi->nama : ""; ?></td>
                    <td style="text-align: center;">
                        <?= Html::a('<i class="fa fa-trash"></i>', ['user-instansi/delete', 'id' => $userInstansi->id], ['data-method' => 'post']); ?>
                    </td>
                    <?php $i++;
                    } ?>
            </table>
        </div>
    </div>
<?php } ?>

<?php if($model->id_user_role == UserRole::PEMERIKSA_ABSENSI
    OR $model->id_user_role == UserRole::PEMERIKSA_KINERJA
    OR $model->id_user_role == UserRole::PEMERIKSA_IKI
) { ?>
    <?= $this->render('_box-user-menu',[
        'model' => $model,
        'debug' => @$debug,
    ]); ?>
<?php } ?>
