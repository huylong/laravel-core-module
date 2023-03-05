<?php

// +----------------------------------------------------------------------
// | HuyPham [Just Like ～ ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017~2021 https://catchadmin.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://github.com/JaguarJack/catchadmin-laravel/blob/master/LICENSE.md )
// +----------------------------------------------------------------------
// | Author: HuyPham[ huyad1102@gmail.com ]
// +----------------------------------------------------------------------

declare(strict_types=1);

namespace Catch\Exceptions;

use Catch\Enums\Code;

class FailedException extends CatchException
{
    protected $code = Code::FAILED;
}
