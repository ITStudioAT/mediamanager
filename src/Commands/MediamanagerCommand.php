<?php

namespace Itstudioat\Mediamanager\Commands;

use Illuminate\Console\Command;

class MediamanagerCommand extends Command
{
    public $signature = 'mm';

    public $description = 'Version Of MediaManager';

    public function handle(): int
    {
        $this->comment(config('mediamanager.version'));

        return self::SUCCESS;
    }
}
