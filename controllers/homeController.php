<?php
class homeController extends controller
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
		$this->arrayInfo = array(
			'user' => $this->user,
			'menuActive' => 'home',

		);
	}

	public function index()
	{
		$this->loadTemplate('home', $this->arrayInfo);
	}
}
