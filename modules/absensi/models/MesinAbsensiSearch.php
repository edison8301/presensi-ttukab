<?php

namespace app\modules\absensi\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\absensi\models\MesinAbsensi;

/**
 * MesinAbsensiSearch represents the model behind the search form of `app\modules\absensi\models\MesinAbsensi`.
 */
class MesinAbsensiSearch extends MesinAbsensi
{
    public $nama_instansi;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_instansi'], 'integer'],
            [['serialnumber'], 'safe'],
            [['nama_instansi'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */

    public function getQuerySearch($params)
    {
        $query = MesinAbsensi::find();
        $query->joinWith(['instansi']);

        $this->load($params);

        // add conditions that should always apply here
        $query->andFilterWhere(['like', 'instansi.nama', $this->nama_instansi]);

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'id_instansi' => $this->id_instansi,
        ]);

        $query->andFilterWhere(['like', 'serialnumber', $this->serialnumber]);

        return $query;
    }

    public function search($params)
    {
        $query = $this->getQuerySearch($params);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $dataProvider;
    }


}
