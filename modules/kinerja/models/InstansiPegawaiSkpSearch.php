<?php

namespace app\modules\kinerja\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\kinerja\models\InstansiPegawaiSkp;
use app\models\User;

/**
 * InstansiPegawaiSkpSearch represents the model behind the search form of `app\modules\kinerja\models\InstansiPegawaiSkp`.
 */
class InstansiPegawaiSkpSearch extends InstansiPegawaiSkp
{
    const SCENARIO_PEGAWAI = 'pegawai';
    const SCENARIO_BAWAHAN = 'bawahan';
    const SCENARIO_ATASAN =  'atasan';

    public $id_pegawai;
    public $nama_pegawai;
    public $nama_instansi;
    public $nama_jabatan;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_instansi_pegawai', 'urutan', 'status_hapus', 'id_user_hapus'], 'integer'],
            [['tahun', 'nomor', 'waktu_hapus','nama_pegawai','nama_instansi',
                'nama_jabatan','tanggal_mulai','tanggal_selesai','id_pegawai'
            ], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        /**
         * bypass scenarios() implementation in the parent class
         * implements self scenario rules
         */
        $scenarios = Model::scenarios();
        $scenarios[self::SCENARIO_PEGAWAI] = $scenarios[self::SCENARIO_DEFAULT];
        $scenarios[self::SCENARIO_BAWAHAN] = $scenarios[self::SCENARIO_DEFAULT];
        $scenarios[self::SCENARIO_ATASAN] = $scenarios[self::SCENARIO_DEFAULT];
        return $scenarios;
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
        $this->load($params);

        $query = InstansiPegawaiSkp::find();
        $query->joinWith(['pegawai']);
        $query->with(['pegawai','instansiPegawai']);

        if (User::isPegawai() AND $this->scenario == self::SCENARIO_PEGAWAI) {
            $query->andWhere([
                'instansi_pegawai.id_pegawai' => User::getIdPegawai()
            ]);
        }

        if (User::isPegawai() AND $this->scenario == self::SCENARIO_ATASAN) {
            $query->joinWith(['instansiPegawai.jabatan']);
            $query->andWhere(['jabatan.id_induk' => User::getIdJabatanBerlaku()]);
        }

        if ($this->id_pegawai !== null) {
            $query->andWhere([
                'instansi_pegawai.id_pegawai' => $this->id_pegawai
            ]);
        }

        $query->andFilterWhere([
            'instansi_pegawai_skp.id' => $this->id,
            'instansi_pegawai_skp.id_instansi_pegawai' => $this->id_instansi_pegawai,
            'instansi_pegawai_skp.tahun' => $this->tahun,
            'instansi_pegawai_skp.urutan' => $this->urutan,
            'instansi_pegawai_skp.status_hapus' => $this->status_hapus,
            'instansi_pegawai_skp.waktu_hapus' => $this->waktu_hapus,
            'instansi_pegawai_skp.id_user_hapus' => $this->id_user_hapus,
        ]);

        $query->andFilterWhere(['like', 'instansi_pegawai_skp.nomor', $this->nomor]);
        $query->andFilterWhere(['like', 'instansi_pegawai_skp.tanggal_mulai', $this->tanggal_mulai]);
        $query->andFilterWhere(['like', 'instansi_pegawai_skp.tanggal_selesai', $this->tanggal_selesai]);
        $query->andFilterWhere(['like', 'pegawai.nama', $this->nama_pegawai]);
        $query->andFilterWhere(['like', 'instansi_pegawai.nama_jabatan',$this->nama_jabatan]);

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
