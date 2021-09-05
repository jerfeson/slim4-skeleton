<?php

//Migration
use App\App;
use App\Helpers\ArrayUtils;

$migrateCommand = [];

if (App::isDevelopment()) {
    $migrations = scandir(MIGRATION_PATH);

    foreach ($migrations as $migration) {
        if ($migration == '.' || $migration === '..') {
            continue;
        }
        $migrateCommand[] = 'Console\\Migration\\' . pathinfo($migration, PATHINFO_FILENAME);
    }
}

// add your  custom commands here
$customCommands = [
    Console\SampleCommand::class,
];

$default['commands'] = ArrayUtils::arrayMergeRecursiveDistinct($customCommands, $migrateCommand);

return $default;
