<?php

namespace app\modules\absensi\controllers;

use Yii;
use app\modules\absensi\models\HariLibur;
use app\modules\absensi\models\HariLiburSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\User;

/**
 * HariLiburController implements the CRUD actions for HariLibur model.
 */
class HariLiburController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'actions'=>['index','create','update','view','delete'],
                        'allow' => true,
                        'matchCallback' => function() { return User::isAdmin(); }
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all HariLibur models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new HariLiburSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single HariLibur model.
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
     * Creates a new HariLibur model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new HariLibur();

        $referrer = Yii::$app->request->referrer;

        if(isset($_POST['referrer']))
            $referrer = $_POST['referrer'];

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect($referrer);
        } else {
            return $this->render('create', [
                'model' => $model,
                'referrer'=>$referrer
            ]);
        }
    }

    /**
     * Updates an existing HariLibur model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $referrer = Yii::$app->request->referrer;

        if(isset($_POST['referrer']))
            $referrer = $_POST['referrer'];

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect($referrer);
        } else {
            return $this->render('update', [
                'model' => $model,
                'referrer'=>$referrer
            ]);
        }
    }

    /**
     * Deletes an existing HariLibur model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the HariLibur model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return HariLibur the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = HariLibur::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
