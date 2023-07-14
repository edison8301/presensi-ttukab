<?php

namespace app\modules\tukin\controllers;

use app\models\User;
use Yii;
use app\modules\tukin\models\InstansiKordinatif;
use app\modules\tukin\models\InstansiKordinatifSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * InstansiKordinatifController implements the CRUD actions for InstansiKordinatif model.
 */
class InstansiKordinatifController extends Controller
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
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['create', 'update', 'view', 'delete', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function () {
                            return User::isAdmin() || User::isMapping();
                        },
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all InstansiKordinatif models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new InstansiKordinatifSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single InstansiKordinatif model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new InstansiKordinatif model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new InstansiKordinatif();
        $referrer = Yii::$app->request->referrer;

        if ($model->load(Yii::$app->request->post())) {
            $referrer = $_POST['referrer'];
            if ($model->save()) {
                Yii::$app->session->setFlash('success','Data berhasil disimpan.');
                return $this->redirect($referrer);
            }
            Yii::$app->session->setFlash('error','Data gagal disimpan. Silahkan periksa kembali isian Anda.');
        }

        return $this->render('create', [
            'model' => $model,
            'referrer'=>$referrer
        ]);

    }

    /**
     * Updates an existing InstansiKordinatif model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $referrer = Yii::$app->request->referrer;

        if ($model->load(Yii::$app->request->post())) {
            $referrer = $_POST['referrer'];
            if ($model->save()) {
                Yii::$app->session->setFlash('success','Data berhasil disimpan.');
                return $this->redirect($referrer);
            }

            Yii::$app->session->setFlash('error','Data gagal disimpan. Silahkan periksa kembali isian Anda.');
        }

        return $this->render('update', [
            'model' => $model,
            'referrer'=>$referrer
        ]);

    }

    /**
     * Deletes an existing InstansiKordinatif model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if ($model->delete()) {
            Yii::$app->session->setFlash('success','Data berhasil dihapus');
        } else {
            Yii::$app->session->setFlash('error','Data gagal dihapus');
        }

        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Finds the InstansiKordinatif model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return InstansiKordinatif the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = InstansiKordinatif::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
