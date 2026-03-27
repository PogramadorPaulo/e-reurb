<?php
class notificationsController extends controller
{
	private $user;
	private $arrayInfo;
	public function __construct()
	{
		$this->user = new Users();
		if (!$this->user->isLogged()) {
			header("Location: " . BASE_URL . "login");
			exit;
		}

		if (!$this->user->hasPermission('notifications_view')) {
			header("Location: " . BASE_URL);
			exit;
		}
		$this->arrayInfo = array(
			'user' => $this->user,
			'menuActive' => 'notifications'

		);
	}

	public function index()
	{
	//$this->arrayInfo['id_user'] = $_SESSION['uid'];
		$this->loadTemplate('notifications', $this->arrayInfo);
	}

	public function view($id)
	{
		$not = new Notifications();
		if (!empty($id)) {
			$id = addslashes($id);
			$this->arrayInfo['list'] = $not->get($id);
			if (empty($this->arrayInfo['list'])) {
				header("Location: " . BASE_URL);
				exit;
			}
			$this->arrayInfo['id'] = $id;
			$this->loadTemplate('notifications-view', $this->arrayInfo);
		} else {
			header("Location: " . BASE_URL);
			exit;
		}
	}
} // Fim function