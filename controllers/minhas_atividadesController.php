<?php
class minhas_atividadesController extends controller
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

		if (!$this->user->hasPermission('minhasAtividades_view')) {
			header("Location: " . BASE_URL);
			exit;
		}
		$this->arrayInfo = array(
			'user' => $this->user,
			'menuActive' => 'minhas_atividades'

		);
	}

	public function index()
	{
		$this->loadTemplate('minhas-atividades', $this->arrayInfo);
	}
} // Fim function