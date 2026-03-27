<?php
setlocale(LC_ALL, 'pt_br');
date_default_timezone_set('America/Sao_Paulo');
require_once 'environment.php';

$config = array();
if (ENVIRONMENT == 'development') {
	define("BASE_URL", "http://localhost/e-reurb-novo/");
	define("PATH_SITE", "../");
	define("NAME", "E-reurb");
	define("DESCRICAO", "E-reurb");
	define("ENDERECO", "");
	define("CIDADE", "");
	define("CEP", "");
	define("TELEFONE", "");
	define("EMAIL_CONTATO", "");
	// ENVIO DE EMAIL 
	
	/*define("EMAIL", "email.contato.disparo@gmail.com");
	define("EMAIL_HOST", "smtp.gmail.com");
	define("EMAIL_PASSWORD", "mvuxwcvfjkqugbpt");
	*/
	define("EMAIL", "pcpaulo413@gmail.com");
	define("EMAIL_HOST", "smtp.gmail.com");
	define("EMAIL_PASSWORD", "gbcmzgatooyncwcg");



	define("DESCRIPTION", "E-reurb");
	define("VERSAO", "Versão 1.2 | 2024");
	define("TAMANHO_UPLOAD", "20");

	// BASE DE DADOS
	$config['dbname'] = 'ereurb27_bd';
	$config['host'] = 'localhost';
	$config['dbuser'] = 'root';
	$config['dbpass'] = '';
} else {
	define("BASE_URL", "https://e-reurbsistema.com.br/sistema/v2/");
	define("PATH_SITE", "../");
	define("NAME", "E-reurb");
	define("DESCRICAO", "E-reurb");
	define("ENDERECO", "");
	define("CIDADE", "");
	define("CEP", "");
	define("TELEFONE", "");
	define("EMAIL_CONTATO", "");
	// ENVIO DE EMAIL 

	/*define("EMAIL", "email.contato.disparo@gmail.com");
	define("EMAIL_HOST", "smtp.gmail.com");
	define("EMAIL_PASSWORD", "mvuxwcvfjkqugbpt");
	*/


	define("EMAIL", "pcpaulo413@gmail.com");
	define("EMAIL_HOST", "smtp.gmail.com");
	define("EMAIL_PASSWORD", "gbcmzgatooyncwcg");

	define("DESCRIPTION", "E-reurb");
	define("VERSAO", "Versão 1.2 | 2024");
	define("TAMANHO_UPLOAD", "20");

	// BASE DE DADOS
	$config['dbname'] = 'ereurb27_bd';
	$config['host'] = 'localhost';
	$config['dbuser'] = 'ereurb27_admin';
	$config['dbpass'] = '78451212@*Pc22';
}
$config['default_lang'] = 'pt-br';

global $db;
try {
	$db = new PDO(
		"mysql:dbname=" . $config['dbname'] . ";host=" . $config['host'],
		$config['dbuser'],
		$config['dbpass'],
		array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
	);
} catch (PDOException $e) {
	echo "ERRO: " . $e->getMessage();
	exit;
}

/* Permissões de usuário */
function hasPermission($userId, $permissionSlug, $db)
{
	// Verifica se o usuário é administrador
	$queryAdmin = "SELECT admin FROM users WHERE id = :userId";
	$stmtAdmin = $db->prepare($queryAdmin);
	$stmtAdmin->bindValue(':userId', $userId, PDO::PARAM_INT);
	$stmtAdmin->execute();
	$user = $stmtAdmin->fetch(PDO::FETCH_ASSOC);

	/*if ($user && $user['admin'] == 1) {
    return true; // Administrador tem todas as permissões
  }*/

	// Verifica permissões do grupo vinculado ao usuário
	$query = "
        SELECT COUNT(*) 
        FROM permission_links pl
        JOIN permission_items pi ON pl.id_permission_item = pi.id
        JOIN users u ON u.id_permission = pl.id_permission_group
        WHERE u.id = :userId AND pi.slug = :permissionSlug
    ";
	$stmt = $db->prepare($query);
	$stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
	$stmt->bindValue(':permissionSlug', $permissionSlug, PDO::PARAM_STR);
	$stmt->execute();
	return $stmt->fetchColumn() > 0;
}


