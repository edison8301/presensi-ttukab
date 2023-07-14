<?php

namespace app\modules\tunjangan\models;

use app\components\Helper;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * This is the model class for table "jabatan_tunjangan_khusus".
 *
 * @property int $id
 * @property int $id_jabatan_tunjangan_khusus_jenis
 * @property int $id_jabatan_tunjangan_golongan
 * @property string $besaran_tpp
 * @property string $tanggal_mulai
 * @property string $tanggal_selesai
 * @property string $keterangan
 * @property int $kelas_jabatan
 * @property int $status_p3k
 */
class JabatanTunjanganKhusus extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'jabatan_tunjangan_khusus';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_jabatan_tunjangan_khusus_jenis', 'besaran_tpp'], 'required'],
            [['id_jabatan_tunjangan_khusus_jenis', 'id_jabatan_tunjangan_golongan'], 'integer'],
            [['besaran_tpp'], 'safe'],
            [['tanggal_mulai', 'tanggal_selesai'], 'safe'],
            [['keterangan'], 'string', 'max' => 255],
            [['kelas_jabatan', 'status_p3k'], 'integer'],
            ['status_p3k', 'in', 'range' => [0, 1], 'message' => 'Status P3K tidak valid'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_jabatan_tunjangan_khusus_jenis' => 'Jenis',
            'id_jabatan_tunjangan_golongan' => 'Golongan',
            'besaran_tpp' => 'Besaran TPP',
            'tanggal_mulai' => 'Tanggal Mulai',
            'tanggal_selesai' => 'Tanggal Selesai',
            'keterangan' => 'Keterangan',
        ];
    }

    public function beforeSave($insert)
    {
        if ($this->tanggal_selesai == null) {
            $this->tanggal_selesai = '9999-12-31';
        }
        $this->besaran_tpp = str_replace(',',null, $this->besaran_tpp);
        return parent::beforeSave($insert);
    }

    /**
     * {@inheritdoc}
     * @return JabatanTunjanganKhususQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new JabatanTunjanganKhususQuery(get_called_class());
    }

    public function getJabatanTunjanganKhususJenis()
    {
        return $this->hasOne(JabatanTunjanganKhususJenis::class,['id'=>'id_jabatan_tunjangan_khusus_jenis']);
    }

    public function getJabatanTunjanganGolongan()
    {
        return $this->hasOne(JabatanTunjanganGolongan::class,['id'=>'id_jabatan_tunjangan_golongan']);
    }

    public function getLabelTanggalMulai()
    {
        if ($this->tanggal_mulai == null) {
            return null;
        }

        return Helper::getTanggal($this->tanggal_mulai);
    }

    public function getLabelTanggalSelesai()
    {
        if ($this->tanggal_selesai == null) {
            return null;
        }

        if ($this->tanggal_selesai == '9999-12-31') {
            return Html::tag('span','Masih Berlaku',['class' => 'label label-success btn-flat btn-xs']);
        }

        return Helper::getTanggal($this->tanggal_selesai);
    }

    public static function getListKeterangan()
    {
        $query = JabatanTunjanganKhusus::find();
        $query->andWhere('keterangan IS NOT NULL AND keterangan != ""');
        $query->groupBy(['keterangan']);

        return ArrayHelper::map($query->all(), 'keterangan', 'keterangan');
    }
}
