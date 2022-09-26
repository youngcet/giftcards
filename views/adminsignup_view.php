<?php

    require ('autoloader.php');

    class AdminsignupView
    {
        private $model;
        private $controller;

        function __construct($controller, $model)
        {
            $this->controller = $controller;
            $this->model = $model;
            $this->errors = '';

            $data = array ('{app.title}' => 'Gift Cards', '{user.name}' => 'Yung');
            $data[App\Constants::ERROR_NOTIFICATION_HTML] = $this->errors;
            $data[App\Constants::REGISTRATION_NOTIFICATION_HTML] = '';

            if (isset ($_GET['reg']))
            {
                $data[App\Constants::REGISTRATION_NOTIFICATION_HTML] = App\Constants::REGISTRATION_SUCCESS_MSG;
            }
            
            if (isset ($_POST['submit']))
            {
                $name = strip_tags (trim ($_POST['name']));
                $email = strip_tags (trim ($_POST['email']));
                $pwd = strip_tags (trim ($_POST['pwd']));

                $name = $this->model->validateUsername ($name);
                if (App\Custom\Error::IsAnError ($name))
                {
                    $this->errors = $name->GetError();
                    $data[App\Constants::ERROR_NOTIFICATION_HTML] = $this->errors;
                }
                else
                {
                    $res = $this->controller->registerUser ($name, $email, $pwd);
                    if (App\Custom\Error::IsAnError ($res))
                    {
                        $this->errors = $res->GetError();
                        $data[App\Constants::ERROR_NOTIFICATION_HTML] = $this->errors;
                    }
                    else
                    {
                        header ('Location: ?reg');
                        die();
                    }
                }
            }

            $htmlparser = new App\Custom\HTMLParser (file_get_contents (App\Constants::HTML_PAGES_DIR.'adminsignup.html'), $data);
            $htmlstring = $htmlparser->GetSubstitutedString(); // get the parsed html string
            if (App\Custom\Error::IsAnError ($htmlstring))
            {
                die ($htmlstring->GetError()); // gets error message
            }

            echo $htmlstring;
            
        }

    }
?>