<?php

namespace Kanboard\Plugin\Calendar;

use Kanboard\Core\Plugin\Base;
use Kanboard\Core\Translator;
use Kanboard\Plugin\Calendar\Formatter\ProjectApiFormatter;
use Kanboard\Plugin\Calendar\Formatter\TaskCalendarFormatter;
use Kanboard\Plugin\Calendar\Formatter\UnscheduledTasksFormatter;

class Plugin extends Base
{
    public function initialize()
    {
        $this->helper->register('calendar', '\Kanboard\Plugin\Calendar\Helper\CalendarHelper');

        $this->container['taskCalendarFormatter'] = $this->container->factory(function ($c) {
            return new TaskCalendarFormatter($c);
        });

        $this->container['projectApiFormatter'] = $this->container->factory(function ($c) {
            return new ProjectApiFormatter($c);
        });

        $this->container['unscheduledTasksFormatter'] = $this->container->factory(function ($c) {
            return new UnscheduledTasksFormatter($c);
        });

        $this->template->hook->attach('template:dashboard:page-header:menu', 'Calendar:dashboard/menu');
        $this->template->hook->attach('template:project:dropdown', 'Calendar:project/dropdown');
        $this->template->hook->attach('template:project-header:view-switcher', 'Calendar:project_header/views');
        $this->template->hook->attach('template:config:sidebar', 'Calendar:config/sidebar');

        $this->hook->on('template:layout:css', array('template' => 'plugins/Calendar/Assets/fullcalendar/main.min.css'));
        $this->hook->on('template:layout:js', array('template' => 'plugins/Calendar/Assets/fullcalendar/main.min.js'));
        $this->hook->on('template:layout:js', array('template' => 'plugins/Calendar/Assets/fullcalendar/locales-all.min.js'));
        $this->hook->on('template:layout:js', array('template' => 'plugins/Calendar/Assets/calendar.js'));
        $this->hook->on('template:layout:css', array('template' => 'plugins/Calendar/Assets/calendar.css'));
    }

    public function onStartup()
    {
        Translator::load($this->languageModel->getCurrentLanguage(), __DIR__.'/Locale');
    }

    public function getPluginName()
    {
        return 'Calendar';
    }

    public function getPluginDescription()
    {
        return t('Calendar view for Kanboard');
    }

    public function getPluginAuthor()
    {
        return 'Frédéric Guillot';
    }

    public function getPluginVersion()
    {
        return '1.3.2';
    }

    public function getPluginHomepage()
    {
        return 'https://github.com/kanboard/plugin-calendar';
    }

    public function getCompatibleVersion()
    {
        return '>=1.2.13';
    }
}

