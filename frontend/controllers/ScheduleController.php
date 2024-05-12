<?php

namespace frontend\controllers;

use common\domain\DataIdUseCase;
use common\domain\DataListUseCase;
use common\helper\LabelHelper;
use common\models\Assessment;
use common\models\Participant;
use common\models\ScheduleDetail;
use common\models\Subject;
use Yii;
use common\models\Schedule;
use common\models\ScheduleSearch;
use yii\base\Exception;
use yii\data\ArrayDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\Response;
use yii\helpers\Json;
use yii\filters\VerbFilter;

use common\helper\MessageHelper;

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
     * Logs out the current user.
     * $id = Schedule Detail Id
     * Example link :
     * http://www.mywebsite.com/presentation/index.html?USER_NAME=John&USER_EMAIL=john@ispringsolutions.com&ADDRESS=NYC
     * @throws Exception
     */
    public function actionOpen($id, $title=null)
    {
        $scheduleDetail = ScheduleDetail::findOne($id);
        $participant = Participant::findone(['username'=>Yii::$app->user->identity->username]);

        $assessment = new Assessment();
        $assessment->schedule_detail_id = $scheduleDetail->id;
        $assessment->office_id = $scheduleDetail->office_id;
        $assessment->schedule_id = $scheduleDetail->schedule_id;
        $assessment->participant_id = $participant->id;
        $assessment->period_id = $scheduleDetail->schedule->period_id;
        $assessment->subject_id = $scheduleDetail->subject_id;
        $assessment->subject_type = $scheduleDetail->subject_type;
        $assessment->save();

        $currentTime = strtotime("now");
        $timer = $scheduleDetail->schedule->getTimer();
        $textLink = '';

        if ($timer > $currentTime) :
            $userinfo = '?USER_NAME=' . Yii::$app->user->identity->username .
                '&SCD=' . $scheduleDetail->id;
            $textLink = $scheduleDetail->getExtractUrl() . $userinfo;
        endif;

        $this->redirect($textLink);
    }
}