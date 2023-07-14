<?php

namespace app\modules\tukin\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "eselon".
 *
 * @property int $id
 * @property Pegawai[] $manyPegawai
 * @property int $count
 * @property string $nama
 */
class Eselon extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'eselon';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db');
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

    public static function getGrafikList()
    {
        foreach (static::find()->all() as $eselon) {
            yield [$eselon->nama, $eselon->getCount()];
        }
    }

    public function getManyPegawai()
    {
        return $this->hasMany(Pegawai::class, ['id_eselon' => 'id']);
    }

    public function getCount()
    {
        return count($this->manyPegawai);
    }

    public static function getList()
    {
        return ArrayHelper::map(self::find()->all(), 'id', 'nama');
    }
}
