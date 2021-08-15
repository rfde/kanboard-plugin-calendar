<?= $this->projectHeader->render($project, 'CalendarController', 'project', false, 'Calendar') ?>
<div class="calendar-view-container">
    <?= $this->calendar->render(
        $this->url->href('CalendarController', 'projectEvents', array('project_id' => $project['id'], 'plugin' => 'Calendar')),
        $this->url->href('CalendarController', 'save', array('project_id' => $project['id'], 'plugin' => 'Calendar'))
    ) ?>
    <div id="calendar-tasklist">
        <div class="calendar-tasklist-title">
            <strong><?= t('Unscheduled tasks') ?></strong>
        </div>
        <?php
            $unscheduledTasks = $this->calendar->getUnscheduledTasks($project["id"]);
            foreach ($unscheduledTasks as $task):
                $colors = $this->calendar->getColors($task["color_id"]);
        ?>
                <a href="<?= $this->url->href('TaskViewController', 'show', array('task_id' => $task["id"])) ?>">
                    <div style="background-color: <?= $colors["background_color"] ?>; border: 1px solid <?= $colors["border_color"] ?>;" class="fc-event fc-h-event fc-daygrid-event fc-daygrid-block-event" data-id="<?= $task["id"] ?>">
                        <div class="fc-event-main">[#<?= $task["id"]?>] <?= $task["title"] ?></div>
                    </div>
                </a>
        <?php endforeach ?>
    </div>
</div>