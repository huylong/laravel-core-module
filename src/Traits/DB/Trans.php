<?php

// +----------------------------------------------------------------------
// | HuyPham [Just Like ～ ]
// +----------------------------------------------------------------------
// | Copyright (c) 2023 ~ now https://daygiare.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: HuyPham[ huyad1102@gmail.com ]
// +----------------------------------------------------------------------

declare(strict_types=1);

namespace BlueStar\Traits\DB;

use Illuminate\Support\Facades\DB;

/**
 * transaction
 */
trait Trans
{
    /**
     * begin transaction
     */
    public function beginTransaction(): void
    {
        DB::beginTransaction();
    }

    /**
     * commit
     */
    public function commit(): void
    {
        DB::commit();
    }

    /**
     * rollback
     */
    public function rollback(): void
    {
        DB::rollBack();
    }

    /**
     * transaction
     *
     * @param \Closure $closure
     */
    public function transaction(\Closure $closure): void
    {
        DB::transaction($closure);
    }
}
