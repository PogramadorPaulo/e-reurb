<?php
class PermissionsController extends controller {
private $user; 
private $arrayInfo;
public function __construct(){

    $this->user = new Users();

    if(!$this->user->isLogged()){
        header("Location: ".BASE_URL."login");
        exit;

    }

    if(!$this->user->hasPermission('permissions_view')){
        header("Location: ".BASE_URL."home");
        exit;
    }

    $this->arrayInfo = array(
        'user' => $this->user,
        'menuActive' => 'permissions'

    );

}
public function index() {
      $p = new Permissions();
      $this->arrayInfo['list'] = $p->getAllGroups();
      $this->loadTemplate('permissions', $this->arrayInfo);
}

public function items() {
      $p = new Permissions();
      $this->arrayInfo['list'] = $p->getAllItems();
      $this->loadTemplate('permissions_items', $this->arrayInfo);
}

public function del($id_group){
   $p = new Permissions();
   $p->deleteGroup($id_group);
   header("Location: ".BASE_URL."permissions");
   exit;	

}	

public function items_del($id){
   $p = new Permissions();
   $p->deleteItem($id);
   header("Location: ".BASE_URL."permissions/items");
   exit;	

}	

public function add(){
      $p = new Permissions();
      $this->arrayInfo['permission_items'] = $p->getAllItems();
      $this->loadTemplate('permissions_add', $this->arrayInfo);
}	

public function items_add(){
      $p = new Permissions();
      $this->arrayInfo['permission_items'] = $p->getAllItems();
      $this->loadTemplate('permissions_addItems', $this->arrayInfo);

}

public function addItem_action() {
    $p = new Permissions();
    if(!empty($_POST['name']) && !empty($_POST['slug'])) {
        $name = $_POST['name'];
        $slug = $_POST['slug'];
        $id = $p->addItem($name, $slug);

        header("Location: ".BASE_URL.'permissions/items');
        exit;

    } else {
        $_SESSION['errorMsg'] = 'Preencha o nome do item de permissão';

        header("Location: ".BASE_URL.'permissions/items_add');
        exit;
    }

}

public function add_action() {
    $p = new Permissions();
    if(!empty($_POST['name'])) {
        $name = $_POST['name'];
        $id = $p->addGroup($name);
        if(isset($_POST['items']) && count($_POST['items']) > 0) {
            $items = $_POST['items'];
            foreach($items as $item) {
                $p->linkItemToGroup($item, $id);
            }
        }
        header("Location: ".BASE_URL.'permissions');
        exit;
    } else {
        $_SESSION['errorMsg'] = 'Preencha o nome do grupo';
        header("Location: ".BASE_URL.'permissions/add');
        exit;
    }

}


public function edit($id){
    if(!empty($id)){
      $p = new Permissions();
      $this->arrayInfo['permission_items'] = $p->getAllItems();
      $this->arrayInfo['permission_id'] = $id;
      $this->arrayInfo['permission_group_name'] = $p->getPermissionGroupName($id);
      $this->arrayInfo['permission_group_slugs'] = $p->getPermissions($id);
      $this->loadTemplate('permissions_edit', $this->arrayInfo);
    }else{
        header("Location: ".BASE_URL.'permissions');
        exit;
    }
}	

public function edit_action($id) {	
  if(!empty($id)){
      $p = new Permissions();
      if(!empty($_POST['name'])) {
          $name = $_POST['name'];
          $p->editName($name, $id);
          $p->clearLinks($id);
          if(isset($_POST['items']) && count($_POST['items']) > 0){
              $items = $_POST['items'];
              foreach($items as $item) {
                  $p->linkItemToGroup($item, $id);
              }
          }
          header("Location: ".BASE_URL.'permissions');
          exit;
      } else {
          $_SESSION['errorMsg'] = 'Preencha o nome do grupo';
          header("Location: ".BASE_URL.'permissions/edit/'.$id);
          exit;
      }
    } else {
      header("Location: ".BASE_URL.'permissions');
      exit;
  }
}

public function items_edit($id) {
    if(!empty($id)){
      $p = new Permissions();
      $this->arrayInfo['permission_id'] = $id;
      $this->arrayInfo['permission_items'] = $p->getItem($id);
      $this->loadTemplate('permissions_editItems', $this->arrayInfo);		
    }else{
        header("Location: ".BASE_URL.'permissions/items');
        exit;
    }
}

public function editItem_action($id) {
    if(!empty($id)){
        $p = new Permissions();
        if(!empty($_POST['name'])) {
            $name = $_POST['name'];
            $p->editNameItem($name, $id);
            header("Location: ".BASE_URL.'permissions/items');
            exit;
        } else {
            $_SESSION['errorMsg'] = 'Preencha o nome do item';

            header("Location: ".BASE_URL.'permissions/items_edit/'.$id);
            exit;
        }
    } else {
        header("Location: ".BASE_URL.'permissions');
        exit;
    }

}


	

} // Fim controller