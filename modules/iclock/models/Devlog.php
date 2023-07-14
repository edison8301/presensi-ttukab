<?php

namespace app\modules\iclock\models;

use Yii;

/**
 * This is the model class for table "devlog".
 *
 * @property integer $id
 * @property string $SN_id
 * @property string $OP
 * @property string $Object
 * @property integer $Cnt
 * @property integer $ECnt
 * @property string $OpTime
 *
 * @property Iclock $sN
 */
class Devlog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'devlog';
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
            [['SN_id', 'OP', 'Cnt', 'ECnt', 'OpTime'], 'required'],
            [['Cnt', 'ECnt'], 'integer'],
            [['OpTime'], 'safe'],
            [['SN_id', 'Object'], 'string', 'max' => 20],
            [['OP'], 'string', 'max' => 8],
            [['SN_id'], 'exist', 'skipOnError' => true, 'targetClass' => Iclock::className(), 'targetAttribute' => ['SN_id' => 'SN']],
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
            'OP' => 'Op',
            'Object' => 'Object',
            'Cnt' => 'Cnt',
            'ECnt' => 'Ecnt',
            'OpTime' => 'Op Time',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSN()
    {
        return $this->hasOne(Iclock::className(), ['SN' => 'SN_id']);
    }
}
