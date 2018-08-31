<?php

namespace PingYo;

class MaritalStatuses
{
    const Single = 1;
    const Married = 2;
    const LivingTogether = 3;
    const Separated = 4;
    const Divorced = 5;
    const Widowed = 6;
    const Other = 7;

    public static function validation_set()
    {
        return [
            self::Single,
            self::Married,
            self::LivingTogether,
            self::Separated,
            self::Divorced,
            self::Widowed,
            self::Other,
        ];
    }
}