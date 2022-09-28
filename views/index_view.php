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
			$data['{user.email}'] = $_SESSION['useremail'];
			$data['{current.month}'] = App\Custom\Utils::GetCurrentMonth ('M');
			$data['{current.day}'] = App\Custom\Utils::GetCurrentDay ('d');
			$data[App\Constants::ERROR_NOTIFICATION_HTML] = '';
			$data[App\Constants::SUCCESS_NOTIFICATION_HTML] = '';
			$data['users'] = array();
			$data['sellers'] = array();
			$data['cards'] = array();

			$userrole = $_SESSION['role'];

			$data['{user.role}'] = $userrole;
			$data['{ucfirst.user.role}'] = ucfirst ($userrole);
			$data['{user.menu.role}'] = $userrole;
			$data['{user.dashboard.role}'] = $userrole;
			$data['{user.list.role}'] = $userrole;
			$data['{modal.details}'] = 'newuser-modal';
			
			$data['{greeting.message}']  = (App\Custom\Utils::GetCurrentHour() > 12) ? App\Constants::GOOD_AFTERNOON_MESSAGE : App\Constants::GOOD_MORNING_MESSAGE;

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

			if (isset ($_POST['sellCard']))
			{
				$id = strip_tags (trim ($_POST['id']));
				$price = strip_tags (trim ($_POST['price']));

				$res = $this->controller->SellCard ($id, $_SESSION['userid'], $price);
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
					
					$qty = (empty ($record)) ? 0 : $record['qty'];
					$qtysold = (empty ($record)) ? 0 : $record['qty_sold'];
					$earnings = (empty ($record)) ? 0 : $record['earnings'];

					$totgiftcards += $qty;
					$totalearnings += $earnings;
					$totqtysold += $qtysold;

					$data['users'][] = array
						(
							'{staff.name}' => $staff['fname'] . ' '.$staff['lname'],
							'{staff.email}' => $staff['email'],
							'{staff.id}' => $staff['id'],
							'{staff.totalcards}'=> $qty,
							'{staff.totalsellers}' => count ($this->controller->GetAllSellers ($staff['id'])),
							'{staff.totalcards.sold}'=> $qtysold,
							'{staff.sales}'=> $earnings,
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
					$qty = (isset ($sellergiftcards['qty'])) ? $sellergiftcards['qty'] : 0;
					$qtysold = (isset ($sellergiftcards['qty_sold'])) ? $sellergiftcards['qty_sold'] : 0;
					$earnings = (isset ($sellergiftcards['earnings'])) ? $sellergiftcards['earnings'] : 0;

					$data['sellers'][] = array
						(
							'{seller.name}' => $seller['fname'] . ' '.$seller['lname'],
							'{seller.email}' => $seller['email'],
							'{seller.id}' => $seller['id'],
							'{seller.totalcards}'=> $qty,
							'{seller.totalcards.sold}'=> $qtysold,
							'{seller.sales}'=> $earnings,
							'{seller.staffid}'=> $_SESSION['userid'],
						);
					
					$name = $seller['fname'].' '.$seller['lname'];
					$chartdata .= "['$name', $earnings],";
				}

				$data['{dougnut.chart}'] = $chartdata;
			}
			
			if ($userrole == App\Constants::SELLER)
			{
				$data['{user.modal.title}'] = ucfirst (App\Constants::GIFTCARD);

				$giftcards = $this->controller->GetGiftCards ($_SESSION['userid']);
				$createdgiftcards = $this->controller->GetCreatedGiftCards ($_SESSION['userid']);
				
				$totgiftcards = (empty ($giftcards['qty'])) ? 0 : $giftcards['qty'];
				$giftcardsold = (empty ($giftcards['qty_sold'])) ? 0 : $giftcards['qty_sold'];
				$earnings = (empty ($giftcards['earnings'])) ? 0 : $giftcards['earnings'];

				$data['{available.giftcards}'] = $totgiftcards;
				$data['{sold.giftcards}'] = $giftcardsold;
				$data['{total.earnings}'] = $earnings;
				$data['{modal.details}'] = 'newcard-modal';

				foreach ($createdgiftcards as $card)
				{
					$data['cards'][] = array
						(
							'{card.title}' => $card['title'],
							'{card.description}' => $card['description'],
							'{card.price}' => $card['price'],
							'{card.status}' => $card['status'],
							'{card.created}'=> $card['created'],
							'{card.id}'=> $card['id'],
							'{card.sellerid}'=> $card['seller_id'],
						);
				}
			}
			
			// parse the html string and the data structure and display the results
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