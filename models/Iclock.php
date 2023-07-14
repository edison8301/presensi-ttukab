<?php

namespace app\models;

use app\modules\absensi\models\MesinAbsensi;
use Yii;

/**
 * This is the model class for table "iclock".
 *
 * @property string $SN
 * @property int $State
 * @property string $LastActivity
 * @property string $TransTimes
 * @property int $TransInterval
 * @property string $LogStamp
 * @property string $OpLogStamp
 * @property string $PhotoStamp
 * @property string $Alias
 * @property int $DeptID
 * @property string $UpdateDB
 * @property string $Style
 * @property string $FWVersion
 * @property int $FPCount
 * @property int $TransactionCount
 * @property int $UserCount
 * @property string $MainTime
 * @property int $MaxFingerCount
 * @property int $MaxAttLogCount
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
 * @property int $AccFun
 * @property int $TZAdj
 * @property int $DelTag
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
    const DEV_STATUS_PAUSE = 0;
    const DEV_STATUS_OK = 1;
    const DEV_STATUS_TRANS = 2;
    const DEV_STATUS_OFFLINE = 3;

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
            [['SN'], 'unique'],
            [['DeptID'], 'exist', 'skipOnError' => true, 'targetClass' => Departments::className(), 'targetAttribute' => ['DeptID' => 'DeptID']],
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
    public function getTemplates()
    {
        return $this->hasMany(Template::className(), ['SN' => 'SN']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserinfos()
    {
        return $this->hasMany(Userinfo::className(), ['SN' => 'SN']);
    }

    public function getDevcmdsAktif()
    {
        return $this
            ->getDevcmds()
            ->andWhere([
                'CmdReturn' => null,
                'CmdOverTime' => null
            ])
            ->all();
    }

    public function getState()
    {
        if ($this->state === self::DEV_STATUS_PAUSE) {
            return $this->state;
        }
        if ($this->LastActivity === null or (date('Y-m-d H:i:s') - $this->LastActivity) > 300) {
            return self::DEV_STATUS_OFFLINE;
        }
        if ($this->getDevcmdsAktif() !== []) {
            return self::DEV_STATUS_TRANS;
        }
        return self::DEV_STATUS_OK;
    }

    public static function getList()
    {
        $query = self::find();

        $list = [];
        foreach($query->all() as $data) {
            $list[$data->SN] = $data->SN.' - '.$data->getNamaInstansi();
        }

        return $list;
    }

    public function getMesinAbsensi()
    {
        return $this->hasOne(MesinAbsensi::class,['serialnumber'=>'SN']);
    }

    public function getNamaInstansi()
    {
        return @$this->mesinAbsensi->instansi->nama;
    }
}
