<?php

namespace app\modules\tukin\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\tukin\models\PegawaiVariabelObjektif;

/**
 * PegawaiVariabelObjektifSearch represents the model behind the search form of `app\modules\tukin\models\PegawaiVariabelObjektif`.
 */
class PegawaiVariabelObjektifSearch extends PegawaiVariabelObjektif
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_pegawai', 'id_ref_variabel_objektif', 'bulan_mulai', 'bulan_selesai'], 'integer'],
            [['tahun'], 'safe'],
            [['tarif'], 'number'],
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
        $query = PegawaiVariabelObjektif::find();

        $this->load($params);

        // add conditions that should always apply here

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'id_pegawai' => $this->id_pegawai,
            'id_ref_variabel_objektif' => $this->id_ref_variabel_objektif,
            'bulan_mulai' => $this->bulan_mulai,
            'bulan_selesai' => $this->bulan_selesai,
            'tahun' => $this->tahun,
            'tarif' => $this->tarif,
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
