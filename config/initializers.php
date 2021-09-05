<?php

// Starts dependency within the APP
return [
    App\Initializer\LocaleInitializer::class,
    App\Initializer\EloquentInitializer::class,
    App\Initializer\MonologInitializer::class,
];
