<?php

namespace Kanboard\Plugin\Calendar\Helper;

use Kanboard\Core\Base;
use Kanboard\Filter\TaskProjectFilter;
use Kanboard\Filter\TaskStatusFilter;
use Kanboard\Model\TaskModel;

/**
 * Calendar Helper
 *
 * @package Kanboard\Plugin\Calendar\Helper
 * @author  Frederic Guillot
 */
class CalendarHelper extends Base
{
    /**
     * Render calendar component
     *
     * @param  string $checkUrl
     * @param  string $saveUrl
     * @return string
     */
    public function render($checkUrl, $saveUrl)
    {
        $params = array(
            'checkUrl' => $checkUrl,
            'saveUrl' => $saveUrl,
            'locale' => strtolower($this->languageModel->getJsLanguageCode())
        );

        return '<div class="js-calendar" data-params=\''.json_encode($params, JSON_HEX_APOS).'\'></div>';
    }

    public function getUnscheduledTasks($projectId)
    {
        $search = $this->userSession->getFilters($projectId);
        $startColumn = $this->configModel->get('calendar_project_tasks', 'date_started');

        $unscheduledTasksQueryBuilder = $this->taskLexer->build($search)
            ->withFilter(new TaskProjectFilter($projectId))
            ->withFilter(new TaskStatusFilter(TaskModel::STATUS_OPEN));

        $unscheduledTasksQueryBuilder
            ->getQuery()
            ->addCondition('(' . $startColumn . ' = \'0\' ' . ' OR ' . $startColumn . ' IS NULL) AND (date_due = \'0\' OR date_due IS NULL)');

        $unscheduledTasks = $unscheduledTasksQueryBuilder
            ->format($this->taskCalendarFormatter->setColumns($startColumn, 'date_due', 'date_completed'));

        return $unscheduledTasks;
    }
}
