<?php

namespace app\modules\kinerja\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\kinerja\models\PegawaiSkp;

/**
 * PegawaiSkpSearch represents the model behind the search form of `app\modules\kinerja\models\PegawaiSkp`.
 */
class PegawaiSkpSearch extends PegawaiSkp
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_instansi_pegawai', 'id_pegawai', 'id_instansi', 'id_jabatan', 'id_golongan', 'id_eselon', 'urutan', 'id_atasan', 'status_hapus'], 'integer'],
            [['nomor', 'tahun', 'tanggal_berlaku'], 'safe'],
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
        $query = PegawaiSkp::find();

        $this->load($params);

        // add conditions that should always apply here

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'id_instansi_pegawai' => $this->id_instansi_pegawai,
            'id_pegawai' => $this->id_pegawai,
            'id_instansi' => $this->id_instansi,
            'id_jabatan' => $this->id_jabatan,
            'id_golongan' => $this->id_golongan,
            'id_eselon' => $this->id_eselon,
            'urutan' => $this->urutan,
            'tahun' => $this->tahun,
            'id_atasan' => $this->id_atasan,
            'tanggal_berlaku' => $this->tanggal_berlaku,
            'status_hapus' => $this->status_hapus,
        ]);

        $query->andFilterWhere(['like', 'nomor', $this->nomor]);

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
