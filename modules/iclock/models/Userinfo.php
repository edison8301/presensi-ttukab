<?php

namespace app\modules\iclock\models;

use Yii;
use yii\helpers\StringHelper;

/**
 * This is the model class for table "userinfo".
 *
 * @property integer $userid
 * @property string $badgenumber
 * @property integer $defaultdeptid
 * @property string $name
 * @property string $Password
 * @property string $Card
 * @property integer $Privilege
 * @property integer $AccGroup
 * @property string $TimeZones
 * @property string $Gender
 * @property string $Birthday
 * @property string $street
 * @property string $zip
 * @property string $ophone
 * @property string $FPHONE
 * @property string $pager
 * @property string $minzu
 * @property string $title
 * @property string $SN
 * @property string $SSN
 * @property string $UTime
 * @property string $State
 * @property string $City
 * @property integer $SECURITYFLAGS
 * @property integer $DelTag
 * @property integer $RegisterOT
 * @property integer $AutoSchPlan
 * @property integer $MinAutoSchInterval
 * @property integer $Image_id
 *
 * @property Checkinout[] $checkinouts
 * @property Template[] $templates
 * @property Departments $defaultdept
 * @property Template $manyTemplate
 * @property mixed $templatesCount
 * @property \yii\db\ActiveQuery $sN
 * @property \yii\db\ActiveQuery $manyCheckinout
 * @property mixed $checkinoutCount
 */
class Userinfo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'userinfo';
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
            [['badgenumber', 'name', 'DelTag'], 'required'],
            [['defaultdeptid', 'Privilege', 'AccGroup', 'SECURITYFLAGS', 'DelTag', 'RegisterOT', 'AutoSchPlan', 'MinAutoSchInterval', 'Image_id'], 'integer'],
            [['Birthday', 'UTime'], 'safe'],
            [['badgenumber', 'Password', 'Card', 'TimeZones', 'ophone', 'FPHONE', 'pager', 'title', 'SN', 'SSN'], 'string', 'max' => 20],
            [['name', 'street'], 'string', 'max' => 40],
            [['Gender', 'State', 'City'], 'string', 'max' => 2],
            [['zip'], 'string', 'max' => 6],
            [['minzu'], 'string', 'max' => 8],
            //[['defaultdeptid'], 'exist', 'skipOnError' => true, 'targetClass' => Departments::className(), 'targetAttribute' => ['defaultdeptid' => 'DeptID']],
            [['SN'], 'exist', 'skipOnError' => true, 'targetClass' => Iclock::className(), 'targetAttribute' => ['SN' => 'SN']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'userid' => 'Userid',
            'badgenumber' => 'NIP',
            'defaultdeptid' => 'Defaultdeptid',
            'name' => 'Nama',
            'Password' => 'Password',
            'Card' => 'Card',
            'Privilege' => 'Privilege',
            'AccGroup' => 'Acc Group',
            'TimeZones' => 'Time Zones',
            'Gender' => 'Gender',
            'Birthday' => 'Birthday',
            'street' => 'Street',
            'zip' => 'Zip',
            'ophone' => 'Ophone',
            'FPHONE' => 'Fphone',
            'pager' => 'Pager',
            'minzu' => 'Minzu',
            'title' => 'Title',
            'SN' => 'SN',
            'SSN' => 'Ssn',
            'UTime' => 'Utime',
            'State' => 'State',
            'City' => 'City',
            'SECURITYFLAGS' => 'Securityflags',
            'DelTag' => 'Del Tag',
            'RegisterOT' => 'Register Ot',
            'AutoSchPlan' => 'Auto Sch Plan',
            'MinAutoSchInterval' => 'Min Auto Sch Interval',
            'Image_id' => 'Image ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getManyCheckinout()
    {
        return $this->hasMany(Checkinout::className(), ['userid' => 'userid']);
    }

    public function getCheckinoutCount()
    {
        return count($this->manyCheckinout);
    }

    public function getManyTemplate()
    {
        return $this->hasMany(Template::className(), ['userid' => 'userid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTemplates()
    {
        return $this->hasMany(Template::className(), ['userid' => 'userid']);
    }

    public function getTemplatesCount()
    {
        return count($this->templates);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDefaultdept()
    {
        return $this->hasOne(Departments::className(), ['DeptID' => 'defaultdeptid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSN()
    {
        return $this->hasOne(Iclock::className(), ['SN' => 'SN']);
    }

    public function countCheckinout()
    {
        $query = $this->getManyCheckinout();
        return $query->count();
    }

    public function countTemplate()
    {
        $query = $this->getManyTemplate();
        return $query->count();
    }

    public function sendToDevice($sn, $sendFp = true)
    {
        $cmdContent = sprintf("DATA USER PIN=%s\tName=%s\tPri=%d\tCard=170601044\tTZ=0\tGrp=1", $this->badgenumber, $this->name, $this->Privilege);
        $cmd = new \app\modules\iclock\models\Devcmds([
                'SN_id' => $sn,
                'CmdContent' => $cmdContent,
                'CmdCommitTime' => date('Y-m-d H:i:s'),
            ]);
        $cmd->save();
        if ($sendFp === true) {
            foreach ($this->templates as $template) {
                $cmdContent = sprintf("DATA FP PIN=%s\tFID=%d\tSize=%d\tValid=%d\tTMP=%s", $this->badgenumber, $template->FingerID, StringHelper::byteLength($template->Template), $template->Valid, $template->Template);
                $cmd = new \app\modules\iclock\models\Devcmds([
                    'SN_id' => $sn,
                    'CmdContent' => $cmdContent,
                    'CmdCommitTime' => date('Y-m-d H:i:s'),
                ]);
                $cmd->save();
            }
        }
    }
}
