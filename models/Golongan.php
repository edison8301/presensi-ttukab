<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "golongan".
 *
 * @property int $id
 * @property string $golongan
 */
class Golongan extends \yii\db\ActiveRecord
{
    const IA = 1;
    const IB = 2;
    const IC = 3;
    const ID = 4;
    const IIA = 5;
    const IIB = 6;
    const IIC = 7;
    const IID = 8;
    const IIIA = 9;
    const IIIB = 10;
    const IIIC = 11;
    const IIID = 12;
    const IVA = 13;
    const IVB = 14;
    const IVC = 15;
    const IVD = 16;
    const IVE = 17;
    const V = 18;
    CONST IX = 19;
    const X = 20;
    const VII = 21;

    public static $golongan_i = [
        self::IA,
        self::IB,
        self::IC,
        self::ID,
    ];

    public static function arrayGolonganI()
    {
        return [
            self::IA,
            self::IB,
            self::IC,
            self::ID
        ];
    }

    public static $golongan_ii = [
        self::IIA,
        self::IIB,
        self::IIC,
        self::IID,
    ];

    public static $golongan_iii = [
        self::IIIA,
        self::IIIB,
        self::IIIC,
        self::IIID,
    ];

    public static $golongan_iv = [
        self::IVA,
        self::IVB,
        self::IVC,
        self::IVD,
        self::IVE,
    ];

    public static function arrayGolonganIi()
    {
        return [
            self::IIA,
            self::IIB,
            self::IIC,
            self::IID,
        ];
    }

    public static function arrayGolonganIii()
    {
        return [
            self::IIIA,
            self::IIIB,
            self::IIIC,
            self::IIID,
        ];
    }

    public static function arrayGolonganIv()
    {
        return [
            self::IVA,
            self::IVB,
            self::IVC,
            self::IVD,
            self::IVE
        ];
    }

    public function __toString()
    {
        return $this->golongan;
    }

    public static function getKelompok()
    {
        return [
            1 => self::$golongan_i,
            2 => self::$golongan_ii,
            3 => self::$golongan_iii,
            4 => self::$golongan_iv,
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'golongan';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['golongan'], 'required'],
            [['golongan'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'golongan' => 'Golongan',
        ];
    }

    public static function getList()
    {
        return ArrayHelper::map(static::find()->all(), 'id', 'golongan');
    }

    public function getPangkatKomaGolongan()
    {
        return $this->pangkat.", ".$this->golongan;
    }
}
