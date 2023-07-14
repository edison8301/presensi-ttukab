<?php

namespace app\modules\absensi\controllers;

use Yii;
use app\models\User;
use app\modules\absensi\models\Dasbor;
use app\models\Instansi;

class DasborController extends \yii\web\Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        if(User::isAdmin()) {
            return $this->redirect(['instansi/index']);
        }

        if(User::isVerifikator()) {
            return $this->redirect(['instansi/index']);
        }

        if(User::isInstansi()) {
            return $this->redirect(['pegawai-rekap-absensi/index']);
        }

        if(User::isPegawai()) {
            return $this->redirect(['pegawai/profil']);
        }

        if(User::isGrup()) {
            return $this->redirect(['/absensi/pegawai/index-shift-kerja']);
        }

        if (User::isMapping()) {
            return $this->redirect(['/tukin/jabatan/index']);
        }
    }

    public function actionAdmin()
    {
        $dasbor = new Dasbor;

        $dasbor->load(Yii::$app->request->queryParams);

        return $this->render('admin',[
            'dasbor'=>$dasbor
        ]);
    }

    public function actionInstansi($id_instansi=null)
    {
        $dasbor = new Dasbor;

        if(User::isInstansi()) {
            $id_instansi = User::getIdInstansi();
        }

        $instansi = Instansi::findOne($id_instansi);

        $dasbor->load(Yii::$app->request->queryParams);

        return $this->render('instansi',[
            'instansi'=>$instansi,
            'dasbor'=>$dasbor
        ]);
    }

    public function actionPegawai($id_pegawai=null)
    {
        return $this->redirect(['pegawai/profil']);

        $dasbor = new Dasbor;

        $dasbor->load(Yii::$app->request->queryParams);

        return $this->render('pegawai',[
            'dasbor'=>$dasbor
        ]);
    }

}
