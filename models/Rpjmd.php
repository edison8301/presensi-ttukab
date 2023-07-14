<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "rpjmd".
 *
 * @property int $id
 * @property string $tahun_awal
 * @property string $tahun_akhir
 * @property string $visi
 * @property string $keterangan
 * @property string $username_pembuat
 * @property string $waktu_dibuat
 * @property string $username_pengubah
 * @property string $waktu_diubah
 */
class Rpjmd extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rpjmd';
    }

    public static function getDb()
    {
        return Yii::$app->get('db_sakip');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tahun_awal'], 'required'],
            [['tahun_awal', 'tahun_akhir', 'waktu_dibuat', 'waktu_diubah'], 'safe'],
            [['visi', 'keterangan'], 'string'],
            [['username_pembuat', 'username_pengubah'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tahun_awal' => 'Tahun Awal',
            'tahun_akhir' => 'Tahun Akhir',
            'visi' => 'Visi',
            'keterangan' => 'Keterangan',
            'username_pembuat' => 'Username Pembuat',
            'waktu_dibuat' => 'Waktu Dibuat',
            'username_pengubah' => 'Username Pengubah',
            'waktu_diubah' => 'Waktu Diubah',
        ];
    }
}
