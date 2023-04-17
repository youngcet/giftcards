<?php

	require ('session.php');

	class ListView
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

            $data['maincards'] = $data['guestcards'] = $data['redeemedcards'] = $data['cards'] = array();

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

            if (! isset ($_GET['group']))
            {
                header ('Location: index?#zero_config');
                die;
            }

            $data['{show.redeemed}'] = (isset ($_GET[App\Constants::REDEEMED])) ? true : false;
            $data['{show.guests}'] = (isset ($_GET[App\Constants::GUESTS])) ? true : false;
                
            $cardno = trim (strip_tags ($_GET['group']));

            if (isset ($_POST['updatecardnumber']))
            {
                $cardid = trim (strip_tags ($_POST['id']));
                $card_number = trim (strip_tags ($_POST['card_number']));

                $updatecardnumber = $this->controller->UpdateGiftCardNumber ($cardno, $card_number, $cardid, $_SESSION['userid']);
                if (App\Custom\Error::IsAnError ($updatecardnumber))
                {
                    $data[App\Constants::ERROR_NOTIFICATION_HTML] = $updatecardnumber->GetError();
                }
                else
                {
                    $data[App\Constants::SUCCESS_NOTIFICATION_HTML] = App\Constants::CHANGES_SAVED;
                }
            }

            if (isset ($_POST['redeemcard']))
            {
                $cardid = trim (strip_tags ($_POST['id']));
                $cardnumber = trim (strip_tags ($_POST['cardno']));

                if (! empty ($cardnumber))
                {
                    $redeemcard = $this->controller->RedeemCard (1, 1, $cardno, $cardid, $_SESSION['userid']);
                    if (App\Custom\Error::IsAnError ($redeemcard))
                    {
                        $data[App\Constants::ERROR_NOTIFICATION_HTML] = $redeemcard->GetError();
                    }
                    else
                    {
                        $data[App\Constants::SUCCESS_NOTIFICATION_HTML] = App\Constants::CARD_SOLD_NOTIFICATION;
                    }
                }
                else
                {
                    $data[App\Constants::ERROR_NOTIFICATION_HTML] = App\Constants::NO_CARD_NUMBER;
                }
            }

            if (empty ($cardno))
            {
                header ('Location: index');
                die;
            }

            $maincardinfo = $this->controller->SelectMainGiftCards ($_SESSION['userid'], $cardno);
            $data['{group.card.title}'] = $maincardinfo['title'];
            $data['{group.card.color}'] = $maincardinfo['color'];
            $data['{group.card.price}'] = $maincardinfo['price'];
            $data['{group.card.description}'] = $maincardinfo['description'];
            $data['{group.card.number}'] = $maincardinfo['card_number'];
            $data['{group.card.qty}'] = $maincardinfo['qty'];
            $data['{group.card.expirydate}'] = $maincardinfo['expiry_date'];
            $data['{group.card.qty_sold}'] = $maincardinfo['qty_sold'];

            $giftcards = $this->controller->SelectGiftCards ($cardno, $_SESSION['userid']); 
            if (App\Custom\Error::IsAnError ($giftcards))
            {
                $data[App\Constants::ERROR_NOTIFICATION_HTML] = $giftcards->GetError();
            }
            else
            {
                foreach ($giftcards as $card)
                {
                    if ($card['expiry_date'] == '') $card['expiry_date'] = App\Constants::NOT_APPLICABLE;
                    $card['{iscardactive}'] = ($card['status'] == App\Constants::ACTIVE) ? true : false;

                    if ($card['{iscardactive}'])
                    {
                        $data['cards'][] = array
                            (
                                '{card.title}' => $card['title'],
                                '{card.id}' => $card['id'],
                                '{card.description}' => $card['description'],
                                '{card.price}' => $card['price'],
                                '{card.expirydate}'=> $card['expiry_date'],
                                '{iscardactive}' => $card['{iscardactive}'],
                                '{card.number}' => $card['card_number']
                            );
                    }
                    else 
                    {
                        $data['redeemedcards'][] = array
                            (
                                '{card.title}' => $card['title'],
                                '{card.id}' => $card['id'],
                                '{card.description}' => $card['description'],
                                '{card.price}' => $card['price'],
                                '{card.expirydate}'=> $card['expiry_date'],
                                '{iscardactive}' => $card['{iscardactive}'],
                                '{card.number}' => $card['card_number']
                            );
                    }
                }
            }

            $data['{cards.count}'] = count ($giftcards);

            $maingiftcards = $this->controller->GetCreatedGiftCards ($_SESSION['userid']);
            if (App\Custom\Error::IsAnError ($maingiftcards))
            {
                $data[App\Constants::ERROR_NOTIFICATION_HTML] = $maingiftcards->GetError();
            }
            else
            {
                foreach ($maingiftcards as $maincard)
                {
                    $isselected = ($cardno == $maincard['card_number']) ? 'selected' : '';
                    $data['maincards'][] = array
                        (
                            '{card.title}' => $maincard['title'],
                            '{card.number}' => $maincard['card_number'],
                            '{card.selected}' => $isselected
                        );
                }
            }

            if ($data['{show.guests}'])
            {
                $guestscards = $this->controller->GuestGiftCards ($_SESSION['userid']);
                foreach ($guestscards as $guest)
                {
                    $card = json_decode (base64_decode ($guest['data']));
                    $data['guestcards'][] = array
                        (
                            '{guest.name}' => $guest['guestname'],
                            '{card.price}' => $card->price,
                            '{card.description}' => $card->description,
                            '{card.title}' => $card->title,
                            '{guest.email}' => $guest['email'],
                            '{card.created}'=> $guest['created'],
                            '{card.number}' => $card->card_number,
                            '{card.expirydate}' => $card->expiry_date
                        );
                }
            }

            $this->model->render ($data);
        }
    }
?>