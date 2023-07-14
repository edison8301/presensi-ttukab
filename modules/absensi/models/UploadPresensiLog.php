<?php

namespace app\modules\absensi\models;

use Yii;
use app\models\Pegawai;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "upload_presensi_log".
 *
 * @property int $id
 * @property int $id_upload_presensi
 * @property string $badgenumber
 * @property string $checktime
 * @property int $checktype
 * @property string $SN
 * @property int $status_kirim
 */
class UploadPresensiLog extends \yii\db\ActiveRecord
{
    const STATUS_GAGAL_KIRIM = 0;
    const STATUS_TERKIRIM = 1;
    const STATUS_SUDAH_TERKIRIM = 2;
    const STATUS_NO_USER = 3;

    protected static $statusLog = [
        self::STATUS_GAGAL_KIRIM => 'Gagal Kirim',
        self::STATUS_TERKIRIM => 'Berhasil Terkirim',
        self::STATUS_SUDAH_TERKIRIM => 'Sudah Terkirim',
        self::STATUS_NO_USER => 'User / Pegawai Tidak Ditemukan',
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'upload_presensi_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_upload_presensi', 'badgenumber', 'checktime', 'checktype', 'SN'], 'required'],
            [['id_upload_presensi', 'checktype', 'status_kirim'], 'integer'],
            [['checktime', 'debug'], 'safe'],
            [['badgenumber', 'SN'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_upload_presensi' => 'Id Upload Presensi',
            'badgenumber' => 'Badgenumber',
            'checktime' => 'Checktime',
            'checktype' => 'Checktype',
            'SN' => 'Sn',
            'status_kirim' => 'Status Kirim',
        ];
    }

    /**
     * @inheritdoc
     * @return UploadPresensiLogQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UploadPresensiLogQuery(get_called_class());
    }

    public function getPegawai()
    {
        return $this->hasOne(Pegawai::class, ['nip' => 'badgenumber']);
    }

    public function getMesinAbsensi()
    {
        return $this->hasOne(MesinAbsensi::class, ['serialnumber' => 'SN']);
    }

    public function getIsStatusGagalKirim()
    {
        return (int) $this->status_kirim === self::STATUS_GAGAL_KIRIM;
    }

    public function getIsStatusTerkirim()
    {
        return (int) $this->status_kirim === self::STATUS_TERKIRIM;
    }

    public function getIsStatusSudahTerkirim()
    {
        return (int) $this->status_kirim === self::STATUS_SUDAH_TERKIRIM;
    }

    public function getIsStatusNoUser()
    {
        return (int) $this->status_kirim === self::STATUS_NO_USER;
    }

    public function getStatusKirim()
    {
        return ArrayHelper::getValue(static::getListStatusLog(), $this->status_kirim, 'N/A');
    }

    public static function getListStatusLog()
    {
        return static::$statusLog;
    }
}
