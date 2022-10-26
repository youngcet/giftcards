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
            $data['{user.modal.title}'] = 'Gift Card';
            $data['giftcards'] = $data['sellers'] = $data['giftcards_redeemed'] = array();
            $data['{total.giftcards}'] = $data['{total.giftcards_redeemed}'] = false;
            $data['{html.page}'] = 'giftcards.html';
            $data['{profile_img}'] = $_SESSION['profile_img'];
            $data['allsellers'] = array();


            if (isset ($_GET['redeemed'])) $data['{html.page}'] = 'redeemed.html';

            if (isset ($_POST['deletecard']))
            {
                $cardid = trim (strip_tags($_POST['cardid']));

                $deletecard = $this->controller->DeleteGiftCard ($cardid);
                if (App\Custom\Error::IsAnError ($deletecard))
                {
                    $data[App\Constants::ERROR_NOTIFICATION_HTML] = $deletecard->GetError();
                }
                else
                {
                    $data[App\Constants::SUCCESS_NOTIFICATION_HTML] = App\Constants::CARD_DELETED_MSG;
                }
            }

            if (isset ($_POST['createCard']))
            {
                $title = trim (strip_tags($_POST['title']));
                $desc = trim (strip_tags ($_POST['desc']));
                $date = trim (strip_tags ($_POST['date']));
                $qty = trim (strip_tags ($_POST['qty']));
                $price = trim (strip_tags ($_POST['price']));
                $color = trim (strip_tags ($_POST['color']));
                $assignee = trim (strip_tags ($_POST['assignee']));

                $insertgiftcard = $this->controller->InsertGiftCard ($_SESSION['userid'], $assignee, $title, $desc, $price, $qty, $color, $date);
                if (App\Custom\Error::IsAnError ($insertgiftcard))
                {
                    $data[App\Constants::ERROR_NOTIFICATION_HTML] = $insertgiftcard->GetError();
                }
                else
                {
                    $this->controller->InsertNotification ($assignee, $_SESSION['fname'].' '.$_SESSION['lname'], "Assigned you to $title gift card", $_SESSION['profile_img']);
                    $data[App\Constants::SUCCESS_NOTIFICATION_HTML] = App\Constants::NEW_CARD_NOTIFICATION;
                }
            }

            if (isset ($_POST['assignuser']))
            {
                $cardid = trim (strip_tags($_POST['card']));
                $sellerid = trim (strip_tags ($_POST['assignuser']));
                $title = trim (strip_tags ($_POST['title']));

                $updatecarduser = $this->controller->UpdateGiftCardSeller ($sellerid, $cardid);
                if (App\Custom\Error::IsAnError ($updatecarduser))
                {
                    $data[App\Constants::ERROR_NOTIFICATION_HTML] = $updatecarduser->GetError();
                }
                else
                {
                    if ($sellerid != 0)
                    {
                        $this->controller->InsertNotification ($sellerid, $_SESSION['fname'].' '.$_SESSION['lname'], "Assigned you to $title gift card", $_SESSION['profile_img']);
                    }
                
                    $data[App\Constants::SUCCESS_NOTIFICATION_HTML] = App\Constants::CHANGES_SAVED;
                }
            }

            if (isset ($_POST[App\Constants::FREEZE]) || isset ($_POST[App\Constants::UNFREEZE]))
            {
                $cardid = trim (strip_tags($_POST['cardid']));
                $userid = trim (strip_tags($_POST['userid']));
                $title = trim (strip_tags ($_POST['title']));

                $freezestatus = (! isset ($_POST[App\Constants::FREEZE])) ? App\Constants::FREEZE : App\Constants::UNFREEZE;
                $freezecard = $this->controller->UpdateMainCardStatus ($freezestatus, $cardid, $userid);
                if (App\Custom\Error::IsAnError ($freezecard))
                {
                    $data[App\Constants::ERROR_NOTIFICATION_HTML] = $freezecard->GetError();
                }
                else
                {
                    $freezestatus = ($freezestatus == App\Constants::UNFREEZE) ? App\Constants::FREEZE : App\Constants::UNFREEZE;
                    App\Notification::Add (new DatabaseHandler(), $userid, $_SESSION['fname'].' '.$_SESSION['lname'], ucfirst ($freezestatus)."d $title gift card", $_SESSION['profile_img']);
                    $data[App\Constants::SUCCESS_NOTIFICATION_HTML] = App\Constants::CHANGES_SAVED;
                }
            }

            $sellers = $this->controller->SelectAllSellers ($_SESSION['userid']);
            if (App\Custom\Error::IsAnError ($sellers))
            {
                $data[App\Constants::ERROR_NOTIFICATION_HTML] = $sellers->GetError();
            }
            
            $allsellers = '';
            foreach ($sellers as $seller)
            {
                $allsellers .= '<option value="'.$seller['id'].'">'.$seller['fname'].' '. $seller['lname'].' ('. $seller['email'].')</option>';
                $data['allsellers'][] = array (
                        '{seller.id}' => $seller['id'],
                        '{seller.name}' => $seller['fname'],
                        '{seller.lname}' => $seller['lname'],
                        '{seller.email}' => $seller['email'],
                    );
            }

            $data['{allsellers}'] = $allsellers;
            $giftcards = $this->controller->GetAllGiftCards ($_SESSION['userid']);
            if (App\Custom\Error::IsAnError ($giftcards))
            {
                $data[App\Constants::ERROR_NOTIFICATION_HTML] = $giftcards->GetError();
            }

            foreach ($giftcards as $giftcard)
            {
                if ($giftcard['color'] == '') $giftcard['color'] = '#000';

                $giftcard['sellerfname'] = 'Not';
                $giftcard['sellerlname'] = 'Assigned';

                if ($giftcard['seller_id'] != '' || $giftcard['seller_id'] != 0)
                {
                    $sellerinfo = $this->controller->SelectSeller ($giftcard['seller_id']);
                    if (App\Custom\Error::IsAnError ($sellerinfo))
                    {
                        $data[App\Constants::ERROR_NOTIFICATION_HTML] = $sellerinfo->GetError();
                        break;
                    }

                    if (! empty ($sellerinfo))
                    {
                        $giftcard['sellerfname'] = $sellerinfo['fname'];
                        $giftcard['sellerlname'] = $sellerinfo['lname'];
                        $giftcard['sellerid'] = $sellerinfo['id'];
                    }
                }
                
                if ($giftcard['expiry_date'] == '') $giftcard['expiry_date'] = App\Constants::NOT_APPLICABLE;
                if ($giftcard['qty'] == $giftcard['qty_sold']) 
                {
                    $data['giftcards_redeemed'][] = array
                        (
                            '{card.id}' => $giftcard['id'],
                            '{card.title}' => $giftcard['title'],
                            '{card.description}' => $giftcard['description'],
                            '{card.price}' => $giftcard['price'],
                            '{card.color}' => $giftcard['color'],
                            '{card.qty}' => $giftcard['qty'],
                            '{card.expiry_date}' => $giftcard['expiry_date'],
                            '{card.qty}' => $giftcard['qty'],
                            '{card.sellerfname}' => $giftcard['sellerfname'],
                            '{card.sellerlname}' => $giftcard['sellerlname'],
                            '{card.sellerid}' => $giftcard['sellerid'],
                            '{card.voucherno}' => $giftcard['card_number']
                        );
                }
                else
                {
                    //$statusmsg = ($giftcard['status'] == App\Constants::FREEZE || $giftcard['status'] != '') ? App\Constants::UNFREEZE_MESSAGE : App\Constants::FREEZE_MESSAGE;
                    //$giftcard['status'] = ($giftcard['status'] == App\Constants::FREEZE || $giftcard['status'] != '') ? App\Constants::UNFREEZE : App\Constants::FREEZE;

                    $statusmsg = ($giftcard['status'] == App\Constants::UNFREEZE || $giftcard['status'] == '') ? App\Constants::UNFREEZE_MESSAGE : App\Constants::FREEZE_MESSAGE;
                    
                    $data['giftcards'][] = array
                    (
                        '{card.id}' => $giftcard['id'],
                        '{card.title}' => $giftcard['title'],
                        '{card.description}' => $giftcard['description'],
                        '{card.price}' => $giftcard['price'],
                        '{card.color}' => $giftcard['color'],
                        '{card.qty}' => $giftcard['qty'],
                        '{card.status}' => $giftcard['status'],
                        '{card.status.msg}' => $statusmsg,
                        '{card.expiry_date}' => $giftcard['expiry_date'],
                        '{card.qty}' => $giftcard['qty'],
                        '{card.sellerfname}' => $giftcard['sellerfname'],
                        '{card.sellerid}' => $giftcard['sellerid'],
                        '{card.sellerlname}' => $giftcard['sellerlname'],
                        '{card.voucherno}' => $giftcard['card_number']
                    );
                }
            }

            if (empty ($data['giftcards'])) $data['{total.giftcards}'] = true;
            if (empty ($data['giftcards_redeemed'])) $data['{total.giftcards_redeemed}'] = true;
           
            $this->model->render ($data);
        }
    }
?>