<?php

use function Pest\Laravel\artisan;

it('displays the version from config', function () {
    config()->set('mediamanager.version', '9.9.9');

    artisan('mm')
        ->expectsOutput('9.9.9')
        ->assertExitCode(0);
});
