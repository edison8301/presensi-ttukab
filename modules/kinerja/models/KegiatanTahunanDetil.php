<?php

namespace app\modules\kinerja\models;

use Yii;

/**
 * This is the model class for table "kegiatan_tahunan_detil".
 *
 * @property integer $id
 * @property integer $id_kegiatan_tahunan
 * @property integer $bulan
 * @property integer $kuantitas
 * @property integer $id_satuan_kuantitas
 */
class KegiatanTahunanDetil extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'kegiatan_tahunan_detil';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_kegiatan_tahunan', 'bulan', 'kuantitas', 'id_satuan_kuantitas'], 'required'],
            [['id_kegiatan_tahunan', 'bulan', 'kuantitas', 'id_satuan_kuantitas'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_kegiatan_tahunan' => 'Id Kegiatan Tahunan',
            'bulan' => 'Bulan',
            'kuantitas' => 'Kuantitas',
            'id_satuan_kuantitas' => 'Id Satuan Kuantitas',
        ];
    }

    public function getKegiatanTahunan()
    {
        return $this->hasOne(KegiatanTahunan::className(), ['id' => 'id_kegiatan_tahunan']);
    }

    public function findAllKegiatanBulananBreakdown()
    {
        $query = KegiatanBulananBreakdown::find();
        $query->andWhere(['id_kegiatan_tahunan_detil'=>$this->id]);
        return $query->all();
    }
}
