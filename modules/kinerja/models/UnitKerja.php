<?php

namespace app\modules\kinerja\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "unit_kerja".
 *
 * @property integer $id
 * @property string $unit_kerja
 */
class UnitKerja extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'unit_kerja';
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
            [['unit_kerja'], 'required'],
            [['unit_kerja'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'unit_kerja' => 'Unit Kerja',
        ];
    }

    public static function getList()
    {
        $query = self::find();

        return ArrayHelper::map($query->all(),'id','unit_kerja');
    }

    public function queryUser()
    {
        $query = \app\modules\kinerja\models\User::find();
        $query->andWhere(['unit_kerja'=>$this->id]);

        return $query;
    }

    public function countUser()
    {
        $query = $this->queryUser();

        return $query->count();
    }

    public function findAllUser()
    {
        $query = $this->queryUser();

        return $query->all();
    }

    public function getTunjanganKinerja()
    {
        return 0;
    }

    public function getTunjanganAbsensi()
    {
        return 0;
    }

    public function getTunjanganTotal()
    {
        return 0;
    }
}
