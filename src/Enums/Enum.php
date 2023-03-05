<?php

namespace BlueStar\Enums;

interface Enum
{
    public function value(): int;

    public function name(): string;
}
