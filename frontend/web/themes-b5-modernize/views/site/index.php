<?php
use common\helper\DateHelper;
use yii\bootstrap5\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var common\models\Schedule $schedules */
/** @var common\models\Participant $participant */
/** @var common\service\ScheduleDetailService $scheduleDetailService */
/** @var frontend\models\TokenForm $tokenForm */

$this->title = Yii::$app->name;
?>


<!-- Content Row -->
<div class="card border-default mb-3">
    <div class="card-header">
        <?= Yii::t('app', 'Schedule'); ?>
        <span class="float-right float-end">
        </span>
    </div>
    <div class="card-body text-default">
        <div class="row">
            <div class="col-md-7 col-xs-12">

                <?php
                $this->registerJs("
                    $('#calendar').fullCalendar({
                        events: '" . Url::to(['site/get-schedules']) . "',
                        editable: false,
                        eventLimit: true,
                        header: {
                            left: 'prev,next today',
                            center: 'title',
                            right: 'month,agendaWeek,agendaDay'
                        },
                        timeFormat: 'H:mm', // 24-hour format (HH:mm),
                        
                        eventClick: function(event) {
                            if (event.url) {
                                window.location.href = event.url; // Redirect to the schedule URL
                                return false; // Prevent the default action (like opening in a new tab)
                            }
                        }
                    });
                ");
                ?>

                <div id="calendar"></div>
            </div>
            <div class="col-md-5 col-xs-12">

                <table class="table table-striped table-sm">
                    <thead>
                    <tr>
                        <th class="center">#</th>
                        <th><?= Yii::t('app', 'Date'); ?></th>
                        <th><?= Yii::t('app', 'Start'); ?></th>
                        <th><?= Yii::t('app', 'Room'); ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($schedules as $i => $schedule) {
                        ?>
                        <tr>
                            <td class="center"><?= $schedule->title; ?></td>
                            <td class="left">
                                <?php
                                $dateStart = DateHelper::formatDate($schedule->date_start);
                                $title =
                                    Html::a($dateStart, ['schedule/view',
                                        'id' => $schedule->id,
                                        'title' => $schedule->title
                                    ]);
                                echo $title;
                                ?>
                            </td>
                            <td class="left">
                                <?= DateHelper::formatTime($schedule->date_start); ?>
                            </td>
                            <td class="left">
                                <?= $schedule->room->title; ?>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
