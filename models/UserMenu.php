<?php

namespace app\models;

use app\components\Session;
use Yii;
use yii\helpers\Html;

/**
 * This is the model class for table "user_menu".
 *
 * @property int $id
 * @property int $id_user
 * @property int $id_user_role_menu
 * @property string $path
 * @property int $status_aktif
 */
class UserMenu extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_menu';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_user', 'id_user_role_menu', 'path'], 'required'],
            [['id_user', 'id_user_role_menu', 'status_aktif'], 'integer'],
            [['path'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_user' => 'Id User',
            'id_user_role_menu' => 'Id User Role Menu',
            'path' => 'Path',
            'status_aktif' => 'Status Aktif',
        ];
    }

    public static function findOrCreate($params = [])
    {
        $query = UserMenu::find();
        $query->andWhere([
            'id_user' => @$params['id_user'],
            'id_user_role_menu' => @$params['id_user_role_menu']
        ]);

        $model = $query->one();
        if($model === null) {
            $model = new UserMenu($params);
        }

        if($model->save() == false)  {
            print_r($model->getErrors());
            die;
        }

        return $model;
    }

    public function getNamaStatusAktif()
    {
        if($this->status_aktif == 1) {
            return "Ya";
        }

        if($this->status_aktif == 0) {
            return "Tidak";
        }
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
                '/user-menu/update',
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
                '/user-menu/delete',
                'id'=>$this->id
            ],[
                'data-method'=>'post',
                'data-confirm'=>'Yakin akan menghapus data?',
                'data-toggle'=>'tooltip',
                'title'=>'Hapus'
            ]).' ';
    }

    public static function findStatusAktif(array $params = [])
    {
        $query = UserMenu::find();
        $query->andWhere([
            'id_user' => Session::getIdUser(),
            'path' => @$params['path']
        ]);

        $model = $query->one();
        if($model !== null) {
            return $model->status_aktif;
        }

        return false;
    }
}
