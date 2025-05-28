<?php

namespace Itstudioat\Mediamanager\Commands;

use Illuminate\Console\Command;

class MediamanagerCommand extends Command
{
    public $signature = 'mediamanager';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
