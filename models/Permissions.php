<?php 
class Permissions extends model {

	public function getPermissionGroupName($id_permission){
		
		 $sql ="SELECT name FROM permission_groups where id = :id";
		 $sql = $this->db->prepare($sql);
		 $sql->bindValue(':id', $id_permission);
		 $sql->execute();
		
		   if($sql->rowCount() > 0){
				 $data = $sql->fetch();
				 
				
				 return $data['name'];
				 
			 }else{
			   return '';
		   }
		
		
	}

	 public function getPermissions($id_permission){
		 $array = array();
		 
		 $sql ="SELECT id_permission_item FROM permission_links WHERE id_permission_group =  :id_permission";
		 $sql = $this->db->prepare($sql);
		 $sql->bindValue(':id_permission', $id_permission);
		 $sql->execute();
		 
		 if($sql->rowCount() > 0){
			 $data = $sql->fetchAll(); 
			 
			foreach($data as $data_item){
				$ids[] = $data_item['id_permission_item'];
			}
			
			 
			 $sql = "SELECT slug FROM permission_items WHERE id IN (".implode(',', $ids).")";
			 $sql = $this->db->query($sql);
			 
			 if($sql->rowCount() > 0){
				 $data = $sql->fetchAll();
				 
				foreach($data as $data_item){
					$array[] = $data_item['slug'];
				   }
				 
				 
			 }
			 
			 
		 }
	 
          return $array;
	 
	 }
	
	public function getAllGroups(){
		$array = array();
		
		 $sql ="SELECT
		 permission_groups.*, 
		 (
		 
		 SELECT count(users.id) from users where
		 users.id_permission = permission_groups.id
		 
		 ) as total_users 
		 
		 FROM permission_groups";
		 $sql = $this->db->query($sql);
		 if($sql->rowCount() > 0){
				 $array = $sql->fetchAll(PDO::FETCH_ASSOC);
				  
		  }
		return $array;
		
	}
	
	
	public function getAllItems(){
		 $array = array();
		 $sql ="SELECT * from permission_items ORDER by name";
		 $sql = $this->db->query($sql);
		 if($sql->rowCount() > 0){
				 $array = $sql->fetchAll(PDO::FETCH_ASSOC);
				  
		  }
		return $array;
		
	}
	
	public function getItem($id){
		 $array = array();
		 $sql ="SELECT * from permission_items where id=:id";
		 $sql = $this->db->prepare($sql);
		 $sql->bindValue(':id',$id);
		 $sql->execute();
		 if($sql->rowCount() > 0){
				 $array = $sql->fetchAll(PDO::FETCH_ASSOC);
				  
		  }
		return $array;
		
	}
	
	public function editName($new_name, $id){
		
		$sql = "Update permission_groups set name =:name Where id =:id";
		$sql = $this->db->prepare($sql);
		$sql->bindValue(':name', $new_name);
		$sql->bindValue(':id', $id);
		$sql->execute();
		
	}
	
	public function clearLinks($id){
		
		$sql = "DELETE FROM permission_links where id_permission_group = :id";
		$sql = $this->db->prepare($sql);
		$sql->bindValue(':id', $id);
		$sql->execute();
		
	}
	
	
	
	public function deleteGroup($id_group){
		
		
		 $sql ="SELECT * FROM users where id_permission = :id_group";
		 $sql = $this->db->prepare($sql);
		 $sql->bindValue(':id_group', $id_group);
		 $sql->execute();
		 
		
		 if($sql->rowCount() === 0){
			 
				 $sql ="DELETE FROM permission_links where id_permission_group = :id_group";
				 $sql = $this->db->prepare($sql);
				 $sql->bindValue(':id_group', $id_group);
				 $sql->execute();
			 
			     $sql ="DELETE FROM permission_groups where id = :id_group";
				 $sql = $this->db->prepare($sql);
				 $sql->bindValue(':id_group', $id_group);
				 $sql->execute();
		  
				  
		  }
		
		
	}
	
	public function deleteItem($id){
		
		
		 $sql ="SELECT * FROM permission_links where id_permission_item = :id";
		 $sql = $this->db->prepare($sql);
		 $sql->bindValue(':id', $id);
		 $sql->execute();
		 
		
		 if($sql->rowCount() === 0){
			 
				 $sql ="DELETE FROM permission_links where id_permission_item = :id";
				 $sql = $this->db->prepare($sql);
				 $sql->bindValue(':id', $id);
				 $sql->execute();
			 
			     $sql ="DELETE FROM permission_items where id = :id";
				 $sql = $this->db->prepare($sql);
				 $sql->bindValue(':id', $id);
				 $sql->execute();
		  
				  
		  }else{
			 $_SESSION['errorMsg'] ='Erro! Existe grupos com este item sendo usado!';
		 }
		
		
	}
	
	public function addGroup($name) {
		$sql = "INSERT INTO permission_groups (name) VALUES (:name)";
		$sql = $this->db->prepare($sql);
		$sql->bindValue(':name', $name);
		$sql->execute();

		return $this->db->lastInsertId();
	}

	public function linkItemToGroup($id_item, $id_group) {
		$sql = "INSERT INTO permission_links (id_permission_group, id_permission_item) VALUES (:id_group, :id_item)";
		$sql = $this->db->prepare($sql);
		$sql->bindValue(':id_item', $id_item);
		$sql->bindValue(':id_group', $id_group);
		$sql->execute();
	}
	
	public function addItem($name, $slug) {
		$sql = "INSERT INTO permission_items (name, slug) VALUES (:name,:slug)";
		$sql = $this->db->prepare($sql);
		$sql->bindValue(':name', $name);
		$sql->bindValue(':slug', $slug);
		$sql->execute();

	}
	
	public function editNameItem($new_name, $id){
		
		$sql = "Update permission_items set name =:name Where id =:id";
		$sql = $this->db->prepare($sql);
		$sql->bindValue(':name', $new_name);
		$sql->bindValue(':id', $id);
		$sql->execute();
		
	}
	
	
	
}
?>