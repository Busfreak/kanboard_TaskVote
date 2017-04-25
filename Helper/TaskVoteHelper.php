<?php

namespace Kanboard\Plugin\TaskVote\Helper;

use Kanboard\Core\Base;

/**
 * TaskVote Helper
 *
 * @package helper
 * @author  Martin Middeke
 */
class TaskVoteHelper extends Base
{
    /**
     * Count votes for task_id
     *
     * @access public
     * @return integer
     */
     public function getVotes($task_id)
    {
       return array(
            'can_upvote' => $this->taskVoteModel->getUserCanUpVote($task_id),
            'can_downvote' => $this->taskVoteModel->getUserCanDownVote($task_id),
            'up_votes' => $this->taskVoteModel->getUpVotes($task_id),
            'down_votes' => $this->taskVoteModel->getDownVotes($task_id)
        );
    }
}