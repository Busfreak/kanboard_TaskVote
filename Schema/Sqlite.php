<?php

namespace Kanboard\Plugin\TaskVote\Schema;

use Kanboard\Core\Security\Token;
use Kanboard\Core\Security\Role;
use PDO;

const VERSION = 1;

function version_1($pdo)
{
    $pdo->exec('CREATE TABLE IF NOT EXISTS task_has_votes (
        "id" INTEGER PRIMARY KEY,
        "task_id" INTEGER NOT NULL,
        "user_id" INTEGER NOT NULL,
        "date" INTEGER,
        "vote" INTEGER,
        FOREIGN KEY(task_id) REFERENCES tasks(id) ON DELETE CASCADE
    )');
}

