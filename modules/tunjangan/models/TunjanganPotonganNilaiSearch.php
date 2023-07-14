<?php

namespace app\modules\tunjangan\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\tunjangan\models\TunjanganPotonganNilai;

/**
 * TunjanganPotonganNilaiSearch represents the model behind the search form of `app\modules\tunjangan\models\TunjanganPotonganNilai`.
 */
class TunjanganPotonganNilaiSearch extends TunjanganPotonganNilai
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_tunjangan_potongan', 'tanggal_mulai', 'tanggal_selesai'], 'integer'],
            [['nilai'], 'number'],
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
        $query = TunjanganPotonganNilai::find();

        $this->load($params);

        // add conditions that should always apply here

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'id_tunjangan_potongan' => $this->id_tunjangan_potongan,
            'nilai' => $this->nilai,
            'tanggal_mulai' => $this->tanggal_mulai,
            'tanggal_selesai' => $this->tanggal_selesai,
        ]);

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
