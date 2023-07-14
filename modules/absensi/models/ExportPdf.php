<?php

namespace app\modules\absensi\models;

use app\models\Instansi;

/**
 * This is the model class for table "export_pdf".
 *
 * @property int $id
 * @property int $id_instansi
 * @property int $bulan
 * @property string $tahun
 * @property string $hash
 *
 * @property Instansi $instansi
 */
class ExportPdf extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'export_pdf';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_instansi', 'bulan', 'tahun', 'hash'], 'safe'],
            [['id_instansi', 'bulan'], 'integer'],
            [['tahun'], 'safe'],
            [['hash'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_instansi' => 'Id Instansi',
            'bulan' => 'Bulan',
            'tahun' => 'Tahun',
            'hash' => 'Hash',
        ];
    }

    public function getInstansi()
    {
        return $this->hasOne(Instansi::class, ['id' => 'id_instansi']);
    }

    private $_namaFile;

    public function setFileName($tandatangan = false)
    {
        if ($tandatangan == false) {
            return time();
        }
        
        if ($this->_namaFile !== null) {
            return $this->_namaFile;
        }

        $this->_namaFile = time().'.pdf';
        return $this->_namaFile;
    }
}
