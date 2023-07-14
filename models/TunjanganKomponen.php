<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tunjangan_komponen".
 *
 * @property int $id
 * @property string $nama
 * @property int $urutan
 * @property int $status_hapus
 * @property int $waktu_hapus
 * @property int $id_user_hapus
 */
class TunjanganKomponen extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tunjangan_komponen';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nama'], 'required'],
            [['urutan', 'status_hapus', 'waktu_hapus', 'id_user_hapus'], 'integer'],
            [['nama'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nama' => 'Nama',
            'urutan' => 'Urutan',
            'status_hapus' => 'Status Hapus',
            'waktu_hapus' => 'Waktu Hapus',
            'id_user_hapus' => 'Id User Hapus',
        ];
    }
}
