<?php

namespace app\modules\kinerja\models;

use Yii;

/**
 * This is the model class for table "instansi_pegawai_sasaran_indikator".
 *
 * @property int $id
 * @property int $id_instansi_pegawai_sasaran
 * @property string $nama
 * @property string $penjelasan
 * @property string $sumber_data
 * @property int $urutan
 */
class InstansiPegawaiSasaranIndikator extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'instansi_pegawai_sasaran_indikator';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_instansi_pegawai_sasaran', 'nama', 'penjelasan', 'sumber_data'], 'required'],
            [['id_instansi_pegawai_sasaran', 'urutan'], 'integer'],
            [['nama', 'penjelasan', 'sumber_data'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_instansi_pegawai_sasaran' => 'Id Instansi Pegawai Sasaran',
            'nama' => 'Nama',
            'penjelasan' => 'Penjelasan / Formulasi Penghitungan',
            'sumber_data' => 'Sumber Data',
            'urutan' => 'Urutan',
        ];
    }
}
