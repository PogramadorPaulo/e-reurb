<?php
class UsersController extends controller
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

		if (!$this->user->hasPermission('users_view')) {
			header("Location: " . BASE_URL);
			exit;
		}
		$this->arrayInfo = array(
			'user' => $this->user,
			'menuActive' => 'users'

		);
	}

	public function index()
	{
		$users = new Users();
		$this->arrayInfo['list'] = $users->getAll($_SESSION['uid']);
		$this->loadTemplate('users', $this->arrayInfo);
	}

	public function add()
	{
		$users = new Users();
		$this->arrayInfo['list_permission'] = $users->getPermissions();
		$this->loadTemplate('user_add', $this->arrayInfo);
	}

	public function add_action()
	{
		$users = new Users();
		if (!empty($_POST['name']) && !empty($_POST['email']) && !empty($_POST['grupo'])) {
			$name = $_POST['name'];
			$email = $_POST['email'];
			$grupo = $_POST['grupo'];
			$admin = $_POST['admin'];

			$users->addUser($name, $email, $grupo, $admin);
			header("Location: " . BASE_URL . 'users/add');
			exit;
		} else {
			$_SESSION['msgErro'] = 'Preencha todos os campos obrigatórios';
			header("Location: " . BASE_URL . 'users/add');
			exit;
		}
	}

	public function edit($id)
	{
		$users = new Users();
		if (!empty($id)) {
			$id = addslashes($id);
			$this->arrayInfo['list'] = $users->selectUser($id);
			if (empty($this->arrayInfo['list'])) {
				$_SESSION['msgErro'] = 'Ops! Usuário não encontrado';
				header("Location: " . BASE_URL . 'users');
				exit;
			}
			$this->arrayInfo['list_permission'] = $users->getPermissions();
			$this->arrayInfo['id_user'] = $id;
			$this->loadTemplate('user_edit', $this->arrayInfo);
		} else {
			$_SESSION['msgErro'] = 'Ops!';
			header("Location: " . BASE_URL . 'users');
			exit;
		}
	}

	public function edit_action($id_user)
	{
		$users = new Users();
		if ($id_user == '') {
			$_SESSION['msgErro'] = 'Ops! algo deu errado';
			header("Location: " . BASE_URL . 'users');
			exit;
		}
		if (!empty($_POST['name']) && !empty($_POST['email']) && !empty($_POST['grupo'])) {

			$name = $_POST['name'];
			$email = $_POST['email'];
			$grupo = $_POST['grupo'];
			$admin = $_POST['admin'];
			$setor = $_POST['setor'];


			$users->editUser($id_user, $name, $email, $grupo, $admin, $setor);
			header("Location: " . BASE_URL . 'users');
			exit;
		} else {
			$_SESSION['msgErro'] = 'Preencha todos os campos obrigatórios';
			header("Location: " . BASE_URL . 'users');
			exit;
		}
	}
} // Fim function