<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * JabatanSearch represents the model behind the search form of `app\modules\tukin\models\Jabatan`.
 */
class JabatanSearch extends Jabatan
{
    public $nama_induk;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_jenis_jabatan', 'id_instansi', 'kelas_jabatan', 'persediaan_pegawai',
                'jumlah_tetap'
            ], 'integer'],
            [['bidang', 'subbidang', 'nama'], 'safe'],
            [['status_kepala'],'safe'],
            [['id_induk'], 'safe'],
            [['id_eselon', 'id_tingkatan_fungsional'], 'safe'],
            [['nilai_jabatan'], 'integer'],
            [['nama_induk'], 'safe'],
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
     * @return JabatanQuery
     */

    public function getQuerySearch($params)
    {
        $query = Jabatan::find();

        $this->load($params);

        // add conditions that should always apply here
        if (User::isMapping()) {
            $query->andWhere(['in', 'id_instansi', User::getListIdInstansi()]);
        }

        if($this->nama_induk != null) {
            $arrayId = Jabatan::findArrayId(['nama'=>$this->nama_induk]);
            $query->andWhere([
                'id_induk' => $arrayId
            ]);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'id_instansi' => $this->id_instansi,
            'kelas_jabatan' => $this->kelas_jabatan,
            'persediaan_pegawai' => $this->persediaan_pegawai,
            'id_jenis_jabatan' => $this->id_jenis_jabatan,
            'status_kepala' => $this->status_kepala,
            'id_eselon' => $this->id_eselon,
            'id_tingkatan_fungsional' => $this->id_tingkatan_fungsional
        ]);

        if (!empty($this->jumlah_tetap)) {
            if ((int) $this->jumlah_tetap === 1) {
                $query->andWhere(['status_jumlah_tetap' => 1]);
            } else {
                $query->andWhere(['status_jumlah_tetap' => 0]);
            }
        }

        $query->andFilterWhere(['like', 'bidang', $this->bidang])
            ->andFilterWhere(['like', 'subbidang', $this->subbidang])
            ->andFilterWhere(['like', 'nama', $this->nama]);

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
