<?php

namespace backend\controllers;

use common\helper\MessageHelper;
use common\models\Schedule;
use common\models\ScheduleDetail;
use common\models\ScheduleSearch;
use common\models\Subject;
use common\service\DataIdService;
use common\service\DataListService;
use Yii;
use yii\data\ArrayDataProvider;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * ScheduleController implements the CRUD actions for Schedule model.
 */
class ScheduleController extends Controller
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
     * Lists all Schedule models.
     * @return mixed
     */
    public function actionIndex()
    {
        if (Yii::$app->user->can('index-schedule')) {
            $searchModel = new ScheduleSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            $periodList = DataListService::getPeriod();
            $roomList = DataListService::getRoom();
            $groupList = DataListService::getGroup();
            $examTypeList = Schedule::getArrayExamType();
            $isAssetList = Schedule::getArrayIsAsset();

            return $this->render('index', [
                'dataProvider' => $dataProvider,
                'searchModel' => $searchModel,
                'periodList' => $periodList,
                'roomList' => $roomList,
                'groupList' => $groupList,
                'examTypeList' => $examTypeList,
                'isAssetList' => $isAssetList
            ]);
        } else {
            MessageHelper::getFlashAccessDenied();
            throw new ForbiddenHttpException;
        }
    }

    /**
     * Displays a single Schedule model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id, $title = null)
    {
        if (Yii::$app->user->can('view-schedule')) {
            $model = $this->findModel($id);
            $providerScheduleDetail = new ArrayDataProvider([
                'allModels' => $model->scheduleDetails,
            ]);

            $providerAssessment = new ArrayDataProvider([
                'allModels' => $model->assessments,
            ]);

            $timeStart          = strtotime($model->date_start);
            $timeOut            = strtotime($model->date_end);
            $tokenTime          = strtotime($model->token_time);
            $countdownTime      = strtotime($model->date_start);
            $currentTime        = strtotime("now");
            $interval           = (int)(abs(($currentTime-$timeStart) / 60));
            $minutesTolerance   = 15; //minutes

            //FIRST TOKEN START 2 MINUTES EARLY FROM DATE_START
            if($currentTime <= $timeStart) {
                // Decrease 2 minutes from current time
                $countdownTime      = $timeStart - (2 * 60);
                $interval           = (int)(abs(($timeStart - $countdownTime) / 60));
                $model->token_time  = date(Yii::$app->params['datetimeSaveFormat']);
                $model->token       = substr(uniqid('', true), -6);
                $model->save();
            } else {
                //START NEW TOKEN
                if($currentTime < $timeOut){
                    $interval = (int)(abs(($currentTime-$tokenTime) / 60));
                    if ($interval >= $minutesTolerance) :
                        $schedule = Schedule::findOne(['id' => $model->id,'office_id'=>$model->office_id]);
                        if($model->token_time < $schedule->token_time) {
                            $model = $schedule;
                        } else {
                            $model->token_time = date(Yii::$app->params['datetimeSaveFormat']);
                            $model->token = substr(uniqid('', true), -6);
                            $model->save();
                        }
                    endif;
                    $countdownTime = strtotime($model->token_time) + ($minutesTolerance * 60);
                }
            }

            return $this->render('view', [
                'model' => $this->findModel($id),
                'providerScheduleDetail' => $providerScheduleDetail,
                'providerAssessment' => $providerAssessment,
                'countdownTime' => $countdownTime,
                'interval' => $interval,
                'minutesTolerance' => $minutesTolerance
            ]);
        } else {
            MessageHelper::getFlashAccessDenied();
            throw new ForbiddenHttpException;
        }
    }

    /**
     * Creates a new Schedule model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if (Yii::$app->user->can('create-schedule')) {

            $model = new Schedule();
            $model->office_id = DataIdService::getOfficeId();
            $model->date_start = date(Yii::$app->params['datetimeSaveFormat']);
            $model->date_end = date(Yii::$app->params['datetimeSaveFormat']);

            $periodList = DataListService::getPeriod();
            $roomList = DataListService::getRoom();
            $groupList = DataListService::getGroup();
            $subjectList = DataListService::getSubject();
            $staffList = DataListService::getStaff();
            $examTypeList = Schedule::getArrayExamType();
            $subjectTypeList = ScheduleDetail::getArraySubjectTypes();

            if ($model->loadAll(Yii::$app->request->post()) && $model->saveAll()) {
                $model->updateIsAsset();
                MessageHelper::getFlashSaveSuccess();
                return $this->redirect(['view', 'id' => $model->id, 'title'=>$model->title]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                    'periodList' => $periodList,
                    'roomList' => $roomList,
                    'groupList' => $groupList,
                    'subjectList' => $subjectList,
                    'staffList' => $staffList,
                    'examTypeList' => $examTypeList,
                    'subjectTypeList' => $subjectTypeList
                ]);
            }
        } else {
            MessageHelper::getFlashAccessDenied();
            throw new ForbiddenHttpException;
        }
    }

    /**
     * Updates an existing Schedule model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        if (Yii::$app->user->can('update-schedule')) {
            $model = $this->findModel($id);
            $officeList = DataListService::getOffice();
            $periodList = DataListService::getPeriod();
            $roomList = DataListService::getRoom();
            $groupList = DataListService::getGroup();
            $subjectList = DataListService::getSubject();
            $staffList = DataListService::getStaff();
            $examTypeList = Schedule::getArrayExamType();
            $subjectTypeList = ScheduleDetail::getArraySubjectTypes();

            if ($model->loadAll(Yii::$app->request->post()) && $model->saveAll()) {
                $model->updateIsAsset();
                MessageHelper::getFlashUpdateSuccess();
                return $this->redirect(['view', 'id' => $model->id,'title'=>$model->title]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                    'officeList' => $officeList,
                    'periodList' => $periodList,
                    'roomList' => $roomList,
                    'groupList' => $groupList,
                    'subjectList' => $subjectList,
                    'staffList' => $staffList,
                    'examTypeList' => $examTypeList,
                    'subjectTypeList' => $subjectTypeList
                ]);
            }
        } else {
            MessageHelper::getFlashAccessDenied();
            throw new ForbiddenHttpException;
        }
    }

    /**
     * Deletes an existing Schedule model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public function actionDelete($id)
    {
        if (Yii::$app->user->can('delete-schedule')) {
            $model = $this->findModel($id);
            $modelDetails = $model->scheduleDetails;

            $transaction = \Yii::$app->db->beginTransaction();
            try {
                foreach ($modelDetails as $modelDetailItem) {
                    $modelDetailItem->deleteAsset();
                    $modelDetailItem->removeExtractFolder($modelDetailItem->getExtractDir());
                    $modelDetailItem->delete();
                }
                $model->delete();
                $transaction->commit();
                MessageHelper::getFlashDeleteSuccess();
                return $this->redirect(['index']);
            } catch (\Exception $e) {
                $transaction->rollBack();
                throw $e;
            }
        } else {
            MessageHelper::getFlashLoginInfo();
            throw new ForbiddenHttpException;
        }
    }


    /**
     * Finds the Schedule model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Schedule the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Schedule::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }

    /**
     * Action to load a tabular form grid
     * for ScheduleDetail
     * @return mixed
     * @author Jiwantoro Ndaru <jiwanndaru@gmail.com>
     *
     * @author Yohanes Candrajaya <moo.tensai@gmail.com>
     */
    public function actionAddScheduleDetail()
    {
        if (Yii::$app->request->isAjax) {
            $subjectList = DataListService::getSubject();
            $subjectTypeList = ScheduleDetail::getArraySubjectTypes();

            $row = Yii::$app->request->post('ScheduleDetail');
            if (!empty($row)) {
                $row = array_values($row);
            }
            if ((Yii::$app->request->post('isNewRecord') && Yii::$app->request->post('_action') == 'load' && empty($row)) || Yii::$app->request->post('_action') == 'add')
                $row[] = [];
            return $this->renderAjax('_formScheduleDetail', [
                'row' => $row,
                'subjectList' => $subjectList,
                'subjectTypeList'=>$subjectTypeList
            ]);
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }

/*
 * ITEM POPULATE ALREADY FILTERED BY OFFICE AND WAREHOUSE
 * 1. actionSelectWarehouse -> filter office and warehouse
 * 2. actionCreate($warehouseId) -> filter item by office and warehouse
 */

    public function actionUpdateRemarkField($subjectId)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        // Assuming you have a model named YourModel and a database table corresponding to it.
        $model = Subject::find()
            ->where([
                'id' => $subjectId,
            ])
            ->one();

        if ($model) {
            return Json::encode([
                'success' => true,
                'remark' => $model->description,
            ]);
        } else {
            return Json::encode(['success' => false, 'message' => 'Record not found']);
        }
    }

    public function actionOpen($id, $title)
    {
        $scheduleDetail = ScheduleDetail::findOne($id);
        $currentTime = strtotime("now");
        $timer = $scheduleDetail->schedule->getTimer();
        $textLink = '';

        if ($timer > $currentTime) :
            $userinfo = '?USER_NAME=' . Yii::$app->user->identity->username .
                '&SCD=' . $scheduleDetail->id;
            $textLink = $scheduleDetail->getExtractUrl() . $userinfo;
        endif;

        $this->redirect(str_replace('admin/','',$textLink));
    }
}
