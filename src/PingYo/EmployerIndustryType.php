<?php

namespace PingYo;

class EmployerIndustryTypes
{

    const ConstructionManufacturing = 1;
    const Military = 2;
    const Health = 3;
    const BankingInsurance = 4;
    const Education = 5;
    const CivilService = 6;
    const SupermarketRetail = 7;
    const UtilitiesTelecom = 8;
    const HotelRestaurantAndLeisure = 9;
    const OtherOfficeBased = 10;
    const OtherNotOfficeBased = 11;
    const None = 12;

    public static function validation_set()
    {
        return [
            self::ConstructionManufacturing,
            self::Military,
            self::Health,
            self::BankingInsurance,
            self::Education,
            self::CivilService,
            self::SupermarketRetail,
            self::UtilitiesTelecom,
            self::HotelRestaurantAndLeisure,
            self::OtherOfficeBased,
            self::OtherNotOfficeBased,
            self::None
        ];
    }
}