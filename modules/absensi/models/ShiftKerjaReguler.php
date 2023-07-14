<?php

namespace app\modules\absensi\models;

use app\modules\absensi\models\ShiftKerja;
use yii2mod\query\ArrayQuery;
use yii2tech\ar\softdelete\SoftDeleteBehavior;
use Yii;

/**
 * This is the model class for table "shift_kerja_reguler".
 *
 * @property int $id
 * @property string $nama
 * @property string $tanggal_mulai
 * @property string $tanggal_selesai
 * @property int $status_hapus
 * @property string $waktu_dihapus
 */
class ShiftKerjaReguler extends \yii\db\ActiveRecord
{
    static $_shiftKerjaReguler = [];

    public $hari_kerja = 1;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shift_kerja_reguler';
    }

    public function behaviors()
    {
        return [
            'softDeleteBehavior' => [
                'class' => SoftDeleteBehavior::class,
                'softDeleteAttributeValues' => [
                    'status_hapus' => true,
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nama', 'id_shift_kerja', 'tanggal_mulai', 'tanggal_selesai'], 'required'],
            [['waktu_dihapus', 'tanggal_mulai', 'tanggal_selesai'], 'safe'],
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
            'id_shift_kerja' => 'Shift Kerja Induk',
            'status_hapus' => 'Status Hapus',
            'waktu_dihapus' => 'Waktu Dihapus',
        ];
    }

    public function getShiftKerja()
    {
        return $this->hasOne(ShiftKerja::class, ['id' => 'id_shift_kerja']);
    }

    public function getAllJamKerjaReguler()
    {
        return $this->hasMany(JamKerjaReguler::class, ['id_shift_kerja_reguler' => 'id']);
    }

    public function getAllJamKerjaRegulerSearch($hari = null)
    {
        $query = new ArrayQuery();
        $query->from($this->allJamKerjaReguler);
        $query->andFilterWhere(['hari' => $hari]);
        return $query;
    }

    public function getManyJamKerja()
    {
        return $this->allJamKerjaReguler;
    }

    public function countJamKerja($hari = null)
    {
        return $this->getAllJamKerjaRegulerSearch($hari)->count();
    }

    public function findAllJamKerja($hari = null)
    {
        return $this->getAllJamKerjaRegulerSearch($hari)->all();
    }

    protected static function setShiftKerjaReguler(ShiftKerja $shiftKerja)
    {
        if (!isset(static::$_shiftKerjaReguler[$shiftKerja->id])) {
            static::$_shiftKerjaReguler[$shiftKerja->id] = $shiftKerja->allShiftKerjaReguler;
        }
        return static::$_shiftKerjaReguler[$shiftKerja->id];
    }

    public static function getShiftKerjaReguler(ShiftKerja $shiftKerja, $tanggal)
    {
        $shiftKerjaReguler = static::setShiftKerjaReguler($shiftKerja);
        if ($shiftKerjaReguler !== []) {
            $query = new ArrayQuery;
            $query->from($shiftKerjaReguler);
            $query->andWhere(['<=', 'tanggal_mulai', $tanggal])
                ->andWhere(['>=', 'tanggal_selesai', $tanggal]);
            if (($result = $query->one()) !== false) {
                return $result;
            }
        }
        return $shiftKerja;
    }

    public function getIsLiburNasional()
    {
        return @$this->shiftKerja->getIsLiburNasional();
    }

    public function getIsDoubleShift()
    {
        return false;
    }
}
