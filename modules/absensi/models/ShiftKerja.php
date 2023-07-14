<?php

namespace app\modules\absensi\models;

use app\modules\absensi\models\ShiftKerjaReguler;
use DateTime;
use yii2mod\query\ArrayQuery;
use yii2tech\ar\softdelete\SoftDeleteBehavior;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "shift_kerja".
 *
 * @property integer $id
 * @property string $nama
 * @property boolean $status_libur_nasional
 *
 * @property JamKerja[] $manyJamKerja
 */
class ShiftKerja extends \yii\db\ActiveRecord
{
    protected static $_default;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shift_kerja';
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
            [['nama', 'hari_kerja'], 'required'],
            [['nama'], 'string', 'max' => 255],
            [['hari_kerja'], 'integer', 'min' => 1, 'max' => 2],
            [['hari_kerja'], 'default', 'value' => 1],
            ['status_libur_nasional', 'boolean'],
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

    public function attributeHints()
    {
        return [
            'hari_kerja' => 'Silahkan masukkan 2 (dua) hari kerja jika shift adalah double shift.'
        ];
    }

    /**
     * @inheritdoc
     * @return ShiftKerjaQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ShiftKerjaQuery(get_called_class());
    }

    public function getManyJamKerja()
    {
        return $this->hasMany(JamKerja::className(), ['id_shift_kerja' => 'id']);
    }

    public function queryJamKerja($hari = null)
    {
        $query = new ArrayQuery();
        $query->from($this->manyJamKerja);
        $query->andFilterWhere(['hari' => $hari]);
        return $query;
    }

    public function countJamKerja($hari = null)
    {
        $query = $this->queryJamKerja($hari);

        return $query->count();
    }

    public function findAllJamKerja($hari = null)
    {
        $query = $this->queryJamKerja($hari);
        $query->orderBy([
            'hari' => SORT_ASC,
            'jam_mulai_hitung' => SORT_ASC,
        ]);
        return $query->all();
    }

    public static function getList()
    {
        return ArrayHelper::map(ShiftKerja::find()->aktif()->all(), 'id', 'nama');
    }

    public static function getListDistinct()
    {
        return ArrayHelper::map(ShiftKerja::find()->aktif()->all(), 'id', 'nama', function ($row) {
            return !$row->getIsDoubleShift() ? 'Reguler' : 'Double Shift';
        });
    }

    /*public function getJamMulaiHitung(\DateTime $date)
    {
    return $date->format('Y-m-d') . ' ' . $this->jam_mulai_hitung;
    }

    public function getJamSelesaiHitung(\DateTime $date)
    {
    return $date->format('Y-m-d') . ' ' . $this->jam_selesai_hitung;
    }*/

    public function beforeSoftDelete()
    {
        KegiatanRiwayatHarian::createRiwayat($this, RiwayatJenis::DELETE);
        $this->waktu_dihapus = date('Y-m-d H:i:s');
        return true;
    }

    public function getIsLiburNasional()
    {
        return (int) $this->status_libur_nasional === 1;
    }

    public function getIsDefault()
    {
        return $this->id === 1;
    }

    public function getHasJamKerja(DateTime $date)
    {
        if ($this->getIsDefault()) {
            return !(in_array(($date->format('N')), [6, 7]) or HariLibur::getIsLibur($date->format('Y-m-d')));
        }
        if ($this->getIsLiburNasional() && HariLibur::getIsLibur($date->format('Y-m-d'))) {
            return false;
        }
        $query = new ArrayQuery();
        $query->from($this->manyJamKerja);
        $query->andWhere(['hari' => $date->format('N')]);
        return $query->one() !== false;
    }

    public static function getDefault()
    {
        if (static::$_default === null) {
            static::$_default = static::findOne(1);
        }
        return static::$_default;
    }

    public function getConditionalShiftKerja($tanggal)
    {
        return ShiftKerjaReguler::getShiftKerjaReguler($this, $tanggal);
    }

    public function getAllShiftKerjaReguler()
    {
        return $this->hasMany(ShiftKerjaReguler::class, ['id_shift_kerja' => 'id']);
    }

    public function getIsDoubleShift()
    {
        return (int) $this->hari_kerja === 2;
    }

    public function getStringIsDoubleShift()
    {
        return $this->getIsDoubleShift() ? "Ya" : "Tidak";
    }
}
