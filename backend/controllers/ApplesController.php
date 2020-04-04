<?php

namespace backend\controllers;

use backend\models\AppleEatForm;
use backend\models\Apples;
use backend\models\ApplesGenerateForm;
use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\base\Exception;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

/**
 * Site ApplesController
 */
class ApplesController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'generate', 'down', 'delete', 'check-all-bad', 'delete-all-bad', 'eat'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [

                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Apples::find()->orderBy('id DESC'),
            'pagination' => [
                'pageSize' => 5,
            ],
        ]);

        return $this->render('index', [
            'ApplesGenerateForm' => new ApplesGenerateForm(),
            'AppleEatForm' => new AppleEatForm(),
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionGenerate()
    {
        $model = new ApplesGenerateForm();

        if ($model->load(Yii::$app->request->post()) && $model->generate()) {
            return $this->redirect(['index']);
        }

        return $this->render('_generate-form', [
            'model' => $model
        ]);
    }

    public function actionDown($id)
    {
        $model = $this->findApple($id);

        try {
            $model->down();
        } catch (Exception $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
        }

        if (Yii::$app->request->isAjax) {
            return $this->runAction('index');
        } else {
            return $this->redirect(['index']);
        }
    }

    public function actionDelete($id)
    {
        $model = $this->findApple($id);

        try {
            $model->delete();
        } catch (Exception $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
        }

        if (Yii::$app->request->isAjax) {
            return $this->runAction('index');
        } else {
            return $this->redirect(['index']);
        }
    }

    public function actionEat($id)
    {
        $apple = $this->findApple($id);

        $model = new AppleEatForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                try {
                    $model->eat($apple);
                } catch (Exception $e) {
                    Yii::$app->session->setFlash('error', $e->getMessage());
                }
            } else {
                Yii::$app->session->setFlash('error', $model->getErrorSummary(true));
            }
        }

        if (Yii::$app->request->isAjax) {
            return $this->runAction('index');
        } else {
            return $this->redirect(['index']);
        }
    }

    public function actionCheckAllBad()
    {
        $markBadCount = Apples::checkAll();
        Yii::$app->session->setFlash('success', $markBadCount ? "$markBadCount apples mark is bad" : "Bad apples not found");
        return $this->redirect(['index']);
    }

    public function actionDeleteAllBad()
    {
        $deleted = Apples::deleteAllBad();
        Yii::$app->session->setFlash('success', $deleted ? "$deleted apples deleted" : "No apples deleted");
        return $this->redirect(['index']);
    }

    /**
     * @param $id
     * @return Apples
     * @throws NotFoundHttpException
     */
    protected function findApple($id)
    {
        if (!$apple = Apples::findOne($id)) {
            throw new NotFoundHttpException('Apple not found');
        }
        return $apple;
    }
}
