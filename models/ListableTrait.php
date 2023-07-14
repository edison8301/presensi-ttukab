<?php 

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

trait ListableTrait {

	public static function getList()
	{
		return ArrayHelper::map(static::find()->all(), 'id', 'nama');
	}
}