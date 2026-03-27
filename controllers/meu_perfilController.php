<?php
class meu_perfilController extends controller
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

		if (!$this->user->hasPermission('meu_perfil_view')) {
			header("Location: " . BASE_URL);
			exit;
		}
		$this->arrayInfo = array(
			'user' => $this->user,
			'menuActive' => 'meu-perfil'

		);
	}

	public function index()
	{
		$users = new Users();
	    $idUser = $_SESSION['uid'];
		$this->arrayInfo['list'] = $users->selectUser($idUser);
		if (empty($this->arrayInfo['list'])) {
			$_SESSION['msgErro'] = 'Ops!';
			header("Location: " . BASE_URL);
			exit;
		}
		$this->loadTemplate('meu-perfil', $this->arrayInfo);
	}
} // Fim function