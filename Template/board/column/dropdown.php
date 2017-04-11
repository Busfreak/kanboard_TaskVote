<li>
    <i class="fa fa-sort fa-fw"></i>
    <?= $this->url->link(t('sort by voting'), 'TaskVoteController', 'sort', array('plugin' => 'taskVote', 'project_id' => $swimlane['project_id'], 'column_id' => $column['id'])) ?>
</li>