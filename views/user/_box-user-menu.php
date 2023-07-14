<?php

use yii\helpers\Html;

?>

<div class="user-role-menu-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Akses Menu</h3>
    </div>

    <div class="box-body">

        <?php if (@$debug) { ?>
            <div style="margin-bottom: 20px;">
                <?= Html::a('<i class="fa fa-plus"></i> Tambah Akses Menu', [
                    '/user-role-menu/create',
                    'id_user_role' => $model->id_user_role,
                ], ['class' => 'btn btn-success btn-flat']) ?>
            </div>
        <?php } ?>

        <table class="table table-bordered">
            <tr>
                <th style="width: 50px; text-align: center">No</th>
                <th>Nama</th>
                <th>Path</th>
                <th style="text-align: center">Akses</th>
                <th></th>
            </tr>
            <?php $i=1; foreach($model->userRole->findAllUserRoleMenu() as $data) { ?>
                <?php
                    $userMenu = $model->findOrCreateUserMenu([
                        'id_user_role_menu' => $data->id,
                        'path' => $data->path
                    ]);
                ?>
                <tr>
                    <td style="text-align: center"><?= $i; ?></td>
                    <td>
                        <?= $data->nama; ?>
                        <?php if (@$debug) { ?>
                            <?= Html::a('<i class="fa fa-pencil"></i>', [
                                '/user-role-menu/update',
                                'id' => $data->id,
                            ]) ?>
                        <?php }  ?>
                    </td>
                    <td><?= $data->path; ?></td>
                    <td style="text-align: center">
                        <?= $userMenu->getNamaStatusAktif(); ?>
                    </td>
                    <td style="width: 120px; text-align: center">
                        <?= $userMenu->getLinkUpdateIcon(); ?>
                        <?= $userMenu->getLinkDeleteIcon(); ?>
                    </td>
                </tr>
                <?php $i++; } ?>
        </table>
    </div>
</div>
