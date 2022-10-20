<?php

    require ('session.php');

    class GiftcardsView
    {
        private $model;
		private $controller;

        function __construct ($controller, $model)
        {
            $this->controller = $controller;
			$this->model = $model;

            $data = array();
            
            $data['{user.fname}'] = $_SESSION['fname'];
			$data['{user.lname}'] = $_SESSION['lname'];
			$data['{user.email}'] = $_SESSION['useremail'];
			$data['{current.month}'] = App\Custom\Utils::GetCurrentMonth ('M');
			$data['{current.day}'] = App\Custom\Utils::GetCurrentDay ('d');
			$data[App\Constants::ERROR_NOTIFICATION_HTML] = '';
			$data[App\Constants::SUCCESS_NOTIFICATION_HTML] = '';
			$data['{user.role}'] = $_SESSION['role'];
            $data['{modal.details}'] = 'newcard-modal';
            $data['{user.modal.title}'] = 'GiftCard';
            $data['giftcards'] = array();

            if (isset ($_POST['createCard']))
            {
                $title = trim (strip_tags($_POST['title']));
                $desc = trim (strip_tags ($_POST['desc']));
                $date = trim (strip_tags ($_POST['date']));
                $qty = trim (strip_tags ($_POST['qty']));
                $price = trim (strip_tags ($_POST['price']));
                $color = trim (strip_tags ($_POST['color']));

                $insertgiftcard = $this->controller->InsertGiftCard ($_SESSION['userid'], $title, $desc, $price, $qty, $color, $date);
                if (App\Custom\Error::IsAnError ($insertgiftcard))
                {
                    $data[App\Constants::ERROR_NOTIFICATION_HTML] = $res->GetError();
                }
                else
                {
                    $data[App\Constants::SUCCESS_NOTIFICATION_HTML] = App\Constants::NEW_CARD_NOTIFICATION;
                }
            }

            $sellers = $this->controller->SelectAllSellers ($_SESSION['userid']);
            if (App\Custom\Error::IsAnError ($sellers))
            {
                $data[App\Constants::ERROR_NOTIFICATION_HTML] = $sellers->GetError();
            }

            foreach ($sellers as $seller)
            {
                $data['sellers'][] = array
                    (
                        '{seller.id}' => $seller['id'],
                        '{seller.name}' => $seller['fname'],
                        '{seller.lname}' => $seller['lname'],
                        '{seller.email}' => $seller['email']
                    );
            }

            $giftcards = $this->controller->GetAllGiftCards ($_SESSION['userid']);
            if (App\Custom\Error::IsAnError ($giftcards))
            {
                $data[App\Constants::ERROR_NOTIFICATION_HTML] = $giftcards->GetError();
            }

            foreach ($giftcards as $giftcard)
            {
                if ($giftcard['color'] == '') $giftcard['color'] = '#000';

                $data['giftcards'][] = array
                    (
                        '{card.title}' => $giftcard['title'],
                        '{card.description}' => $giftcard['description'],
                        '{card.price}' => $giftcard['price'],
                        '{card.color}' => $giftcard['color'],
                        '{card.qty}' => $giftcard['qty'],
                        '{card.expiry_date}' => $giftcard['expiry_date']
                    );
            }

            $this->model->render ($data);
        }
    }
?>