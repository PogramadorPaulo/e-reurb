<?php
include_once('../../config.php');

$stmt = $db->prepare("SELECT municipio_id, municipio_name FROM tb_municipios WHERE municipio_status = 1 ORDER BY municipio_name");
$stmt->execute();
$municipios = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo '<option value="">-- Todos os Municípios --</option>';
foreach ($municipios as $m) {
    echo '<option value="' . $m['municipio_id'] . '">' . htmlspecialchars($m['municipio_name']) . '</option>';
}
