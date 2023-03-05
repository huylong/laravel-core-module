<?php

// +----------------------------------------------------------------------
// | HuyPham [Just Like ～ ]
// +----------------------------------------------------------------------
// | Copyright (c) 2023~ now https://daygiare.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: HuyPham[ huyad1102@gmail.com ]
// +----------------------------------------------------------------------

declare(strict_types=1);

namespace BlueStar\Base;

use BlueStar\Enums\Code;
use BlueStar\Exceptions\FailedException;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

/**
 * base BlueStar controller
 */
abstract class BlueStarController extends Controller
{
    /**
     * @param string|null $guard
     * @param string|null $field
     * @return mixed
     */
    protected function getLoginUser(string|null $guard = null,  string|null $field = null): mixed

    {
        $user = Auth::guard($guard ?: getGuardName())->user();

        if (! $user) {
            throw new FailedException('登录失效, 请重新登录', Code::LOST_LOGIN);
        }

        if ($field) {
            return $user->getAttribute($field);
        }

        return $user;
    }


    /**
     * @param $guard
     * @return mixed
     */
    protected function getLoginUserId($guard = null): mixed
    {
        return $this->getLoginUser($guard, 'id');
    }
}
