<?php

namespace PingYo;

class NationalIdentityNumberTypes
{

    const None = null;
    const NationalInsurance = 1;

    public static function validation_set()
    {
        return [self::None, self::NationalInsurance];
    }
}