<?php

namespace frontend\controllers;

use backend\models\Assessment;
use backend\models\Participant;
use backend\models\Schedule;
use common\helper\CacheCloud;
use Yii;
use backend\models\AssessmentDetail;
use backend\models\AssessmentDetailSearch;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\db\StaleObjectException;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;

use common\helper\MessageHelper;

/**
 * AssessmentDetailController implements the CRUD actions for AssessmentDetail model.
 */
class AssessmentDetailController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all AssessmentDetail models.
     * @return mixed
     */
    public function actionIndex()
    {
        if (Yii::$app->user->can('index-assessmentdetail')) {
            $searchModel = new AssessmentDetailSearch;
            $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

            $officeId   = CacheCloud::getInstance()->getOfficeId();

            $assessmentList = ArrayHelper::map(Assessment::find()
                ->where(['office_id' => $officeId])
                ->asArray()->all(), 'id', 'title');

            $participantList = ArrayHelper::map(Participant::find()
                ->where(['office_id' => $officeId])
                ->asArray()->all(), 'id', 'title');

            return $this->render('index', [
                'dataProvider' => $dataProvider,
                'searchModel' => $searchModel,
                'assessmentList' => $assessmentList,
                'participantList' => $participantList
            ]);
        } else {
            MessageHelper::getFlashAccessDenied();
            throw new ForbiddenHttpException;
        }
    }

    /**
     * Displays a single AssessmentDetail model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        if (Yii::$app->user->can('view-assessmentdetail')) {
            $model = $this->findModel($id);

            $assessmentList = ArrayHelper::map(Assessment::find()
                ->where(['office_id' => $model->office_id])
                ->asArray()->all(), 'id', 'title');

            $participantList = ArrayHelper::map(Participant::find()
                ->where(['office_id' => $model->office_id])
                ->asArray()->all(), 'id', 'title');

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('view', [
                    'model' => $model,
                    'assessmentList' => $assessmentList,
                    'participantList' => $participantList
                ]);
            }
        } else {
            MessageHelper::getFlashAccessDenied();
            throw new ForbiddenHttpException;
        }
    }

    /**
     * Creates a new AssessmentDetail model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if (Yii::$app->user->can('create-assessmentdetail')) {
            $model = new AssessmentDetail;
            $officeId   = CacheCloud::getInstance()->getOfficeId();

            $assessmentList = ArrayHelper::map(Assessment::find()
                ->where(['office_id' => $officeId])
                ->asArray()->all(), 'id', 'title');

            $participantList = ArrayHelper::map(Participant::find()
                ->where(['office_id' => $officeId])
                ->asArray()->all(), 'id', 'title');

            try {
                if ($model->load(Yii::$app->request->post()) && $model->save()) {
                    return $this->redirect(['view', 'id' => $model->id]);
                } else {
                    return $this->render('create', [
                        'model' => $model,
                        'assessmentList' => $assessmentList,
                        'participantList' => $participantList
                    ]);
                }
            } catch (StaleObjectException $e) {
                throw new StaleObjectException('The object being updated is outdated.');
            }
        } else {
            MessageHelper::getFlashAccessDenied();
            throw new ForbiddenHttpException;
        }
    }

    /**
     * Updates an existing AssessmentDetail model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        if (Yii::$app->user->can('update-assessmentdetail')) {
            try {
                $model = $this->findModel($id);
                $officeId   = CacheCloud::getInstance()->getOfficeId();

                $assessmentList = ArrayHelper::map(Assessment::find()
                    ->where(['office_id' => $model->office_id])
                    ->asArray()->all(), 'id', 'title');

                $participantList = ArrayHelper::map(Participant::find()
                    ->where(['office_id' => $model->office_id])
                    ->asArray()->all(), 'id', 'title');

                if ($model->load(Yii::$app->request->post()) && $model->save()) {
                    return $this->redirect(['view', 'id' => $model->id]);
                } else {
                    return $this->render('update', [
                        'model' => $model,
                        'assessmentList' => $assessmentList,
                        'participantList' => $participantList
                    ]);
                }
            } catch (StaleObjectException $e) {
                throw new StaleObjectException('The object being updated is outdated.');
            }
        } else {
            MessageHelper::getFlashAccessDenied();
            throw new ForbiddenHttpException;
        }
    }

    /**
     * Deletes an existing AssessmentDetail model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if (Yii::$app->user->can('delete-assessmentdetail')) {
            $this->findModel($id)->delete();

            return $this->redirect(['index']);
        } else {
            MessageHelper::getFlashLoginInfo();
            throw new ForbiddenHttpException;
        }
    }

    /**
     * Finds the AssessmentDetail model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AssessmentDetail the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AssessmentDetail::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
