<?php

namespace app\modules\tunjangan\models;

use Yii;
use app\components\Helper;
use app\modules\tukin\models\Instansi;
use app\modules\tunjangan\models\JabatanGolongan;
use yii\helpers\Html;

/**
 * This is the model class for table "jabatan_tunjangan_pelaksana".
 *
 * @property int $id
 * @property int $id_instansi
 * @property int $id_jabatan_golongan
 * @property double $besaran_tpp
 * @property string $tanggal_mulai
 * @property string $tanggal_selesai
 */
class JabatanTunjanganPelaksana extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'jabatan_tunjangan_pelaksana';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['besaran_tpp'], 'required'],
            [['id_instansi', 'id_jabatan_golongan'], 'integer'],
            [['tanggal_mulai', 'tanggal_selesai'], 'safe'],
            [['id_jabatan_tunjangan_golongan', 'kelas_jabatan'], 'integer']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_instansi' => 'Instansi',
            'id_jabatan_golongan' => 'Golongan',
            'id_jabatan_tunjangan_golongan' => 'Golongan',
            'besaran_tpp' => 'Besaran TPP',
            'tanggal_mulai' => 'Tanggal Mulai',
            'tanggal_selesai' => 'Tanggal Selesai',
        ];
    }

    public function getInstansi()
    {
        return $this->hasOne(Instansi::className(), ['id' => 'id_instansi']);
    }

    public function getJabatanTunjanganGolongan()
    {
        return $this->hasOne(JabatanTunjanganGolongan::className(), ['id' => 'id_jabatan_tunjangan_golongan']);
    }

    public function getJabatanGolongan()
    {
        return $this->hasOne(JabatanGolongan::className(), ['id' => 'id_jabatan_golongan']);
    }

    public function getLabelTanggalSelesai()
    {
        if ($this->tanggal_selesai == '9999-12-31') {
            return Html::tag('span','Masih Berlaku',['class' => 'label label-success btn-flat btn-xs']);
        }
        return Helper::getTanggal($this->tanggal_selesai);
    }

    public function beforeSave($insert)
    {
        if ($this->tanggal_selesai == null) {
            $this->tanggal_selesai = '9999-12-31';
        }
        $this->besaran_tpp = str_replace(',',null, $this->besaran_tpp);
        return parent::beforeSave($insert);
    }
}
