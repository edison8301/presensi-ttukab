<?php

namespace app\models;

use Yii;
use yii2tech\ar\position\PositionBehavior;
use yii\helpers\Html;

/**
 * This is the model class for table "user_role_menu".
 *
 * @property int $id
 * @property int $id_user_role
 * @property string $nama
 * @property string $path
 */
class UserRoleMenu extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_role_menu';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_user_role', 'nama', 'path'], 'required'],
            [['id_user_role', 'urutan'], 'integer'],
            [['nama', 'path'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'positionBehavior' => [
                'class' => PositionBehavior::class,
                'positionAttribute' => 'urutan',
                'groupAttributes' => [
                    'id_user_role',
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_user_role' => 'Id User Role',
            'nama' => 'Nama',
            'path' => 'Path',
        ];
    }

    public function getUserRole()
    {
        return $this->hasOne(UserRole::class,['id'=>'id_user_role']);
    }

    public function getNamaUserRole()
    {
        return @$this->userRole->nama;
    }

    public function accessUpdate()
    {
        if(User::isAdmin()) {
            return true;
        }

        return false;
    }

    public function getLinkUpdateIcon()
    {
        if($this->accessUpdate()==false) {
            return null;
        }

        return Html::a('<i class="fa fa-pencil"></i>',[
                '/user-role-menu/update',
                'id'=>$this->id
            ],[
                'data-toggle'=>'tooltip',
                'title'=>'Ubah'
            ]).' ';
    }


    public function accessDelete()
    {
        if(User::isAdmin()) {
            return true;
        }

        return false;

    }

    public function getLinkDeleteIcon()
    {
        if($this->accessDelete()==false) {
            return null;
        }

        return Html::a('<i class="fa fa-trash"></i>',[
                '/user-role-menu/delete',
                'id'=>$this->id
            ],[
                'data-method'=>'post',
                'data-confirm'=>'Yakin akan menghapus data?',
                'data-toggle'=>'tooltip',
                'title'=>'Hapus'
            ]).' ';
    }

    public function updateUserMenu()
    {
        foreach($this->findAllUserMenu() as $userMenu) {
            $userMenu->updateAttributes([
                'path' => $this->path
            ]);
        }
    }

    /**
     * @return UserMenu
     */
    public function findAllUserMenu()
    {
        $query = UserMenu::find();
        $query->andWhere([
            'id_user_role_menu' => $this->id
        ]);

        return $query->all();
    }
}
