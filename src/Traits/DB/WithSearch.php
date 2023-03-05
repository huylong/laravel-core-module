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

/**
 * base operate
 */
trait WithSearch
{
    /**
     * @var array $searchable
     */
    public array $searchable = [];

    /**
     *
     * @param array $searchable
     * @return $this
     */
    public function setSearchable(array $searchable): static
    {
        $this->searchable = $searchable;

        return $this;
    }
}
