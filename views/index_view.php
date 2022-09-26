<?php

    require ('session.php');

    class IndexView
    {
        private $model;

        private $controller;

        function __construct ($controller, $model)
        {
            $this->controller = $controller;
            $this->model = $model;
            
            $data = array ('{app.title}' => 'Gift Cards', '{user.name}' => 'Yung');
            $data['{user.fname}'] = $_SESSION['fname'];
            $data['{user.lname}'] = $_SESSION['lname'];
            $data[App\Constants::ERROR_NOTIFICATION_HTML] = '';
            $data[App\Constants::SUCCESS_NOTIFICATION_HTML] = '';

            $userrole = getUserRole();
            if (App\Custom\Error::IsAnError ($userrole))
            {
                // handle error
                die ('something went wrong!'); // gets error message
                // $htmlstring->GetCode(); // gets error code
            }

            $data['{user.role}'] = $userrole;
            $data['{ucfirst.user.role}'] = ucfirst ($userrole);
            $data['{user.menu.role}'] = $userrole;
            $data['{user.dashboard.role}'] = $userrole;
            $data['{user.list.role}'] = $userrole;
            
            if (isset ($_GET['logout']))
            {
                session_destroy();
                header ('Location: login');
                die();
            }

            if (isset ($_POST['addUser']))
            {
                $name = strip_tags (trim ($_POST['name']));
                $email = strip_tags (trim ($_POST['email']));
                $pwd = strip_tags (trim ($_POST['pwd']));
                
                $name = $this->model->validateUsername ($name);
                if (App\Custom\Error::IsAnError ($name))
                {
                    $data[App\Constants::ERROR_NOTIFICATION_HTML] = $name->GetError();
                }
                else
                {
                    $res = $this->controller->CreateUser ($userrole, $name, $email, $pwd, $_SESSION['userid']);
                    if (App\Custom\Error::IsAnError ($res))
                    {
                        $data[App\Constants::ERROR_NOTIFICATION_HTML] = $res->GetError();
                    }
                    else
                    {
                        $data[App\Constants::SUCCESS_NOTIFICATION_HTML] = App\Constants::NEW_USER_NOTIFICATION;
                    }
                }
            }

            if ($userrole == App\Constants::ADMIN)
            {
                $allstaff = $this->controller->GetAllStaff ($_SESSION['userid']);
                $earnings = $this->controller->GetTotalEarnings ($_SESSION['userid']);
                $totalcards = $this->controller->GetTotalGiftCards ($_SESSION['userid']);
                
                $data['{user.modal.title}'] = ucfirst (App\Constants::STAFF);

                $data['{total.staff}'] = count ($allstaff);
                $data['{total.earnings}'] = 0;
                $data['{total.giftcards}'] = $totalcards[0]['totalcards'];
                
                foreach ($allstaff as $staff)
                {
                    $data['users'][] = array
                        (
                            '{staff.name}' => $staff['fname'] . ' '.$staff['lname'],
                            '{staff.email}' => $staff['email'],
                            '{staff.totalcards}'=> 0,
                            '{staff.totalcards.sold}'=> 0,
                            '{staff.sales}'=> 0,
                        );
                }
            }
            
            $htmlparser = new App\Custom\HTMLParser (file_get_contents (App\Constants::HTML_PAGES_DIR.'index.html'), $data);
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