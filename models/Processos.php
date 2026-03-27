<?php

class Processos extends model
{
	
	public function get($id, $idMunicipio)
	{
		$array = array();
		$sql = $this->db->prepare("
		 SELECT * from procedures 
		 INNER JOIN tb_municipios ON tb_municipios.municipio_id = procedures.municipio
		 WHERE procedures.cod_procedimento=:id AND procedures.municipio=:municipio");
		$sql->bindValue(':id', $id);
		$sql->bindValue(':municipio', $idMunicipio);
		//	$sql->bindValue(':id_municipio', $i);
		$sql->execute();
		if ($sql->rowCount() > 0) {
			$array = $sql->fetchAll();
		}
		return $array;
	}

	public function getAll($id)
	{
		$array = array();
		$sql = $this->db->prepare("
		 SELECT * from procedures 
		 INNER JOIN tb_municipios ON tb_municipios.municipio_id = procedures.municipio
		 WHERE procedures.cod_procedimento=:id");
		$sql->bindValue(':id', $id);
		//	$sql->bindValue(':id_municipio', $i);
		$sql->execute();
		if ($sql->rowCount() > 0) {
			$array = $sql->fetchAll();
		}
		return $array;
	}

	public function getEtapasProcesso($id)
	{
		$array = array();
		$sql = $this->db->prepare("
		SELECT * from etapas_processo 
		INNER JOIN etapas_procedimentos ON etapas_processo.etapa_processo_id = etapas_procedimentos.etapa_id
		INNER JOIN status_etapa ON etapas_procedimentos.procedimento_status = status_etapa.status_id
		WHERE processo_id=:id
		ORDER BY etapa_ordem
		");
		$sql->bindValue(':id', $id);
		$sql->execute();
		if ($sql->rowCount() > 0) {
			$array = $sql->fetchAll();
		}
		return $array;
	}


}
