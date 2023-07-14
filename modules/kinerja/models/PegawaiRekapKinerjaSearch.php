<?php

namespace app\modules\kinerja\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\kinerja\models\PegawaiRekapKinerja;

/**
 * PegawaiRekapKinerjaSearch represents the model behind the search form of `app\modules\kinerja\models\PegawaiRekapKinerja`.
 */
class PegawaiRekapKinerjaSearch extends PegawaiRekapKinerja
{
    public $nama_instansi;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_instansi'], 'integer'],
            [['bulan', 'tahun', 'waktu_buat', 'waktu_update', 'nama_instansi', 'id_instansi', 'progres', 'id_pegawai'], 'safe'],
            [['potongan_skp', 'potongan_ckhp', 'potongan_total'], 'number'],
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
        $query = PegawaiRekapKinerja::find()->joinWith(['pegawai', 'instansi']);

        $this->load($params);

        // add conditions that should always apply here
        if (empty($this->bulan)) {
            $this->bulan = date('m');
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'tahun' => $this->tahun,
            'potongan_skp' => $this->potongan_skp,
            'potongan_ckhp' => $this->potongan_ckhp,
            'potongan_total' => $this->potongan_total,
            'waktu_buat' => $this->waktu_buat,
            'waktu_update' => $this->waktu_update,
        ]);

        $query->andFilterWhere(['like', 'bulan', $this->bulan])
            ->andFilterWhere(['like', 'pegawai.nama', $this->id_pegawai])
            ->andFilterWhere(['like', 'instansi.nama', $this->nama_instansi])
            ->andFilterWhere(['like', 'progres', $this->progres]);

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
