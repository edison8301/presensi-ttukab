<?php

namespace app\modules\tukin\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * PegawaiSearch represents the model behind the search form of `app\modules\tukin\models\Pegawai`.
 */
class PegawaiSearch extends Pegawai
{
    public $kelas_jabatan;
    public $bulan;
    /**
     * @var Instansi
     */
    public $searchInstansi;
    /**
     * @var string nama instansi
     */
    public $namaInstansi;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'bulan', 'id_instansi', 'id_jabatan', 'id_atasan', 'id_golongan', 'id_instansi_pegawai_bak', 'status_batas_pengajuan', 'id_eselon', 'id_pegawai_status', 'jumlah_userinfo', 'jumlah_checkinout', 'kelas_jabatan'], 'integer'],
            [['namaInstansi'], 'string'],
            [['nama', 'nip', 'nama_jabatan', 'gender', 'tempat_lahir', 'tanggal_lahir', 'alamat', 'telepon', 'email', 'foto', 'grade', 'gelar_depan', 'gelar_belakang', 'eselon_bak', 'status_hapus'], 'safe'],
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
        $query = Pegawai::find()->aktif();

        $this->load($params);

        if (!empty($this->kelas_jabatan)) {
            $query->joinWith('jabatan');
            $query->andWhere(['jabatan.kelas_jabatan' => $this->kelas_jabatan]);
        }
        if (User::isInstansi()) {
            $this->id_instansi = \app\models\User::getIdInstansi();
        }
        if ($this->searchInstansi !== null) {
            $ids = array_keys($this->searchInstansi->getManySubInstansi()->select('intansi.id')->indexBy('instansi.id')->asArray()->all());
            $ids[] = $this->searchInstansi->id;
            $query->andWhere(['in', 'id_instansi', $ids]);
        }
        if (User::isMapping()) {
            $query->andWhere(['in', 'id_instansi', \app\models\User::getListIdInstansi()]);
        }

        // add conditions that should always apply here

        // grid filtering conditions
        $query->andFilterWhere([
            'id_instansi' => $this->id_instansi,
            'id_jabatan' => $this->id_jabatan,
            'id_atasan' => $this->id_atasan,
            'id_golongan' => $this->id_golongan,
            'id_instansi_pegawai_bak' => $this->id_instansi_pegawai_bak,
            'status_batas_pengajuan' => $this->status_batas_pengajuan,
            'tanggal_lahir' => $this->tanggal_lahir,
            'id_eselon' => $this->id_eselon,
            'id_pegawai_status' => $this->id_pegawai_status,
            'status_hapus' => $this->status_hapus,
            'jumlah_userinfo' => $this->jumlah_userinfo,
            'jumlah_checkinout' => $this->jumlah_checkinout,
        ]);

        $query->andFilterWhere(['like', 'pegawai.nama', $this->nama])
            ->andFilterWhere(['like', 'instansi.nama', $this->namaInstansi])
            ->andFilterWhere(['like', 'nip', $this->nip])
            ->andFilterWhere(['like', 'nama_jabatan', $this->nama_jabatan])
            ->andFilterWhere(['like', 'gender', $this->gender])
            ->andFilterWhere(['like', 'tempat_lahir', $this->tempat_lahir])
            ->andFilterWhere(['like', 'alamat', $this->alamat])
            ->andFilterWhere(['like', 'telepon', $this->telepon])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'foto', $this->foto])
            ->andFilterWhere(['like', 'grade', $this->grade])
            ->andFilterWhere(['like', 'gelar_depan', $this->gelar_depan])
            ->andFilterWhere(['like', 'gelar_belakang', $this->gelar_belakang])
            ->andFilterWhere(['like', 'eselon_bak', $this->eselon_bak]);

        return $query;
    }

    /**
     * @return Pegawai[]
     */
    public function searchPegawaiRekap()
    {
        if (empty($this->bulan)) {
            $this->bulan = date('m');
        }
        $query = Pegawai::find()
            ->with([
                'kelasJabatan',
                'pegawaiRekapTunjangan.instansi',
                'pegawaiRekapTunjangan.pegawaiRekapKinerja',
                'pegawaiRekapTunjangan.pegawaiRekapAbsensi',
                'hukumanDisiplin',
                'instansi',
                'manyPegawaiVariabelObjektif'
            ])
            ->orderBy(['id_golongan' => SORT_DESC]);
        if (User::isPegawai()) {
            $query->andWhere(['pegawai.id' => Yii::$app->user->identity->id_pegawai]);
        } else {
            $query->andWhere(['id_instansi' => $this->id_instansi]);
        }
        return $query->all();
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
