<?php

namespace app\modules\absensi\models;

use app\jobs\UploadPresensiJob;
use app\models\User;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\web\UploadedFile;

/**
 * This is the model class for table "upload_presensi".
 *
 * @property int $id
 * @property int $id_upload_presensi_remote
 * @property string $file
 * @property int $status
 * @property string $user_pengupload
 * @property string $waktu_diupload
 * @property string $SN [varchar(255)]
 *
 * @property int $id_queue [int(11)]
 * @property bool $isGagal
 * @property bool $isSelesai
 * @property mixed $isQueueProses
 * @property mixed $user
 * @property mixed $isQueueSelesai
 * @property bool $isProses
 * @property mixed $isQueueMenunggu
 * @property string $statusQueue
 */
class UploadPresensi extends \yii\db\ActiveRecord
{
    const STATUS_PROSES = 0;
    const STATUS_BERHASIL = 1;
    const STATUS_GAGAL = 2;

    /**
     * @var UploadedFile
     */
    public $fileInstance;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'upload_presensi';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['file','SN'], 'required'],
            [['status', 'id_queue', 'id_upload_presensi_remote'], 'integer'],
            [['waktu_diupload'], 'safe'],
            [['user_pengupload', 'file', 'SN'], 'string', 'max' => 255],
            ['fileInstance', 'file', 'checkExtensionByMimeType' => false,  'skipOnEmpty' => true, 'extensions' => ['dat']],
        ];
    }

    public function behaviors()
    {
        /** @noinspection PhpUndefinedFieldInspection */
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'waktu_diupload',
                'updatedAtAttribute' => false,
                'value' => date('Y-m-d H:i:s'),
            ],
            [
                'class' => BlameableBehavior::class,
                'createdByAttribute' => 'user_pengupload',
                'updatedByAttribute' => false,
                'value' => @Yii::$app->user->identity->username
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'file' => 'File',
            'status' => 'Status',
            'SN' => 'Kode Mesin',
            'user_pengupload' => 'User Pengupload',
            'waktu_diupload' => 'Waktu Diupload',
        ];
    }

    /**
     * @inheritdoc
     * @return UploadPresensiQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UploadPresensiQuery(static::class);
    }

    public function getManyUploadPresensiLog()
    {
        return $this->hasMany(UploadPresensiLog::class,['id_upload_presensi'=>'id']);
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_pengupload']);
    }

    protected function setFileName($token)
    {
        $this->file =  "{$this->fileInstance->baseName}_$token.{$this->fileInstance->extension}";
    }

    public function upload($token = null)
    {
        if ($token === null) {
            $token = time();
        }
        $this->setFileName($token);
        if ($this->fileInstance instanceof  UploadedFile && $this->validate()) {
            $this->fileInstance->saveAs("uploads/{$this->file}");
            return true;
        }
        return false;
    }

    public function afterSave($insert, $changedAttributes)
    {
        if ($insert === true) {
            $id = Yii::$app->queue->push(new UploadPresensiJob(['id' => $this->id, 'SN' => $this->SN, 'file' => $this->file]));
            $this->id_queue = $id;
            $this->save(false);
        }
        return parent::afterSave($insert, $changedAttributes);
    }

    public function getIsSelesai()
    {
        return $this->status === self::STATUS_BERHASIL;
    }

    public function getIsGagal()
    {
        return $this->status === self::STATUS_GAGAL;
    }

    public function getIsProses()
    {
        return $this->status === self::STATUS_PROSES;
    }

    public function getStatus()
    {
        if ($this->getIsSelesai()) {
            return 'Selesai';
        }

        if ($this->getIsGagal()) {
            return 'Gagal';
        }

        if ($this->getIsProses()) {
            return 'Proses';
        }

        return 'N/A';
    }

    public function getIsQueueMenunggu()
    {
        return Yii::$app->queue->isWaiting($this->id_queue);
    }

    public function getIsQueueProses()
    {
        return Yii::$app->queue->isReserved($this->id_queue);
    }

    public function getIsQueueSelesai()
    {
        return Yii::$app->queue->isDone($this->id_queue);
    }

    public function getStatusQueue()
    {
        if ($this->getIsQueueMenunggu()) {
            return 'Menunggu';
        }

        if ($this->getIsQueueProses()) {
            return 'Proses';
        }

        if ($this->getIsQueueSelesai()) {
            return 'Selesai';
        }

        return 'N/A';
    }

    public function getCountUploadPresensiLog()
    {
        return $this->getManyUploadPresensiLog()->count();
    }
}
