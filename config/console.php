<?php

//Migration
use App\App;
use App\Helpers\ArrayUtils;

$customCommands = [];

if (App::isDevelopment()) {
    $migrations = scandir(MIGRATION_PATH);

    foreach ($migrations as $migration) {
        if ($migration == '.' || $migration === '..') {
            continue;
        }
        
        $customCommands[] = 'Console\\Migration\\' . pathinfo($migration, PATHINFO_FILENAME);
    }
}

// add your  custom commands here
$customCommands = Console\SampleCommand::class;

$default['commands'] = ArrayUtils::arrayMergeRecursiveDistinct($customCommands);

return $default;
