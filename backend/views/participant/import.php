<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var backend\models\Participant $model
 */

$this->title = Yii::t('app', 'Import {modelClass}', [
    'modelClass' => 'Participant',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Participants'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="card border-default mb-3">
    <div class="card-header">Please fill out the form below 
        <span class="pull-right">
            Participant
        </span>
    </div>
    <div class="card-body text-default">
        <div class="participant-create">
            <?= $this->render('_import', [
                'model' => $model,
                'officeList'=>$officeList,
                'groupList' => $groupList,
                'archiveList' => $archiveList
            ]) 
            ?>
        </div>
    </div>
</div>