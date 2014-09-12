<?php

namespace PingYo;

class PayFrequencyTypes
{

    const Weekly = 1;
    const BiWeekly = 2;
    const Fortnightly = 3;
    const LastDayMonth = 4;
    const LastWorkingDayMonth = 5;
    const SpecificDayOfMonth = 6;
    const TwiceMonthly = 7;
    const FourWeekly = 8;
    const LastFriday = 9;
    const LastThursday = 10;
    const LastWednesday = 11;
    const LastTuesday = 12;
    const LastMonday = 13;
    const Other = 14;
    const None = 15;

    public static function validation_set()
    {
        return [
            self::Weekly,
            self::BiWeekly,
            self::Fortnightly,
            self::LastDayMonth,
            self::LastWorkingDayMonth,
            self::SpecificDayOfMonth,
            self::TwiceMonthly,
            self::FourWeekly,
            self::LastFriday,
            self::LastThursday,
            self::LastWednesday,
            self::LastTuesday,
            self::LastMonday,
            self::Other,
            self::None
        ];
    }
}