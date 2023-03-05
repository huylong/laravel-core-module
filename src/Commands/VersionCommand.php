<?php

// +----------------------------------------------------------------------
// | HuyPham [Just Like ～ ]
// +----------------------------------------------------------------------
// | Copyright (c) 2023~ now https://daygiare.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: HuyPham[ huyad1102@gmail.com ]
// +----------------------------------------------------------------------

namespace Catch\Commands;

use Catch\CatchAdmin;
use Illuminate\Console\Command;
class VersionCommand extends Command
{
    protected $signature = 'catch:version';

    protected $description = 'show the version of catchadmin';

    public function handle(): void
    {
        $this->info(CatchAdmin::VERSION);
    }
}
