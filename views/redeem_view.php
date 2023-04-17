<?php

	require ('session.php');

	class RedeemView
	{
		private $model;

		private $controller;

		function __construct ($controller, $model)
		{
			$this->controller = $controller;
			$this->model = $model;

            $data = array ('{app.title}' => 'Gift Cards');
            $data['{user.fname}'] = $_SESSION['fname'];
			$data['{user.lname}'] = $_SESSION['lname'];
			$data['{user.email}'] = $_SESSION['useremail'];
			$data['{current.month}'] = App\Custom\Utils::GetCurrentMonth ('M');
			$data['{current.day}'] = App\Custom\Utils::GetCurrentDay ('d');
			$data[App\Constants::ERROR_NOTIFICATION_HTML] = '';
			$data[App\Constants::SUCCESS_NOTIFICATION_HTML] = '';
            $data['notifications'] = array();
            $data['{notification.count}'] = 0;
            $data['{profile_img}'] = $_SESSION['profile_img'];

            $userrole = $_SESSION['role'];
            $data['{user.role}'] = $userrole;

            if ($userrole != App\Constants::SELLER)
            {
                die ('You do not have access to view this page');
            }

            $notifications = $this->controller->SelectNotifications ($_SESSION['userid']);
            foreach ($notifications as $notification)
            {
                $data['notifications'][] = array
                    (
                        '{notification.profileimg}' => $notification['profile_img'],
                        '{notification.title}' => $notification['title'],
                        '{notification.id}' => $notification['id'],
                        '{notification.text}' => $notification['text'],
                        '{notification.time}' => $notification['created'],
                    );
            }

            $data['{notification.count}'] = count ($notifications);

            if (isset ($_POST['redeemcard']))
            {
                $qty = trim (strip_tags ($_POST['qty']));
                $id = trim (strip_tags ($_POST['id']));
                $current_qty = trim (strip_tags ($_POST['current_qty']));

                if ($qty > $current_qty || ($qty - $current_qty) < 0)
                {
                    $data[App\Constants::ERROR_NOTIFICATION_HTML] = App\Constants::GIFTCARDS_EXCEEDED;
                }
                else 
                {
                    $redeem = $this->controller->RedeemGiftCard ($qty, $id);
                    if (App\Custom\Error::IsAnError ($redeem))
                    {
                        $data[App\Constants::ERROR_NOTIFICATION_HTML] = $redeem->GetError();
                    }
                    else
                    {
                        $data[App\Constants::SUCCESS_NOTIFICATION_HTML] = App\Constants::CARD_SOLD_NOTIFICATION;
                    }
                }
            }

            if (isset ($_GET['redeem']))
            {
                $cardno = trim (strip_tags ($_GET['redeem']));
                $card = $this->controller->GetGiftCards ($cardno);

                if ($card['expiry_date'] == '') $card['expiry_date'] = App\Constants::NOT_APPLICABLE;
                $card['{iscardactive}'] = ($card['qty'] != $card['qty_sold']) ? true : false;
                if ($card['color'] == '') $card['color'] = '#000';

                $data['cards'][] = array
                    (
                        '{card.title}' => $card['title'],
                        '{card.description}' => $card['description'],
                        '{card.price}' => $card['price'],
                        '{card.status}' => $card['status'],
                        '{card.expirydate}'=> $card['expiry_date'],
                        '{card.color}' => $card['color'],
                        '{card.id}'=> $card['id'],
                        '{card.sellerid}'=> $card['seller_id'],
                        '{card.qty}' => $card['qty'],
                        '{card.qtysold}' => $card['qty_sold'],
                        '{iscardactive}' => $card['{iscardactive}'],
                        '{card.number}' => $card['card_number']
                    );
            }
            else
            {
                header ('Location: index?#zero_config');
                die;
            }

            $this->model->render ($data);
        }
    }
?>