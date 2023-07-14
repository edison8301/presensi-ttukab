<?php

namespace app\modules\kinerja\models;

use app\components\Session;
use Yii;
use yii\helpers\Html;

/**
 * This is the model class for table "skp_lampiran".
 *
 * @property int $id
 * @property int $id_skp
 * @property int $id_skp_lampiran_jenis
 * @property string $uraian
 * @property InstansiPegawaiSkp $skp
 */
class SkpLampiran extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'skp_lampiran';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_skp', 'id_skp_lampiran_jenis', 'uraian'], 'required'],
            [['id_skp', 'id_skp_lampiran_jenis'], 'integer'],
            [['uraian'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_skp' => 'Id Skp',
            'id_skp_lampiran_jenis' => 'Id Skp Lampiran Jenis',
            'uraian' => 'Uraian',
        ];
    }

    public function getSkp()
    {
        return $this->hasOne(InstansiPegawaiSkp::class, ['id' => 'id_skp']);
    }

    public function canUpdate()
    {
        if (Session::isAdmin()) {
            return true;
        }

        if (Session::isPegawai()
            AND @$this->skp->instansiPegawai->id_pegawai == Session::getIdPegawai()
        ) {
            return true;
        }

        return false;
    }

    public function getLinkUpdateIcon()
    {
        if ($this->canUpdate() == false) {
            return null;
        }

        return Html::a('<i class="fa fa-pencil"></i>', [
            '/kinerja/skp-lampiran/update',
            'id' => $this->id,
        ]);
    }

    public function getLinkDeleteIcon()
    {
        if ($this->canUpdate() == false) {
            return null;
        }

        return Html::a('<i class="fa fa-trash"></i>', [
            '/kinerja/skp-lampiran/delete',
            'id' => $this->id,
        ], [
            'data-method' => 'post',
            'data-confirm' => 'Yakin akan menghapus data?'
        ]);
    }
}
