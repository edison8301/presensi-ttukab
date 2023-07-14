<?php

namespace app\modules\absensi\models;

use Yii;
use app\models\User;

/**
 * This is the model class for table "hari_libur".
 *
 * @property integer $id
 * @property string $tanggal
 * @property string $keterangan
 */
class HariLibur extends \yii\db\ActiveRecord
{
    protected static $_hari_libur;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'hari_libur';
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
            [['tanggal', 'keterangan'], 'required'],
            [['tanggal'], 'safe'],
            [['keterangan'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tanggal' => 'Tanggal',
            'keterangan' => 'Keterangan',
        ];
    }

    public static function isLibur($tanggal)
    {
        $model = self::findOne(['tanggal'=>$tanggal]);
        if($model!==null)
            return true;
    }

    public static function getIsLibur($tanggal)
    {
        return isset(static::getHariLibur()[$tanggal]);
    }

    public static function getHariLibur()
    {
        if (static::$_hari_libur === null) {
            $query = static::find();
            $tanggal_awal = User::getTahun() . '-01-01';
            $tanggal_akhir = User::getTahun() . '-12-31';
            $query->andWhere(['between', 'tanggal', $tanggal_awal, $tanggal_akhir])
                ->indexBy('tanggal');
            static::$_hari_libur = $query->all();
        }
        return static::$_hari_libur;
    }
}
