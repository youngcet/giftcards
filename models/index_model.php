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
    }
?>