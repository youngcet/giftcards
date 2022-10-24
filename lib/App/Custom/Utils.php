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

        public static function generateCode ($limit)
        {
            $code = '';
            for($i = 0; $i < $limit; $i++) 
            { 
                $code .= ($i % 4 == 0) ? '-'.mt_rand(0, 9) : mt_rand(0, 9);
            }
            
            return ltrim($code, $code[0]);
        }

        public static function compress_image ($source_url, $destination_url, $quality)
        {
            $info = getimagesize ($source_url);
    
            if ($info['mime'] == 'image/jpeg') $image = imagecreatefromjpeg ($source_url);
            elseif ($info['mime'] == 'image/gif') $image = imagecreatefromgif ($source_url);
            elseif ($info['mime'] == 'image/png') $image = imagecreatefrompng ($source_url);
            elseif ($info['mime'] == 'image/webp') $image = imagecreatefromwebp ($source_url);
            
            imagejpeg ($image, $destination_url, $quality);
        }
    }

?>