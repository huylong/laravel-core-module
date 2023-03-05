<?php

// +----------------------------------------------------------------------
// | HuyPham [Just Like ～ ]
// +----------------------------------------------------------------------
// | Copyright (c) 2023 ~ now https://daygiare.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: HuyPham[ huyad1102@gmail.com ]
// +----------------------------------------------------------------------

namespace BlueStar\Commands\Migrate;

use BlueStar\BlueStarAdmin;
use BlueStar\Commands\BlueStarCommand;
use Illuminate\Support\Facades\File;

class SeedRun extends BlueStarCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bluestar:db:seed {module} {--seeder=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'bluestar db seed';

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
        $files = File::allFiles(BlueStarAdmin::getModuleSeederPath($this->argument('module')));

        $fileNames = [];

        $seeder = $this->option('seeder');

        if ($seeder) {
            foreach ($files as $file) {
                if (pathinfo($file->getBasename(), PATHINFO_FILENAME) == $seeder) {
                    $class = require_once $file->getRealPath();

                    $seeder = new $class();

                    $seeder->run();
                }
            }
        } else {
            foreach ($files as $file) {
                if (File::exists($file->getRealPath())) {
                    $class = require_once $file->getRealPath();
                    $class = new $class();
                        $class->run();
                }
            }
        }
    }
}
