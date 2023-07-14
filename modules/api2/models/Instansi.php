<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 19/05/2019
 * Time: 06.46
 */

namespace app\modules\api2\models;


use yii\helpers\Html;

class Instansi extends \app\models\Instansi
{
    /*
    public function fields()
    {
        return [
            'id',
            'nama',
            'nama_lengkap' => function ($model) {
                return $model->nama . ' ' . $model->singkatan;
            },
            'linkView'
        ];
    }
    */

    public function getLinkView()
    {
        return Html::a('<i class="fa fa-pencil"></i>',['/instansi/view','id'=>$this->id]);
    }

    public function extraFields()
    {
        return ['manyPegawai'];
    }


}
