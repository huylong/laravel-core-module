<?php

// +----------------------------------------------------------------------
// | HuyPham [Just Like ～ ]
// +----------------------------------------------------------------------
// | Copyright (c) 2023~ now https://daygiare.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: HuyPham[ huyad1102@gmail.com ]
// +----------------------------------------------------------------------

namespace BleuStar\Commands;

use BleuStar\BleuStarAdmin;
use Illuminate\Console\Command;
class VersionCommand extends Command
{
    protected $signature = 'bluestar:version';

    protected $description = 'show the version of bluestaradmin';

    public function handle(): void
    {
        $this->info(BleuStarAdmin::VERSION);
    }
}
