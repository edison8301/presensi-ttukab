<?php

namespace app\modules\kinerja\models;

use Yii;

/**
 * This is the model class for table "instansi_pegawai_sasaran".
 *
 * @property int $id
 * @property int $id_instansi_pegawai
 * @property string $nama
 * @property int $urutan
 *
 * @property InstansiPegawaiSasaranIndikator[] $manyInstansiPegawaiSasaranIndikator
 */
class InstansiPegawaiSasaran extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'instansi_pegawai_sasaran';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_instansi_pegawai', 'nama'], 'required'],
            [['id_instansi_pegawai', 'urutan'], 'integer'],
            [['nama'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_instansi_pegawai' => 'Id Instansi Pegawai',
            'nama' => 'Sasaran',
            'urutan' => 'Urutan',
        ];
    }

    public function getManyInstansiPegawaiSasaranIndikator()
    {
        return $this->hasMany(InstansiPegawaiSasaranIndikator::class, ['id_instansi_pegawai_sasaran' => 'id'])->orderBy(['urutan' => SORT_ASC]);
    }
}
