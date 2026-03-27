<?php
class MunicipioController extends controller
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

		if (!$this->user->hasPermission('municipio_view')) {
			header("Location: " . BASE_URL);
			exit;
		}
		$this->arrayInfo = array(
			'user' => $this->user,
			'menuActive' => 'municipio'

		);
	}

	public function index()
	{
		$m = new Municipio();
		$user = new Users();
		// Verifica se o usuário está logado
		if (!$user->isLogged()) {
			header("Location: " . BASE_URL . "login");
			exit;
		}

		$this->loadTemplate('municipio', $this->arrayInfo);
	}

} // Fim function