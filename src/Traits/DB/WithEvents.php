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

use Closure;

/**
 * base operate
 */
trait WithEvents
{
    protected ?Closure $beforeGetList = null;


    protected ?Closure $afterFirstBy = null;

    /**
     *
     * @param Closure $closure
     * @return $this
     */
    public function setBeforeGetList(Closure $closure): static
    {
        $this->beforeGetList = $closure;

        return $this;
    }

    /**
     *
     * @param Closure $closure
     * @return $this
     */
    public function setAfterFirstBy(Closure $closure): static
    {
        $this->afterFirstBy = $closure;

        return $this;
    }
}
