<?php

namespace Kanboard\Plugin\Calendar\Formatter;

use DateTime;
use Kanboard\Core\Filter\FormatterInterface;
use Kanboard\Formatter\BaseFormatter;

/**
 * Calendar event formatter for unscheduled tasks
 *
 * @package  Kanboard\Plugin\Calendar\Formatter
 * @author   Till Schlueter
 */
class UnscheduledTasksFormatter extends TaskCalendarFormatter implements FormatterInterface
{

    /**
     * Return structured array of unscheduled tasks
     *
     * @access public
     * @return array
     */
    public function format()
    {
        $unscheduledTasks = $this->query->findAll();
        if (sizeof($unscheduledTasks) > 0) {
            $result = array();
            $projectId = $unscheduledTasks[0]['project_id'];
            $activeSwimlanes = $this->swimlaneModel->getAllByStatus($projectId);
            foreach ($activeSwimlanes as $activeSwimlane) {
                $result[$activeSwimlane['id']] = array(
                    'id' => $activeSwimlane['id'],
                    'name' => $activeSwimlane['name'],
                    'unscheduled_tasks' => array()
                );
            }
            foreach ($unscheduledTasks as $unscheduledTask) {
                $result[$unscheduledTask['swimlane_id']]['unscheduled_tasks'][] = array(
                    'id' => $unscheduledTask['id'],
                    'swimlane' => $unscheduledTask['swimlane_id'],
                    'title' => '[' . t('#%d', $unscheduledTask['id']) . '] '.$unscheduledTask['title'],
                    'backgroundColor' => $this->colorModel->getBackgroundColor($unscheduledTask['color_id']),
                    'borderColor' => $this->colorModel->getBorderColor($unscheduledTask['color_id']),
                    'url' => $this->helper->url->to('TaskViewController', 'show', array('task_id' => $unscheduledTask['id'], 'project_id' => $unscheduledTask['project_id'])),
                );
            }
            return $result;
        } else {
            return array();
        }
    }
}
