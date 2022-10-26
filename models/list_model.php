<?php

    class ListModel
    {
        function __construct()
        {

        }

        function render ($data)
        {
            $htmlfile = ($data['{show.redeemed']) ? 'redeemed_list.html' : 'list.html';
                
            $htmlparser = new App\Custom\HTMLParser (file_get_contents (App\Constants::HTML_PAGES_DIR.$htmlfile), $data);
			$htmlstring = $htmlparser->GetSubstitutedString();
			if (App\Custom\Error::IsAnError ($htmlstring))
			{
				die ($htmlstring->GetError());
			}

			echo $htmlstring;
        }
    }
?>