<?php

// +----------------------------------------------------------------------
// | HuyPham [Just Like ～ ]
// +----------------------------------------------------------------------
// | Copyright (c) 2023 ~ now https://daygiare.com All rights reserved.
// +----------------------------------------------------------------------// +----------------------------------------------------------------------
// | Licensed ( https://github.com/yanwenwu/catch-admin/blob/master/LICENSE.txt )
// +----------------------------------------------------------------------
// | Author: HuyPham[ huyad1102@gmail.com ]
// +----------------------------------------------------------------------

declare(strict_types=1);

namespace Catch\Enums;

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
            Status::Enable => '启用',

            Status::Disable => '禁用'
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
