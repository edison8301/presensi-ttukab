<?php

namespace app\modules\tukin\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\tukin\models\Jabatan;

/**
 * JabatanSearch represents the model behind the search form of `app\modules\tukin\models\Jabatan`.
 */
class JabatanSearch extends Jabatan
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_jenis_jabatan', 'id_instansi', 'kelas_jabatan', 'persediaan_pegawai', 'jumlah_tetap'], 'integer'],
            [['bidang', 'subbidang', 'nama'], 'safe'],
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
        $query = Jabatan::find();

        $this->load($params);

        // add conditions that should always apply here
        if (User::isMapping()) {
            $query->andWhere(['in', 'id_instansi', \app\models\User::getListIdInstansi()]);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'id_instansi' => $this->id_instansi,
            'kelas_jabatan' => $this->kelas_jabatan,
            'persediaan_pegawai' => $this->persediaan_pegawai,
        ]);

        if (!empty($this->jumlah_tetap)) {
            if ((int) $this->jumlah_tetap === 1) {
                $query->andWhere(['status_jumlah_tetap' => 1]);
            } else {
                $query->andWhere(['status_jumlah_tetap' => 0]);
            }
        }

        if (!empty($this->id_jenis_jabatan)) {
            if ((int) $this->id_jenis_jabatan === 1) {
                $query->andWhere(['id_jenis_jabatan' => $this->id_jenis_jabatan]);
            } else {
                $query->andWhere(['!=', 'id_jenis_jabatan', 1]);
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
