<?php

// +----------------------------------------------------------------------
// | HuyPham [Just Like ～ ]
// +----------------------------------------------------------------------
// | Copyright (c) 2023 ~ now https://daygiare.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: HuyPham[ huyad1102@gmail.com ]
// +----------------------------------------------------------------------

namespace BlueStar\Enums;

enum Code: int implements Enum
{
    case SUCCESS = 10000; // Success
    case LOST_LOGIN = 10001; // Login expired
    case VALIDATE_FAILED = 10002; // Validation failed
    case PERMISSION_FORBIDDEN = 10003; // Forbidden by permission
    case LOGIN_FAILED = 10004; // Login failed
    case FAILED = 10005; // Operation failed
    case LOGIN_EXPIRED = 10006; //  Login expired
    case LOGIN_BLACKLIST = 10007; // User is blacklisted
    case USER_FORBIDDEN = 10008; // User account is forbidden
    case WECHAT_RESPONSE_ERROR = 40000; // WeChat response error

    /**
     * message
     *
     */
    public function message(): string
    {
        return $this->name();
    }


    /**
     * get value
     *
     * @return int
     */
    public function value(): int
    {
        return match ($this) {
            Code::SUCCESS => 10000,
            Code::LOST_LOGIN => 10001,
            Code::VALIDATE_FAILED => 10002,
            Code::PERMISSION_FORBIDDEN => 10003,
            Code::LOGIN_FAILED => 10004,
            Code::FAILED => 10005,
            Code::LOGIN_EXPIRED => 10006,
            Code::LOGIN_BLACKLIST => 10007,
            Code::USER_FORBIDDEN => 10008,
            Code::WECHAT_RESPONSE_ERROR => 40000,
        };
    }

    /**
     * name
     *
     * @return string
     */
    public function name(): string
    {
        return match ($this) {
            self::SUCCESS => 'Operation successful',
            self::LOST_LOGIN => 'Authentication expired',
            self::VALIDATE_FAILED => 'Verification failed',
            self::PERMISSION_FORBIDDEN => 'Permission denied',
            self::LOGIN_FAILED => 'Login failed',
            self::FAILED => 'Operation failed',
            self::LOGIN_EXPIRED => 'Login expired',
            self::LOGIN_BLACKLIST => 'Blacklisted',
            self::USER_FORBIDDEN => 'Account disabled',
            self::WECHAT_RESPONSE_ERROR => 'WeChat response error'
        };
    }
}
