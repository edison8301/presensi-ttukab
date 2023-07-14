<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ref_pegawai".
 *
 * @property integer $id
 * @property string $kode_pegawai
 * @property string $nama
 * @property string $nip
 * @property string $kode_absensi
 */
class RefPegawai extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ref_pegawai';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['kode_pegawai', 'nip', 'kode_absensi'], 'string', 'max' => 50],
            [['nama'], 'string', 'max' => 255],
            [['kode_pegawai'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'kode_pegawai' => 'Kode Pegawai',
            'nama' => 'Nama',
            'nip' => 'NIP',
            'kode_absensi' => 'Kode Absensi',
        ];
    }

    

    public function getPegawai()
    {
        return $this
            ->hasOne(Pegawai::class, ['kode_pegawai' => 'kode_pegawai'])
            ->andWhere(['tahun' => User::getTahun()]);
    }

    public function getAllPegawai()
    {
        return $this->hasMany(Pegawai::class, ['kode_pegawai' => 'kode_pegawai']);
    }

    public function findUser()
    {
        $query = User::find();
        $query->andWhere([
            'username'=>$this->kode_pegawai,
        ]);

        $model = $query->one();

        if($model===null)
        {
            $model = new User;
            $model->kode_user_role = 'pegawai';
            $model->username = $this->kode_pegawai;
            $model->password = Yii::$app->getSecurity()->generatePasswordHash($this->kode_pegawai);

            $model->save();
        }

        return $model;

    }

    public function getIdUser()
    {
        $model = $this->findUser();

        if($model!==null)
            return $model->id;

        return null;
    }
}
