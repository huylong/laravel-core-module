<?php

// +----------------------------------------------------------------------
// | HuyPham [Just Like ～ ]
// +----------------------------------------------------------------------
// | Copyright (c) 2023 ~ now https://daygiare.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: HuyPham[ huyad1102@gmail.com ]
// +----------------------------------------------------------------------

namespace Catch\Commands\Migrate;

use Catch\CatchAdmin;
use Catch\Commands\CatchCommand;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class MigrationRollback extends CatchCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'catch:migrate:rollback {module} {--force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'rollback module tables';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $module = $this->argument('module');

        if (! File::isDirectory(CatchAdmin::getModuleMigrationPath($module))) {
            Artisan::call('migration:rollback', [
                '--path' => CatchAdmin::getModuleRelativePath(CatchAdmin::getModuleMigrationPath($module)),

                '--force' => $this->option('force')
            ]);
        } else {
            $this->error('No migration files in module');
        }
    }
}
