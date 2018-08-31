<?php

namespace PingYo;

class IncomeSourceTypes
{

    const SelfEmployed = 1;
    const EmployedFullTime = 2;
    const EmployedPartTime = 3;
    const EmployedTemporary = 4;
    const Pension = 5;
    const DisabilityBenefits = 6;
    const Benefits = 7;

    public static function validation_set()
    {
        return [
            self::SelfEmployed,
            self::EmployedFullTime,
            self::EmployedPartTime,
            self::EmployedTemporary,
            self::Pension,
            self::DisabilityBenefits,
            self::Benefits
        ];
    }
}