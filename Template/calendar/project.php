<?= $this->projectHeader->render($project, 'CalendarController', 'project', false, 'Calendar') ?>
<div class="calendar-view-container">
    <?= $this->calendar->render(
        $this->url->href('CalendarController', 'projectEvents', array('project_id' => $project['id'], 'plugin' => 'Calendar')),
        $this->url->href('CalendarController', 'save', array('project_id' => $project['id'], 'plugin' => 'Calendar'))
    ) ?>
    <div id="calendar-tasklist">
        <div class="calendar-tasklist-title">
            <strong><?= t('Unscheduled tasks') ?></strong>
            <?= $this->modal->large('plus', '', 'TaskCreationController', 'show', array('project_id' => $project['id'])) ?>
        </div>
        <?php
            $unscheduledTasks = $this->calendar->getUnscheduledTasks($project["id"]);
            foreach ($unscheduledTasks as $swimlane) {
                if (sizeof($swimlane["unscheduled_tasks"]) > 0) {
        ?>
                    <div class="calendar-tasklist-title calendar-tasklist-title-swimlane">
                        <?= $swimlane['name'] ?>
                        <?= $this->modal->large('plus', '', 'TaskCreationController', 'show', array('project_id' => $project['id'], 'swimlane_id' => $swimlane['id'])) ?>
                    </div>
            <?php
                    foreach (array_reverse($swimlane["unscheduled_tasks"]) as $task) {
            ?>
                        <a href="<?= $this->url->href('TaskViewController', 'show', array('task_id' => $task["id"])) ?>">
                            <div style="background-color: <?= $task["backgroundColor"] ?>; border: 1px solid <?= $task["borderColor"] ?>;" class="fc-event fc-h-event fc-daygrid-event fc-daygrid-block-event" data-id="<?= $task["id"] ?>">
                                <div class="fc-event-main"><?= $task["title"] ?></div>
                            </div>
                        </a>
            <?php
                    } // foreach (task)
                } // end if
            } // foreach (swimlane)
        ?>
    </div>
</div>