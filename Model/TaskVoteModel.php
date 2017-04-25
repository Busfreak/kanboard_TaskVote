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
     * Return user can upvote
     *
     * @access public
     * @param  integer   $task_id    Task id
     * @return boolean
     */
    public function getUserCanUpVote($task_id)
    {
        return !$this->db->table(self::TABLE)
            ->eq('task_id', $task_id)
            ->eq('user_id', $this->userSession->getId())
            ->eq('vote', 1)
            ->exists();
    }

    /**
     * Return user can downvote
     *
     * @access public
     * @param  integer   $task_id    Task id
     * @return boolean
     */
    public function getUserCanDownVote($task_id)
    {
        return !$this->db->table(self::TABLE)
            ->eq('task_id', $task_id)
            ->eq('user_id', $this->userSession->getId())
            ->eq('vote', -1)
            ->exists();
    }

    /**
     * Return upvotes
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
     * Return downvotes
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
     * vote task
     *
     * @access private
     * @param  integer   $task_id    Column id
     * @return array
     */
    private function vote($task_id, $column_id, $value)
    {
        $this->db->startTransaction();

        if ($this->getUserCanUpVote($task_id) and $this->getUserCanDownVote($task_id)){
            if (! $this->db->table(self::TABLE)->insert(array('task_id' => $task_id, 'user_id' => $this->userSession->getId(), 'date' => time(), 'vote' => $value))) {
                $this->db->cancelTransaction();
                return false;
            }
        $vote_id = $this->db->getLastId();
        }
        else {
            $vote = $this->db->table(self::TABLE)
                ->eq('task_id', $task_id)
                ->eq('user_id', $this->userSession->getId())
                ->findOne();
            $vote_id = (int) $vote['id'];

            if (! $this->db->table(self::TABLE)->eq('id', $vote_id)->update(array('date' => time(), 'vote' => $value))) {
                $this->db->cancelTransaction();
                return false;
            }
        }

        $this->db->closeTransaction();

        return (int) $vote_id;
    }
}