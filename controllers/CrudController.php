<?php

namespace app\controllers;

use app\models\CadastralNumbers;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CrudController implements the CRUD actions for CadastralNumbers model.
 */
class CrudController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all CadastralNumbers models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => CadastralNumbers::find(),
            /*
            'pagination' => [
                'pageSize' => 50
            ],
            'sort' => [
                'defaultOrder' => [
                    'cadastr_id' => SORT_DESC,
                ]
            ],
            */
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single CadastralNumbers model.
     * @param string $cadastr_id Cadastr ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($cadastr_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($cadastr_id),
        ]);
    }

    /**
     * Creates a new CadastralNumbers model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CadastralNumbers();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'cadastr_id' => $model->cadastr_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing CadastralNumbers model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $cadastr_id Cadastr ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($cadastr_id)
    {
        $model = $this->findModel($cadastr_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'cadastr_id' => $model->cadastr_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing CadastralNumbers model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $cadastr_id Cadastr ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($cadastr_id)
    {
        $this->findModel($cadastr_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the CadastralNumbers model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $cadastr_id Cadastr ID
     * @return CadastralNumbers the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($cadastr_id)
    {
        if (($model = CadastralNumbers::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
