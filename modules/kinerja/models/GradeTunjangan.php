<?php

namespace app\modules\kinerja\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "grade_tunjangan".
 *
 * @property integer $id
 * @property string $grade
 * @property integer $tunjangan
 */
class GradeTunjangan extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'grade_tunjangan';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['grade', 'tunjangan'], 'required'],
            [['tunjangan'], 'integer'],
            [['grade'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'grade' => 'Grade',
            'tunjangan' => 'Tunjangan',
        ];
    }

    public static function getList()
    {
        return ArrayHelper::map(self::find()->all(),'grade','tunjangan');
    }

    public function getTunjangan()
    {
        return number_format($this->tunjangan,0,'.',',');
    }
}
