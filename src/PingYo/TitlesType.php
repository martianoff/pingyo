<?php

namespace PingYo;

class TitleTypes
{
    const MR = 1;
    const MRS = 2;
    const MS = 3;
    const MISS = 4;

    public static function validation_set()
    {
        return [self::MR, self::MRS, self::MS, self::MISS];
    }
}