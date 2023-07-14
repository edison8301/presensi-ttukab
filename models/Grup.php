<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "grup".
 *
 * @property int $id
 * @property string $nama
 * @property int $id_instansi
 */
class Grup extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'grup';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nama'], 'required'],
            [['id_instansi'], 'integer'],
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
            'nama' => 'Nama',
            'id_instansi' => 'Instansi',
        ];
    }

    public function getInstansi()
    {
        return $this->hasOne(Instansi::class, ['id' => 'id_instansi']);
    }

    public function getManyGrupPegawai()
    {
        return $this->hasMany(GrupPegawai::class, ['id_grup' => 'id']);
    }

    public static function getList()
    {
        return ArrayHelper::map(Grup::find()->all(),'id','nama');
    }

    public static function accessCreate()
    {
        return User::isAdmin();
    }

    public function queryGrupPegawai()
    {
        $query = $this->getManyGrupPegawai();
        return $query;
    }


    public function countGrupPegawai()
    {
        $query = $this->queryGrupPegawai();
        return $query->count();
    }

    public function findUser()
    {
        $model = User::findOne([
            'id_grup' => $this->id,
            'id_user_role' => UserRole::GRUP
        ]);

        if ($model===null)
        {
            $model = new User;
            $model->username = $this->nama;
            $model->id_grup = $this->id;
            $model->id_user_role = UserRole::GRUP;
            $model->password = Yii::$app->getSecurity()->generatePasswordHash($this->nama);

            if (!$model->save()) {
                if (@$model->getErrors()['username'] !== null) {
                    $model->username = null;
                } else {
                    print_r($model->getErrors());
                    die();
                }
            }
        }

        return $model;
    }

    public function getIdUser()
    {
        $user = $this->findUser();
        return $user->id;
    }

    public function getUsername()
    {
        $user = $this->findUser();
        return $user->username;
    }

    public function accessUpdate()
    {
        if(User::isAdmin()) {
            return true;
        }

        return false;
    }

    public static function accessIndex()
    {
        if(User::isAdmin()) {
            return true;
        }

        return false;
    }
}
