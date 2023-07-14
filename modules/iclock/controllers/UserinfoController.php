<?php

namespace app\modules\iclock\controllers;

use app\models\Pegawai;
use Yii;
use app\models\User;
use app\modules\iclock\models\Userinfo;
use app\modules\iclock\models\UserinfoSearch;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * UserinfoController implements the CRUD actions for Userinfo model.
 */
class UserinfoController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'view','create','update', 'update-v2'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return User::isAdmin();
                        },
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Userinfo models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserinfoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Userinfo model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate()
    {
        $model = new Userinfo();

        $model->DelTag = 0;
        $model->SN = 'AF44173260219';

        $referrer = Yii::$app->request->referrer;

        if($model->load(Yii::$app->request->post())) {
            if($model->save()) {
                Yii::$app->session->setFlash('success','Data berhasil disimpan');
                return $this->redirect($referrer);
            }
        }

        return $this->render('create',[
            'model' => $model,
            'referrer' => $referrer
        ]);
    }

    public function actionUpdateV2($id)
    {
        $pegawai = Pegawai::findOne($id);

        if($pegawai == null) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $model = new Userinfo();
        $model->name = $pegawai->nama;
        $model->badgenumber = strval($pegawai->nip);

        $referrer = Yii::$app->request->referrer;
        
        if($model->load(Yii::$app->request->post())) {
            $model->DelTag = 0;
            if($model->save(false)) {
                
                $pegawai->jumlah_userinfo = $pegawai->getManyUserinfo()->count();
                $pegawai->save();

                Yii::$app->session->setFlash('success','Data berhasil disimpan');
                return $this->redirect(['/absensi/perawatan/pegawai-tanpa-userinfo']);
            }
        }

        return $this->render('create-v2',[
            'model' => $model,
            'referrer' => $referrer
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $referrer = Yii::$app->request->referrer;

        if($model->load(Yii::$app->request->post())) {
            if($model->save()) {
                Yii::$app->session->setFlash('success','Data berhasil disimpan');
                return $this->redirect($referrer);
            }
        }

        return $this->render('update',[
            'model' => $model,
            'referrer' => $referrer
        ]);
    }

    /**
     * Finds the Userinfo model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Userinfo the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Userinfo::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionPerawatan($proses=0)
    {
        $userid = '3481';
        $badgenumber = '197411112002122006';

        $query = Userinfo::find();
        $query->andWhere('userid != :userid AND badgenumber = :badgenumber',[
            ':userid'=> $userid,
            ':badgenumber'=> $badgenumber
        ]);

        if($proses==1) {
            Userinfo::deleteAll('userid != :userid AND badgenumber = :badgenumber',[
                ':userid'=> $userid,
                ':badgenumber'=> $badgenumber
            ]);
        }

        print $query->count();
    }

}
