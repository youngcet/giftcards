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
    }

?>