// Função para verificar se a etapa está concluída
function isEtapaConcluida($processoId, $etapaId, $db)
{
	try {
		$stmt = $db->prepare("
            SELECT *  
            FROM etapas_procedimentos 
            WHERE processo_id = :processo_id AND etapa_id = :etapa_id
        ");
		$stmt->bindValue(':processo_id', $processoId, PDO::PARAM_INT);
		$stmt->bindValue(':etapa_id', $etapaId, PDO::PARAM_INT);
		$stmt->execute();
		$result = $stmt->fetch(PDO::FETCH_ASSOC);

		// Retorna true se o status da etapa for "concluido"
		return isset($result['procedimento_status']) && $result['procedimento_status'] == '4' or $result['procedimento_status'] == '5';
	} catch (Exception $e) {
		error_log("Erro ao verificar etapa: " . $e->getMessage());
		return false;
	}
}

function validarData($data, $formato = 'Y-m-d')
{
	$dateTimeObj = DateTime::createFromFormat($formato, $data);
	return $dateTimeObj && $dateTimeObj->format($formato) === $data;
}

// Função para verificar se a data final é posterior à data inicial
function validarPeriodo($data_inicio, $data_termino)
{
	$inicio = DateTime::createFromFormat('Y-m-d', $data_inicio);
	$termino = DateTime::createFromFormat('Y-m-d', $data_termino);

	return $inicio && $termino && $inicio <= $termino;
}



function validarDatetimeLocal($datetime)
{
	// Verifica se o formato da data e hora é válido (AAAA-MM-DDTHH:mm)
	$formatoValido = preg_match('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}$/', $datetime);

	if (!$formatoValido) {
		return false;
	}

	// Divide a string em data e hora
	list($data, $hora) = explode('T', $datetime);

	// Verifica se a data é válida
	$dataValida = strtotime($data) !== false;

	// Verifica se a hora é válida
	$horaValida = strtotime($hora) !== false;

	// Retorna verdadeiro se ambos, data e hora, são válidos
	return $dataValida && $horaValida;
}


/* funções  */
function dia_extenso($data)
{
	$meses = array(1 => "Janeiro", 2 => "Fevereiro", 3 => "Março", 4 => "Abril", 5 => "Maio", 6 => "Junho", 7 => "Julho", 8 => "Agosto", 9 => "Setembro", 10 => "Outubro", 11 => "Novembro", 12 => "Dezembro");
	$diasdasemana = array(1 => "Segunda-Feira", 2 => "Terça-Feira", 3 => "Quarta-Feira", 4 => "Quinta-Feira", 5 => "Sexta-Feira", 6 => "Sábado", 0 => "Domingo");
	$variavel = $data;
	$variavel = str_replace('/', '-', $variavel);
	$hoje = getdate(strtotime($variavel));
	$dia = $hoje["mday"];
	$mes = $hoje["mon"];
	$nomemes = $meses[$mes];
	$ano = $hoje["year"];
	$diadasemana = $hoje["wday"];
	$nomediadasemana = $diasdasemana[$diadasemana];
	return $nomediadasemana;
}

function data_extenso($data)
{
	$meses = array(1 => "Janeiro", 2 => "Fevereiro", 3 => "Março", 4 => "Abril", 5 => "Maio", 6 => "Junho", 7 => "Julho", 8 => "Agosto", 9 => "Setembro", 10 => "Outubro", 11 => "Novembro", 12 => "Dezembro");
	$diasdasemana = array(1 => "Segunda-Feira", 2 => "Terça-Feira", 3 => "Quarta-Feira", 4 => "Quinta-Feira", 5 => "Sexta-Feira", 6 => "Sábado", 0 => "Domingo");
	$variavel = $data;
	$variavel = str_replace('/', '-', $variavel);
	$hoje = getdate(strtotime($variavel));
	$dia = $hoje["mday"];
	$mes = $hoje["mon"];
	$nomemes = $meses[$mes];
	$ano = $hoje["year"];
	$diadasemana = $hoje["wday"];
	$nomediadasemana = $diasdasemana[$diadasemana];
	return $nomediadasemana . ', ' . $dia . ' de ' . $nomemes . ' de ' . $ano;
}
function data_extenso_abreviada($data)
{
	$meses = array(1 => "Janeiro", 2 => "Fevereiro", 3 => "Março", 4 => "Abril", 5 => "Maio", 6 => "Junho", 7 => "Julho", 8 => "Agosto", 9 => "Setembro", 10 => "Outubro", 11 => "Novembro", 12 => "Dezembro");
	$diasdasemana = array(1 => "Segunda-Feira", 2 => "Terça-Feira", 3 => "Quarta-Feira", 4 => "Quinta-Feira", 5 => "Sexta-Feira", 6 => "Sábado", 0 => "Domingo");
	$variavel = $data;
	$variavel = str_replace('/', '-', $variavel);
	$hoje = getdate(strtotime($variavel));
	$dia = $hoje["mday"];
	$mes = $hoje["mon"];
	$nomemes = $meses[$mes];
	$ano = $hoje["year"];
	$diadasemana = $hoje["wday"];
	return  $dia . ' de ' . $nomemes . ' de ' . $ano;
}
function validarEmail($email)
{
	// Padrão de expressão regular para validar e-mails
	$padrao = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';

	// Testa se o e-mail corresponde ao padrão
	if (preg_match($padrao, $email)) {
		return true; // E-mail válido
	} else {
		return false; // E-mail inválido
	}
}

// função que limita os qtd de caractéres
function str_limit_chars($string, $limit,  $point = "...")
{
	$string = trim(filter_var($string, FILTER_SANITIZE_SPECIAL_CHARS));
	if (mb_strlen($string) <= $limit) {
		return $string;
	}

	$chars = mb_substr($string, 0, mb_strrpos(mb_substr($string, 0, $limit), " "));
	return "{$chars}{$point}";
}


function generateRandomCode($length = 30)
{
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$charactersLength = strlen($characters);
	$randomString = '';
	for ($i = 0; $i < $length; $i++) {
		$randomString .= $characters[mt_rand(0, $charactersLength - 1)];
	}
	return $randomString;
}

function generateRandomNumberCode($length = 30)
{
	$randomNumbers = '';
	for ($i = 0; $i < $length; $i++) {
		$randomNumbers .= random_int(0, 9);
	}
	return $randomNumbers;
}

function slugify($string)
{
	// Converte para minúsculas
	$string = strtolower($string);

	// Remove acentos
	$string = iconv('UTF-8', 'ASCII//TRANSLIT', $string);

	// Substitui caracteres não alfanuméricos por hífens
	$string = preg_replace('/[^a-z0-9]+/', '-', $string);

	// Remove hífens duplicados e trim em hífens no início/fim
	$string = trim($string, '-');

	return $string;
}
