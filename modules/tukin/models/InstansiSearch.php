<?php

namespace app\modules\tukin\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\tukin\models\Instansi;

/**
 * InstansiSearch represents the model behind the search form of `app\modules\tukin\models\Instansi`.
 */
class InstansiSearch extends Instansi
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_induk', 'id_instansi_jenis'], 'integer'],
            [['nama', 'singkatan', 'alamat', 'telepon', 'email'], 'safe'],
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
        $query = Instansi::find();

        $this->load($params);

        // add conditions that should always apply here
        if (User::isMapping()) {
            $query->andWhere(['in', 'id', \app\models\User::getListIdInstansi()]);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'id_induk' => $this->id_induk,
            'id_instansi_jenis' => $this->id_instansi_jenis,
        ]);

        $query->andFilterWhere(['like', 'nama', $this->nama])
            ->andFilterWhere(['like', 'singkatan', $this->singkatan])
            ->andFilterWhere(['like', 'alamat', $this->alamat])
            ->andFilterWhere(['like', 'telepon', $this->telepon])
            ->andFilterWhere(['like', 'email', $this->email]);

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
