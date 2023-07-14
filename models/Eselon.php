<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "eselon".
 *
 * @property int $id
 * @property string $nama
 * @property int $non_eselon
 */
class Eselon extends \yii\db\ActiveRecord
{
    use ListableTrait;

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

    public static $eselon_i = [
        self::ESELON_IA,
        self::ESELON_IB,
    ];

    public static $eselon_ii = [
        self::ESELON_IIA,
        self::ESELON_IIB,
    ];

    public static $eselon_iii = [
        self::ESELON_IIIA,
        self::ESELON_IIIB,
    ];

    public static $eselon_iv = [
        self::ESELON_IVA,
        self::ESELON_IVB,
    ];

    public function __toString()
    {
        return $this->nama;
    }

    public static function getKelompok()
    {
        return [
            1 => self::$eselon_i,
            2 => self::$eselon_ii,
            3 => self::$eselon_iii,
            4 => self::$eselon_iv,
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'eselon';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nama'], 'required'],
            [['nama'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nama' => 'Nama',
        ];
    }
}
