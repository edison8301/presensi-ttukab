<?php

namespace app\modules\kinerja\models;

use app\components\Session;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\kinerja\models\KegiatanHarianDiskresi;

/**
 * KegiatanHarianDiskresiSearch represents the model behind the search form of `app\modules\kinerja\models\KegiatanHarianDiskresi`.
 */
class KegiatanHarianDiskresiSearch extends KegiatanHarianDiskresi
{
    public $tahun;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_pegawai'], 'integer'],
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

    public function getQuerySearch($params)
    {
        $query = KegiatanHarianDiskresi::find();

        $this->load($params);

        // add conditions that should always apply here
        if ($this->tahun == null) {
            $this->tahun = Session::getTahun();
        }

        $datetime = \DateTime::createFromFormat('Y-n', $this->tahun . '-12');

        $query->andWhere('tanggal >= :tanggal_awal AND tanggal <= :tanggal_akhir', [
            ':tanggal_awal' => $datetime->format('Y-01-01'),
            ':tanggal_akhir' => $datetime->format('Y-m-t'),
        ]);

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'id_pegawai' => $this->id_pegawai,
            'tanggal' => $this->tanggal,
        ]);

        $query->andFilterWhere(['like', 'keterangan', $this->keterangan]);

        return $query;
    }

    public function search($params)
    {
        $query = $this->getQuerySearch($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $dataProvider;
    }


}
