<?php

namespace app\modules\iclock\models;

use Yii;

/**
 * This is the model class for table "template".
 *
 * @property integer $templateid
 * @property integer $userid
 * @property string $Template
 * @property integer $FingerID
 * @property integer $Valid
 * @property integer $DelTag
 * @property string $SN
 * @property string $UTime
 * @property string $BITMAPPICTURE
 * @property string $BITMAPPICTURE2
 * @property string $BITMAPPICTURE3
 * @property string $BITMAPPICTURE4
 * @property integer $USETYPE
 * @property string $Template2
 * @property string $Template3
 *
 * @property Iclock $sN
 * @property Userinfo $user
 */
class Template extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */

    public $userinfo_name;
    public $userinfo_badgenumber;
    
    public static function tableName()
    {
        return 'template';
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
            [['userid', 'Template', 'FingerID', 'Valid', 'DelTag'], 'required'],
            [['userid', 'FingerID', 'Valid', 'DelTag', 'USETYPE'], 'integer'],
            [['Template', 'BITMAPPICTURE', 'BITMAPPICTURE2', 'BITMAPPICTURE3', 'BITMAPPICTURE4', 'Template2', 'Template3'], 'string'],
            [['UTime'], 'safe'],
            [['SN'], 'string', 'max' => 20],
            [['userid', 'FingerID'], 'unique', 'targetAttribute' => ['userid', 'FingerID'], 'message' => 'The combination of Userid and Finger ID has already been taken.'],
            [['userid', 'FingerID'], 'unique', 'targetAttribute' => ['userid', 'FingerID'], 'message' => 'The combination of Userid and Finger ID has already been taken.'],
            [['SN'], 'exist', 'skipOnError' => true, 'targetClass' => Iclock::className(), 'targetAttribute' => ['SN' => 'SN']],
            [['userid'], 'exist', 'skipOnError' => true, 'targetClass' => Userinfo::className(), 'targetAttribute' => ['userid' => 'userid']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'templateid' => 'Templateid',
            'userid' => 'Userid',
            'Template' => 'Template',
            'FingerID' => 'Finger ID',
            'Valid' => 'Valid',
            'DelTag' => 'Del Tag',
            'SN' => 'Sn',
            'UTime' => 'Utime',
            'BITMAPPICTURE' => 'Bitmappicture',
            'BITMAPPICTURE2' => 'Bitmappicture2',
            'BITMAPPICTURE3' => 'Bitmappicture3',
            'BITMAPPICTURE4' => 'Bitmappicture4',
            'USETYPE' => 'Usetype',
            'Template2' => 'Template2',
            'Template3' => 'Template3',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSN()
    {
        return $this->hasOne(Iclock::className(), ['SN' => 'SN']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserinfo()
    {
        return $this->hasOne(Userinfo::className(), ['userid' => 'userid']);
    }

    public function getMesinAbsensi()
    {
        return $this->hasOne(\app\modules\absensi\models\MesinAbsensi::className(), ['serialnumber' => 'SN']);
    }
}
