<?php
/**
 * Normaliza chaves title/tittle em respostas JSON (compatibilidade com código legado).
 */
if (!function_exists('json_response_normalize')) {
	function json_response_normalize(array $data)
	{
		if (isset($data['tittle']) && !isset($data['title'])) {
			$data['title'] = $data['tittle'];
		}
		if (isset($data['title']) && !isset($data['tittle'])) {
			$data['tittle'] = $data['title'];
		}
		return $data;
	}

	/**
	 * Envia JSON com Content-Type e encerra o script (por padrão).
	 */
	function json_response_send(array $data, $exit = true)
	{
		header('Content-Type: application/json; charset=utf-8');
		echo json_encode(json_response_normalize($data), JSON_UNESCAPED_UNICODE);
		if ($exit) {
			exit;
		}
	}
}
