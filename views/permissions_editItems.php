<!-- Page-header end -->
<div class="pcoded-inner-content">
  <!-- Main-body start -->
  <div class="main-body">
    <div class="page-wrapper">
      <!-- Page-body start -->
      <div class="page-body">
        <form action="<?php echo BASE_URL; ?>permissions/editItem_action/<?php echo $permission_id; ?>" method="POST">
          <div class="card">
            <div class="card-header">
              <h5 class="card-title">Editar Item de Permissões</h5>
              <div class="card-tools float-right">
                <button type="submit" class="btn waves-effect waves-light btn-success btn-square btn-block"><i class="ti-save"></i> Salvar</button>
              </div>
            </div>
            <div class="card-body">
              <?php if (!empty($error)) : ?>
                <div class="callout callout-danger">
                  <p class="text-danger"> <?php echo $error; ?></p>
                </div>
              <?php endif; ?>
              <?php foreach ($permission_items as $item) : ?>
                <div class="form-group">
                  <label for="exampleInputEmail1">Nome do item</label>
                  <input type="text" class="form-control" id="name" name="name" value="<?php echo $item['name'] ?>" placeholder="">
                </div>
              <?php endforeach; ?>
            </div>

          </div>
        </form>
        <!-- /.card -->
      </div><!-- /.container-fluid -->
    </div>
  </div>
</div>
<!-- /.content -->