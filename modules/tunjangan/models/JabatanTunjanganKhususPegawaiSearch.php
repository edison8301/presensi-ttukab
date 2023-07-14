<?php

namespace app\modules\tunjangan\models;

use app\components\Session;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\tunjangan\models\JabatanTunjanganKhususPegawai;

/**
 * JabatanTunjanganKhususPegawaiSearch represents the model behind the search form of `app\modules\tunjangan\models\JabatanTunjanganKhususPegawai`.
 */
class JabatanTunjanganKhususPegawaiSearch extends JabatanTunjanganKhususPegawai
{
    public $nama_pegawai;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_pegawai', 'id_instansi', 'id_jabatan_tunjangan_khusus_jenis'], 'integer'],
            [['tanggal_mulai', 'tanggal_selesai', 'keterangan'], 'safe'],
            [['nama_pegawai'], 'safe']
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
     * @return JabatanTunjanganKhususPegawaiQuery
     */

    public function getQuerySearch($params)
    {
        $this->load($params);

        $query = JabatanTunjanganKhususPegawai::find();
        $query->with(['pegawai','jabatanTunjanganKhususJenis']);

        if($this->nama_pegawai != null) {
            $query->joinWith(['pegawai']);
            $query->andFilterWhere(['like', 'pegawai.nama', $this->nama_pegawai]);
        }

        if(Session::isInstansi()) {
            $this->id_instansi = Session::getIdInstansi();
        }

        if(Session::isAdminInstansi()) {
            $this->id_instansi = Session::getIdInstansi();
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'id_pegawai' => $this->id_pegawai,
            'id_instansi' => $this->id_instansi,
            'tanggal_mulai' => $this->tanggal_mulai,
            'tanggal_selesai' => $this->tanggal_selesai,
            'id_jabatan_tunjangan_khusus_jenis' => $this->id_jabatan_tunjangan_khusus_jenis
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
