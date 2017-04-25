<?php

namespace Kanboard\Plugin\TaskVote;

use Kanboard\Core\Plugin\Base;
use Kanboard\Core\Translator;

/**
 * TaskVote
 *
 * @author Martin Middeke
 */

class Plugin extends Base
{
    public function initialize()
    {
        $this->template->hook->attach('template:board:task:icons', 'TaskVote:board/task/icons');
    }

    public function onStartup()
    {
        Translator::load($this->languageModel->getCurrentLanguage(), __DIR__.'/Locale');
    }

    public function getClasses()
    {
        return array(
            'Plugin\TaskVote\Model' => array(
                'TaskVoteModel'
            ),
        );
    }

    public function getHelpers()
    {
        return array(
            'Plugin\TaskVote\Helper' => array(
                'TaskVoteHelper'
            )
        );
    }

    public function getPluginName()
    {
        return 'TaskVote';
    }

    public function getPluginDescription()
    {
        return t('Voting for Tasks');
    }

    public function getPluginAuthor()
    {
        return 'Martin Middeke';
    }

    public function getPluginVersion()
    {
        return '0.0.2';
    }

    public function getPluginHomepage()
    {
        return 'https://github.com/busfreak/TaskVote';
    }

    public function getCompatibleVersion()
    {
        return '>=1.0.42';
    }
}
