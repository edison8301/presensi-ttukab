<?php

namespace app\modules\tunjangan\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\tunjangan\models\JabatanTunjanganKhusus;

/**
 * JabatanTunjanganKhususSearch represents the model behind the search form of `app\modules\tunjangan\models\JabatanTunjanganKhusus`.
 */
class JabatanTunjanganKhususSearch extends JabatanTunjanganKhusus
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_jabatan_tunjangan_khusus_jenis', 'id_jabatan_tunjangan_golongan'], 'integer'],
            [['besaran_tpp'], 'number'],
            [['tanggal_mulai', 'tanggal_selesai', 'keterangan'], 'safe'],
            [['kelas_jabatan', 'status_p3k'], 'integer'],
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
        $query = JabatanTunjanganKhusus::find();

        $this->load($params);

        // add conditions that should always apply here

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'id_jabatan_tunjangan_khusus_jenis' => $this->id_jabatan_tunjangan_khusus_jenis,
            'id_jabatan_tunjangan_golongan' => $this->id_jabatan_tunjangan_golongan,
            'besaran_tpp' => $this->besaran_tpp,
            'tanggal_mulai' => $this->tanggal_mulai,
            'tanggal_selesai' => $this->tanggal_selesai,
            'kelas_jabatan' => $this->kelas_jabatan,
            'status_p3k' => $this->status_p3k,
        ]);

        $query->andFilterWhere(['like', 'keterangan', $this->keterangan]);

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
