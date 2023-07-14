<?php

namespace app\modules\tukin\models;

use Yii;

/**
 * This is the model class for table "instansi_serapan_anggaran".
 *
 * @property int $id
 * @property int $id_instansi
 * @property string $tahun
 * @property int $bulan
 * @property double $target
 * @property double $realisasi
 *
 * @property float|int $serapan
 */
class InstansiSerapanAnggaran extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'instansi_serapan_anggaran';
    }

    public static function accessCreate()
    {
        return User::isAdmin();
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_instansi', 'tahun', 'bulan'], 'required'],
            [['id_instansi', 'bulan'], 'integer'],
            [['tahun'], 'safe'],
            [['target', 'realisasi'], 'number'],
            [['id_instansi', 'tahun', 'bulan'], 'unique', 'targetAttribute' => ['id_instansi', 'tahun', 'bulan']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_instansi' => 'Id Instansi',
            'tahun' => 'Tahun',
            'bulan' => 'Bulan',
            'target' => 'Target',
            'realisasi' => 'Realisasi',
        ];
    }

    public function getSerapan()
    {
        if (!empty($this->realisasi) && !empty($this->target)) {
            if ($this->realisasi > $this->target) {
                return 100;
            } else {
                return $this->realisasi / $this->target * 100;
            }
        }
        return 0;
    }

    public function getPotongan()
    {
        return 100 - $this->getSerapan();
    }
}
