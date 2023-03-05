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
use Illuminate\Support\Str;
use PhpParser\Node\Name;

class SeederMake extends BlueStarCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bluestar:make:seeder {module} {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'make module seeder';


    /**
     *
     * @return void
     * @throws \Exception
     * @author BlueStarAdmin
     * @time 2021年08月01日
     */
    public function handle(): void
    {
        $seederPath = BlueStarAdmin::getModuleSeederPath($this->argument('module'));

        $file = $seederPath.$this->getSeederName().'.php';

        if (File::exists($file)) {
            $answer = $this->ask($file.' already exists, Did you want replace it?', 'Y');

            if (! Str::of($answer)->lower()->exactly('y')) {
                exit;
            }
        }

        File::put($file, $this->getSeederContent());

        if (File::exists($file)) {
            $this->info($file.' has been created');
        } else {
            $this->error($file.' create failed');
        }
    }

    /**
     * seeder content
     *
     * @return string
     * @throws \Exception
     */
    protected function getSeederContent(): string
    {
        return File::get(dirname(__DIR__).DIRECTORY_SEPARATOR.'stubs'.DIRECTORY_SEPARATOR.'seeder.stub');
    }

    /**
     * seeder name
     *
     * @return string
     */
    protected function getSeederName(): string
    {
        return Str::of($this->argument('name'))->ucfirst()->toString();
    }
}
