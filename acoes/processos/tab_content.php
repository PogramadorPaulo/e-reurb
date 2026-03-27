<?php
/**
 * Reservado para eventual carregamento HTML por etapa via AJAX.
 * O fluxo atual usa a view completa e init sob demanda (assets/js/processos/etapas-tabs.js).
 */
require_once dirname(__DIR__, 2) . '/config.php';

http_response_code(501);
json_response_send([
	'status' => 'info',
	'title' => 'Não disponível',
	'message' => 'Carregamento parcial de etapa não está ativo. Acesse a página do processo.',
	'icon' => 'info',
]);
