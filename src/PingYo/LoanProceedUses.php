<?php

namespace PingYo;

class LoanProceedUses
{
    const Subsistence = 1;
    const OneOffPurchase = 2;
    const Other = 3;
    const DebtConsolidation = 4;
    const CarLoan = 5;
    const PayBills = 6;
    const PayOffLoan = 7;
    const ShortTermCash = 8;
    const HomeImprovements = 9;

    public static function validation_set()
    {
        return [
            self::Subsistence,
            self::OneOffPurchase,
            self::Other,
            self::DebtConsolidation,
            self::CarLoan,
            self::PayBills,
            self::PayOffLoan,
            self::ShortTermCash,
            self::HomeImprovements,
        ];
    }
}