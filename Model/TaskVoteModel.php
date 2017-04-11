<?php

namespace Kanboard\Plugin\TaskVote\Model;

use Kanboard\Core\Base;
use Kanboard\Model\SubtaskModel;
use Kanboard\Model\UserModel;
use Kanboard\Model\TaskModel;

/**
 * TaskVote model
 *
 * @package  model
 * @author   Martin Middeke
 */
class TaskVoteModel extends Base
{
    /**
     * SQL table name for TaskVote
     *
     * @var string
     */
    const TABLE = 'task_has_votes';

    /**
     * Return user can vote
     *
     * @access public
     * @param  integer   $task_id    Task id
     * @return boolean
     */
    public function getUserCanVote($task_id)
    {
        return !$this->db->table(self::TABLE)
            ->eq('task_id', $task_id)
            ->eq('user_id', $this->userSession->getId())
            ->exists();
    }

    /**
     * Return upvote
     *
     * @access public
     * @param  integer   $task_id    Task id
     * @return integer
     */
    public function getUpVotes($task_id)
    {
        $stats = $this->db->table(self::TABLE)
            ->columns('SUM(vote) AS vote')
            ->eq('task_id', $task_id)
            ->gte('vote', 0)
            ->findOne();
        return abs($stats['vote']);
    }

     /**
     * Return downvote
     *
     * @access public
     * @param  integer   $task_id    Task id
     * @return integer
     */
    public function getDownVotes($task_id)
    {
        $stats = $this->db->table(self::TABLE)
            ->columns('SUM(vote) AS vote')
            ->eq('task_id', $task_id)
            ->lte('vote', 0)
            ->findOne();
        return abs($stats['vote']);
    }

     /**
     * Return voting order
     *
     * @access private
     * @param  integer   $column_id    Column id
     * @return array
     */
    private function getVotingOrder($column_id)
    {
        return $this->db
            ->table(TaskModel::TABLE)
            ->columns(
                TaskModel::TABLE.'.id',
                TaskModel::TABLE.'.position'
            )
            ->subquery("SELECT COALESCE(SUM(vote), 0) FROM ".self::TABLE." WHERE task_id=".TaskModel::TABLE.".id", 'vote')
            ->join(self::TABLE, 'task_id', 'id')
            ->eq(TaskModel::TABLE.'.column_id', $column_id)
            ->desc('vote')
            ->groupBy(TaskModel::TABLE.'.id')
            ->findAll();
    }

     /**
     * Sort tasks by voting
     *
     * @access public
     * @param  integer   $column_id    Column id
     * @return arboolean|integerray
     */
    public function sortByVoting($column_id)
    {
        $tasks = $this->getVotingOrder($column_id);
        $position = 1;
        foreach ($tasks as $task) {
            $this->db->table(TaskModel::TABLE)->eq('id', intval($task['id']))->update(array('position' => $position));
            $position += 1;
        }
    }

    /**
     * upvote task
     *
     * @access public
     * @param  integer   $task_id    Column id
     * @return boolean|integer
     */
    public function upvote($task_id, $column_id)
    {
        return $this->vote($task_id, $column_id, 1);
    }

    /**
     * downvote task
     *
     * @access public
     * @param  integer   $task_id    Column id
     * @return array
     */
    public function downvote($task_id, $column_id)
    {
        return $this->vote($task_id, $column_id, -1);
    }

    /**
     * downvote task
     *
     * @access private
     * @param  integer   $task_id    Column id
     * @return array
     */
    private function vote($task_id, $column_id, $value)
    {
        $this->db->startTransaction();

        if (! $this->db->table(self::TABLE)->insert(array('task_id' => $task_id, 'user_id' => $this->userSession->getId(), 'date' => time(), 'vote' => $value))) {
            $this->db->cancelTransaction();
            return false;
        }

        $vote_id = $this->db->getLastId();

        $this->db->closeTransaction();

        $this->sortByVoting($column_id);

        return (int) $vote_id;
    }
}