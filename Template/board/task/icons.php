<?php
$votes = $this->TaskVoteHelper->getVotes($task['id']);
?>
    <span class="task-board-icons-row">
        <?php if ($votes['can_upvote']): ?>
            <?= $this->url->link(
                '<i class="fa fa-thumbs-up fa-fw"></i>' . $votes['up_votes'],
                'TaskVoteController',
                'upvote',
                array('plugin' => 'taskVote', 'project_id' => $task['project_id'], 'task_id' => $task['id'], 'column_id' => $task['column_id']),
                false,
                '',
                t('upvote')
            ) ?>
        <?php else: ?>
            <span title="<?= t('You have already voted for this task') ?>">
                <i class="fa fa-thumbs-up fa-fw"></i><?= $votes['up_votes'] ?>
            </span>
        <?php endif ?>

        <?php if ($votes['can_downvote']): ?>
            <?= $this->url->link(
                '<i class="fa fa-thumbs-down fa-fw"></i>' . $votes['down_votes'],
                'TaskVoteController',
                'downvote',
                array('plugin' => 'taskVote', 'project_id' => $task['project_id'], 'task_id' => $task['id'], 'column_id' => $task['column_id']),
                false,
                '',
                t('downvote')
            ) ?>
        <?php else: ?>
            <span title="<?= t('You have already voted for this task') ?>">
                <i class="fa fa-thumbs-down fa-fw"></i><?= $votes['down_votes'] ?>
            </span>
        <?php endif ?>
    </span>