<?php

namespace app\modules\absensi\models;

use Yii;

/**
 * This is the model class for table "keterangan".
 *
 * @property integer $id
 * @property string $nip
 * @property string $tanggal
 * @property integer $id_keterangan_jenis
 */
class Keterangan extends \yii\db\ActiveRecord
{
    const TUNGGU = 2;
    const VERIFIKASI = 1;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'keterangan';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db');
    }

    public function getKeteranganJenis()
    {
        return $this->hasOne(KeteranganJenis::className(), ['id' => 'id_keterangan_jenis']);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nip', 'tanggal', 'id_keterangan_jenis'], 'required'],
            [['tanggal','keterangan','status','berkas'], 'safe'],
            [['id_keterangan_jenis'], 'integer'],
            [['nip'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nip' => 'NIP',
            'tanggal' => 'Tanggal',
            'id_keterangan_jenis' => 'Jenis Keterangan',
        ];
    }

    public function getRelationField($relation,$field)
    {
        if(!empty($this->$relation->$field))
            return $this->$relation->$field;
        else
            return null;
    }
}
