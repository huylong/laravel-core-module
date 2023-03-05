<?php

// +----------------------------------------------------------------------
// | HuyPham [Just Like ～ ]
// +----------------------------------------------------------------------
// | Copyright (c) 2023 ~ now https://daygiare.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: HuyPham[ huyad1102@gmail.com ]
// +----------------------------------------------------------------------

declare(strict_types=1);

namespace BlueStar\Exceptions;

use BlueStar\Enums\Enum;
use Symfony\Component\HttpKernel\Exception\HttpException;
use BlueStar\Enums\Code;

abstract class BlueStarException extends HttpException
{
    protected $code = 0;

    /**
     * @param string $message
     * @param int|Code $code
     */
    public function __construct(string $message = '', int|Code $code = 0)
    {
        if ($code instanceof Enum) {
            $code = $code->value();
        }

        if ($this->code instanceof Enum && ! $code) {
            $code = $this->code->value();
        }

        parent::__construct($this->statusCode(), $message ?: $this->message, null, [], $code);
    }

    /**
     * status code
     *
     * @return int
     */
    public function statusCode(): int
    {
        return 500;
    }

    /**
     * render
     *
     * @return array
     */
    public function render(): array
    {
        return [
            'code' => $this->code,

            'message' => $this->message
        ];
    }
}
