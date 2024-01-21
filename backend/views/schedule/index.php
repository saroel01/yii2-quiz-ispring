<?php

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ScheduleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use kartik\export\ExportMenu;
use kartik\grid\GridView;

$this->title = Yii::t('app', 'Schedule');
$this->params['breadcrumbs'][] = $this->title;
$search = "$('.search-button').click(function(){
	$('.search-form').toggle(1000);
	return false;
});";
$this->registerJs($search);
?>
<div class="schedule-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <div class="search-form" style="display:none">
        <?=  $this->render('_search', ['model' => $searchModel]); ?>
    </div>
    <?php 
    $gridColumn = [
        ['class' => 'yii\grid\SerialColumn'],
        ['attribute' => 'id', 'visible' => false],
        [
                'attribute' => 'office_id',
                'label' => Yii::t('app', 'Office'),
                'value' => function($model){
                    if ($model->office)
                    {return $model->office->title;}
                    else
                    {return NULL;}
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => $officeList,
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => '', 'id' => 'grid-schedule-search-office_id']
        ],
        [
            'attribute' => 'group_id',
            'label' => Yii::t('app', 'Group'),
            'value' => function($model){
                if ($model->group)
                {return $model->group->title;}
                else
                {return NULL;}
            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => $groupList,
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => '', 'id' => 'grid-schedule-search-group_id']
        ],
        [
            'attribute' => 'room_id',
            'label' => Yii::t('app', 'Room'),
            'value' => function($model){
                if ($model->room)
                {return $model->room->title;}
                else
                {return NULL;}
            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => $roomList,
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => '', 'id' => 'grid-schedule-search-room_id']
        ],
        'title',
        'date_start',
        'date_end',
        [
            'class' => 'common\widgets\ActionColumn',
            'contentOptions' => ['style' => 'white-space:nowrap;'],
            'template'=>'{update} {view}',
            'buttons' => [
                'update' => function ($url, $model) {
                    return Html::a('<i class="fas fa-pencil-alt"></i>',
                        Yii::$app->urlManager->createUrl(['schedule/update', 'id' => $model->id]),
                        [
                            'title' => Yii::t('yii', 'Edit'),
                            'class'=>'btn btn-sm btn-info',
                        ]
                    );
                },
                'view' => function ($url, $model) {
                    return Html::a('<i class="fas fa-eye"></i>',
                        Yii::$app->urlManager->createUrl(['schedule/view', 'id' => $model->id]),
                        [
                            'title' => Yii::t('yii', 'View'),
                            'class'=>'btn btn-sm btn-info',
                        ]
                    );
                },
            ],
        ],
    ]; 
    ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $gridColumn,
        'pjax' => true,
        'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-schedule']],
        'panel' => [
            'type' => GridView::TYPE_DEFAULT,
            'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i>  ' . Html::encode($this->title).' </h3>',
        ],
        'export' => false,
        // your toolbar can include the additional full export menu
        'toolbar' => [
        
            [
                'content'=>
                    Html::a('<i class="fas fa-plus"></i> Add New', ['create'], ['class' => 'btn btn-success'])
                    . ' '.
                    Html::a('<i class="fas fa-redo"></i> Reset List', ['index'], ['class' => 'btn btn-info'])
                    . ' '.
                    Html::a('<i class="fas fa-search"></i> Advance Search', ['#'], ['class' => 'btn btn-warning search-button']),
                'options' => ['class' => 'btn-group-md', 'style'=>'margin-right:5px']
            ],
            
            '{export}',
            ExportMenu::widget([
                'dataProvider' => $dataProvider,
                'columns' => $gridColumn,
                'target' => ExportMenu::TARGET_BLANK,
                'fontAwesome' => true,
                'dropdownOptions' => [
                    'label' => 'Full',
                    'class' => 'btn btn-default',
                    'itemsBefore' => [
                        '<li class="dropdown-header">Export All Data</li>',
                    ],
                ],
                'exportConfig' => [
                    ExportMenu::FORMAT_PDF => false
                ]
            ]) ,
        ],
        
        'responsive' => true,
        'hover' => true,
        'condensed' => true,
        'floatHeader' => false,

        'bordered' => true,
        'striped' => false,
        'responsiveWrap' => false,
        
        
    ]); ?>

</div>