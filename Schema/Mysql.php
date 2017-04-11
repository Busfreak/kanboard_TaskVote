<?php

namespace Kanboard\Plugin\TaskVote\Schema;

use PDO;

const VERSION = 1;

function version_1(PDO $pdo)
{
    $pdo->exec('CREATE TABLE IF NOT EXISTS task_has_votes (
        `id` INT NOT NULL AUTO_INCREMENT,
        `task_id` INT NOT NULL,
        `user_id` INT NOT NULL,
        `date` INT,
        `vote` INT,
        FOREIGN KEY(task_id) REFERENCES tasks(id) ON DELETE CASCADE,
        PRIMARY KEY(id)
    ) ENGINE=InnoDB CHARSET=utf8');
}

