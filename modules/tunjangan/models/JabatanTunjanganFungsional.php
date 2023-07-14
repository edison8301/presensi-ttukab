<?php

namespace app\modules\tunjangan\models;

use Yii;
use app\components\Helper;
use app\modules\tukin\models\Instansi;
use yii\helpers\Html;

/**
 * This is the model class for table "jabatan_tunjangan_fungsional".
 *
 * @property int $id
 * @property int $id_instansi
 * @property int $id_tingkatan_fungsional
 * @property double $besaran_tpp
 * @property string $tanggal_mulai
 * @property string $tanggal_selesai
 * @property int $status_p3k
 */
class JabatanTunjanganFungsional extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'jabatan_tunjangan_fungsional';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_instansi', 'id_tingkatan_fungsional', 'besaran_tpp'], 'required'],
            [['id_instansi', 'id_tingkatan_fungsional'], 'integer'],
            [['besaran_tpp'], 'safe'],
            [['tanggal_mulai', 'tanggal_selesai'], 'safe'],
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
            'id_instansi' => 'Instansi',
            'id_tingkatan_fungsional' => 'Tingkatan Fungsional',
            'besaran_tpp' => 'Besaran TPP',
            'tanggal_mulai' => 'Tanggal Mulai',
            'tanggal_selesai' => 'Tanggal Selesai',
        ];
    }

    public function getInstansi()
    {
        return $this->hasOne(Instansi::className(), ['id' => 'id_instansi']);
    }

    public function getTingkatanFungsional()
    {
        return $this->hasOne(TingkatanFungsional::className(), ['id' => 'id_tingkatan_fungsional']);
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
