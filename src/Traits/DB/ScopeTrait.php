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

trait ScopeTrait
{
    /**
     * creator
     */
    public static function scopeCreator($query): void
    {
        $model = app(static::class);

        if (in_array($model->getCreatorIdColumn(), $model->getFillable())) {
                $userModel = app(getAuthUserModel());

            $query->addSelect([
                    'creator' => $userModel->whereColumn($userModel->getKeyName(), $model->getTable() . '.' . $model->getCreatorIdColumn())
                        ->select('username')->limit(1)
                ]);
        }
    }
}
