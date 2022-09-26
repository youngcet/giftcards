<?php
    session_start();

    require ('autoloader.php');

    class LoginView
    {
        private $model;

        private $controller;

        function __construct($controller, $model)
        {
            $this->controller = $controller;
            $this->model = $model;
            
            $data = array ('{app.title}' => 'Gift Cards', '{user.name}' => 'Yung');
            $data[App\Constants::ERROR_NOTIFICATION_HTML] = '';

            if (isset ($_SESSION['userid']))
            {
                header ('Location: index');
                die();
            }

            if (isset ($_POST['submit']))
            {
                $email = strip_tags (trim ($_POST['email']));
                $pwd = strip_tags (trim ($_POST['pwd']));
               
                $res = $this->controller->loginUser ($email, $pwd);
                if (App\Custom\Error::IsAnError ($res))
                {
                    $data[App\Constants::ERROR_NOTIFICATION_HTML] = $res->GetError();
                }
                else
                {
                    $userdata = $this->controller->GetUserData();
                    $_SESSION['userid'] = $userdata[0]['id'];
                    $_SESSION['useremail'] = $userdata[0]['email'];
                    $_SESSION['fname'] = $userdata[0]['fname'];
                    $_SESSION['lname'] = $userdata[0]['lname'];
                   
                    header ('Location: index');
                    die();
                }
            }

            $htmlparser = new App\Custom\HTMLParser (file_get_contents (App\Constants::HTML_PAGES_DIR.'login.html'), $data);
            $htmlstring = $htmlparser->GetSubstitutedString(); // get the parsed html string

            // check for errors
            if (App\Custom\Error::IsAnError ($htmlstring))
            {
                // handle error
                die ($htmlstring->GetError()); // gets error message
                // $htmlstring->GetCode(); // gets error code
            }

            echo $htmlstring;
            
        }

    }
?>