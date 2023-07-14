<?php

namespace app\modules\tukin\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\tukin\models\InstansiSerapanAnggaran;

/**
 * InstansiSerapanAnggaranSearch represents the model behind the search form of `app\modules\tukin\models\InstansiSerapanAnggaran`.
 */
class InstansiSerapanAnggaranSearch extends InstansiSerapanAnggaran
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_instansi', 'bulan'], 'integer'],
            [['tahun'], 'safe'],
            [['target', 'realisasi'], 'number'],
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
        $query = InstansiSerapanAnggaran::find();

        $this->load($params);

        // add conditions that should always apply here

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'id_instansi' => $this->id_instansi,
            'tahun' => $this->tahun,
            'bulan' => $this->bulan,
            'target' => $this->target,
            'realisasi' => $this->realisasi,
        ]);

        return $query;
    }

    public function search($params)
    {
        $query = $this->getQuerySearch($params);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        return $dataProvider;
    }


}
