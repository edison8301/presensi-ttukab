<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "eselon".
 *
 * @property int $id
 * @property string $pendidikan
 */
class Pendidikan extends \yii\db\ActiveRecord
{
    const ESELON_IA = 1;
    const ESELON_IB = 2;
    const ESELON_IIA = 3;
    const ESELON_IIB = 4;
    const ESELON_IIIA = 5;
    const ESELON_IIIB = 6;
    const ESELON_IVA = 7;
    const ESELON_IVB = 8;
    const ESELON_VA = 9;
    const NON_ESELON = 10;

    public static function getList()
    {
        $query = Pendidikan::find();
        return ArrayHelper::map($query->all(),'id','nama');
    }

    public function __toString()
    {
        return $this->pendidikan;
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pendidikan';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pendidikan'], 'required'],
            [['pendidikan'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pendidikan' => 'Nama',
        ];
    }
}
