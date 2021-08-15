<?php

namespace Kanboard\Plugin\Calendar\Helper;

use Kanboard\Core\Base;

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

    public function getUnscheduledTasks($projectId) {
        $allTasks = $this->taskFinderModel->getAll($projectId);
        $unscheduledTasks = array();
        foreach (array_reverse($allTasks) as $task) {
            if ((empty($task["date_due"]) && empty($task["date_start"]))) {
                array_push($unscheduledTasks, $task);
            }
        }
        return $unscheduledTasks;
    }

    public function getColors($colorId) {
        return array(
            "border_color" => $this->colorModel->getBorderColor($colorId),
            "background_color" => $this->colorModel->getBackgroundColor($colorId)
        );
    }
}
