<?php

namespace app\modules\absensi\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\absensi\models\HariLibur;
use app\models\User;

/**
 * HariLiburSearch represents the model behind the search form about `app\modules\absensi\models\HariLibur`.
 */
class HariLiburSearch extends HariLibur
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['tanggal', 'keterangan'], 'safe'],
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
    public function search($params)
    {
        $this->load($params);

        $query = HariLibur::find();

        $query->andWhere('tanggal >= :tanggal_awal AND tanggal <= :tanggal_akhir',[
            ':tanggal_awal'=>User::getTahun().'-01-01',
            ':tanggal_akhir'=>User::getTahun().'-12-31'
        ]);

        $query->andFilterWhere([
            'id' => $this->id,
            'tanggal' => $this->tanggal,
        ]);


        $query->andFilterWhere(['like', 'keterangan', $this->keterangan]);

        $query->orderBy(['tanggal'=>SORT_ASC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);


        return $dataProvider;
    }
}
