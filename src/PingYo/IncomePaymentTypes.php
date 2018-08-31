<?php

namespace PingYo;

class IncomePaymentTypes
{
    const Cheque = 2;
    const Cash = 3;
    const RegionalDirectDeposit = 4;
    const NonRegionalDirectDeposit = 5;

    public static function validation_set()
    {
        return [self::Cheque, self::Cash, self::RegionalDirectDeposit, self::NonRegionalDirectDeposit];
    }
}