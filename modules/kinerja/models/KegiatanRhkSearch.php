<?php

namespace app\modules\kinerja\models;

use app\components\Session;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\kinerja\models\KegiatanRhk;

/**
 * KegiatanRhkSearch represents the model behind the search form of `app\modules\kinerja\models\KegiatanRhk`.
 */
class KegiatanRhkSearch extends KegiatanRhk
{
    public $nomor_skp;
    public $id_kegiatan_status;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_pegawai', 'id_instansi_pegawai', 'id_kegiatan_rhk_jenis',  'id_kegiatan_rhk_atasan'], 'integer'],
            [['tahun', 'nama'], 'safe'],
            [['nomor_skp', 'id_kegiatan_status'], 'safe'],
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
     * @return \yii\db\ActiveQuery
     */

    public function getQuerySearch($params)
    {
        $query = KegiatanRhk::find();
        $query->joinWith(['instansiPegawaiSkp']);

        $this->load($params);

        // add conditions that should always apply here
        if (Session::isPegawai()) {
            $this->id_pegawai = Session::getIdPegawai();
        }

        if ($this->tahun == null) {
            $this->tahun = Session::getTahun();
        }

        if ($this->nomor_skp != null) {
            $query->joinWith(['instansiPegawaiSkp']);
            $query->andWhere(['instansi_pegawai_skp.nomor' => $this->nomor_skp]);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'kegiatan_rhk.id' => $this->id,
            'kegiatan_rhk.tahun' => $this->tahun,
            'kegiatan_rhk.id_pegawai' => $this->id_pegawai,
            'kegiatan_rhk.id_instansi_pegawai' => $this->id_instansi_pegawai,
            'kegiatan_rhk.id_kegiatan_rhk_jenis' => $this->id_kegiatan_rhk_jenis,
            'kegiatan_rhk.id_kegiatan_rhk_atasan' => $this->id_kegiatan_rhk_atasan,
        ]);

        $query->andFilterWhere(['like', 'nama', $this->nama]);

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

    public function setNomorSkp()
    {
        if (Session::isPegawai()) {
            $listInstansiPegawai = InstansiPegawaiSkp::getList([
                'id_pegawai' => Session::getIdPegawai(),
            ]);

            $this->nomor_skp = array_shift($listInstansiPegawai);
        }
    }


}
