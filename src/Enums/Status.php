<?php

// +----------------------------------------------------------------------
// | HuyPham [Just Like ～ ]
// +----------------------------------------------------------------------
// | Copyright (c) 2023 ~ now https://daygiare.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: HuyPham[ huyad1102@gmail.com ]
// +----------------------------------------------------------------------

declare(strict_types=1);

namespace BlueStar\Enums;

enum Status: int implements Enum
{
    case Enable = 1;

    case Disable = 2;

    /**
     * @desc name
     *
     */
    public function name(): string
    {
        return match ($this) {
            Status::Enable => 'Enable',

            Status::Disable => 'Disable'
        };
    }

    /**
     * get value
     *
     * @return int
     */
    public function value(): int
    {
        return match ($this) {
            Status::Enable => 1,

            Status::Disable => 2,
        };
    }
}
