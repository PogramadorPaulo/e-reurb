<?php
class Notifications extends model
{

	public function get($id)
	{
		$array = array();
		$sql = " SELECT * FROM tb_notifications_painel WHERE not_codigo=:id";
		$sql = $this->db->prepare($sql);
		$sql->bindValue(':id', $id);
		$sql->execute();
		if ($sql->rowCount() > 0) {
			
			$update = "Update tb_notifications_painel set not_leitura=:not_leitura, not_leitura_date=:not_leitura_date Where not_codigo=:id";
			$update = $this->db->prepare($update);
			$update->bindValue(':not_leitura', 0);
			$update->bindValue(':not_leitura_date', date('Y-m-d H:i:s'));
			$update->bindValue(':id', $id);
			$update->execute();

			$array = $sql->fetchAll(PDO::FETCH_ASSOC);
		}
		return $array;
	}
}
