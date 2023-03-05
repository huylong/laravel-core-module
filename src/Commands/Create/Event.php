<?php

// +----------------------------------------------------------------------
// | HuyPham [Just Like ～ ]
// +----------------------------------------------------------------------
// | Copyright (c) 2023 ~ now https://daygiare.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: HuyPham[ huyad1102@gmail.com ]
// +----------------------------------------------------------------------

declare(strict_types=1);

namespace BlueStar\Commands\Create;

use BlueStar\BlueStarAdmin;
use BlueStar\Commands\BlueStarCommand;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

;

class Event extends BlueStarCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bluestar:make:event {module} {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'create bluestar module event';


    public function handle()
    {
        $eventPath = BlueStarAdmin::getModuleEventPath($this->argument('module'));

        $file = $eventPath.$this->getEventFile();

        if (File::exists($file)) {
            $answer = $this->ask($file.' already exists, Did you want replace it?', 'Y');

            if (! Str::of($answer)->lower()->exactly('y')) {
                exit;
            }
        }

        File::put($file, Str::of($this->getStubContent())->replace([
            '{namespace}', '{event}'
        ], [trim(BlueStarAdmin::getModuleEventsNamespace($this->argument('module')), '\\'), $this->getEventName()])->toString());

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
    protected function getEventFile(): string
    {
        return $this->getEventName().'.php';
    }

    /**
     *
     *
     * @return string
     */
    protected function getEventName(): string
    {
        return  Str::of($this->argument('name'))
            ->whenContains('Event', function ($str) {
                return $str;
            }, function ($str) {
                return $str->append('Event');
            })->ucfirst()->toString();
    }

    /**
     * get stub content
     *
     * @return string
     */
    protected function getStubContent(): string
    {
        return File::get(dirname(__DIR__).DIRECTORY_SEPARATOR.'stubs'.DIRECTORY_SEPARATOR.'event.stub');
    }
}
