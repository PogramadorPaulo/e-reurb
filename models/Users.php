<?php

class Users extends model
{

	private $uid;
	private $permissions;
	private $userName;
	private $isAdmin;
	private $isMunicipio;


	public function isLogged()
	{

		if (!empty($_SESSION['token'])) {
			$token = $_SESSION['token'];

			$sql = "SELECT id, id_permission, user_municipio, name, admin FROM users WHERE token = :token";
			$sql = $this->db->prepare($sql);
			$sql->bindValue(':token', $token);
			$sql->execute();
			if ($sql->rowCount() > 0) {
				$p = new Permissions();
				$data = $sql->fetch();
				$this->uid = $data['id'];
				$this->userName = $data['name'];
				$this->isAdmin = $data['admin'];
				$this->isMunicipio = $data['user_municipio'];
				$this->permissions = $p->getPermissions($data['id_permission']);
				return true;
			}
		}
		return false;
	}

	public function getId()
	{
		return $this->uid;
	}

	public function getMunicipio()
	{
		return $this->isMunicipio;
	}

	public function getName()
	{
		return $this->userName;
	}

	public function isAdmin()
	{
		if ($this->isAdmin == '1') {
			return true;
		} else {
			return false;
		}
	}

	public function hasPermission($permission_slug)
	{

		if (in_array($permission_slug, $this->permissions)) {
			return true;
		} else {
			return false;
		}
	}


	public function validateLogin($email, $password)
	{

		$sql = "SELECT id FROM users WHERE email =:email AND password =:password AND status = 1";
		$sql = $this->db->prepare($sql);
		$sql->bindValue(':email', $email);
		$sql->bindValue(':password', md5($password));
		$sql->execute();

		if ($sql->rowCount() > 0) {
			$data = $sql->fetch();

			$token = md5(time() . rand(0, 999) . $data['id'] . time());

			$sql = "UPDATE users SET token =:token WHERE id =:id";
			$sql = $this->db->prepare($sql);
			$sql->bindValue(':token', $token);
			$sql->bindValue(':id', $data['id']);
			$sql->execute();

			$_SESSION['token'] = $token;
			$_SESSION['uid'] = $data['id'];

			return true;
		}

		return false;
	}
	public function getAll($loggedUserId)
	{
		$array = array();
		$sql = "
		SELECT 
			users.id,
			users.name,
			users.email,
			users.admin,
			users.status,
			permission_groups.name as permission_name
		FROM users 
		LEFT JOIN permission_groups ON permission_groups.id = users.id_permission
		WHERE users.id != :loggedUserId
		ORDER BY users.admin ASC";

		$sql = $this->db->prepare($sql);
		$sql->bindValue(':loggedUserId', $loggedUserId); // Bind the logged user ID
		$sql->execute();

		if ($sql->rowCount() > 0) {
			$array = $sql->fetchAll(\PDO::FETCH_ASSOC);
		}

		return $array;
	}



	public function getPermissions()
	{
		$array = array();
		$sql = "
		SELECT * from permission_groups
		ORDER BY name ASC";
		$sql = $this->db->prepare($sql);
		$sql->execute();
		if ($sql->rowCount() > 0) {
			$array = $sql->fetchAll(\PDO::FETCH_ASSOC);
		}
		return $array;
	}

	public function getSetores()
	{
		$array = array();
		$sql = "
		SELECT * from tb_users_setor
		WHERE setorUser_status = 1
		ORDER BY setorUser_name ASC";
		$sql = $this->db->prepare($sql);
		$sql->execute();
		if ($sql->rowCount() > 0) {
			$array = $sql->fetchAll(\PDO::FETCH_ASSOC);
		}
		return $array;
	}


