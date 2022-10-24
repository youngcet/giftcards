<?php

    namespace App\User;

    class Admin implements Role
    {
        public function getUsersHTMLFile() 
        {
            return App\Constants::HTML_PAGES_DIR.'admin.html';
        }
    }
?>