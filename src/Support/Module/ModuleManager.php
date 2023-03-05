<?php

// +----------------------------------------------------------------------
// | HuyPham [Just Like ～ ]
// +----------------------------------------------------------------------
// | Copyright (c) 2023 ~ now https://daygiare.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: HuyPham[ huyad1102@gmail.com ]
// +----------------------------------------------------------------------


declare(strict_types=1);

namespace Catch\Support\Module;

use Catch\Support\Module\Driver\DatabaseDriver;
use Catch\Support\Module\Driver\FileDriver;
use Illuminate\Contracts\Container\Container;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Manager;

class ModuleManager extends Manager
{
    public function __construct(Container|\Closure $container)
    {
        if ($container instanceof \Closure) {
            $container = $container();
        }

        parent::__construct($container);
    }

    /**
     * @return string|null
     */
    public function getDefaultDriver(): string|null
    {
        // TODO: Implement getDefaultDriver() method.
        return $this->config->get('catch.module.driver.default', $this->defaultDriver());
    }

    /**
     * create file driver
     *
     * @return FileDriver
     */
    public function createFileDriver(): FileDriver
    {
        return new FileDriver();
    }

    /**
     * create database driver
     *
     * @return DatabaseDriver
     */
    public function createDatabaseDriver(): DatabaseDriver
    {
        return new DatabaseDriver();
    }

    /**
     * default driver
     *
     * @return string
     */
    protected function defaultDriver():string
    {
        return 'file';
    }
}
