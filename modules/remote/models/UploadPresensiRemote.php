<?php

namespace app\modules\remote\models;

use app\jobs\UploadPresensiJob;
use app\jobs\UploadPresensiRemoteJob;
use app\modules\absensi\models\MesinAbsensi;
use app\modules\absensi\models\UploadPresensi;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\httpclient\Client;
use yii\web\UploadedFile;

/**
 * This is the model class for table "upload_presensi_remote".
 *
 * @property int $id
 * @property int $id_queue
 * @property string $SN
 * @property string $file
 * @property int $status
 * @property string $user_pengupload
 * @property UploadPresensi $uploadPresensi
 * @property mixed $fileName
 * @property string $statusUpload
 * @property string $waktu_diupload
 */
class UploadPresensiRemote extends \yii\db\ActiveRecord
{
    /**
     * @var \yii\web\UploadedFile
     */
    public $fileInstance;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'upload_presensi_remote';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_queue', 'status'], 'integer'],
            [['file'], 'required'],
            [['waktu_diupload'], 'safe'],
            [['SN', 'file', 'user_pengupload'], 'string', 'max' => 255],
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
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_queue' => 'Id Queue',
            'SN' => 'Sn',
            'file' => 'File',
            'status' => 'Status',
            'user_pengupload' => 'User Pengupload',
            'waktu_diupload' => 'Waktu Diupload',
        ];
    }

    protected function setFileName($token)
    {
        $this->file =  "{$this->fileInstance->baseName}_$token.{$this->fileInstance->extension}";
    }

    public function getMesinAbsensi()
    {
        return $this->hasOne(MesinAbsensi::class, ['serialnumber' => 'SN']);
    }

    public function getInstansi()
    {
        return $this->hasOne(\app\models\Instansi::class, ['id' => 'id_instansi'])
            ->via('mesinAbsensi');
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

    public function getUploadPresensi()
    {
        return $this->hasOne(UploadPresensi::class, ['id_upload_presensi_remote' => 'id']);
    }

    public function getStatusUpload()
    {
        $client = new Client();
        $url = Yii::$app->params['targetRest'];
        $response = $client->createRequest()
            ->setMethod('GET')
            ->setUrl("$url?r=api/upload-presensi/view&id=" . $this->id_queue)
            ->send();
        if ($response->isOk) {
            $status = $response->data['status'];
            switch ($status) {
                case UploadPresensi::STATUS_BERHASIL:
                    return 'Berhasil';
                case UploadPresensi::STATUS_GAGAL:
                    return 'Gagal';
                case UploadPresensi::STATUS_PROSES:
                    return 'Proses';
            }
        } else {
            return 'Mengirim';
        }
    }

    public function afterSave($insert, $changedAttributes)
    {
        if ($insert === true) {
            $id = Yii::$app->queue->push(new UploadPresensiRemoteJob(['id' => $this->id]));
            $this->updateAttributes(['id_queue' => $id]);
        }
        return parent::afterSave($insert, $changedAttributes);
    }


}
