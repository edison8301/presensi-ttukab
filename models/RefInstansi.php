<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ref_instansi".
 *
 * @property integer $id
 * @property string $kode_instansi
 * @property string $nama
 * @property string $alamat
 * @property string $telepon
 * @property string $email
 */
class RefInstansi extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ref_instansi';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['kode_instansi', 'nama'], 'required'],
            [['kode_instansi'], 'string', 'max' => 50],
            [['nama', 'alamat', 'telepon', 'email'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'kode_instansi' => 'Kode Instansi',
            'nama' => 'Nama',
            'alamat' => 'Alamat',
            'telepon' => 'Telepon',
            'email' => 'Email',
        ];
    }

    public function getAllInstansi()
    {
        return $this->hasMany(Instansi::class, ['kode_instansi' => 'kode_instansi']);
    }
}
