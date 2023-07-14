<?php

namespace app\modules\absensi\models;

use app\components\Session;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\absensi\models\HukumanDisiplin;

/**
 * HukumanDisiplinSearch represents the model behind the search form of `app\modules\absensi\models\HukumanDisiplin`.
 */
class HukumanDisiplinSearch extends HukumanDisiplin
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_pegawai', 'id_hukuman_disiplin_jenis', 'status_hapus', 'bulan'], 'integer'],
            [['tahun', 'keterangan', 'waktu_dihapus'], 'safe'],
            [['tanggal_mulai','tanggal_selesai'], 'safe'],
            [['id_instansi'], 'integer']
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
     * @return HukumanDisiplinQuery
     */

    public function getQuerySearch($params)
    {
        $this->load($params);

        $query = HukumanDisiplin::find();

        if(Session::isAdminInstansi()) {
            $this->id_instansi = Session::getIdInstansi();
        }

        if(Session::isInstansi()) {
            $this->id_instansi = Session::getIdInstansi();
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'id_pegawai' => $this->id_pegawai,
            'id_hukuman_disiplin_jenis' => $this->id_hukuman_disiplin_jenis,
            'id_instansi' => $this->id_instansi,
            'bulan' => $this->bulan,
            'tahun' => $this->tahun,
            'status_hapus' => $this->status_hapus,
            'waktu_dihapus' => $this->waktu_dihapus,
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
