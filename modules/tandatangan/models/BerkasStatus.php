<?php

namespace app\modules\tandatangan\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "berkas_status".
 *
 * @property int $id
 * @property string $nama
 */
class BerkasStatus extends \yii\db\ActiveRecord
{
    const SELESAI = 1;
    const VERIFIKASI = 2;
    const TANDATANGAN = 3;
    const TOLAK = 4;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'berkas_status';
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

    public static function getList()
    {
        return ArrayHelper::map(static::find()->all(), 'id', 'nama');
    }
}
