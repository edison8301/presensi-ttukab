<?php

namespace app\modules\kinerja\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\kinerja\models\JadwalKegiatanBulan;

/**
 * JadwalKegiatanBulanSearch represents the model behind the search form of `app\modules\kinerja\models\JadwalKegiatanBulan`.
 */
class JadwalKegiatanBulanSearch extends JadwalKegiatanBulan
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'bulan'], 'integer'],
            [['tahun', 'tanggal'], 'safe'],
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
        $query = JadwalKegiatanBulan::find();

        $this->load($params);

        // add conditions that should always apply here

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'tahun' => $this->tahun,
            'bulan' => $this->bulan,
            'tanggal' => $this->tanggal,
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
