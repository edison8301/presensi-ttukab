<?php

namespace app\modules\tukin\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "ref_variabel_objektif".
 *
 * @property int $id
 * @property string $kode
 * @property string $kelompok
 * @property string $uraian
 * @property int $satuan
 * @property double $tarif
 */
class RefVariabelObjektif extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ref_variabel_objektif';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['kode', 'kelompok', 'uraian', 'satuan', 'tarif'], 'required'],
            [['tarif'], 'number'],
            [['kode', 'kelompok', 'uraian', 'satuan'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'kode' => 'Kode',
            'kelompok' => 'Kelompok',
            'uraian' => 'Uraian',
            'satuan' => 'Satuan',
            'tarif' => 'Tarif',
        ];
    }

    /**
     * {@inheritdoc}
     * @return RefVariabelObjektifQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new RefVariabelObjektifQuery(get_called_class());
    }

    public static function getList()
    {
        return ArrayHelper::map(static::find()->all(), 'id', function (self $self) {
            return $self->uraian . ' - ' . Yii::$app->formatter->asInteger($self->tarif);
        });
    }
}
