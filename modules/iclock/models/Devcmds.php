<?php

namespace app\modules\iclock\models;

use Yii;

/**
 * This is the model class for table "devcmds".
 *
 * @property integer $id
 * @property string $SN_id
 * @property string $CmdContent
 * @property string $CmdCommitTime
 * @property string $CmdTransTime
 * @property string $CmdOverTime
 * @property integer $CmdReturn
 * @property integer $User_id
 *
 * @property Iclock $sN
 * @property AuthUser $user
 */
class Devcmds extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'devcmds';
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
            [['SN_id', 'CmdContent', 'CmdCommitTime'], 'required'],
            [['CmdContent'], 'string'],
            [['CmdCommitTime', 'CmdTransTime', 'CmdOverTime'], 'safe'],
            [['CmdReturn', 'User_id'], 'integer'],
            [['SN_id'], 'string', 'max' => 20],
            [['SN_id'], 'exist', 'skipOnError' => true, 'targetClass' => Iclock::className(), 'targetAttribute' => ['SN_id' => 'SN']],
            //[['User_id'], 'exist', 'skipOnError' => true, 'targetClass' => AuthUser::className(), 'targetAttribute' => ['User_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'SN_id' => 'Sn ID',
            'CmdContent' => 'Cmd Content',
            'CmdCommitTime' => 'Cmd Commit Time',
            'CmdTransTime' => 'Cmd Trans Time',
            'CmdOverTime' => 'Cmd Over Time',
            'CmdReturn' => 'Cmd Return',
            'User_id' => 'User ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSN()
    {
        return $this->hasOne(Iclock::className(), ['SN' => 'SN_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(AuthUser::className(), ['id' => 'User_id']);
    }
}
