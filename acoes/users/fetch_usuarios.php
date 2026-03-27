<?php
include_once('../../config.php');

$municipio_id = $_POST['municipio_id'] ?? null;

$sql = "SELECT u.*, p.name AS permission_name 
        FROM users u 
        LEFT JOIN permission_groups p ON u.id_permission = p.id 
        WHERE 1 ";

if (!empty($municipio_id)) {
    $sql .= "AND u.user_municipio = :municipio_id ";
}

$sql .= "ORDER BY u.name";

$stmt = $db->prepare($sql);

if (!empty($municipio_id)) {
    $stmt->bindValue(':municipio_id', $municipio_id);
}

$stmt->execute();
$list = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Gera o HTML dinamicamente
foreach ($list as $item): ?>
    <tr>
        <?php if ($item['admin'] === '1'): ?>
            <td><strong><?= $item['name']; ?></strong></td>
        <?php else: ?>
            <td><?= $item['name']; ?></td>
        <?php endif; ?>

        <td><?= $item['email']; ?></td>
        <td><?= $item['permission_name']; ?></td>
        <td>
            <label class="switch">
                <input type="checkbox" class="status-toggle" data-id="<?= $item['id']; ?>" <?= $item['status'] == '1' ? 'checked' : ''; ?>>
                <span class="slider round"></span>
            </label>
        </td>
        <td>
            <div class="btn-group" role="group">
                <a href="<?= BASE_URL . 'users/edit/' . $item['id']; ?>" class="btn btn-primary btn-sm modalEdit">
                    <i class="ti-pencil-alt"></i> Editar
                </a>
            </div>
        </td>
    </tr>
<?php endforeach; ?>