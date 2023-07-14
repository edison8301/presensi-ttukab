<?php

namespace app\modules\tunjangan\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "jabatan_golongan".
 *
 * @property int $id
 * @property string $nama
 */
class JabatanTunjanganGolongan extends \yii\db\ActiveRecord
{

    const I = 1;
    const II = 2;
    const III = 3;
    const IV = 4;
    const V = 5;
    const IX = 6;
    const X = 7;
    const VII = 8;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'jabatan_tunjangan_golongan';
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
        return ArrayHelper::map(self::find()->all(), 'id', 'nama');
    }
}
