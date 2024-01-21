<?php

namespace backend\models;

use common\helper\CacheCloud;
use Yii;
use \backend\models\base\Schedule as BaseSchedule;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

/**
 * This is the model class for table "tx_schedule".
 */
class Schedule extends BaseSchedule
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //TAMBAHAN
            [['title','group_id', 'room_id','date_start','date_end'], 'required'],

            [['office_id', 'group_id', 'room_id', 'created_by', 'updated_by', 'is_deleted', 'deleted_by', 'verlock'], 'integer'],
            [['date_start', 'date_end', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['description'], 'string'],
            [['title'], 'string', 'max' => 100],
            [['uuid'], 'string', 'max' => 36],
            [['verlock'], 'default', 'value' => '0'],
            [['verlock'], 'mootensai\components\OptimisticLockValidator']
        ];
    }

}
