<?php

class Municipio extends model
{

	public function getAtivos()
	{
		$sql = $this->db->prepare("
			SELECT municipio_id, municipio_name, municipio_uf
			FROM tb_municipios
			WHERE municipio_status = 1
			ORDER BY municipio_name ASC
		");
		$sql->execute();

		return $sql->fetchAll(PDO::FETCH_ASSOC);
	}

}
