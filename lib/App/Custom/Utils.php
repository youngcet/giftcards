<?php

    namespace App\Custom;

    trait Utils
    {
        public static function EncryptStringMd5 ($string)
        {
            return md5 ($string);
        }

        public static function IsStringMd5Match ($md5string, $string)
        {
            return (md5 ($string) === $md5string) ? true : false;
        }

        public static function GetCurrentDate()
        {
            //date_default_timezone_set ('America/Los_Angeles'); // using server time
            return date ('m/d/Y h:i:s a', time());
        }

        public static function GetCurrentMonth ($format)
        {
            //date_default_timezone_set ('America/Los_Angeles'); // using server time
            return date ("$format", time());
        }

        public static function GetCurrentDay ($format)
        {
            //date_default_timezone_set ('America/Los_Angeles'); // using server time
            return date ("$format", time());
        }

        public static function GetCurrentHour()
        {
            //date_default_timezone_set ('America/Los_Angeles'); // using server time
            return date ('H', time());
        }
    }

?>