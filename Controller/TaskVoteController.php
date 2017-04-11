<?php

namespace Kanboard\Plugin\TaskVote\Controller;

use Kanboard\Controller\BaseController;

/**
 * TaskVote
 *
 * @package controller
 * @author  Martin Middeke
 */
class TaskVoteController extends BaseController {

    /**
     * Sort tasks in specific column according to their vote
     *
     * @access public
     */
    public function sort()
    {
        $column_id = $this->request->getIntegerParam('column_id');
        $project = $this->getProject();
        $search = $this->helper->projectHeader->getSearchQuery($project);

        $this->taskVoteModel->sortByVoting($column_id);

        $this->response->redirect($this->helper->url->to('BoardViewController', 'show', array('project_id' => $project['id'], 'search' => $search)), true);
    }

    /**
     * upvote task
     *
     * @access public
     */
    public function upvote()
    {
        $task_id = $this->request->getIntegerParam('task_id');
        $column_id = $this->request->getIntegerParam('column_id');
        $project = $this->getProject();
        $search = $this->helper->projectHeader->getSearchQuery($project);

        $result = $this->taskVoteModel->upvote($task_id, $column_id);

        $this->taskVoteModel->sortByVoting($column_id);

        $this->response->redirect($this->helper->url->to('BoardViewController', 'show', array('project_id' => $project['id'], 'search' => $search)), true);
    }

    /**
     * downvote task
     *
     * @access public
     */
    public function downvote()
    {
        $task_id = $this->request->getIntegerParam('task_id');
        $column_id = $this->request->getIntegerParam('column_id');
        $project = $this->getProject();
        $search = $this->helper->projectHeader->getSearchQuery($project);

        $result = $this->taskVoteModel->downvote($task_id, $column_id);

        $this->taskVoteModel->sortByVoting($column_id);

        $this->response->redirect($this->helper->url->to('BoardViewController', 'show', array('project_id' => $project['id'], 'search' => $search)), true);
    }
}
