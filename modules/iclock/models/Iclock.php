<?php

namespace app\modules\iclock\models;

use Yii;

/**
 * This is the model class for table "iclock".
 *
 * @property string $SN
 * @property integer $State
 * @property string $LastActivity
 * @property string $TransTimes
 * @property integer $TransInterval
 * @property string $LogStamp
 * @property string $OpLogStamp
 * @property string $PhotoStamp
 * @property string $Alias
 * @property integer $DeptID
 * @property string $UpdateDB
 * @property string $Style
 * @property string $FWVersion
 * @property integer $FPCount
 * @property integer $TransactionCount
 * @property integer $UserCount
 * @property string $MainTime
 * @property integer $MaxFingerCount
 * @property integer $MaxAttLogCount
 * @property string $DeviceName
 * @property string $AlgVer
 * @property string $FlashSize
 * @property string $FreeFlashSize
 * @property string $Language
 * @property string $VOLUME
 * @property string $DtFmt
 * @property string $IPAddress
 * @property string $IsTFT
 * @property string $Platform
 * @property string $Brightness
 * @property string $BackupDev
 * @property string $OEMVendor
 * @property string $City
 * @property integer $AccFun
 * @property integer $TZAdj
 * @property integer $DelTag
 * @property string $FPVersion
 * @property string $PushVersion
 *
 * @property Checkinout[] $checkinouts
 * @property Devcmds[] $devcmds
 * @property Devlog[] $devlogs
 * @property Departments $dept
 * @property IclockIclockmsg[] $iclockIclockmsgs
 * @property IclockOplog[] $iclockOplogs
 * @property Template[] $templates
 * @property Userinfo[] $userinfos
 */
class Iclock extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'iclock';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_iclock');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['SN', 'State', 'TransInterval', 'Alias', 'UpdateDB', 'AccFun', 'TZAdj', 'DelTag'], 'required'],
            [['State', 'TransInterval', 'DeptID', 'FPCount', 'TransactionCount', 'UserCount', 'MaxFingerCount', 'MaxAttLogCount', 'AccFun', 'TZAdj', 'DelTag'], 'integer'],
            [['LastActivity'], 'safe'],
            [['SN', 'LogStamp', 'OpLogStamp', 'PhotoStamp', 'Alias', 'Style', 'MainTime', 'IPAddress', 'Platform'], 'string', 'max' => 20],
            [['TransTimes', 'City'], 'string', 'max' => 50],
            [['UpdateDB', 'FlashSize', 'FreeFlashSize', 'VOLUME', 'DtFmt', 'FPVersion', 'PushVersion'], 'string', 'max' => 10],
            [['FWVersion', 'DeviceName', 'AlgVer', 'Language', 'BackupDev', 'OEMVendor'], 'string', 'max' => 30],
            [['IsTFT', 'Brightness'], 'string', 'max' => 5],
            //[['DeptID'], 'exist', 'skipOnError' => true, 'targetClass' => Departments::className(), 'targetAttribute' => ['DeptID' => 'DeptID']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'SN' => 'Sn',
            'State' => 'State',
            'LastActivity' => 'Last Activity',
            'TransTimes' => 'Trans Times',
            'TransInterval' => 'Trans Interval',
            'LogStamp' => 'Log Stamp',
            'OpLogStamp' => 'Op Log Stamp',
            'PhotoStamp' => 'Photo Stamp',
            'Alias' => 'Alias',
            'DeptID' => 'Dept ID',
            'UpdateDB' => 'Update Db',
            'Style' => 'Style',
            'FWVersion' => 'Fwversion',
            'FPCount' => 'Fpcount',
            'TransactionCount' => 'Transaction Count',
            'UserCount' => 'User Count',
            'MainTime' => 'Main Time',
            'MaxFingerCount' => 'Max Finger Count',
            'MaxAttLogCount' => 'Max Att Log Count',
            'DeviceName' => 'Device Name',
            'AlgVer' => 'Alg Ver',
            'FlashSize' => 'Flash Size',
            'FreeFlashSize' => 'Free Flash Size',
            'Language' => 'Language',
            'VOLUME' => 'Volume',
            'DtFmt' => 'Dt Fmt',
            'IPAddress' => 'Ipaddress',
            'IsTFT' => 'Is Tft',
            'Platform' => 'Platform',
            'Brightness' => 'Brightness',
            'BackupDev' => 'Backup Dev',
            'OEMVendor' => 'Oemvendor',
            'City' => 'City',
            'AccFun' => 'Acc Fun',
            'TZAdj' => 'Tzadj',
            'DelTag' => 'Del Tag',
            'FPVersion' => 'Fpversion',
            'PushVersion' => 'Push Version',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCheckinouts()
    {
        return $this->hasMany(Checkinout::className(), ['SN' => 'SN']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDevcmds()
    {
        return $this->hasMany(Devcmds::className(), ['SN_id' => 'SN']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDevlogs()
    {
        return $this->hasMany(Devlog::className(), ['SN_id' => 'SN']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDept()
    {
        return $this->hasOne(Departments::className(), ['DeptID' => 'DeptID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIclockIclockmsgs()
    {
        return $this->hasMany(IclockIclockmsg::className(), ['SN_id' => 'SN']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIclockOplogs()
    {
        return $this->hasMany(IclockOplog::className(), ['SN' => 'SN']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getManyTemplate()
    {
        return $this->hasMany(Template::className(), ['SN' => 'SN']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getManyUserinfo()
    {
        return $this->hasMany(Userinfo::className(), ['SN' => 'SN']);
    }

    public function getManyPegawaiUserinfo()
    {
        return $this->hasMany(\app\models\Pegawai::className(), ['nip' => 'badgenumber'])
            ->via('manyUserinfo');
    }

    public function getMesinAbsensi()
    {
        return $this->hasOne(\app\modules\absensi\models\MesinAbsensi::className(), ['serialnumber' => 'SN']);
    }

    public function getInstansi()
    {
        return $this->hasOne(\app\models\Instansi::className(), ['id' => 'id_instansi'])
            ->via('mesinAbsensi');
    }

    public function getManyPegawaiInstansi()
    {
        return $this->hasMany(\app\models\Pegawai::className(), ['id_instansi' => 'id'])
            ->via('instansi');
    }

    public function countUserinfo()
    {
        $query = $this->getManyUserinfo();
        return $query->count();
    }

    public function countPegawaiUserinfo()
    {
        $query = $this->getManyPegawaiUserinfo();
        return $query->count();
    }


    public function countPegawaiInstansi()
    {
        $query = $this->getManyPegawaiInstansi();
        return $query->count();
    }

    /**
     * Mencari semua mesin dimana mesinnya yang bukan mesin development
     * atau bukan dari Instansi terkait
     * @see params['snException']
     */
    public static function findAllMesinBabel()
    {
        return static::find()
            // ->andWhere()
            ->andWhere([
                'and',
                ['not in', 'SN', Yii::$app->params['snException']],
                ['DelTag' => 0]
            ])
            ->all();
    }
}
