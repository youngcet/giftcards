<?php

    class IndexModel
    {
        function __construct()
        {

        }

        function validateUsername ($name)
        {
            $parts = explode (' ', $name);
            return (count ($parts) > 1) ? $name : new App\Custom\Error (-1, 'Name & Last Name Required!');
        }

        function getSubstString ($role, $data)
        {
            $htmlstring = $htmlfile = '';

            if ($role == App\Constants::ADMIN) $htmlfile = 'admin.html';
            if ($role == App\Constants::STAFF) $htmlfile = 'staff.html';

            if ($htmlfile != '')
            {
                $htmlparser = new App\Custom\HTMLParser (file_get_contents (App\Constants::HTML_PAGES_DIR.$htmlfile), $data);
                $htmlstring = $htmlparser->GetSubstitutedString(); 
            }
            
			return $htmlstring;
        }

        public function ParseHTMLFile ($html, $data)
        {
            $htmlparser = new App\Custom\HTMLParser (file_get_contents ($html), $data);
			return $htmlparser->GetSubstitutedString();
        }
    }
?>