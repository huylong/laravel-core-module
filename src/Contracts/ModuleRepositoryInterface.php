<?php

// +----------------------------------------------------------------------
// | HuyPham [Just Like ～ ]
// +----------------------------------------------------------------------
// | Copyright (c) 2023 ~ now https://daygiare.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: HuyPham[ huyad1102@gmail.com ]
// +----------------------------------------------------------------------


declare(strict_types=1);

namespace BlueStar\Contracts;

use Illuminate\Support\Collection;

interface ModuleRepositoryInterface
{
    public function all(array $search): Collection;

    public function create(array $module): bool|int;

    public function show(string $name): Collection;

    public function update(string $name, array $module): bool|int;

    public function delete(string $name): bool|int;

    public function disOrEnable(string $name): bool|int;

    public function getEnabled(): Collection;

    public function enabled(string $moduleName): bool;
}
