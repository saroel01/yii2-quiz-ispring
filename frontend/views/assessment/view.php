<?php

use kartik\select2\Select2;
use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var common\models\Assessment $model
 */

$this->title = $model->quiz_title . '-'.$model->assessment->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Assessment Details'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="assessment-view">

    <?= DetailView::widget([
        'model' => $model,
        'condensed' => false,
        'hover' => true,
        'mode' => Yii::$app->request->get('edit') == 't' ? DetailView::MODE_EDIT : DetailView::MODE_VIEW,
        'panel' => [
            'heading' => $this->title,
            'type' => DetailView::TYPE_DEFAULT,
        ],
        'attributes' => [
            [
                'attribute'=>'assessment_id',
                'value'=>($model->assessment_id!=null) ? $model->assessment->title:'',
                'type'=>DetailView::INPUT_SELECT2,
                'options' => ['id' => 'assessment_id', 'prompt' => '', 'disabled'=>false],
                'items' => $assessmentList,
                'widgetOptions'=>[
                    'class'=> Select2::class,
                    'data'=>$assessmentList,
                ],
            ],
            [
                'attribute' => 'participant_id',
                'value'=> ($model->participant_id!=null) ? $model->participant->title:'',
                'type' => DetailView::INPUT_SELECT2,
                'options' => ['id' => 'participant_id', 'prompt' => '', 'disabled'=>false],
                'items' => $participantList,
                'widgetOptions'=>[
                    'class'=> Select2::class,
                    'data' => $participantList,
                ],
            ],

            'app_version',
            'earned_points',
            'passing_score',
            'passing_score_percent',
            'gained_score',
            'evaluate_score',
            'quiz_title',
            'quiz_type',
            'username',
            [
                'attribute'=>'time_limit',
                'value' => gmdate("H:i:s", $model->time_limit),
            ],
            [
                'attribute'=>'used_time',
                'value' => gmdate("H:i:s", $model->used_time),
            ],

            'time_spent',
            [
                'group'=>true,
                'rowOptions'=>['class'=>'default']
            ],
            [
                'columns' => [
                    [
                        'attribute'=>'created_at',
                        'format'=>'date',
                        'type'=>DetailView::INPUT_HIDDEN,
                        'valueColOptions'=>['style'=>'width:30%']
                    ],
                    [
                        'attribute'=>'updated_at',
                        'format'=>'date',
                        'type'=>DetailView::INPUT_HIDDEN,
                        'valueColOptions'=>['style'=>'width:30%']
                    ],
                ],
            ],

        ],
        'deleteOptions' => [
            'url' => ['delete', 'id' => $model->id],
        ],
        'enableEditMode' => false,
    ]) ?>

</div>
