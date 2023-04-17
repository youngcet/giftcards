<?php

    class RedeemModel
    {
        function __construct()
        {
            
        }

        function render ($data)
        {
            $htmlparser = new App\Custom\HTMLParser (file_get_contents (App\Constants::HTML_PAGES_DIR.'redeem.html'), $data);
			$htmlstring = $htmlparser->GetSubstitutedString();
			if (App\Custom\Error::IsAnError ($htmlstring))
			{
				die ($htmlstring->GetError());
			}

			echo $htmlstring;
        }
    }
?>