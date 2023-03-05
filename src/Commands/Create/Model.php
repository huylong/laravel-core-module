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
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class Model extends BlueStarCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bluestar:make:model {module} {model} {--t= : the model of table name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'create bluestar module';

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
    public function handle()
    {
        if (! Schema::hasTable($this->getTableName())) {
            $this->error('Schema ['.$this->getTableName().'] not found');
            exit;
        }

        $modelPath = BlueStarAdmin::getModuleModelPath($this->argument('module'));

        $file = $modelPath.$this->getModelFile();

        if (File::exists($file)) {
            $answer = $this->ask($file.' already exists, Did you want replace it?', 'Y');

            if (! Str::of($answer)->lower()->exactly('y')) {
                exit;
            }
        }

        File::put($file, $this->getModelContent());

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
    protected function getModelFile(): string
    {
        return $this->getModelName().'.php';
    }

    /**
     *
     *
     * @return string
     */
    protected function getModelName(): string
    {
        return Str::of($this->argument('model'))->ucfirst()->toString();
    }

    /**
     * get stub content
     *
     * @return string
     */
    protected function getStubContent(): string
    {
        return File::get(dirname(__DIR__).DIRECTORY_SEPARATOR.'stubs'.DIRECTORY_SEPARATOR.'model.stub');
    }


    /**
     * get model content
     *
     * @return string
     */
    protected function getModelContent(): string
    {
        return Str::of($this->getStubContent())

            ->replace(
                [
                    '{namespace}', '{model}', '{table}', '{fillable}'
                ],
                [
                    $this->getModelNamespace(), $this->getModelName(),

                    $this->getTableName(), $this->getFillable()
                ]
            )->toString();
    }

    /**
     * get namespace
     *
     * @return string
     */
    protected function getModelNamespace(): string
    {
        return trim(BlueStarAdmin::getModuleModelNamespace($this->argument('module')), '\\');
    }

    /**
     * get table name
     *
     * @return string
     */
    protected function getTableName(): string
    {
        return $this->option('t') ?

            $this->option('t') :

            Str::of($this->argument('model'))->snake()->lcfirst()->toString();
    }

    /**
     *
     *
     * @return string
     */
    protected function getFillable(): string
    {
        $fillable = Str::of('');

        foreach (getTableColumns($this->getTableName()) as $column) {
            $fillable = $fillable->append("'{$column}', ");
        }

        return $fillable->trim(',')->toString();
    }
}
