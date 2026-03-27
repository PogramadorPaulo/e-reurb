<?php
class LoginController extends controller
{
	private $user;
	private $arrayInfo;
	public function index()
	{
		$this->user = new Users();
		if ($this->user->isLogged()) {
			header("Location: " . BASE_URL);
			exit;
		}
		$dados = array(
			'error' => ''

		);

		if (!empty($_SESSION['errorMsg'])) {
			$dados['error'] = $_SESSION['errorMsg'];
			$_SESSION['errorMsg'] = '';
		}

		$this->loadView('login');
	}
	

	public function logout()
	{

		unset($_SESSION['token']);
		unset($_SESSION['uid']);
		header("Location: " . BASE_URL . "login");
		exit;
	}

	public function recuperar()
	{

		$this->loadView('recuperar-password');
	}



}
