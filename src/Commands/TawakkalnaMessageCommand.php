<?php

namespace Abather\TawakkalnaMessage\Commands;

use Illuminate\Console\Command;

class TawakkalnaMessageCommand extends Command
{
    public $signature = 'tawakkalna-message';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
