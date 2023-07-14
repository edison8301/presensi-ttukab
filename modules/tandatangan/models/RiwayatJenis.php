<?php

namespace app\modules\tandatangan\models;

use Yii;

/**
 * This is the model class for table "riwayat_jenis".
 *
 * @property int $id
 * @property string $nama
 */
class RiwayatJenis extends \yii\db\ActiveRecord
{
    const BERKAS_DIBUAT = 1;
    const BERKAS_MENTAH_DIUNDUH = 2;
    const BERKAS_DITANDATANGAN = 3;
    const BERKAS_TANDATANGAN_DIUNDUH = 4;
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'riwayat_jenis';
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
            [['nama'], 'required'],
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
        ];
    }
}
