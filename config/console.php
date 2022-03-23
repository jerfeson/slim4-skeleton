<?php

//Migration
use App\App;
use App\Helpers\ArrayUtils;

$commands = [];

if (App::isDevelopment()) {
    $migrations = scandir(MIGRATION_PATH);

    foreach ($migrations as $migration) {
        if ($migration == '.' || $migration === '..') {
            continue;
        }

        $commands[] = 'Console\\Migration\\' . pathinfo($migration, PATHINFO_FILENAME);
    }
}

// add your  custom commands here
$commands[] = Console\SampleCommand::class;

$default['commands'] = ArrayUtils::arrayMergeRecursiveDistinct($commands);

return $default;
