<?php

namespace common\models;

use common\helper\LabelHelper;
use Yii;
use \common\models\base\Assessment as BaseAssessment;

/**
 * This is the model class for table "tx_assessment_detail".
 */
class Assessment extends BaseAssessment
{
    const SUBJECT_TYPE_GENERAL      = ScheduleDetail::SUBJECT_TYPE_GENERAL;
    const SUBJECT_TYPE_LITERACY     = ScheduleDetail::SUBJECT_TYPE_LITERACY;
    const SUBJECT_TYPE_NUMERATION   = ScheduleDetail::SUBJECT_TYPE_NUMERATION;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['office_id', 'schedule_id', 'schedule_detail_id', 'participant_id', 'period_id', 'subject_id', 'subject_type', 'is_completed', 'created_by', 'updated_by', 'is_deleted', 'deleted_by', 'verlock'], 'integer'],
            [['earned_points', 'passing_score', 'passing_score_percent', 'gained_score', 'evaluate_score'], 'number'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['app_version', 'quiz_title', 'quiz_type', 'username', 'time_limit', 'used_time', 'time_spent'], 'string', 'max' => 50],
            [['uuid'], 'string', 'max' => 36],
            [['verlock'], 'default', 'value' => '0'],
            [['verlock'], 'mootensai\components\OptimisticLockValidator']
        ];
    }
    public function beforeSave($insert) {
        if (!parent::beforeSave($insert)) {
            return false;
        }

//        if ($this->isNewRecord) {
//            $this->office_id   = $this->assessment->office_id;
//            $this->period_id   = $this->assessment->period_id;
//        }

        $this->evaluate_score = ceil(($this->earned_points/$this->gained_score)*100);

        return true;
    }

    public static function getArraySubjectTypes()
    {
        return [
            //MASTER
            self::SUBJECT_TYPE_GENERAL => Yii::t('app', 'General'),
            self::SUBJECT_TYPE_LITERACY  => Yii::t('app', 'Literacy'),
            self::SUBJECT_TYPE_NUMERATION  => Yii::t('app', 'Numeration'),
        ];
    }

    public static function getOneSubjectType($_module = null)
    {
        if($_module)
        {
            $arrayModule = self::getArraySubjectTypes();

            switch ($_module) {
                case ($_module == self::SUBJECT_TYPE_GENERAL):
                    $returnValue = LabelHelper::getPrimary($arrayModule[$_module]);
                    break;
                case ($_module == self::SUBJECT_TYPE_LITERACY):
                    $returnValue = LabelHelper::getSuccess($arrayModule[$_module]);
                    break;
                case ($_module == self::SUBJECT_TYPE_NUMERATION):
                    $returnValue = LabelHelper::getDanger($arrayModule[$_module]);
                    break;
                default:
                    $returnValue = LabelHelper::getDefault($arrayModule[$_module]);
            }

            return $returnValue;

        }
        else
            return;
    }
}
