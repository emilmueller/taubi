
<?php
   include 'roles.php';
   include 'permissions.php';
?>


<div class=table-responsive>
    <table class="table table-striped text-center align-middle">
    <thead>
        <tr>
        <th>Permission</th>
        <?php foreach ($roles as $role): ?>
            <th title='Click to Edit'>
                <button type="button" class="btn btn-dark btn-outline-light" data-bs-toggle="modal" data-role-id ='<?= htmlspecialchars(json_encode($role['id']), ENT_QUOTES) ?>' data-bs-target="#roleModal"><?= htmlspecialchars($role['name']) ?></button>
            </th>
        <?php endforeach; ?>
        <th title='Click to Edit'>
                <button type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#newRoleModal">Neue Rolle</button>
        </th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($permissions as $perm): ?>
        <tr>
            <td>
            <strong><?= htmlspecialchars($perm['name']) ?></strong><br>
            <small><?= htmlspecialchars($perm['description']) ?></small>
            </td>
            <?php foreach ($roles as $role): ?>
            <td>
                <?php $assigned = in_array($perm['id'], $role['permissions']); ?>
                <button
                class="btn btn-sm <?= $assigned ? 'btn-success' : 'btn-danger' ?>"
                onclick="togglePermission(<?= $role['id'] ?>, <?= $perm['id'] ?>, this)"
                >
                <?= $assigned ? '<i class="bi bi-check-lg"></i>' : '<i class="bi bi-x-lg"></i>' ?>
                </button>
            </td>
            <?php endforeach; ?>
        </tr>
        <?php endforeach; ?>
    </tbody>
    </table>
</div>

<!-- Modal Role Edit-->
<div class="modal fade" id="roleModal" tabindex="-1" aria-labelledby="editRoleModalLabel">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editRoleModalLabel">Rolle bearbeiten</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Schließen"></button>
      </div>
      <div class="modal-body">
        <form id="editRoleForm">

        
          
        </form>
      </div>
      <div class="modal-footer">

        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Schließen</button>
        <button id="deleteRoleBtn" type="button" class="btn btn-danger">Löschen</button>
        <button id="saveRoleBtn" type="button" class="btn btn-primary" >Speichern</button>
      </div>
      
    </div>
  </div>
</div>

<!-- Modal new Role-->
<div class="modal fade" id="newRoleModal" tabindex="-1" aria-labelledby="newRoleModalLabel">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="newRoleModalLabel">Rolle erfassen</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Schließen"></button>
      </div>
      <div class="modal-body">
        <form id="newRoleForm">
            <div class="row mb-3 align-items-center">
                <div class="col-4 text-end fw-semibold">
                    <label for="roleName" class="form-label">Name:</label>
                </div>
                <div class="col-8">
                    <input type="text" class="form-control" id="roleName" name="name" />
                </div>
            </div>
            <div class="row mb-3 align-items-center">
                <div class="col-4 text-end fw-semibold">
                    <label for="roleDescription" class="form-label">Description:</label>
                </div>
                <div class="col-8">
                    <textarea  rows="3" class="form-control" id="roleDescription" name="description" ></textarea>
                </div>
            </div>

        
          
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Schließen</button>
        <button id="saveNewRoleBtn" type="button" class="btn btn-primary" >Speichern</button>
      </div>
      
    </div>
  </div>
</div>

