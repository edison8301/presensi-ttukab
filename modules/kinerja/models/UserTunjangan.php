<?php

namespace app\modules\kinerja\models;

use Yii;

/**
 * This is the model class for table "user_tunjangan".
 *
 * @property integer $id
 * @property integer $id_user_tunjangan_jenis
 * @property string $nip
 * @property integer $bulan
 * @property string $tahun
 * @property string $persen_nilai
 * @property string $persen_bobot
 * @property string $pokok_tunjangan
 * @property string $jumlah_tunjangan
 */
class UserTunjangan extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_tunjangan';
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
            [['bulan'], 'integer'],
            [['nip'], 'required'],
            [['tahun','jenis'], 'safe'],
            [['nip'], 'string', 'max' => 50],
            [['persen_nilai', 'persen_bobot', 'pokok_tunjangan', 'jumlah_tunjangan'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_user_tunjangan_jenis' => 'Id User Tunjangan Jenis',
            'nip' => 'Nip',
            'bulan' => 'Bulan',
            'tahun' => 'Tahun',
            'persen_nilai' => 'Persen Nilai',
            'persen_bobot' => 'Persen Bobot',
            'pokok_tunjangan' => 'Pokok Tunjangan',
            'jumlah_tunjangan' => 'Jumlah Tunjangan',
        ];
    }
}
