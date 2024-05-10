<?php
/** @var yii\web\View $this */
$this->title = Yii::$app->name;

use yii\helpers\Html;
use yii\helpers\Url;

?>


<!-- Content Row -->
<div class="row">

    <!-- Earnings (Monthly) Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            <?= Yii::t('app', 'Participant (Offline)'); ?>
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?= $countOfflineParticipant ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-user fa-2x text-gray-300"></i>
                    </div>
                </div>
                <a href="<?=Url::to(['participant/index'])?>" class="card-link stretched-link"></a>
            </div>
        </div>
    </div>

    <!-- Earnings (Monthly) Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            <?= Yii::t('app', 'Participant (Online)'); ?>
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?= $countOnlineParticipant ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-user fa-2x text-primary"></i>
                    </div>
                </div>
                <a href="<?=Url::to(['participant/index'])?>" class="card-link stretched-link"></a>
            </div>
        </div>
    </div>


    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            <?= Yii::t('app', 'Schedule'); ?>
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?= $countSchedule ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                    </div>
                </div>
                <a href="<?=Url::to(['schedule/index'])?>" class="card-link stretched-link"></a>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            <?= Yii::t('app', 'Assessment'); ?>
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?= $countAssessment ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-comments fa-2x text-gray-300"></i>
                    </div>
                </div>
                <a href="<?=Url::to(['assessment/index'])?>" class="card-link stretched-link"></a>
            </div>
        </div>
    </div>
</div>

<!-- Content Row -->

<div class="row">
    <div class="col-md-6 col-xs-12">

    </div>
</div>
