<?php

namespace BlueStar\Commands\Migrate;

use BlueStar\BlueStarAdmin;
use BlueStar\Commands\BlueStarCommand;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class MigrateFresh extends BlueStarCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bluestar:migrate:fresh {module} {--force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'bluestar migrate fresh';

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

        if (! File::isDirectory(BlueStarAdmin::getModuleMigrationPath($module))) {
            Artisan::call('migration:fresh', [
                '--path' => BlueStarAdmin::getModuleRelativePath(BlueStarAdmin::getModuleMigrationPath($module)),

                '--force' => $this->option('force')
            ]);
        } else {
            $this->error('No migration files in module');
        }
    }
}
