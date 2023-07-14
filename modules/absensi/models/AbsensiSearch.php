<?php

namespace app\modules\absensi\models;

use Yii;
use yii\base\Model;

/**
 * AbsensiSearch represents the model behind the search form about `backend\absensi\models\Absensi`.
 */
class AbsensiSearch extends Model
{
    /**
     * @inheritdoc
     */

    public $bulan;
    public $tahun;
    public $tanggal;

    public function rules()
    {
        return [
            [['bulan', 'tahun'], 'integer'],
            [['tanggal'], 'safe'],
        ];
    }

   
}