	public function addUser($name, $email, $grupo, $admin)
	{
		// Verifica se já existe um usuário com o mesmo e-mail
		$sql_consulta = "SELECT id, email FROM users WHERE email = :email";
		$sql_consulta = $this->db->prepare($sql_consulta);
		$sql_consulta->bindValue(':email', $email);
		$sql_consulta->execute();

		if ($sql_consulta->rowCount() > 0) {
			$_SESSION['msgErro'] = "Ops! Já existe um usuário cadastrado com este e-mail: " . $email;
			return false;
		} else {
			// Insere novo usuário no banco de dados
			$sql = "INSERT INTO users (id_permission, email, name, admin, status, data) 
                VALUES (:id_permission, :email, :name, :admin, :status, :data)";
			$sql = $this->db->prepare($sql);
			$sql->bindValue(':name', $name);
			$sql->bindValue(':email', $email);
			$sql->bindValue(':id_permission', $grupo);
			$sql->bindValue(':admin', $admin);
			$sql->bindValue(':status', 1);
			$sql->bindValue(':data', date("Y-m-d H:i"));
			$sql->execute();

			if ($sql->rowCount() > 0) {
				// Gerar chave para recuperação de senha
				$userId = $this->db->lastInsertId();
				$chave_recuperar_senha = password_hash($userId, PASSWORD_DEFAULT);

				// Atualiza o campo de recuperação de senha
				$sql_update = "UPDATE users SET recuperar_senha = :recuperar_senha WHERE id = :id";
				$sql_update = $this->db->prepare($sql_update);
				$sql_update->bindValue(':recuperar_senha', $chave_recuperar_senha);
				$sql_update->bindValue(':id', $userId);
				$sql_update->execute();

				// Envia e-mail de boas-vindas com o link para criar a senha
				require 'PHPMailer-master/PHPMailerAutoload.php';
				$mail = new PHPMailer();
				$mail->IsSMTP();
				$mail->Host = EMAIL_HOST;
				$mail->Port = 465;
				$mail->SMTPAuth = true;
				$mail->SMTPSecure = 'ssl';
				$mail->Username = EMAIL;
				$mail->Password = EMAIL_PASSWORD;
				$mail->SMTPOptions = array('ssl' => array('verify_peer' => false, 'verify_peer_name' => false, 'allow_self_signed' => true));
				$mail->From = EMAIL;
				$mail->FromName = NAME;
				$mail->AddAddress($email, 'Novo Usuário');

				$mail->IsHTML(true);
				$mail->CharSet = 'UTF-8';
				$mail->Subject = "Novo usuário - " . NAME;
				$mail->Body = '
					<html>
					<head>
						<style>
							body {
								font-family: Arial, sans-serif;
								background-color: #f4f4f4;
								color: #333333;
								margin: 0;
								padding: 0;
							}
							.container {
								width: 80%;
								margin: 0 auto;
								background-color: #ffffff;
								padding: 20px;
								border-radius: 10px;
								box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
							}
							h2 {
								color: #2E6FA7;
							}
							p {
								line-height: 1.6;
							}
							a.button {
								display: inline-block;
								padding: 10px 20px;
								font-size: 16px;
								color: #ffffff;
								background-color: #2E6FA7;
								text-decoration: none;
								border-radius: 5px;
								margin-top: 20px;
							}
						.footer {
                            margin-top: 30px;
                            font-size: 12px;
                            color: #777777;
                            text-align: center;
                            }
						.warning {
                            color: #FF0000;
                            font-weight: bold;
                            }
						</style>
					</head>
					<body>
						<div class="container">
							<h2>Bem-vindo(a) ao ' . NAME . '</h2>
							<p>Olá, <strong>' . $name . '</strong>,</p>
							<p>Você foi cadastrado(a) como novo usuário no sistema. Para garantir a segurança e acesso ao ' . NAME . ', é necessário criar uma senha de acesso.</p>
							<p><strong>Informações da sua conta:</strong></p>
							<ul>
								<li><strong>Nome:</strong> ' . $name . '</li>
								<li><strong>E-mail:</strong> ' . $email . '</li>
								<li><strong>Data de criação:</strong> ' . date("d/m/Y H:i") . '</li>
							</ul>
							<p>Para criar sua nova senha e acessar o sistema, clique no botão abaixo:</p>
							<p><a href="' . BASE_URL . 'views/new-password.php?chave=' . $chave_recuperar_senha . '" target="_blank" class="button">Criar nova senha</a></p>
							<p>Se você não solicitou este cadastro, ignore este e-mail. Sua conta permanecerá inativa até que a senha seja criada.</p>
							<p><strong>Dicas de segurança:</strong></p>
							<ul>
								<li>Crie uma senha forte, combinando letras, números e símbolos.</li>
								<li>Não compartilhe sua senha com outras pessoas.</li>
								<li>Utilize sempre um e-mail seguro para o cadastro no sistema.</li>
							</ul>
							<p class="warning">Atenção: Este é um e-mail automático. Não responda.</p>
							<div class="footer">
                              <p>&copy; ' . date("Y") . ' ' . NAME . '. Todos os direitos reservados.</p>
                            </div>
						</div>
					</body>
					</html>
				';


				// Envia o e-mail
				if (!$mail->Send()) {
					$_SESSION['msgErro'] = "Erro ao enviar e-mail: " . $mail->ErrorInfo;
				} else {
					$_SESSION['msgSuccess'] = "Usuário cadastrado com sucesso!";
				}
			} else {
				$_SESSION['msgErro'] = "Ops! Houve um problema ao cadastrar o usuário. Tente novamente.";
			}
		}
	}


	public function selectUser($id)
	{
		$array = array();
		$sql = "SELECT * from users WHERE id=:id";
		$sql = $this->db->prepare($sql);
		$sql->bindValue(':id', $id);
		$sql->execute();
		if ($sql->rowCount() > 0) {
			$array = $sql->fetchAll(PDO::FETCH_ASSOC);
		}
		return $array;
	}

	public function editUser($id_user, $name, $email, $grupo, $admin)
	{
		$sql = "
		 UPDATE users SET name =:name, email=:email, admin=:admin, id_permission=:id_permission, data_update=:data_update
		 WHERE id =:id";
		$sql = $this->db->prepare($sql);
		$sql->bindValue(':name', $name);
		$sql->bindValue(':email', $email);
		$sql->bindValue(':id_permission', $grupo);
		$sql->bindValue(':admin', $admin);
		$sql->bindValue(':data_update', date('Y-m-d H:i'));
		$sql->bindValue(':id', $id_user);
		$sql->execute();
		$_SESSION['msgSuccess'] = "Usuário foi editado com sucesso!";
		header("Location: " . BASE_URL . "users");
	}
	public function getCargos()
	{
		$array = array();
		$sql = "SELECT * FROM tb_users_cargos ORDER BY users_cargo_name";
		$sql = $this->db->query($sql);
		if ($sql->rowCount() > 0) {
			$array = $sql->fetchAll(PDO::FETCH_ASSOC);
		}
		return $array;
	}
}
