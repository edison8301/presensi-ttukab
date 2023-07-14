<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * This is the model class for table "user_role".
 *
 * @property integer $id
 * @property string $kode
 * @property string $nama
 */
class UserRole extends \yii\db\ActiveRecord
{
    const ADMIN = 1;
    const PEGAWAI = 2;
    const INSTANSI = 3;
    const VERIFIKATOR = 4;
    const GRUP = 5;
    const MAPPING = 6;
    const ADMIN_INSTANSI = 7;
    const ADMIN_IKI = 8;
    const OPERATOR_ABSEN = 9;
    const OPERATOR_STRUKTUR = 10;
    const PEMERIKSA_ABSENSI = 11;
    const PEMERIKSA_KINERJA = 12;
    const PEMERIKSA_IKI = 13;
    const MAPPING_RPJMD = 14;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_role';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nama'], 'required'],
            [['nama'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'kode' => 'Kode',
            'nama' => 'Nama',
        ];
    }

    public static function getList()
    {
        return ArrayHelper::map(UserRole::find()->all(),'id','nama');
    }

    /**
     * @return UserRoleMenu[]
     */
    public function findAllUserRoleMenu()
    {
        $query = UserRoleMenu::find();
        $query->andWhere([
            'id_user_role' => $this->id
        ]);
        $query->orderBy(['urutan' => SORT_ASC]);
        
        return $query->all();
    }

    public function getLinkUpdateIcon()
    {
        if($this->accessUpdate()==false) {
            return null;
        }

        return Html::a('<i class="fa fa-pencil"></i>',[
                '/jabatan/update',
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
                '/jabatan/delete',
                'id'=>$this->id
            ],[
                'data-method'=>'post',
                'data-confirm'=>'Yakin akan menghapus data?',
                'data-toggle'=>'tooltip',
                'title'=>'Hapus'
            ]).' ';
    }
}
