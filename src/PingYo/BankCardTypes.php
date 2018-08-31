<?php

namespace PingYo;

class BankCardTypes
{
    const Solo = 2;
    const SwitchMaestro = 3;
    const Visa = 4;
    const VisaDebit = 5;
    const VisaDelta = 6;
    const VisaElectron = 7;
    const MasterCard = 9;
    const MasterCardDebit = 10;


    public static function validation_set()
    {
        return [
            self::Solo,
            self::SwitchMaestro,
            self::Visa,
            self::VisaDebit,
            self::VisaDelta,
            self::VisaElectron,
            self::MasterCard,
            self::MasterCardDebit,
        ];
    }
}