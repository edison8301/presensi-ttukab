<?php

namespace app\modules\tandatangan\models;

use Yii;

/**
 * This is the model class for table "verifikasi".
 *
 * @property int $id
 * @property int $id_berkas
 * @property int $id_verifikasi_status
 * @property int $urutan
 * @property string $nip_verifikasi
 * @property string $nama_verifikasi
 * @property string $jabatan_verifikasi
 * @property string $keterangan_verifikasi
 * @property string $waktu_verifikasi
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 */
class Verifikasi extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'verifikasi';
    }

    public static function getDb()
    {
        return Yii::$app->get('db_tandatangan');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_berkas'], 'required'],
            [['id_berkas', 'id_verifikasi_status', 'urutan'], 'integer'],
            [['keterangan_verifikasi'], 'string'],
            [['waktu_verifikasi', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['nip_verifikasi'], 'string', 'max' => 20],
            [['nama_verifikasi', 'jabatan_verifikasi'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_berkas' => 'Id Berkas',
            'id_verifikasi_status' => 'Id Verifikasi Status',
            'urutan' => 'Urutan',
            'nip_verifikasi' => 'Nip Verifikasi',
            'nama_verifikasi' => 'Nama Verifikasi',
            'jabatan_verifikasi' => 'Jabatan Verifikasi',
            'keterangan_verifikasi' => 'Keterangan Verifikasi',
            'waktu_verifikasi' => 'Waktu Verifikasi',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
        ];
    }
}
