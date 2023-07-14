<?php

namespace app\modules\kinerja\models;

use Yii;
use function sprintf;

/**
 * This is the model class for table "jadwal_kegiatan_bulan".
 *
 * @property int $id
 * @property string $tahun
 * @property int $bulan
 * @property string $tanggal
 */
class JadwalKegiatanBulan extends \yii\db\ActiveRecord
{
    const TANGGAL_DEFAULT = 10;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'jadwal_kegiatan_bulan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tahun', 'bulan', 'tanggal'], 'required'],
            [['tahun', 'tanggal'], 'safe'],
            [['bulan'], 'integer'],
            [['tahun', 'bulan'], 'unique', 'targetAttribute' => ['tahun', 'bulan']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tahun' => 'Tahun',
            'bulan' => 'Bulan',
            'tanggal' => 'Tanggal',
        ];
    }

    public static function findOrCreate($bulan, $tahun = null)
    {
        if ($tahun === null) {
            $tahun = \app\models\User::getTahun();
        }
        $find = static::find()
            ->andWhere(['bulan' => $bulan, 'tahun' => $tahun])
            ->one();
        if (!$find) {
            $find = new static([
                    'tahun' => $tahun,
                    'bulan' => $bulan,
                    'tanggal' => "$tahun-$bulan-" . self::TANGGAL_DEFAULT,
                ]);
            $find->save();

        }
        return $find;
    }
}
