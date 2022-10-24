<?php

	require ('session.php');
// 	ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

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
			$data['{user.email}'] = $_SESSION['useremail'];
			$data['{current.month}'] = App\Custom\Utils::GetCurrentMonth ('M');
			$data['{current.day}'] = App\Custom\Utils::GetCurrentDay ('d');
			$data[App\Constants::ERROR_NOTIFICATION_HTML] = '';
			$data[App\Constants::SUCCESS_NOTIFICATION_HTML] = '';
			$data['users'] = array();
			$data['sellers'] = array();
			$data['cards'] = array();
			$data['notifications'] = array();

			$userrole = $_SESSION['role'];

			$data['{user.role}'] = $userrole;
			$data['{ucfirst.user.role}'] = ucfirst ($userrole);
			$data['{user.menu.role}'] = $userrole;
			$data['{user.dashboard.role}'] = $userrole;
			$data['{user.list.role}'] = $userrole;
			$data['{modal.details}'] = 'newuser-modal';
			$data['{deleteuser}'] = false;
			$data['{user.delete}'] = $data['{delete.userid}'] = $data['{delete.role}'] = $data['{admin.users}'] = $data['{staff.users}'] = '';
			$showdeletepopup = true;
			$data['{notification.count}'] = 0;
			$data['{hide.makeallasread}'] = 'none';
			
			$data['{greeting.message}']  = (App\Custom\Utils::GetCurrentHour() > 12) ? App\Constants::GOOD_AFTERNOON_MESSAGE : App\Constants::GOOD_MORNING_MESSAGE;

			$userinfo = $this->controller->SelectUserInfo ($userrole, $_SESSION['userid']);
			$_SESSION['profile_img'] = ($userinfo['profile_img'] == '') ? App\Constants::DEFAULT_USER_PROFILE_IMG: $userinfo['profile_img'];

			if (isset ($_GET['logout']))
			{
				session_destroy();
				header ('Location: login');
				die();
			}

			if (isset ($_POST['seen']))
			{
				$this->controller->DeleteNotification ($_SESSION['userid']);
			}

			if (isset ($_POST['deleteuser']))
			{
				$useridtodelete = trim (strip_tags ($_POST['id']));
				$userrole = trim (strip_tags ($_POST['role']));

				if (! empty ($useridtodelete))
				{
					if ($userrole == App\Constants::STAFF) $type = App\Constants::STAFF;
					if ($userrole == App\Constants::SELLER) $type = App\Constants::SELLER;

					$deleteuser = $this->controller->DeleteUser ($type, base64_decode ($useridtodelete));
					if (App\Custom\Error::IsAnError ($deleteuser))
					{
						$data[App\Constants::ERROR_NOTIFICATION_HTML] = $deleteuser->GetError();
					}
					else
					{
						$data[App\Constants::SUCCESS_NOTIFICATION_HTML] = App\Constants::USER_DELETED_MSG;
						$showdeletepopup = false;

						header ('Location: index');
						die;
					}
				}
			}

			if (isset ($_GET['del']) && isset ($_GET['u']) && isset ($_GET['role']) && $showdeletepopup)
			{
				$deleteuserid = trim (strip_tags ($_GET['del']));
				$user = trim (strip_tags ($_GET['u']));
				$role = trim (strip_tags ($_GET['role']));

				if (! empty ($deleteuserid))
				{
					$data['{user.delete}'] = $user;
					$data['{deleteuser}'] = true;
					$data['{delete.userid}'] = $deleteuserid;
					$data['{delete.role}'] = $role;
				} 
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

			if (isset ($_POST['createCard']))
			{
				$title = strip_tags (trim ($_POST['title']));
				$desc = strip_tags (trim ($_POST['desc']));
				$price = strip_tags (trim ($_POST['price']));

				$giftcards = $this->controller->GetGiftCards ($_SESSION['userid']);
				$totgiftcards = (empty ($giftcards['qty'])) ? 0 : $giftcards['qty'];
				if ($totgiftcards > 0)
				{
					$price = str_replace ('$', '', $price);
					$res = $this->controller->CreateCard ($title, $desc, $price, $_SESSION['userid']);
					if (App\Custom\Error::IsAnError ($res))
					{
						$data[App\Constants::ERROR_NOTIFICATION_HTML] = $res->GetError();
					}
					else
					{
						$data[App\Constants::SUCCESS_NOTIFICATION_HTML] = App\Constants::NEW_CARD_NOTIFICATION;
					}
				}
				else
				{
					$data[App\Constants::ERROR_NOTIFICATION_HTML] = App\Constants::GIFTCARDS_EXCEEDED;
				}
			}

			if (isset ($_POST['redeemcard']))
			{
				$id = strip_tags (trim ($_POST['card']));
				$redeem = strip_tags (trim ($_POST['redeem']));

				$res = $this->controller->RedeemCard ($id, $redeem);
				if (App\Custom\Error::IsAnError ($res))
				{
					$data[App\Constants::ERROR_NOTIFICATION_HTML] = $res->GetError();
				}
				else
				{
					$data[App\Constants::SUCCESS_NOTIFICATION_HTML] = App\Constants::CARD_SOLD_NOTIFICATION;
				}
			}

			if (isset ($_POST['updategiftcards']))
			{
				$sellerid = strip_tags (trim ($_POST['seller_id']));
				$staffid = strip_tags (trim ($_POST['staff_id']));
				$qty = strip_tags (trim ($_POST['qty']));

				$res = $this->controller->UpdateSellerGiftCards ($sellerid, $staffid, $qty);
				if (App\Custom\Error::IsAnError ($res))
				{
					$data[App\Constants::ERROR_NOTIFICATION_HTML] = $res->GetError();
				}
				else
				{
					$data[App\Constants::SUCCESS_NOTIFICATION_HTML] = App\Constants::CARD_UPDATED_NOTIFICATION;
				}
			}

			if (isset ($_POST['updatePwd']))
			{
				$pwd = strip_tags (trim ($_POST['pwd']));
				
				if (! empty ($pwd))
				{
					$res = $this->controller->UpdateUserPassword ($userrole, $pwd, $_SESSION['userid']);
					if (App\Custom\Error::IsAnError ($res))
					{
						$data[App\Constants::ERROR_NOTIFICATION_HTML] = $res->GetError();
					}
					else
					{
						$data[App\Constants::SUCCESS_NOTIFICATION_HTML] = App\Constants::CHANGES_SAVED;
					}
				}

				if (! empty ($_FILES['img']))
				{
					$filename = preg_replace ('/\s+/', '', $_FILES['img']['name']);
                    $tmpname = preg_replace ('/\s+/', '', $_FILES['img']['tmp_name']);

					$filename = App\Constants::USER_IMG_DIR.$_SESSION['userid'].'_'.$userrole.'_'.$filename;
					App\Custom\Utils::compress_image ($tmpname, $filename, 80);

					$updateimg = $this->controller->UpdateUserProfileImg ($userrole, $filename, $_SESSION['userid']);
					if (App\Custom\Error::IsAnError ($updateimg))
					{
						$data[App\Constants::ERROR_NOTIFICATION_HTML] = $updateimg->GetError();
					}
					else
					{
						$data[App\Constants::SUCCESS_NOTIFICATION_HTML] = App\Constants::CHANGES_SAVED;
					}
				}
				
			}

			// check user role and build the data structure
			if ($userrole == App\Constants::ADMIN)
			{
				$allstaff = $this->controller->GetAllStaff ($_SESSION['userid']);
				$totearnings = $this->controller->GetTotalEarnings ($_SESSION['userid']);
				$totalcards = $this->controller->GetTotalGiftCards ($_SESSION['userid']);
				
				$data['{user.modal.title}'] = ucfirst (App\Constants::STAFF);
				$chartdata = '';
				
				$totgiftcards = $totalearnings = $totqtysold = 0;
				foreach ($allstaff as $staff)
				{
					$record = $this->controller->GetAllStaffGiftCards ($staff['id']);
					
					if (empty ($record)) continue;

					$qty = ($record['qty'] == '') ? 0 : $record['qty'];
					$qtysold = ($record['qty_sold'] == '') ? 0 : $record['qty_sold'];
					$earnings = ($record['earnings'] == '') ? 0 : $record['earnings'];
					
					$totgiftcards += $qty;
					$totalearnings += $earnings;
					$totqtysold += $qtysold;

					if ($staff['profile_img'] == '') $staff['profile_img'] = App\Constants::DEFAULT_USER_PROFILE_IMG;

					$data['users'][] = array
						(
							'{staff.name}' => $staff['fname'] . ' '.$staff['lname'],
							'{staff.email}' => $staff['email'],
							'{staff.id}' => $staff['id'],
							'{staff.id.encoded}' => base64_encode ($staff['id']),
							'{staff.totalcards}'=> $qty,
							'{staff.totalsellers}' => count ($this->controller->GetAllSellers ($staff['id'])),
							'{staff.totalcards.sold}'=> $qtysold,
							'{staff.sales}'=> $earnings,
							'{staff.profileimg}'=> $staff['profile_img'],
						);
					
					$name = $staff['fname'].' '.$staff['lname'];
					$chartdata .= "['$name', $earnings],";
				}

				$data['{total.staff}'] = count ($allstaff);
				$data['{total.earnings}'] = $totalearnings;
				$data['{total.giftcards}'] = $totgiftcards;
				$data['{total.giftcardssold}'] = $totqtysold;
				$data['{dougnut.chart}'] = $chartdata;
			}
			
			if ($userrole == App\Constants::STAFF)
			{
				$data['{user.modal.title}'] = ucfirst (App\Constants::SELLER);

				$allsellers = $this->controller->GetAllSellers ($_SESSION['userid']);
				$earnings = $this->controller->GetTotalSellerEarnings ($_SESSION['userid']);
				$totalcards = $this->controller->GetTotalSellerGiftCards ($_SESSION['userid']);

				$totalearnings = (empty ($earnings['earnings'])) ? 0 : $earnings['earnings'];
				$totgiftcards = (empty ($earnings['qty'])) ? 0 : $earnings['qty'];
				$qtysold = (empty ($earnings['qty_sold'])) ? 0 : $earnings['qty_sold'];

				$data['{total.sellers}'] = count ($allsellers);
				$data['{total.earnings}'] = $totalearnings;
				$data['{total.giftcards}'] = $totgiftcards;
				$data['{total.giftcardssold}'] = $qtysold;

				// chart data
				$data['{total.staff}'] = $data['{total.sellers}'];

				$chartdata = '';

				foreach ($allsellers as $seller)
				{
					$sellergiftcards = $this->controller->GetAllSellerGiftCards ($seller['id']);
					
					$sellercards = $this->controller->SellerGiftCards ($seller['id']);
					if (App\Custom\Error::IsAnError ($sellercards))
					{
						$data[App\Constants::ERROR_NOTIFICATION_HTML] = $sellercards->GetError();
					}
					
					$tooltiptext = array();
					$avaiabletotalgiftcards = 0;
					$totalsellerearnings = 0;
					foreach ($sellercards as $card)
					{
						$tooltiptext[] = $card['title'].' ('.$card['qty'].')';
						$avaiabletotalgiftcards += $card['qty'];
						$totalsellerearnings += ($card['price']*$card['qty_sold']);
					}
					
					$qty = (isset ($sellergiftcards['qty'])) ? $sellergiftcards['qty'] : 0;
					$qtysold = (isset ($sellergiftcards['qty_sold'])) ? $sellergiftcards['qty_sold'] : 0;
					$earnings = (isset ($sellergiftcards['earnings'])) ? $sellergiftcards['earnings'] : 0;

					if ($seller['profile_img'] == '') $seller['profile_img'] = App\Constants::DEFAULT_USER_PROFILE_IMG;
					
					$data['sellers'][] = array
						(
							'{seller.name}' => $seller['fname'] . ' '.$seller['lname'],
							'{seller.email}' => $seller['email'],
							'{seller.id}' => $seller['id'],
							'{seller.totalcards}'=> $avaiabletotalgiftcards,
							'{seller.id.encoded}' => base64_encode ($seller['id']),
							'{seller.totalcards.sold}'=> $qtysold,
							'{seller.sales}'=> $totalsellerearnings,
							'{seller.staffid}'=> $_SESSION['userid'],
							'{tooltip.text}' => implode (', ', $tooltiptext),
							'{seller.totalgiftcards}' => count ($tooltiptext),
							'{seller.profileimg}' => $seller['profile_img']
						);
					
					$name = $seller['fname'].' '.$seller['lname'];
					$chartdata .= "['$name', $totalsellerearnings],";
				}

				$data['{dougnut.chart}'] = $chartdata;
			}
			
			if ($userrole == App\Constants::SELLER)
			{
				$data['{user.modal.title}'] = ucfirst (App\Constants::GIFTCARD);

				$notifications = $this->controller->SelectNotifications ($_SESSION['userid']);
				foreach ($notifications as $notification)
				{
					// $datetime = explode (' ', $notification['created']);
					// $createdtime = explode (' ', $datetime[1]);
					// $time = $createdtime[0].':'.$createdtime[1];

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
				if ($data['{notification.count}']) $data['{hide.makeallasread}'] = 'block';

				$giftcards = $this->controller->GetGiftCards ($_SESSION['userid']);
				$createdgiftcards = $this->controller->GetCreatedGiftCards ($_SESSION['userid']);
				
				$totgiftcards = (empty ($giftcards['qty'])) ? 0 : $giftcards['qty'];
				$giftcardsold = (empty ($giftcards['qty_sold'])) ? 0 : $giftcards['qty_sold'];
				$earnings = (empty ($giftcards['earnings'])) ? 0 : $giftcards['earnings'];

				$data['{available.giftcards}'] = $totgiftcards;
				$data['{sold.giftcards}'] = $giftcardsold;
				$data['{total.earnings}'] = $earnings;
				$data['{modal.details}'] = 'newcard-modal';
				$data['{card.form}'] = 'form';
				
				foreach ($createdgiftcards as $card)
				{
					if ($card['expiry_date'] == '') $card['expiry_date'] = App\Constants::NOT_APPLICABLE;
					$card['{iscardactive}'] = ($card['qty'] != $card['qty_sold']) ? true : false;

					$data['cards'][] = array
						(
							'{card.title}' => $card['title'],
							'{card.description}' => $card['description'],
							'{card.price}' => $card['price'],
							'{card.status}' => $card['status'],
							'{card.expirydate}'=> $card['expiry_date'],
							'{card.id}'=> $card['id'],
							'{card.sellerid}'=> $card['seller_id'],
							'{card.qty}' => $card['qty'],
							'{card.qtysold}' => $card['qty_sold'],
							'{iscardactive}' => $card['{iscardactive}'],
							'{card.number}' => $card['card_number']
						);
				}
			}
			
			$data['{profile_img}'] = $_SESSION['profile_img'];
			
			
			$data['{admin.users}'] = $this->model->getSubstString ($userrole, $data);
			$data['{staff.users}'] = $this->model->getSubstString ($userrole, $data);

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

		public function GetStaffSales ($cards)
		{
			$totalsales = 0;
			foreach ($cards as $card) $totalsales += $card['price'];
			return $totalsales;
		}
	}
?>