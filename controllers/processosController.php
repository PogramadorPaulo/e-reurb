<?php
class processosController extends controller
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

		if (!$this->user->hasPermission('processos_view')) {
			//$_SESSION['errorMsg'] = 'Usuário não autorizado';
			header("Location: " . BASE_URL);
			exit;
		}
	}

	public function index()
	{
		$this->arrayInfo = array(
			'user' => $this->user,
			'menuActive' => 'prc'

		);
		$u = new Users();
		if (!$u->isLogged()) {
			header("Location: " . BASE_URL . 'login');
			exit;
		}

		$idMunicipio = $u->getMunicipio();
		$this->arrayInfo['idMunicipio'] = $idMunicipio;
		$this->loadTemplate('processos', $this->arrayInfo);
	}
	public function view($id)
	{
		// Inicializa o array com informações gerais
		$this->arrayInfo = [
			'user' => $this->user,
			'menuActive' => 'prc'
		];

		// Verifica se o ID foi fornecido
		if (empty($id)) {
			header("Location: " . BASE_URL . 'processos');
			exit;
		}

		$u = new Users();

		// Verifica se o usuário está logado
		if (!$u->isLogged()) {
			header("Location: " . BASE_URL . 'login');
			exit;
		}

		$p = new Processos();
		$this->arrayInfo['id'] = $id;
		$idMunicipio = $u->getMunicipio();
		$this->arrayInfo['idMunicipio'] = $idMunicipio;

		// Verifica se o usuário é administrador
		if ($u->isAdmin() == 1) {
			$this->arrayInfo['procedimento'] = $p->getAll($id);

			// Redireciona se nenhum procedimento for encontrado
			if (empty($this->arrayInfo['procedimento'])) {
				header("Location: " . BASE_URL . 'processos');
				exit;
			}
		} else {
			// Busca o procedimento específico para o município do usuário
			$this->arrayInfo['procedimento'] = $p->get($id, $idMunicipio);

			// Redireciona se nenhum procedimento for encontrado
			if (empty($this->arrayInfo['procedimento'])) {
				header("Location: " . BASE_URL . 'processos');
				exit;
			}
		}

		// Obtém as etapas do processo
		$this->arrayInfo['etapas'] = $p->getEtapasProcesso($id);

		// Carrega a view com as informações
		$this->loadTemplate('processo-view', $this->arrayInfo);
	}


	public function upload()
	{
		if (!empty($_FILES['file']['tmp_name'])) {

			$type_allowed = array('image/jpeg', 'image/png');
			if (in_array($_FILES['file']['type'], $type_allowed)) {
				$newname = md5(time() . rand(0, 999)) . '.jpg';
				$newname = uniqid() . '-' . basename($_FILES['file']['name']);
				move_uploaded_file($_FILES['file']['tmp_name'], 'assets/tema/images/' . $newname);

				$array = array(
					'location' => BASE_URL . 'assets/tema/images/' . $newname
				);
				echo json_encode($array);
				exit;
			}
		}
	}
} // Fim function