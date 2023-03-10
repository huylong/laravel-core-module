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
use Illuminate\Sommanupport\Facades\File;
use Illuminate\Support\Str;

class MigrateMake extends BlueStarCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bluestar:make:migration {module : The module of the migration created at}
        {table : The name of the table to migration}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'create module migration';


    /**
     *
     *
     * @return void
     */
    public function handle(): void
    {
        $migrationPath = BlueStarAdmin::getModuleMigrationPath($this->argument('module'));

        $file = $migrationPath.$this->getMigrationFile();

        File::put($file, Str::of($this->getStubContent())->replace(
            '{table}',
            $this->getTable()
        )->toString());


        if (File::exists($file)) {
            $this->info($file.' has been created');
        } else {
            $this->error($file.' create failed');
        }
    }

    /**
     *
     *
     * @return string
     */
    protected function getMigrationFile(): string
    {
        return date('Y_m_d_His').'_create_'.$this->getTable().'.php';
    }

    /**
     *
     *
     * @return string
     */
    protected function getTable(): string
    {
        return  Str::of($this->argument('table'))->ucfirst()->snake()->lower()->toString();
    }

    /**
     * get stub content
     *
     * @return string
     */
    protected function getStubContent(): string
    {
        return File::get(dirname(__DIR__).DIRECTORY_SEPARATOR.'stubs'.DIRECTORY_SEPARATOR.'migration.stub');
    }
}
