<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TunjanganJabatanKomponen;

/**
 * TunjanganJabatanKomponenSearch represents the model behind the search form of `app\models\TunjanganJabatanKomponen`.
 */
class TunjanganJabatanKomponenSearch extends TunjanganJabatanKomponen
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_jabatan', 'id_tunjangan_komponen', 'status_aktif'], 'integer'],
            [['jumlah_tunjangan'], 'number'],
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
        $query = TunjanganJabatanKomponen::find();

        $this->load($params);

        // add conditions that should always apply here

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'id_jabatan' => $this->id_jabatan,
            'id_tunjangan_komponen' => $this->id_tunjangan_komponen,
            'jumlah_tunjangan' => $this->jumlah_tunjangan,
            'status_aktif' => $this->status_aktif,
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
